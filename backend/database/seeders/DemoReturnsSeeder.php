<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\RefundStatus;
use App\Models\ReturnDecision;
use App\Models\ReturnItem;
use App\Models\ReturnModel;
use App\Models\ReturnRefund;
use App\Models\ReturnShipment;
use App\Models\ReturnStatus;
use App\Models\ShipmentStatus;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Creates an identifiable, internally consistent demo return history.
 *
 * Customer details use reserved example domains and visibly fictional addresses.
 */
class DemoReturnsSeeder extends Seeder
{
    private const USER_EMAIL = 'test@example.com';
    private const RETURN_PREFIX = 'DEMO-RMA-';
    private const RETURN_COUNT = 45;

    /** @var array<string, ReturnStatus> */
    private array $returnStatuses;

    /** @var array<string, ShipmentStatus> */
    private array $shipmentStatuses;

    /** @var array<string, RefundStatus> */
    private array $refundStatuses;

    /** @var array<string, ReturnDecision> */
    private array $decisions;

    private User $user;

    private int $shipmentNumber;

    private int $refundNumber;

    public function run(): void
    {
        $this->user = User::query()->where('email', self::USER_EMAIL)->first()
            ?? throw new RuntimeException('Demo user test@example.com was not found.');

        if (!$this->user->current_organization_id) {
            throw new RuntimeException('Demo user has no current organization.');
        }

        Auth::login($this->user);

        if (ReturnModel::query()->where('return_number', 'like', self::RETURN_PREFIX . '%')->exists()) {
            throw new RuntimeException('Demo returns already exist; no data was added.');
        }

        $this->returnStatuses = ReturnStatus::query()->get()->keyBy('code')->all();
        $this->shipmentStatuses = ShipmentStatus::query()->get()->keyBy('code')->all();
        $this->refundStatuses = RefundStatus::query()->get()->keyBy('code')->all();
        $this->decisions = ReturnDecision::query()->get()->keyBy('code')->all();
        $organizationId = (int) $this->user->current_organization_id;
        $this->shipmentNumber = (int) ReturnShipment::query()->where('organization_id', $organizationId)->max('shipment_number');
        $this->refundNumber = (int) ReturnRefund::query()->where('organization_id', $organizationId)->max('refund_number');

        DB::transaction(function (): void {
            for ($index = 0; $index < self::RETURN_COUNT; $index++) {
                $this->createReturn($index);
            }
        });

        Carbon::setTestNow();
        Auth::logout();
    }

    private function createReturn(int $index): void
    {
        $startedAt = CarbonImmutable::parse('2026-05-15 09:00:00')
            ->addDays($index * 2)
            ->setTime(9 + ($index % 7), ($index * 7) % 60);
        Carbon::setTestNow($startedAt);

        $customer = Customer::query()->create($this->customerData($index));
        $return = ReturnModel::query()->create([
            'return_number' => self::RETURN_PREFIX . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
            'customer_id' => $customer->id,
            'order_reference' => 'DEMO-BEST-' . $startedAt->format('ym') . '-' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT),
            'reason' => $this->reason($index),
        ]);

        foreach ($this->items($index) as $line => $item) {
            $return->items()->save(new ReturnItem($item + ['line_no' => $line + 1]));
        }

        $this->addNote($return, 'Demo-Datensatz: Kundendaten und Adresse sind ausdrücklich fiktiv.', $startedAt->addMinutes(8));

