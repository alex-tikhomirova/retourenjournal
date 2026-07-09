# Roadmap to MVP

Документ рабочий. Его задача - не красиво описать проект, а помочь довести Retourenjournal до демонстрируемого MVP без расползания фокуса.

Роль AI/PM: держать порядок задач, уточнять приоритеты, давать следующий небольшой шаг, фиксировать забытые задачи и помогать не хвататься за всё сразу.

## 1. MVP Definition

MVP считается готовым, когда можно показать полный рабочий сценарий немецкому/европейскому зрителю:

1. Пользователь регистрируется или входит.
2. Пользователь создает организацию и работает внутри активной организации.
3. Пользователь создает возврат с покупателем и товарами.
4. Возврат получает начальный статус.
5. Пользователь видит список возвратов и открывает карточку возврата.
6. Пользователь меняет статус возврата по понятной логике.
7. Пользователь выбирает решение по возврату.
8. Пользователь добавляет доставку и меняет/видит её данные.
9. Пользователь добавляет refund и меняет/видит его статус.
10. Пользователь видит timeline/audit значимых действий.
11. Данные одной организации не видны другой организации.
12. Интерфейс выглядит цельно на немецком языке.
13. Проект можно локально поднять, прогнать smoke tests и показать как портфолио/MVP.

## 2. Current Project State

Уже есть:

- Laravel backend + PostgreSQL migrations.
- Vue SPA + Vite.
- Sanctum/Breeze API authentication.
- Организации и `current_organization_id`.
- Multi-tenant scope через `OrganizationScope`.
- Доменные таблицы: returns, items, customers, shipments, refunds, notes, events, statuses, decisions.
- API для организации, списка возвратов, просмотра возврата, создания/обновления возврата.
- API для создания/обновления shipments.
- API для создания/обновления refunds.
- Lookup API для return statuses, decisions, shipment statuses, refund statuses.
- Основной frontend flow: returns list -> create return -> return detail.
- UI-блоки для статуса, решения, товаров, клиента, shipment list, refund list, timeline.
- `docs/CONCEPT.md` с русской и английской выдержкой.

Основные незавершенности из кода:

- `frontend/src/pages/app/return/refund/RefundForm.vue` пустой, refund creation UI не завершен.
- `RefundItem.vue` не показывает статус refund.
- В `ReturnRefundService` TODO по logging и recalculation.
- В `ShipmentService` TODO по logging и recalculation, хотя часть событий уже есть в `ReturnShipment` model.
- Нет отдельного API/UI для notes, хотя таблица и relation есть.
- Нет domain feature tests для returns/shipments/refunds/tenant isolation.
- Нет нормальной обработки validation errors в формах.
- UI тексты местами смешаны DE/EN и местами видна битая кодировка.
- `RegisterPage.vue` пока на английском, а продукт должен быть German-first.
- `ReturnResource` пустой и отдает модель по умолчанию; для MVP лучше зафиксировать явный API contract.
- Валидация местами подозрительная: например `required|nullable` в update request, refund update готовит `amount_cents` из `cost`, но сервис сумму не обновляет.
- Нет финального README/runbook для локального запуска и demo walkthrough.
- Форма создания возврата требует доводки: подсказка/генерация номера, frontend validation, понятные required/optional поля, редирект в созданный возврат.
- Страница возврата требует доводки: редактирование данных клиента, стиль решений, подсказки по следующим действиям, сброс решения, refund form/status flow.
- Нет единой frontend-системы пользовательских сообщений/alerts для ошибок API и успешных действий.
- Public surface не завершен: app header, landing page, legal placeholders/docs для немецкого рынка.

## 3. Work Strategy

Работаем короткими итерациями. Не улучшаем всё подряд.

Правило приоритета:

1. Сначала закрываем полный доменный workflow.
2. Потом делаем его надежным и tenant-safe.
3. Потом приводим UI к demo-ready состоянию.
4. Потом документация, тесты, README, портфолио-упаковка.
5. Коммерческие идеи, интеграции и расширения - после MVP.

Definition of done для каждой задачи:

