# Retourenjournal / Return Management Tool

## Русская выдержка

Retourenjournal - это API-first B2B SaaS-приложение для управления возвратами товаров на немецком рынке. Проект находится на стадии MVP и строится как связка Laravel API, PostgreSQL и Vue SPA без Inertia.

Продукт предназначен не для замены ERP, интернет-магазина или логистической платформы, а для структурированного учета возвратов: кто вернул товар, по какому заказу, какие позиции участвуют в возврате, какие доставки были созданы, нужна ли компенсация, в каком статусе находится процесс и какие действия уже произошли.

Основная аудитория:

- kleine und mittlere Online-Shops;
- Etsy, eBay и Instagram-продавцы;
- B2B-компании, работающие по счетам;
- компании, которым нужен контроль возвратов без внедрения полноценной ERP.

Ключевая ценность:

- прозрачная статусная модель возврата;
- история значимых действий через `return_events`;
- финансовая прослеживаемость через `return_refunds`;
- логистическая прослеживаемость через `return_shipments`;
- строгая multi-tenant изоляция данных по `organization_id`;
- один активный tenant у пользователя через `current_organization_id`.

## Минимальный workflow

1. Пользователь регистрируется и создает или выбирает активную организацию.
2. Сотрудник создает возврат с номером возврата, покупателем, ссылкой на заказ и позициями.
3. Возврат получает начальный системный статус.
4. В возврат добавляются одна или несколько доставок: клиент -> продавец, продавец -> клиент, повторные отправки.
5. По доставкам фиксируются перевозчик, tracking number, стоимость, плательщик и статус.
6. При необходимости создается возврат денег с суммой, валютой, reference и собственным статусом.
7. Изменения доменной модели попадают в `return_events` как audit trail и timeline.
8. Возврат закрывается терминальным статусом: например `closed`, `rejected` или `cancelled`.

## Архитектурные принципы

- API-first: frontend работает как отдельная Vue SPA поверх REST API.
- Backend: Laravel, Sanctum, PostgreSQL.
- Frontend: Vue, Vite, Pinia, router.
- Multi-tenant by default: доменные таблицы привязаны к организации.
- `OrganizationScope` автоматически ограничивает запросы активной организацией пользователя.
- Системные справочники статусов доступны глобально, но модель допускает расширение под организацию.
- Источник истины - доменные таблицы; `return_events` не заменяет данные, а объясняет историю изменений.
- Интерфейс продукта ведется на немецком языке.
- Рабочая документация и обсуждения с ИИ ведутся на русском.

## Текущий MVP scope

Реализованная API-поверхность включает:

- authentication через Laravel Breeze API + Sanctum;
- текущую организацию пользователя;
- создание организации;
- список возвратов;
- просмотр, создание и обновление возврата;
- создание и обновление доставок возврата;
- создание и обновление refund-записей;
- lookup endpoints для статусов возврата, решений, статусов доставки и refund-статусов.

Доменная модель включает:

- `organizations`, `organization_user`, `users`;
- `customers`;
- `returns`, `return_items`;
- `return_statuses`, `return_decisions`;
- `return_shipments`, `shipment_statuses`;
- `return_refunds`, `refund_statuses`;
- `return_notes`;
- `return_events`.

## Non-goals

Retourenjournal сознательно не включает в MVP:

- полноценную ERP;
- бухгалтерский учет;
- складской учет;
- автоматическую интеграцию с DHL, Shopify, WooCommerce, eBay или Amazon;
- автоматическое создание shipping labels;
- платежный процессинг;
- customer support/helpdesk.

## Product positioning

Это бесплатный сервис "as is" и одновременно портфолио-проект с коммерческим потенциалом. Его можно развивать как:

- демонстрацию backend/full-stack архитектуры;
- основу для кастомной доработки под конкретную компанию;
- продукт для продажи прав или передачи заинтересованному бизнесу;
- повод для job/consulting conversation вокруг Laravel, Vue, API-first и multi-tenant SaaS.

---

## English Snapshot

Retourenjournal is an API-first B2B SaaS application for return management on the German market. The MVP is built with Laravel, PostgreSQL, and a separate Vue SPA.

The product is intentionally focused. It is not an ERP, shop integration layer, logistics automation tool, accounting system, or helpdesk. It exists to give small businesses a structured way to track return cases, return items, customers, shipments, refunds, statuses, and the history of important actions.

Target users:

- small and medium online shops;
- Etsy, eBay, and Instagram sellers;
- B2B companies working with invoices;
- companies that need return control without ERP complexity.

Core value:

- transparent return lifecycle;
- strict status model;
- audit timeline through `return_events`;
- financial traceability through refunds;
- logistical traceability through shipments;
- strong multi-tenant isolation by `organization_id`;
- one active organization per user through `current_organization_id`.

The product interface is German-first. Internal planning and AI-facing documentation are maintained in Russian.

## MVP Workflow

1. A user signs in and works inside the current organization.
2. An employee creates a return with customer data, order reference, and return items.
3. The system assigns the initial return status.
4. One or more shipments can be attached to the return.
5. Shipment status, carrier, tracking number, cost, and payer can be tracked.
6. Optional refunds can be created and updated with their own status lifecycle.
7. Significant changes are represented as return events for audit and timeline use.
8. The return is eventually closed with a terminal status.

## Technical Snapshot

- Backend: Laravel API, Sanctum auth, PostgreSQL.
- Frontend: Vue SPA, Vite, Pinia, Vue Router.
- Architecture: API-first, multi-tenant, domain tables as source of truth.
- Tenant isolation: organization-scoped models use an Eloquent global scope.
- Statuses: system dictionaries with room for organization-specific extension.
- UI language: German.
- Documentation language: Russian, with English summaries for external readers.

## Commercial/Portfolio Goal

Retourenjournal is a practical MVP and portfolio-grade architecture sample. It can be used as a foundation for custom development, a product demo for job discussions, or a base for a future commercial version if there is demand.