        if ($index < 25) {
            $this->completeApprovedReturn($return, $index, $startedAt);
        } elseif ($index < 32) {
            $this->completeRejectedReturn($return, $index, $startedAt);
        } elseif ($index < 35) {
            $this->approvedButOpenReturn($return, $index, $startedAt);
        } elseif ($index < 37) {
            $this->rejectedButOpenReturn($return, $index, $startedAt);
        } elseif ($index < 40) {
            $this->inReviewReturn($return, $index, $startedAt);
        } elseif ($index < 42) {
            $this->waitingReturn($return, $index, $startedAt);
        } elseif ($index === 44) {
            $this->setReturnStatus($return, 'cancelled', $startedAt->addHours(3));
            $this->addNote($return, 'Kunde hat die Anfrage vor Versand der Ware zurückgezogen.', $startedAt->addHours(3)->addMinutes(2));
        }
    }

    private function completeApprovedReturn(ReturnModel $return, int $index, CarbonImmutable $startedAt): void
    {
        $decisionCode = ['refund_full', 'refund_partial', 'replacement', 'refund_no_return'][$index % 4];
        $decision = $this->decisions[$decisionCode];
        $reviewAt = $startedAt->addDays(5 + ($index % 4));

        if ($decision->requires_inbound_item) {
            $this->setReturnStatus($return, 'waiting_item', $startedAt->addHours(2));
            $shipment = $this->createShipment($return, 1, $startedAt->addHours(3), 2, 499 + (($index % 3) * 100));
            $this->progressShipment($shipment, $startedAt->addDays(1), $reviewAt->subHours(3));
        } else {
            $reviewAt = $startedAt->addDays(1);
            $this->addNote($return, 'Fotoprüfung abgeschlossen; Rücksendung aus Kulanz nicht erforderlich.', $reviewAt->subHours(2));
        }

        $this->setReturnStatus($return, 'in_review', $reviewAt);
        $this->addNote($return, $index % 2 === 0
            ? 'Wareneingang geprüft: Seriennummer und Lieferumfang stimmen überein.'
            : 'Prüfung abgeschlossen: gemeldeter Mangel konnte nachvollzogen werden.', $reviewAt->addHours(1));
        $this->setDecision($return, $decisionCode, $reviewAt->addHours(3));
        $this->setReturnStatus($return, 'approved', $reviewAt->addHours(4));

        if ($decision->requires_refund) {
            $amount = $decisionCode === 'refund_partial'
                ? (int) round($this->returnValue($return) * 0.6)
                : $this->returnValue($return);
            $refund = $this->createRefund($return, $amount, $reviewAt->addHours(5));
            $this->progressRefund($refund, $reviewAt->addDays(1), $reviewAt->addDays(2));
        }

        if ($decision->requires_outbound_shipment) {
            $shipment = $this->createShipment($return, 2, $reviewAt->addDays(1), 2, 0);
            $this->progressShipment($shipment, $reviewAt->addDays(2), $reviewAt->addDays(4));
        }

        $this->addNote($return, 'Vorgang vollständig dokumentiert; alle erforderlichen Folgeprozesse abgeschlossen.', $reviewAt->addDays(5));
        $this->setReturnStatus($return, 'closed', $reviewAt->addDays(5)->addMinutes(15));
    }

    private function completeRejectedReturn(ReturnModel $return, int $index, CarbonImmutable $startedAt): void
    {
        $conditionRejected = $index % 2 === 0;
        $reviewAt = $startedAt->addDays($conditionRejected ? 6 : 1);

        if ($conditionRejected) {
            $this->setReturnStatus($return, 'waiting_item', $startedAt->addHours(2));
            $inbound = $this->createShipment($return, 1, $startedAt->addHours(4), 1, 599);
            $this->progressShipment($inbound, $startedAt->addDays(2), $reviewAt->subHours(4));
        }

        $this->setReturnStatus($return, 'in_review', $reviewAt);
        $this->setDecision($return, $conditionRejected ? 'reject_condition' : 'reject_out_of_policy', $reviewAt->addHours(2));
        $this->addNote($return, $conditionRejected
            ? 'Ablehnung nach Prüfung: deutliche Gebrauchsspuren und Zubehör fehlt.'
            : 'Ablehnung: Antrag ging nach Ablauf der dokumentierten Rückgabefrist ein.', $reviewAt->addHours(2)->addMinutes(5));
        $this->setReturnStatus($return, 'rejected', $reviewAt->addHours(3));

        if ($conditionRejected) {
            $outbound = $this->createShipment($return, 2, $reviewAt->addDays(1), 1, 599);
            $this->progressShipment($outbound, $reviewAt->addDays(2), $reviewAt->addDays(4));
        }

        $this->setReturnStatus($return, 'closed', $reviewAt->addDays(5));
    }

    private function approvedButOpenReturn(ReturnModel $return, int $index, CarbonImmutable $startedAt): void
    {
        $decisionCode = $index === 34 ? 'replacement' : 'refund_full';
        $this->receiveForReview($return, $index, $startedAt);
        $decisionAt = $startedAt->addDays(5);
        $this->setDecision($return, $decisionCode, $decisionAt);
        $this->setReturnStatus($return, 'approved', $decisionAt->addHour());

        if ($decisionCode === 'replacement') {
            $this->createShipment($return, 2, $decisionAt->addHours(2), 2, 0);
            $this->addNote($return, 'Ersatzsendung angelegt; Übergabe an Versand steht noch aus.', $decisionAt->addHours(2)->addMinutes(5));
        } else {
            $refund = $this->createRefund($return, $this->returnValue($return), $decisionAt->addHours(2));
            if ($index % 2 === 0) {
                $this->setRefundStatus($refund, 'processing', $decisionAt->addDays(1));
            }
        }
    }

    private function rejectedButOpenReturn(ReturnModel $return, int $index, CarbonImmutable $startedAt): void
    {
        $this->receiveForReview($return, $index, $startedAt);
        $decisionAt = $startedAt->addDays(5);
        $this->setDecision($return, 'reject_condition', $decisionAt);
        $this->setReturnStatus($return, 'rejected', $decisionAt->addHour());
        $this->createShipment($return, 2, $decisionAt->addHours(2), 1, 599);
        $this->addNote($return, 'Ware wird nach Ablehnung an den Kunden zurückgesendet.', $decisionAt->addHours(2)->addMinutes(5));
    }

    private function inReviewReturn(ReturnModel $return, int $index, CarbonImmutable $startedAt): void
    {
        $this->receiveForReview($return, $index, $startedAt);
        $this->addNote($return, ['Technische Funktionsprüfung läuft.', 'Fotos des Transportschadens werden geprüft.', 'Rückfrage zum fehlenden Zubehör an Kunden gesendet.'][$index - 37], $startedAt->addDays(5));
    }

    private function waitingReturn(ReturnModel $return, int $index, CarbonImmutable $startedAt): void
    {
        $this->setReturnStatus($return, 'waiting_item', $startedAt->addHours(2));
        $shipment = $this->createShipment($return, 1, $startedAt->addHours(3), 2, 499);
        if ($index === 41) {
            $this->setShipmentStatus($shipment, 'shipped', $startedAt->addDays(1));
            $this->setShipmentStatus($shipment, 'in_transit', $startedAt->addDays(2));
        }
        $this->addNote($return, $index === 40 ? 'Retourenlabel bereitgestellt; Einlieferung steht aus.' : 'Sendung ist unterwegs zum Lager.', $startedAt->addDays(2)->addHour());
    }

    private function receiveForReview(ReturnModel $return, int $index, CarbonImmutable $startedAt): void
    {
        $this->setReturnStatus($return, 'waiting_item', $startedAt->addHours(2));
        $shipment = $this->createShipment($return, 1, $startedAt->addHours(3), 2, 499 + (($index % 3) * 100));
        $this->progressShipment($shipment, $startedAt->addDays(1), $startedAt->addDays(4));
        $this->setReturnStatus($return, 'in_review', $startedAt->addDays(4)->addHour());
    }

    private function createShipment(ReturnModel $return, int $direction, CarbonImmutable $at, int $payer, int $costCents): ReturnShipment
    {
        Carbon::setTestNow($at);
        $number = ++$this->shipmentNumber;

        $shipment = $return->shipments()->save(new ReturnShipment([
            'direction' => $direction,
            'payer' => $payer,
            'cost_cents' => $costCents,
            'currency' => 'EUR',
            'status_id' => $this->shipmentStatuses['created']->id,
            'carrier' => $number % 2 === 0 ? 'DHL' : 'Hermes',
            'tracking_number' => 'DEMO-TRACK-' . str_pad((string) $number, 10, '0', STR_PAD_LEFT),
            'label_ref' => 'DEMO-LABEL-' . $number,
        ]));
        $shipment->shipment_number = $number;
        return $shipment;
    }

    private function progressShipment(ReturnShipment $shipment, CarbonImmutable $shippedAt, CarbonImmutable $deliveredAt): void
    {
        $this->setShipmentStatus($shipment, 'shipped', $shippedAt);
        $this->setShipmentStatus($shipment, 'in_transit', $shippedAt->addHours(12));
        $this->setShipmentStatus($shipment, 'delivered', $deliveredAt);
    }

    private function setShipmentStatus(ReturnShipment $shipment, string $code, CarbonImmutable $at): void
    {
        Carbon::setTestNow($at);
        $shipment->status_id = $this->shipmentStatuses[$code]->id;
        $shipment->save();
    }

    private function createRefund(ReturnModel $return, int $amountCents, CarbonImmutable $at): ReturnRefund
    {
        Carbon::setTestNow($at);
        $number = ++$this->refundNumber;

        $refund = $return->refunds()->save(new ReturnRefund([
            'status_id' => $this->refundStatuses['pending']->id,
            'amount_cents' => $amountCents,
            'currency' => 'EUR',
            'reference' => 'DEMO-RF-' . str_pad((string) $number, 8, '0', STR_PAD_LEFT),
        ]));
        $refund->refund_number = $number;
        return $refund;
    }

    private function progressRefund(ReturnRefund $refund, CarbonImmutable $processingAt, CarbonImmutable $refundedAt): void
    {
        $this->setRefundStatus($refund, 'processing', $processingAt);
        Carbon::setTestNow($refundedAt);
        $refund->status_id = $this->refundStatuses['refunded']->id;
        $refund->processed_at = $refundedAt;
        $refund->save();
    }

    private function setRefundStatus(ReturnRefund $refund, string $code, CarbonImmutable $at): void
    {
        Carbon::setTestNow($at);
        $refund->status_id = $this->refundStatuses[$code]->id;
        $refund->save();
    }

    private function setReturnStatus(ReturnModel $return, string $code, CarbonImmutable $at): void
    {
        Carbon::setTestNow($at);
        $return->status_id = $this->returnStatuses[$code]->id;
        $return->save();
    }

    private function setDecision(ReturnModel $return, string $code, CarbonImmutable $at): void
    {
        Carbon::setTestNow($at);
        $return->decision_id = $this->decisions[$code]->id;
        $return->save();
    }

    private function addNote(ReturnModel $return, string $note, CarbonImmutable $at): void
    {
        Carbon::setTestNow($at);
        $return->notes()->create([
            'organization_id' => $this->user->current_organization_id,
            'created_by_user_id' => $this->user->id,
            'note' => $note,
        ]);
    }

    /** @return array{name: string, email: string, phone: string, address_text: string} */
    private function customerData(int $index): array
    {
        $firstNames = ['Anna', 'Lukas', 'Sophie', 'Felix', 'Leonie', 'Jonas', 'Clara', 'Paul', 'Mia', 'Maximilian', 'Emilia', 'Leon', 'Johanna', 'David', 'Nina'];
        $lastNames = ['Mustermann', 'Beispiel', 'Testmann', 'Musterfrau', 'Demokunde', 'Beispielmann', 'Testkunde', 'Musterberg', 'Fiktiv', 'Probe'];
        $cities = ['Berlin', 'Hamburg', 'München', 'Köln', 'Leipzig', 'Bremen', 'Dresden', 'Hannover', 'Nürnberg', 'Potsdam'];
        $postalCodes = ['10115', '20095', '80331', '50667', '04109', '28195', '01067', '30159', '90402', '14467'];
        $cityIndex = $index % count($cities);

        return [
            'name' => $firstNames[$index % count($firstNames)] . ' ' . $lastNames[$index % count($lastNames)] . ' (Musterkunde)',
            'email' => 'demo.kunde.' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) . '@example.invalid',
            'phone' => '+49 000 0000 ' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
            'address_text' => 'MUSTERADRESSE – NICHT ZUSTELLBAR\nMusterweg ' . ($index + 1) . '\n' . $postalCodes[$cityIndex] . ' ' . $cities[$cityIndex],
        ];
    }

    /** @return array<int, array{sku: string, item_name: string, quantity: int, unit_price_cents: int, currency: string}> */
    private function items(int $index): array
    {
        $catalog = [
            ['sku' => 'IKEA-80275887', 'item_name' => 'IKEA KALLAX Regal, weiß, 77×147 cm', 'quantity' => 1, 'unit_price_cents' => 5999, 'currency' => 'EUR'],
            ['sku' => 'IKEA-30275861', 'item_name' => 'IKEA KALLAX Regal, weiß, 147×147 cm', 'quantity' => 1, 'unit_price_cents' => 12900, 'currency' => 'EUR'],
            ['sku' => 'IKEA-BILLY-WEISS', 'item_name' => 'IKEA BILLY Bücherregal, weiß, 80×28×202 cm', 'quantity' => 1, 'unit_price_cents' => 4999, 'currency' => 'EUR'],
            ['sku' => 'IKEA-KALLAX-77', 'item_name' => 'IKEA KALLAX Regal, weiß, 77×77 cm', 'quantity' => 1, 'unit_price_cents' => 2999, 'currency' => 'EUR'],
        ];
        $items = [$catalog[$index % count($catalog)]];
        if ($index % 3 === 0) {
            $items[] = $catalog[($index + 1) % count($catalog)];
        }

        return $items;
    }

    private function reason(int $index): string
    {
        return [
            'Verpackung bei Anlieferung beschädigt; eine Seitenwand hat eine sichtbare Druckstelle.',
            'Farbe passt nicht zur übrigen Einrichtung; Ware nur zur Ansicht ausgepackt.',
            'Bohrungen sind versetzt, Montage laut Anleitung nicht möglich.',
            'Falsche Größe bestellt; Originalverpackung und Zubehör vollständig vorhanden.',
            'Ein Bauteil fehlt in der Verpackung.',
            'Oberfläche weist bereits beim Auspacken Kratzer auf.',
            'Artikel doppelt bestellt.',
        ][$index % 7];
    }

    private function returnValue(ReturnModel $return): int
    {
        return (int) $return->items()->get()->sum(fn (ReturnItem $item): int => $item->quantity * $item->unit_price_cents);
    }
}