- работает в UI или API;
- ошибка пользователя обработана понятным сообщением;
- tenant isolation не нарушена;
- немецкий UI-текст не выглядит случайным;
- если задача касается backend-домена, добавлен хотя бы один feature/smoke test или ручной check записан в roadmap.

## 4. Phase 0 - Stabilize Baseline

Цель: понять, что текущий проект запускается, и зафиксировать чистую стартовую точку.

### P0.1 Проверить текущее рабочее дерево

Статус: todo

Задачи:

- Разобрать уже существующие незакоммиченные изменения в backend/frontend.
- Понять, какие изменения были сделаны вручную и должны остаться.
- Отдельно закоммитить документацию `docs/CONCEPT.md` и `docs/ROADMAP_TO_MVP.md`, если всё ок.
- Не смешивать roadmap-коммит с кодовыми правками.

Acceptance criteria:

- Есть понятный `git status`.
- Документация отделена от функциональных изменений.
- Нет случайно потертых пользовательских правок.

### P0.2 Проверить локальный запуск

Статус: todo

Задачи:

- Поднять backend и PostgreSQL через docker compose или текущий локальный способ.
- Поднять frontend через `npm run dev`.
- Проверить, что `/`, `/login`, `/register`, `/app/returns` открываются.
- Проверить, что Sanctum CSRF/login работает.

Acceptance criteria:

- Есть короткая инструкция запуска.
- Из браузера можно войти или зарегистрироваться.
- Ошибки запуска записаны в roadmap как отдельные задачи.

### P0.3 Проверить миграции с нуля

Статус: todo

Задачи:

- Прогнать fresh migrate на dev/test базе.
- Убедиться, что системные statuses и decisions создаются.
- Проверить, нет ли конфликтов в nullable unique для global dictionaries.

Acceptance criteria:

- Новая база поднимается без ручных SQL-фиксов.
- После миграций доступны return statuses, shipment statuses, refund statuses, decisions.

## 5. Phase 1 - Complete Core Return Workflow

Цель: один возврат можно провести от создания до закрытия без обходных путей.

### P1.0 Return creation form UX

Статус: todo

Задачи:

- Определить обязательные и необязательные поля формы создания возврата.
- Сделать подсказку для `return_number`: авто-предзаполнение, понятный prefix, возможность ручного изменения.
- Решить формат номера для MVP: например `RET-YYYY-XXXX` или простой `RET-<id/sequence>`.
- Добавить frontend validation до отправки формы: customer name, at least one item, item name, quantity, корректные суммы.
- Добавить отображение server-side validation errors рядом с полями или в общей зоне формы.
- Автокомплит клиента по телефону/email оставить как optional, если после основного workflow останется время.

MVP recommendation:

- В MVP сделать удобный номер возврата и frontend validation.
- Автокомплит/подгрузку существующего клиента по телефону перенести после MVP или сделать только очень простой lookup, если API уже почти готов.

Acceptance criteria:

- Пользователь понимает, какие поля обязательны.
- Номер возврата предложен автоматически, но его можно изменить.
- Ошибки формы понятны до и после ответа API.

### P1.1 Return create/list/detail polish

Статус: todo

Задачи:

- Добавить loading/error/empty states на `ReturnsPage.vue`.
- После создания возврата вести пользователя на карточку созданного возврата, а не просто обратно в список.
- На `ReturnNewPage.vue` обработать validation errors от API.
- Подсветить optional поля и не заставлять заполнять лишнее для быстрого создания.
- Перевести оставшиеся labels на немецкий: например `reason` -> `Grund`.
- Проверить, что `return_number` можно оставить пустым и backend его сгенерирует.

Acceptance criteria:

- Создание возврата понятно пользователю.
- При ошибке API форма не молчит.
- После сохранения пользователь видит созданный возврат.

### P1.2 Return status workflow

Статус: todo

Задачи:

