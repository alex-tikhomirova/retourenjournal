# Retourenjournal

API-first B2B SaaS application for **return management (Retourenmanagement)** on the German market.

Retourenjournal is a focused **Return Management Tool** designed for small and medium-sized businesses that need structured control over product returns **without implementing a full ERP system**.

---

## 🚀 Project Status

* **Stage:** API-first MVP
* **Backend:** Laravel + PostgreSQL
* **Frontend:** Vue.js SPA (Vite)
* **Architecture:** Multi-tenant, domain-driven

This project is functional but intentionally scoped as an MVP and architectural showcase.

---

## 🎯 Target Audience

* Small and medium online shops
* Etsy / eBay / Instagram sellers
* B2B companies working with invoices (Rechnung)
* Businesses that must track returns but **do not want ERP complexity**

---

## 💡 Core Idea

Retourenjournal focuses on **clarity and traceability of return processes**:

* Transparent return lifecycle
* Strict status model
* Full history of actions
* Financial and logistical traceability
* Strong multi-tenant data isolation

The system deliberately avoids:

* ERP features
* Shop integrations
* Logistics automation

---

## 🔁 Minimal Return Workflow

1. Employee creates a return
2. System assigns an initial return status
3. Customer and return items are added
4. One or more shipments are created

    * customer → seller
    * seller → customer
    * repeated shipments if needed
5. Shipment statuses are updated
6. Optional refund is created with its own status
7. All significant actions are logged as return events
8. Return is closed with a terminal status

---

## 🧠 Architectural Principles

* **API-first** (SPA + REST API)
* **Multi-tenant** (`organization_id` in all domain tables)
* One active organization per user (`current_organization_id`)
* Statuses as system dictionaries with extensibility
* Domain tables as source of truth
* `return_events` used for audit log and timeline
* No Inertia — clean SPA + API separation

---

## 🏗️ Domain Model (High-Level)

* organizations
* users
* returns
* return_items
* customers
* shipments
* shipment_statuses
* refunds
* refund_statuses
* return_statuses
* return_events

---

## 🎯 Project Goals

This project serves multiple purposes:

1. **Portfolio project** demonstrating backend and product architecture skills
2. **Foundation for potential commercial customization**
3. **Conversation starter** for job offers, consulting, or acquisition

---

## 📄 License

This software is **proprietary and confidential**.

Copyright (c) 2026 Alexandra Tikhomirova.

All rights reserved.

This software may not be copied, modified, distributed, or used without explicit permission from the author.

---

## 📬 Contact

For collaboration, customization, or commercial inquiries:

**Alexandra Tikhomirova**
Backend / Full-stack Developer (PHP, Laravel, Vue)
