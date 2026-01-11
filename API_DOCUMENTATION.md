# API Documentation - Digital System for Food Stalls

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
API ini menggunakan **Laravel Sanctum** untuk autentikasi. Setelah login, Anda akan mendapatkan token yang harus disertakan di setiap request.

### Header Authentication
```
Authorization: Bearer {your_token}
```

---

## 🔓 PUBLIC ENDPOINTS (Tanpa Authentication)

### 1. Register User
```http
POST /api/v1/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "Cashier"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

---

### 2. Login
```http
POST /api/v1/login
```

**Request Body:**
```json
{
    "email": "admin@gmail.com",
    "password": "admin123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@gmail.com",
            "role": "Admin"
        },
        "token": "2|xyz789...",
        "token_type": "Bearer"
    }
}
```

---

### 3. Get All Categories (Public)
```http
GET /api/v1/categories
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `active` | boolean | Filter by active status |
| `search` | string | Search by name |
| `with_menu_count` | boolean | Include menu count |
| `with_menus` | boolean | Include menus |
| `per_page` | integer | Items per page (pagination) |

**Response (200):**
```json
{
    "success": true,
    "message": "Daftar kategori berhasil diambil",
    "data": [
        {
            "id": 1,
            "name": "Makanan",
            "description": "Berbagai jenis makanan",
            "is_active": true
        }
    ]
}
```

---

### 4. Get All Menus (Public)
```http
GET /api/v1/menus
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `category_id` | integer | Filter by category |
| `available` | boolean | Filter by availability |
| `in_stock` | boolean | Only show items in stock |
| `search` | string | Search by name |
| `min_price` | number | Minimum price filter |
| `max_price` | number | Maximum price filter |
| `sort_by` | string | Sort field (name, price, stock, created_at) |
| `sort_order` | string | Sort order (asc, desc) |
| `per_page` | integer | Items per page |

**Response (200):**
```json
{
    "success": true,
    "message": "Daftar menu berhasil diambil",
    "data": [
        {
            "id": 1,
            "name": "Nasi Goreng",
            "description": "Nasi goreng spesial",
            "price": "25000.00",
            "stock": 50,
            "is_available": true,
            "category": {
                "id": 1,
                "name": "Makanan"
            }
        }
    ]
}
```

---

## 🔐 PROTECTED ENDPOINTS (Memerlukan Authentication)

### Header wajib:
```
Authorization: Bearer {your_token}
Accept: application/json
```

---

### 5. Get User Profile
```http
GET /api/v1/user
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Admin",
        "email": "admin@gmail.com",
        "role": "Admin",
        "created_at": "2026-01-11T00:00:00.000000Z"
    }
}
```

---

### 6. Logout
```http
POST /api/v1/logout
```

**Response (200):**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

### 7. Update Profile
```http
PUT /api/v1/user/update
```

**Request Body:**
```json
{
    "name": "New Name",
    "email": "newemail@example.com"
}
```

---

### 8. Update Password
```http
PUT /api/v1/user/password
```

**Request Body:**
```json
{
    "current_password": "oldpassword",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

---

## 🛒 ORDER ENDPOINTS (Cashier & Admin)

### 9. Create Order
```http
POST /api/v1/orders
```

**Request Body:**
```json
{
    "items": [
        {
            "menu_id": 1,
            "quantity": 2
        },
        {
            "menu_id": 3,
            "quantity": 1
        }
    ],
    "customer_name": "Budi",
    "table_number": "A1",
    "notes": "Tidak pedas",
    "payment_method": "cash",
    "amount_paid": 100000
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Pesanan berhasil dibuat",
    "data": {
        "order": {
            "id": 1,
            "order_number": "ORD-20260111-0001",
            "total_price": "75000.00",
            "status": "Paid",
            "orderItems": [...]
        },
        "transaction": {
            "id": 1,
            "transaction_number": "TRX-20260111-0001",
            "amount": "75000.00",
            "payment_status": "Paid"
        },
        "payment": {
            "total": 75000,
            "paid": 100000,
            "change": 25000
        }
    }
}
```

---

### 10. Get My Orders
```http
GET /api/v1/orders
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `status` | string | Pending, Paid, Completed |
| `date` | string | Filter by date (YYYY-MM-DD) |
| `per_page` | integer | Items per page |

---

### 11. Get Order Detail
```http
GET /api/v1/orders/{id}
```

---

### 12. Update Order Status
```http
PATCH /api/v1/orders/{id}/status
```

**Request Body:**
```json
{
    "status": "Completed"
}
```

---

### 13. Complete Order
```http
POST /api/v1/orders/{id}/complete
```

---

## 💰 TRANSACTION ENDPOINTS (Cashier & Admin)

### 14. Get My Transactions
```http
GET /api/v1/transactions
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `payment_status` | string | Paid, Pending, Refunded |
| `date` | string | Filter by date (default: today) |
| `per_page` | integer | Items per page |

---

### 15. Get Transaction Detail
```http
GET /api/v1/transactions/{id}
```

---

## 👑 ADMIN ONLY ENDPOINTS

### Category Management

#### Create Category
```http
POST /api/v1/categories
```
**Request Body:**
```json
{
    "name": "Minuman",
    "description": "Berbagai jenis minuman",
    "is_active": true
}
```

#### Update Category
```http
PUT /api/v1/categories/{id}
```

#### Delete Category
```http
DELETE /api/v1/categories/{id}
```

---

### Menu Management

#### Create Menu
```http
POST /api/v1/menus
```
**Request Body (form-data for image upload):**
```
category_id: 1
name: Nasi Goreng
description: Nasi goreng spesial
price: 25000
stock: 50
is_available: true
image: [file]
```

#### Update Menu
```http
PUT /api/v1/menus/{id}
```

#### Delete Menu
```http
DELETE /api/v1/menus/{id}
```

---

### Admin Reports

#### Get All Orders (Admin)
```http
GET /api/v1/admin/orders
```

#### Get All Transactions (Admin)
```http
GET /api/v1/admin/transactions
```

#### Daily Report
```http
GET /api/v1/admin/reports/daily?date=2026-01-11
```

**Response:**
```json
{
    "success": true,
    "data": {
        "date": "2026-01-11",
        "summary": {
            "total_transactions": 25,
            "total_revenue": 1500000,
            "average_transaction": 60000
        },
        "hourly_breakdown": {...},
        "top_menus": [...]
    }
}
```

#### Monthly Report
```http
GET /api/v1/admin/reports/monthly?month=1&year=2026
```

---

## 📝 Error Responses

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### 422 Validation Error
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### 404 Not Found
```json
{
    "message": "No query results for model [App\\Models\\Menu] 999"
}
```

---

## 🧪 Testing dengan cURL

### Login
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@gmail.com","password":"admin123"}'
```

### Get Menus (dengan token)
```bash
curl -X GET http://localhost:8000/api/v1/menus \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Create Order
```bash
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "items": [{"menu_id": 1, "quantity": 2}],
    "payment_method": "cash",
    "amount_paid": 100000
  }'
```

---

## 📱 Testing dengan Postman

1. Import collection atau buat request baru
2. Set environment variable: `base_url` = `http://localhost:8000/api/v1`
3. Untuk authenticated requests, set header:
   - `Authorization`: `Bearer {{token}}`
   - `Accept`: `application/json`

---

## Credentials Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@gmail.com | admin123 |
| Cashier | cashier@foodstall.com | kasir123 |