- Зафиксировать разрешенные переходы статусов в документе или коде.
- Проверить `StateSwitch.vue`: created -> waiting_item/in_review, in_review -> approved/rejected, approved/rejected -> closed, closed/cancelled -> restore.
- Решить, нужен ли статус `cancelled` как кнопка в MVP.
- На backend ограничить или хотя бы валидировать `status_id`, чтобы нельзя было отправить несуществующий статус.
- Проверить event logging для `status_id`.

Acceptance criteria:

- Статус меняется из UI.
- Timeline показывает понятное событие на немецком.
- Нельзя установить чужой/несуществующий статус.

### P1.3 Decision workflow

Статус: todo

Задачи:

- Проверить все seeded return decisions и их outcome.
- Убедиться, что выбор решения сохраняется через API.
- Проверить, что кнопки approve/reject появляются только когда есть подходящее решение.
- Убрать риск случайного сброса decision без подтверждения, если это мешает UX.
- Если решение уже выбрано, кнопка `Ändern` должна переводить блок в режим редактирования/сброса, а не выглядеть как неработающая кнопка.
- Добавить подсказки о следующих действиях после решения: нужна ли обратная отправка, нужен ли refund, можно ли закрывать возврат.
- Привести карточки/подсказки решения к общему визуальному стилю приложения.

Acceptance criteria:

- Пользователь может выбрать решение.
- Решение влияет на доступные следующие статусы.
- Timeline показывает смену решения.

### P1.4 Customer and items edit decision

Статус: todo

Задачи:

- Сделать редактирование пользовательских/customer данных на странице возврата: name, email, phone, address_text.
- Решить, обновляется ли существующий `Customer` или только customer snapshot для возврата. Если snapshot нет, аккуратно обновлять текущего customer в рамках tenant.
- Для items принять MVP-решение: редактировать после создания или только показывать.
- Если items не редактируем, убрать/задизейблить кнопки `Bearbeiten`, которые ничего не делают.
- Если items редактируем, добавить API и UI отдельной задачей после customer edit.

MVP recommendation:

- Customer editing включить в MVP, потому что ошибка в телефоне/email реальна и раздражает.
- Items editing оставить optional: для первого MVP достаточно корректно создать и показать товары.

Acceptance criteria:

- Пользователь может исправить данные клиента из карточки возврата.
- В UI нет кнопок-пустышек.
- Поведение по товарам явно понятно.

## 6. Phase 2 - Shipments and Refunds

Цель: закрыть финансовую и логистическую прослеживаемость, потому что это часть ценности продукта.

### P2.1 Shipment create/update finish

Статус: todo

Задачи:

- Проверить `ReturnShipmentForm.vue`: создание работает, edit работает или честно недоступен.
- Проверить `ShipmentItem.vue`: можно ли менять shipment status, carrier, tracking.
- Исправить тексты и кодировку в shipment UI.
- В backend добавить/согласовать logging для shipment created/status/tracking changes.
- Решить, нужен ли автоматический пересчет return status от shipment в MVP.

MVP recommendation:

- Автоматический пересчет return status от shipment пока не делать.
- События shipment в timeline оставить.
- Статус возврата менять вручную через `StateSwitch`.

Acceptance criteria:

- Можно добавить shipment.
- Можно увидеть carrier/tracking/status.
- Timeline показывает важные shipment changes.

### P2.2 Refund create UI

Статус: todo

Задачи:

- Реализовать `RefundForm.vue`.
- Поля MVP: amount, currency EUR, reference.
- После save закрывать форму и обновлять return detail.
- Добавить validation errors.
- Немецкие labels: Betrag, Referenz, Erstattung anlegen, Abbrechen, Speichern.

Acceptance criteria:

- Refund можно создать из карточки возврата.
- Refund появляется в списке без перезагрузки страницы вручную.
- Ошибки показываются пользователю.

### P2.3 Refund status/update

Статус: todo

Задачи:

- В `RefundItem.vue` показать refund status.
- Добавить смену refund status или простой edit control.
- Проверить `ReturnRefundUpdateRequest`: убрать странный `cost -> amount_cents`, если сумма не обновляется.
- В `ReturnRefundService` обновлять нужные поля явно.
- При статусе `refunded` выставлять `processed_at`, если это нужно MVP.
- Добавить refund events в `return_events` или решить, что refund status виден только в refund list.

