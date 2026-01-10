# 📚 Setup Guide - Digital System for Food Stalls

## 🎯 Project Overview
Sistem Digital untuk Warung Makan berbasis Laravel dengan fitur lengkap:
- Manajemen Kategori & Menu
- Sistem Pemesanan
- Transaksi & Pembayaran
- Laporan Penjualan
- Role-based Access (Admin & Owner)

## 📁 Folder Structure
```
Digital-System-for-Food-Stalls/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # All feature controllers
│   │   │   ├── Auth/            # Login & Register
│   │   │   ├── CategoryController.php
│   │   │   ├── MenuController.php
│   │   │   ├── OrderController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── ReportController.php
│   │   │   └── DashboardController.php
│   │   ├── Middleware/
│   │   │   └── CheckRole.php    # Role-based middleware
│   │   └── Requests/             # Form validation
│   │       ├── CategoryRequest.php
│   │       ├── MenuRequest.php
│   │       └── OrderRequest.php
│   └── Models/                   # Eloquent models with relationships
│       ├── Role.php
│       ├── User.php
│       ├── Category.php
│       ├── Menu.php
│       ├── Order.php
│       ├── OrderItem.php
│       └── Transaction.php
├── database/
│   ├── migrations/               # Database schema
│   │   ├── 2024_01_01_000001_create_roles_table.php
│   │   ├── 2024_01_01_000002_add_role_id_to_users_table.php
│   │   ├── 2024_01_01_000003_create_categories_table.php
│   │   ├── 2024_01_01_000004_create_menus_table.php
│   │   ├── 2024_01_01_000005_create_orders_table.php
│   │   ├── 2024_01_01_000006_create_order_items_table.php
│   │   └── 2024_01_01_000007_create_transactions_table.php
│   └── seeders/                  # Dummy data
│       ├── RoleSeeder.php
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       └── MenuSeeder.php
├── resources/
│   └── views/                    # Blade templates
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       ├── categories/
│       ├── menus/
│       ├── orders/
│       ├── transactions/
│       ├── reports/
│       └── dashboard.blade.php
└── routes/
    └── web.php                   # Route definitions with middleware

```

## 🛠️ Tech Stack
- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL/MariaDB
- **Frontend**: Blade + Tailwind CSS 4.0
- **Authentication**: Laravel built-in
- **Architecture**: MVC

## 📋 Requirements
- PHP >= 8.2
- Composer
- MySQL atau MariaDB
- Node.js & npm
- Git (optional)

## 🚀 Installation Steps

### 1. Clone atau Copy Project
```bash
cd c:\Kuliah\PFW\Tugas14\Digital-System-for-Food-Stalls
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Setup Environment
```bash
# Copy file .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database
Buka file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=food_stall_system
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Create Database
Buat database di MySQL:
```sql
CREATE DATABASE food_stall_system;
```

### 6. Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Run seeders (dummy data)
php artisan db:seed
```

### 7. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Run Application
```bash
# Start Laravel development server
php artisan serve
```

Aplikasi akan berjalan di: **http://127.0.0.1:8000**

## 👤 Default Users

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

**Admin:**
- Email: `admin@foodstall.com`
- Password: `password`

**Owner:**
- Email: `owner@foodstall.com`
- Password: `password`

**Owner 2:**
- Email: `budi@foodstall.com`
- Password: `password`

## 🎯 Features by Role

### Admin
- ✅ View all reports
- ✅ Manage users (future feature)
- ✅ Access to all features

### Food Stall Owner
- ✅ Manage categories (CRUD)
- ✅ Manage menus (CRUD)
- ✅ Process orders
- ✅ View transactions
- ✅ View sales reports

## 📊 Database Tables

1. **roles** - User roles (admin, owner)
2. **users** - User accounts
3. **categories** - Menu categories
4. **menus** - Menu items with stock
5. **orders** - Customer orders
6. **order_items** - Order details
7. **transactions** - Payment transactions

## 🔒 Security Features

- **Role-based Access Control**: Middleware `role:admin,owner`
- **Form Validation**: Custom Form Request classes
- **CSRF Protection**: Laravel built-in
- **Password Hashing**: Bcrypt

## 🎨 Key Features

### 1. Category Management
- Create, Read, Update, Delete categories
- Track number of menus per category

### 2. Menu Management
- Full CRUD operations
- Price and stock management
- Category association
- Low stock alerts

### 3. Order Management
- Create orders with multiple items
- Automatic price calculation
- Stock management
- Order status tracking (Pending, Paid, Completed)

### 4. Transaction History
- View all transactions
- Filter by payment status and date
- Transaction details with order items

### 5. Sales Reports
- Daily sales summary
- Total income statistics
- Top selling menus
- Date range filtering

## 🧪 Testing

```bash
# Run tests
php artisan test
```

## 📝 Development Notes

### Eloquent Relationships
- `User` belongsTo `Role`
- `User` hasMany `Orders`
- `Category` hasMany `Menus`
- `Menu` belongsTo `Category`
- `Order` belongsTo `User`
- `Order` hasMany `OrderItems`
- `Order` hasOne `Transaction`
- `OrderItem` belongsTo `Menu` and `Order`
- `Transaction` belongsTo `Order`

### Middleware Usage
```php
// In web.php
Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    // Protected routes
});
```

### Form Validation
All forms use Form Request classes for validation with Indonesian error messages.

## 🐛 Troubleshooting

### Error: "No application encryption key"
```bash
php artisan key:generate
```

### Error: Database connection
- Pastikan MySQL service berjalan
- Cek konfigurasi di file `.env`
- Pastikan database sudah dibuat

### Error: "Class not found"
```bash
composer dump-autoload
```

### Tailwind CSS not working
```bash
npm run build
```

## 📞 Support

Untuk pertanyaan atau bantuan, silakan hubungi developer.

---

**© 2026 Digital System for Food Stalls**
```
