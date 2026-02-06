# 3PL Logistics - Quick Reference

## 🌐 Access URLs
- **Admin**: http://127.0.0.1:8001/admin/login
- **Client**: http://127.0.0.1:8001/client/login
- **Agent**: http://127.0.0.1:8001/agent/login

## 🔑 Login Credentials
| Role   | Email              | Password |
|--------|--------------------|----------|
| Admin  | admin@3pl.com      | password |
| Client | client@example.com | password |
| Agent  | agent@3pl.com      | password |

## 📦 Workflow Quick Steps

### 1️⃣ Client: Create Shipment
- Login → Shipments → Create Shipment
- Fill form (NO label needed)
- Copy Shipment Code

### 2️⃣ Agent: Scan-1 (Receive)
- Login → Scan-1
- Enter Shipment Code
- Status: Pending → Received in Warehouse

### 3️⃣ Client: Create Order
- Login → Orders → Create Order
- Select shipment, fill details
- **MUST upload label** (PDF/Image)
- Copy Order Code

### 4️⃣ Agent: Scan-2 (Prepare)
- Login → Scan-2
- Enter Order Code
- Status: Pending Scan-2 → Prepared for Delivery

### 5️⃣ Agent: Scan-3 (Handover)
- Login → Scan-3
- Enter Order Code
- Status: Prepared → Handover
- **Settlement automatically created!**

### 6️⃣ Admin: Approve Settlement
- Login → Settlements
- Click "Approve" → "Mark Paid"

## 🎨 Color Codes
- 🔵 **Blue** = Admin
- 🟢 **Green** = Client
- 🟣 **Purple** = Agent

## ⚠️ Important Rules
- ❌ Shipments do NOT have labels
- ✅ Orders MUST have labels
- 🔒 Group ID is immutable (auto-generated)
- 💰 Settlements ONLY created after Scan-3
- 📊 Default rate: $5 per unit

## 🗂️ File Storage
- Shipment Images: `storage/app/public/shipments/`
- Order Labels: `storage/app/public/labels/`

## 🚀 Start Servers
```bash
# Laravel
php artisan serve

# Vite (Tailwind CSS)
npm run dev
```

## 🛠️ Useful Commands
```bash
# Reset database
php artisan migrate:fresh --seed

# Create storage link
php artisan storage:link

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```