MVP recommendation:

- Добавить refund events для `created`, `status_id`, `reference`.
- `processed_at` выставлять при переходе в `refunded`.

Acceptance criteria:

- Refund имеет видимый статус.
- Статус можно изменить.
- Timeline или audit отражает refund-действия.

## 7. Phase 3 - Audit Trail and Data Integrity

Цель: сделать историю действий правдоподобной и полезной, а multi-tenant изоляцию надежной.

### P3.1 Explicit API resources

Статус: todo

Задачи:

- Заполнить `ReturnResource` явным `toArray()`.
- Проверить `ReturnShipmentResource`, `ReturnRefundResource`, `ReturnEventResource`.
- Убедиться, что frontend получает стабильный shape, а не случайную Eloquent-сериализацию.

Acceptance criteria:

- API contract понятен из resources.
- Frontend не зависит от случайно подгруженных relation names.

### P3.2 Tenant isolation audit

Статус: todo

Задачи:

- Проверить route model binding для `ReturnModel $return` под `OrganizationScope`.
- Проверить, что update shipment/refund не может обновить запись другого return внутри той же или другой организации.
- В сервисах update искать shipment/refund с привязкой к `$return->id`, а не только по id.
- Добавить tests на cross-tenant access denied/not found.

Acceptance criteria:

- Нельзя прочитать/обновить чужой return.
- Нельзя обновить чужой shipment/refund, даже если известен id.

### P3.3 Event model cleanup

Статус: todo

Задачи:

- Проверить `ReturnEvent::getEventTitleAttribute()` на null/неожиданный field.
- Проверить `getEventRefAttribute()` на безопасное поведение, чтобы timeline не падал при удаленной ссылке.
- Добавить event fields для refund.
- Исправить немецкие titles с нормальной UTF-8 кодировкой.
- Решить правило для create events: при создании сущности писать `erstellt/angelegt`, а не визуально показывать это как обычное изменение поля.
- Определить, какие поля логировать при создании return/shipment/refund, чтобы timeline был полезным, но не шумным.

MVP recommendation:

- Для создания сущности показывать одно понятное событие: `Retoure erstellt`, `Sendung angelegt`, `Erstattung angelegt`.
- Детальные field changes логировать для статусов, решения, tracking number, refund status/reference.

Acceptance criteria:

- Timeline не ломает карточку возврата.
- События читаемы на немецком.
- Создание и изменение визуально различаются.

### P3.4 Notes decision

Статус: todo

Задачи:

- Решить, входят ли notes в MVP.
- Если входят: добавить API create/list и UI маленького блока комментариев.
- Если не входят: оставить relation/table, но не показывать в UI и не рекламировать как feature.

MVP recommendation:

- Notes можно отложить после core workflow, если timeline уже есть.

Acceptance criteria:

- Нет заявленной, но неработающей функции.

## 8. Phase 4 - German-first UI and Demo Quality

Цель: приложение должно выглядеть как осознанный немецкий B2B tool, а не смесь черновиков.

### P4.1 German text pass

Статус: todo

Задачи:

- Пройти все Vue-файлы и привести visible text к немецкому.
- Исправить битые строки: `RÃ¼ckgabe`, `lÃ¤uft`, `DatenschutzerklÃ¤rung`, etc.
- Register page перевести с английского на немецкий.
- Единообразно выбрать термины: Retoure, Rückgabe, Erstattung, Sendung, Händler.

Acceptance criteria:

- В интерфейсе нет английского, кроме технически оправданных слов типа SKU.
- Umlauts отображаются корректно.

### P4.2 Error/loading/empty states and alerts

Статус: todo

Задачи:

- Добавить загрузку на returns list и return detail.
- Добавить empty states для shipments/refunds/items/history.
- Добавить catch/error display для create/update actions.
- Сделать простую систему alerts/toasts для пользовательских сообщений: success, warning, error.
- Разделить ошибки API на пользовательские и технические.
- Глобально обработать 401/419 в axios interceptor или хотя бы не оставлять пользователя без реакции.

