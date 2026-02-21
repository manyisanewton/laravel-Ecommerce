# Symatech Ecommerce Platform

A full-stack ecommerce application built with Laravel (API backend) and React (responsive frontend), implementing:
- Role-based access (`Administrator`, `Normal User`)
- JWT authentication
- API-driven architecture
- CORS-enabled frontend/backend integration
- Product ordering workflow
- Activity logging and report exports (Excel/PDF)
- Payment gateway abstraction for M-PESA, Flutterwave, and PesaPal/DPO

## 1. Project Structure

```text
.
├── backend/                  # Laravel API application scaffold
├── frontend/                 # React + Vite frontend
├── docs/                     # OpenAPI + Postman docs
├── .gitignore
└── README.md
```

## 2. Implemented Requirements

### Administrator Features
- User management (activate/deactivate users)
- Product management (create/update/delete)
- View all orders
- Activity log report (captures endpoint, method, payload, date/time)
- Filterable reports by date range:
  - Registered users
  - Orders
  - Activity log
- Export reports to Excel and PDF

### Normal User Features
- Register account
- Login with JWT
- Browse products
- Add to cart and checkout

### Architecture / Integration
- REST API via Laravel routes/controllers
- JWT auth guard for secured endpoints
- Middleware-based role authorization
- Middleware-based activity log capture
- CORS configuration for frontend origin
- API documentation in OpenAPI + Postman formats

### Payment Support
- M-PESA (Safaricom Sandbox) integration point
- Flutterwave integration point
- PesaPal/DPO integration point
- Webhook callback endpoints provided for payment status updates

## 3. Technology Stack

### Backend
- PHP 8.2+
- Laravel 11
- `tymon/jwt-auth` (JWT)
- `maatwebsite/excel` (Excel exports)
- `barryvdh/laravel-dompdf` (PDF exports)

### Frontend
- React 19
- Vite
- Axios
- React Router

### UI Palette Used
- Dark Green: `#246F1C`
- Green: `#3FA30A`
- Yellow: `#EFB709`
- Light Gray: `#ECE8E8`

## 4. Backend Setup (Laravel)

Run these commands from repository root.

### 4.1 Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB

### 4.2 Install dependencies

```bash
cd backend
composer install
composer require tymon/jwt-auth maatwebsite/excel barryvdh/laravel-dompdf
```

### 4.3 Environment configuration

```bash
cp .env.example .env
```

Update `.env` with your DB and payment credentials:
- DB settings (`DB_HOST`, `DB_DATABASE`, etc.)
- JWT (`JWT_SECRET` generated below)
- `FRONTEND_URL`
- `MPESA_*`, `FLUTTERWAVE_*`, `PESAPAL_*`

Generate app and JWT keys:

```bash
php artisan key:generate
php artisan jwt:secret
```

### 4.4 Database migration and seed

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

Default admin:
- Email: `admin@symatechlabs.com`
- Password: `Admin@123456`

### 4.5 Run backend API

```bash
php artisan serve
```

Backend URL: `http://localhost:8000`

## 5. Frontend Setup (React)

From repository root:

```bash
cd frontend
npm install
npm run dev
```

Frontend URL: `http://localhost:5173`

Set backend API URL in frontend env if needed:

```bash
# frontend/.env
VITE_API_URL=http://localhost:8000/api
```

## 6. Key API Endpoints

### Auth
- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout` (JWT)
- `GET /api/auth/me` (JWT)

### User
- `GET /api/products` (JWT)
- `POST /api/checkout` (JWT)
- `GET /api/orders/my` (JWT)

### Admin
- `GET /api/admin/users`
- `PATCH /api/admin/users/{user}/toggle-status`
- `POST /api/admin/products`
- `PUT /api/admin/products/{product}`
- `DELETE /api/admin/products/{product}`
- `GET /api/admin/orders`
- `GET /api/admin/reports/users?from=YYYY-MM-DD&to=YYYY-MM-DD`
- `GET /api/admin/reports/orders?from=YYYY-MM-DD&to=YYYY-MM-DD`
- `GET /api/admin/reports/activity?from=YYYY-MM-DD&to=YYYY-MM-DD`
- `GET /api/admin/reports/export/{type}?from=YYYY-MM-DD&to=YYYY-MM-DD&format=excel|pdf`

### Payment Webhooks
- `POST /api/payments/mpesa/callback`
- `POST /api/payments/flutterwave/callback`
- `POST /api/payments/pesapal/callback`

## 7. API Documentation / Postman

- OpenAPI spec: `docs/openapi.yaml`
- Postman collection: `docs/postman_collection.json`

How to use Postman:
1. Import `docs/postman_collection.json`
2. Set variable `base_url = http://localhost:8000/api`
3. Login/register and set JWT in `token` variable
4. Test protected endpoints

## 8. Report Generation

Reports can be filtered with `from` and `to` dates.
Formats:
- `format=excel` -> `.xlsx`
- `format=pdf` -> `.pdf`

Example:

```http
GET /api/admin/reports/export/orders?from=2026-01-01&to=2026-12-31&format=pdf
Authorization: Bearer <JWT>
```

## 9. Payment Integration Notes

The gateways are scaffolded with service adapters and callback handlers. Replace stub logic in:
- `backend/app/Services/Payments/MpesaGateway.php`
- `backend/app/Services/Payments/FlutterwaveGateway.php`
- `backend/app/Services/Payments/PesapalGateway.php`

Webhook handling is in:
- `backend/app/Http/Controllers/Api/WebhookController.php`

## 10. Responsive UI Coverage

Frontend includes:
- Mobile + desktop responsive layout
- Role-aware navigation
- Auth screens (login/register)
- Product list and cart
- Checkout provider selection (M-PESA/Flutterwave/PesaPal)
- Admin report dashboard with export actions

Main styles file:
- `frontend/src/styles/app.css`

## 11. Troubleshooting

- `php: command not found`
  - Install PHP 8.2+ and ensure it is on PATH.
- `composer: command not found`
  - Install Composer globally.
- JWT errors (`Token could not be parsed`)
  - Ensure `Authorization: Bearer <token>` header is sent.
- CORS errors
  - Verify `FRONTEND_URL` and `backend/config/cors.php` values.
- Export issues
  - Confirm Excel/PDF packages are installed and autoloaded.

## 12. GitHub Push Guide (manyisanewton)

Your remote is already configured:
- `origin -> git@github.com:manyisanewton/laravel-Ecommerce.git`

### 12.1 Commit changes

```bash
git add .
git commit -m "Add full Laravel API + React frontend scaffold with docs"
```

### 12.2 Push to GitHub

```bash
git push -u origin main
```

If your branch is not `main`, check it first:

```bash
git branch --show-current
```

Then push that branch:

```bash
git push -u origin <your-branch>
```

If SSH auth fails, configure SSH key for GitHub or switch remote to HTTPS.

## 13. Recommended Next Steps

1. Boot this into a full Laravel runtime (`artisan`, providers, app kernel) and run feature tests.
2. Replace payment stubs with real sandbox API calls and signature verification.
3. Add CI (lint, test, build) and deploy backend/frontend separately.
