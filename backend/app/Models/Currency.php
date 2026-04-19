<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;


/**
 * Currency
 *
 * @author Alexandra Tikhomirova
 */
class Currency
{

    public string $code;
    public string $symbol;


    public function __construct(string $code, string $symbol)
    {
        $this->code = $code;
        $this->symbol = $symbol;
    }
    public static function rates(): array
    {
        return [
            'EUR', 1, //€
            'USD', 1.1805, //$
            'GBP', 0.87630, //£
        ];
    }

    public static function active(): Currency
    {
        return new Currency('EUR', '€');
    }
}
