# Symatech Laravel Ecommerce Backend

## Features Delivered
- JWT authentication (`register`, `login`, `logout`, `me`)
- Roles: `admin` and `user`
- Admin controls:
  - Activate/deactivate users
  - Manage products (CRUD)
  - View all orders
  - Reports with date filters (users, orders, activity log)
  - Export reports to Excel/PDF
- Normal user controls:
  - Register/login
  - Browse products
  - Place orders and pay
- API architecture with middleware-based activity logging
- CORS configured for frontend app
- Payment gateway abstraction with M-PESA + Flutterwave + PesaPal/DPO stubs and webhook endpoints

## Run Setup
1. Create a fresh Laravel 11 project in `backend/` if not already bootstrapped.
2. Copy these files into the Laravel app.
3. Install dependencies:
   - `composer install`
   - `composer require tymon/jwt-auth maatwebsite/excel barryvdh/laravel-dompdf`
4. Configure env:
   - `cp .env.example .env`
   - set DB and payment credentials
   - `php artisan key:generate`
   - `php artisan jwt:secret`
5. Run migrations/seed:
   - `php artisan migrate`
   - `php artisan db:seed --class=AdminSeeder`
6. Start API:
   - `php artisan serve`

Default admin:
- `admin@symatechlabs.com`
- `Admin@123456`

## API Docs
- OpenAPI spec: `docs/openapi.yaml`
- Postman collection: `docs/postman_collection.json`