MVP recommendation:

- Не показывать пользователю сырые exception traces или технический JSON.
- Для validation errors показывать поля/сообщения.
- Для неожиданных ошибок показывать спокойное немецкое сообщение вроде `Aktion konnte nicht ausgeführt werden. Bitte versuchen Sie es erneut.` и писать детали в console/log только для dev.

Acceptance criteria:

- Пользователь понимает, что происходит при загрузке и ошибке.
- Ошибка API не выглядит как сломанная кнопка.
- Необработанные исключения не пугают пользователя техническими деталями.

### P4.3 Navigation, app shell, and headers

Статус: todo

Задачи:

- Заполнить `AppLayout.vue`: текущая организация, навигация к Retouren, logout.
- Убрать пустые nav/action placeholders.
- Доработать шапку внутри приложения: active nav, account/org area, logout.
- Доработать шапку на landing/auth/public pages, чтобы бренд выглядел единообразно.
- Footer оставить аккуратным или упростить.

Acceptance criteria:

- После входа понятно, где пользователь находится и как выйти.
- Public и app surfaces выглядят как один продукт.

### P4.4 Responsive sanity pass

Статус: todo

Задачи:

- Проверить returns list и return detail на обычном ноутбуке.
- Проверить узкий экран хотя бы на базовую читаемость.
- Исправить самые явные overflow/table issues.

Acceptance criteria:

- Demo на ноутбуке выглядит стабильно.
- Узкий экран не разваливается критично.

## 9. Phase 5 - Public Surface, Legal, and Launch Readiness

Цель: подготовить то, что видит внешний человек до входа в приложение, и не забыть юридический минимум для немецкого рынка.

### P5.1 Landing page

Статус: todo

Задачи:

- Сделать красивую German-first landing page.
- Показать продукт ясно: Retourenjournal как простой инструмент для Retourenmanagement без ERP.
- Добавить CTA: регистрация/вход.
- Добавить секции: problem, workflow, benefits, target users, limits/non-goals.
- Добавить 1-2 visual/product screenshots или аккуратные mockups после готовности UI.

Acceptance criteria:

- Посадочная объясняет продукт за 20-30 секунд.
- Нет обещаний функций, которых нет в MVP.

### P5.2 Feature teasers / placeholders

Статус: todo

Задачи:

- Прикинуть post-MVP возможности, которые можно показать как `Geplant`/`Coming later`.
- Возможные teaser-заглушки: integrations, analytics, custom statuses, team roles, export, email notifications.
- Не делать fake-working UI: если feature не работает, она должна быть явно помечена как planned.

Acceptance criteria:

- Есть аккуратные заманухи для будущего развития.
- Пользователь не может принять заглушку за рабочую функцию.

### P5.3 Legal documents checklist

Статус: todo

Важно: это не юридическая консультация. Для публичного запуска в Германии документы нужно перепроверить с актуальными требованиями или специалистом.

Задачи:

- Подготовить placeholder/checklist для `Impressum`.
- Подготовить placeholder/checklist для `Datenschutzerklärung`.
- Подготовить Terms/Nutzungsbedingungen или service terms для бесплатного сервиса `as is`.
- Подготовить privacy notes по cookies, auth/session, server logs, contact data, customer data inside returns.
- Решить, нужен ли AVV/DPA как B2B SaaS, если клиенты вводят данные своих покупателей.
- Добавить public routes/pages или хотя бы footer links без битых ссылок.

Acceptance criteria:

- В footer нет юридических ссылок в никуда.
- Есть список юридических документов и кто/когда должен их проверить.

## 10. Phase 6 - Tests and Release Packaging

Цель: перед показом иметь минимальную уверенность и понятный способ запуска.

### P6.1 Backend feature tests

Статус: todo

Минимальный набор:

- user can create organization;
- user can create return with customer and items;
- user can list only own organization returns;
- user can update return status/decision;
- user can create shipment for own return;
- user can create refund for own return;
- user cannot access another organization's return/shipment/refund;
- return events are created for important changes.

