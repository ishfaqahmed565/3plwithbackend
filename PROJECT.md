Nice, this is a solid spec already. I’ll give you a **clean, updated `PROJECT.md`** that:

* ✅ Explicitly **starts with Laravel + Blade + Tailwind frontend setup**
* ✅ Verifies **DB connection first**
* ✅ Then layers **backend → frontend → scans → settlement**
* ✅ Is **Copilot / Claude–safe** (no ambiguity, no assumptions)
* ✅ Matches **everything you pasted earlier**

You can paste this directly as `PROJECT.md`.

---

# 📘 PROJECT.md

## 3PL Logistics Management System (Laravel + Blade + Tailwind)

---

## 0. IMPLEMENTATION ORDER (MANDATORY)

⚠️ **This order must be followed strictly**

1. Create Laravel project
2. Integrate **Blade + Tailwind CSS**
3. Configure database & verify connection
use mysql and mysql password=password
4. Create authentication & role guards
5. Create migrations (schema-first)
6. Implement backend business logic
7. Build frontend pages on top of backend
8. Implement scan flows
9. Implement settlement logic

No shortcuts.

---

## 1. TECH STACK

| Layer       | Technology             |
| ----------- | ---------------------- |
| Backend     | Laravel 10+            |
| Frontend    | Blade (`.blade.php`)   |
| Styling     | Tailwind CSS           |
| Auth        | Laravel Guards         |
| Database    | MySQL / PostgreSQL     |
| File Upload | Laravel Storage        |
| Roles       | Admin / Client / Agent |

---

## 2. PROJECT INITIAL SETUP

### 2.1 Create Laravel Project

```bash
composer create-project laravel/laravel 3pl-logistics
cd 3pl-logistics
```

---

### 2.2 Frontend Integration (FIRST)

#### Blade

* Use Laravel default Blade engine
* No Vue / React / Inertia
* All UI must be `.blade.php`

#### Tailwind CSS

* Install Tailwind using Laravel official method
* Configure:

  * `tailwind.config.js`
  * `resources/css/app.css`
* Build with Vite

📌 **Before anything else**, create a test Blade page:

```
resources/views/welcome.blade.php
```

Verify:

* Tailwind styles load
* Blade rendering works

---

### 2.3 Database Configuration (EARLY CHECK)

1. Configure `.env`
2. Create empty database
3. Run:

```bash
php artisan migrate
```

✅ **Project must load homepage without errors**
❌ Do not continue until DB connection is confirmed

---

## 3. AUTHENTICATION & ACCESS CONTROL

### 3.1 Separate Login Pages (NON-NEGOTIABLE)

| Role   | URL             | Guard  |
| ------ | --------------- | ------ |
| Admin  | `/admin/login`  | admin  |
| Client | `/client/login` | client |
| Agent  | `/agent/login`  | agent  |

Rules:

* No shared login page
* No role switching
* Admin creates Clients & Agents
* Clients cannot self-register

---

## 4. CORE DOMAIN MODELS (SCHEMA-FIRST)

---

## 4.1 CLIENTS

### Purpose

Business entities using warehouse services.

### Rules

* Created only by Admin
* Exactly **one immutable Group ID**

### Schema

```
clients
- id
- name
- email
- phone
- group_id (unique, immutable)
- created_at
- updated_at
```

---

## 4.2 SHIPMENTS (INBOUND INVENTORY)

### Definition

Bulk inventory received into warehouse.

📌 Matches **Create Shipment UI**

### Rules

* No label upload
* Only Scan-1
* Increases inventory
* One shipment → many orders

### Status Flow

```
pending
received_in_warehouse (scan-1)
```

### Schema

```
shipments
- id
- shipment_code
- client_id
- tracking_id (optional)
- source
- product_name
- product_description
- category
- product_image_path
- quantity_total
- quantity_available
- status
- received_at
- created_at
- updated_at
```

---

## 4.3 ORDERS (OUTBOUND – CUSTOMER DELIVERY)

### Definition

Individual customer delivery created from shipment inventory.

📌 Matches **Create Order UI**

### Critical Rules

* Shipment reference required
* Quantity ≤ available inventory
* Label upload is mandatory
* Inventory deducted on creation

### Status Flow

```
pending_scan2
prepared_for_delivery (scan-2)
handover_to_delivery_partner (scan-3) → settlement trigger
```

### Schema

```
orders
- id
- order_code
- client_id
- shipment_id
- tracking_id
- quantity
- customer_name
- customer_phone
- customer_address
- label_file_path
- status
- scan_2_at
- scan_3_at
- created_at
- updated_at
```

---

## 4.4 INVENTORY (DERIVED ONLY)

❌ No separate inventory table.

Inventory comes from:

```
shipments.quantity_available
```

Rules:

* Decrease on order creation
* Cannot go below zero
* Multiple orders per shipment allowed

---

## 4.5 SCANS (STATE TRANSITIONS)

Scans are logical events, not hardware integrations.

### Shipment Scan

| Scan   | Effect           |
| ------ | ---------------- |
| Scan-1 | Inventory usable |

### Order Scans

| Scan   | Effect                         |
| ------ | ------------------------------ |
| Scan-2 | Prepared for delivery          |
| Scan-3 | Handover complete → settlement |

---

## 4.6 LABEL RULES

| Entity   | Label         |
| -------- | ------------- |
| Shipment | ❌ Not allowed |
| Order    | ✅ Mandatory   |

Formats:

* PDF
* JPG
* PNG

Stored as file path only.

---

## 4.7 SETTLEMENTS

### Trigger (ABSOLUTE RULE)

✅ Created **only after Scan-3**

❌ Not on Scan-1
❌ Not on Scan-2
❌ Not on order creation

### Schema

```
settlements
- id
- order_id
- client_id
- amount
- settlement_rule_id
- status (pending / approved / paid)
- created_at
```

---

## 5. FRONTEND PAGES (BLADE)

### Required Pages

#### Admin

* Login
* Dashboard
* Create Client
* Create Agent
* View Shipments
* View Orders
* Settlement Management

#### Client

* Login
* Dashboard
* Create Shipment
* Create Order
* Inventory View
* Order History

#### Agent

* Login
* Scan Shipment (Scan-1)
* Scan Orders (Scan-2 / Scan-3)

---

## 6. UI–BACKEND CONTRACT

### Create Shipment

* Product image required
* No label
* Status = `pending`

### Create Order

* Shipment selection required
* Quantity validation required
* Label upload required
* Status = `pending_scan2`

---

## 7. BUSINESS ASSUMPTIONS

* No courier API integration
* No payment gateway
* Scans are simulated actions
* Focus on correctness & traceability

---

## 8. NON-NEGOTIABLE RULES (FINAL)

* Shipments ≠ Orders
* Orders MUST consume inventory
* Labels are NEVER optional for orders
* Settlement ONLY after Scan-3
* Clients NEVER self-register
* Frontend must be Blade + Tailwind

---

## 9. NEXT STEPS (CHOOSE ONE)

* 🧱 Laravel migrations
* 🧠 Service-layer logic
* 🧭 State machine implementation
* 🎨 Blade UI scaffolding
* 🧪 Feature & unit tests

Just tell me what to generate next.