Acceptance criteria:

- `composer test` или `php artisan test` проходит.
- Тесты покрывают не всё, но защищают MVP demo flow.

### P6.2 Frontend build check

Статус: todo

Задачи:

- Прогнать `npm run build`.
- Исправить build errors/warnings, которые мешают сборке.
- Проверить, что production build открывается через preview или backend static strategy, если нужно.

Acceptance criteria:

- Frontend собирается без ошибок.

### P6.3 README and demo script

Статус: todo

Задачи:

- Обновить README без битой кодировки.
- Добавить local setup: backend, db, frontend.
- Добавить demo walkthrough на 5-7 шагов.
- Добавить статус проекта: API-first MVP, proprietary, German-first UI.

Acceptance criteria:

- Новый человек понимает, что это за проект и как его запустить.
- Есть сценарий демонстрации.

### P6.4 Portfolio packaging

Статус: later

Задачи:

- Сделать 3-5 screenshots.
- Написать short case study: problem, architecture, trade-offs, multi-tenant isolation, audit trail.
- Подготовить короткое описание для CV/GitHub/LinkedIn.

Acceptance criteria:

- Проект можно показать как работу разработчика, а не только как локальный код.

## 11. Parking Lot - Not MVP

Не берем до MVP, если только явно не понадобится:

- DHL/DPD/Hermes API integrations.
- Shopify/WooCommerce/eBay/Amazon integrations.
- Payment provider integrations.
- Real shipping labels.
- Full customer portal.
- Team roles/permissions beyond owner/member basics.
- Custom organization-specific statuses UI.
- Advanced dashboard analytics.
- Email notifications.
- Public pricing/monetization pages.
- Customer autocomplete by phone/email, unless the basic customer edit flow is already stable.
- Real customer merge/deduplication.
- Legal pages beyond placeholder/checklist level before legal review.

## 12. Immediate Next Tasks

Следующий рабочий порядок:

1. Разобрать текущие незакоммиченные изменения и отделить documentation changes от code changes.
2. Запустить проект локально и проверить базовый auth/onboarding/returns flow.
3. Довести форму создания возврата: required/optional поля, подсказка номера, frontend validation, API errors, redirect в созданный возврат.
4. Доработать страницу возврата: customer edit, убрать кнопки-пустышки, привести decision block к стилю.
5. Закрыть refund creation UI: `RefundForm.vue`.
6. Исправить refund backend update/status/processed_at.
7. Добавить/проверить refund events в timeline и правило create vs update events.
8. Усилить tenant-safe update для shipment/refund.
9. Перепроверить весь workflow вручную: registration -> organization -> return -> decision -> shipment -> refund -> close.
10. Пройти немецкие visible strings и кодировку в ключевых страницах.
11. Доработать app/public headers и landing skeleton.
12. Добавить минимальные backend feature tests.
13. Обновить README и demo walkthrough.

## 13. How We Will Work

Когда ты не знаешь, за что браться, порядок такой:

1. Открываем этот roadmap.
2. Берем первую todo-задачу из `Immediate Next Tasks`.
3. Я дроблю её на маленькие задания: что открыть, что проверить, что изменить.
4. После выполнения я обновляю roadmap или предлагаю, какую галочку поставить.
5. Если всплывает забытая задача, добавляем её в нужную фазу или Parking Lot.

Формат задания от PM:

- Цель: одно предложение.
- Файлы: где работать.
- Сделать: 3-7 конкретных действий.
- Проверить: как понять, что готово.
- Не трогать: что специально оставляем вне задачи.

## 14. Open Questions

Нужно решить по мере работы:

- Нужны ли notes в первом MVP или timeline достаточно?
- Нужна ли отмена возврата (`cancelled`) как явная кнопка?
- Нужно ли разрешать редактирование customer/items после создания возврата?
- Нужны ли роли в организации сейчас или достаточно owner/current organization?
- Какой demo path важнее: портфолио для работодателя или продукт для потенциального клиента?