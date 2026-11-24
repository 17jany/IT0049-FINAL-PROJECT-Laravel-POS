# Sukli POS — Laravel Point-of-Sale System

> A Laravel-based Point-of-Sale system for managing products, customers, staff accounts, inventory, and sales transactions.

## Features

- **Home (`/`)** — redirects guests to login and authenticated staff to the dashboard.
- **About (`/about`)** — displays information about the system.
- **Authentication** — staff login, registration, logout, and protected management pages.
- **Dashboard (`/dashboard`)** — displays store overview, sales summary, transactions, products, and low-stock information.

### Product Management

- **Products (`/products`)** — lists active products and supports searching.
- **Create Product (`/products/create`)** — adds a product with name, category, description, price, stock quantity, and optional image.
- **Edit Product (`/products/{product}/edit`)** — updates product details and replaces the product image.
- **Archive Product** — removes products from the active list while preserving records.
- **Product Images** — uploaded images are stored on Laravel's public storage disk.

### Customer Management

- **Customers (`/customers`)** — lists active customers and supports searching by name, phone, or email.
- **Create Customer (`/customers/create`)** — adds a customer with validated information.
- **Edit Customer (`/customers/{customer}/edit`)** — updates customer information and credit settings.
- **Archive Customer** — hides a customer from the active list.
- **Restore Customer** — restores an archived customer.
- **Delete Customer** — permanently deletes an archived customer only when there is no sales history.
- **Customer Status** — displays whether a customer is a first-time customer or a returning Suki customer based on recorded sales.

### Staff Management

- **Staff Accounts (`/users`)** — displays staff accounts, usernames, names, and avatars.
- **Create Staff (`/users/create`)** — creates a staff account with a unique username and hashed password.
- **Edit Staff (`/users/{user}/edit`)** — updates staff information, password, and avatar.
- **Delete Staff** — deletes a staff account, except for the currently logged-in account.
- **Avatar Uploads** — uploaded avatars are validated and stored on Laravel's public storage disk.
- **Avatar Placeholder** — staff without an avatar are shown a default placeholder image.

### Sales and Inventory

- **Record Sale (`/sales/create`)** — records a transaction with one or more products.
- **Optional Customer** — a sale may be recorded with or without an assigned customer.
- **Cash Payment** — calculates the total amount, cash received, and change.
- **Stock Deduction** — product stock automatically decreases after a successful sale.
- **Stock Validation** — sales are rejected when the requested quantity exceeds available stock.
- **Transaction Safety** — sales and stock updates are processed inside a database transaction.

### Sales History

- **Sales History (`/sales`)** — displays previous transactions.
- **Transaction Details (`/sales/{sale}`)** — shows products, customer, staff member, quantities, totals, and payment details.
- **Sales Filters** — filters sales by date, customer, and staff member.
- **CSV Export (`/sales/export`)** — exports sales history as a CSV file.
- **PDF Receipt (`/sales/{sale}/receipt`)** — downloads a printable PDF receipt.

### Profile and Account Security

- **Profile (`/profile`)** — updates the logged-in staff member's name, username, and email.
- **Change Password (`/profile/password`)** — changes the current password after validating the existing password.
- **Account Protection** — management pages are available only to authenticated staff members.

## Requirements

- PHP 8.2 or later
- Composer
- Node.js and npm
- Laravel 12
- MySQL or SQLite
- PHP extensions required by Laravel and the selected database driver
- PHP GD extension if image-processing features are enabled

## Installation and Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/17jany/IT0049-FINAL-PROJECT-Laravel-POS.git
   cd IT0049-FINAL-PROJECT-Laravel-POS
   ```

2. **Install PHP dependencies:**

   ```bash
   composer install
   ```

3. **Install frontend dependencies:**

   ```bash
   npm install
   ```

4. **Create the environment file if it does not exist.**

   Windows PowerShell:

   ```powershell
   Copy-Item .env.example .env
   ```

   macOS/Linux:

   ```bash
   cp .env.example .env
   ```

5. **Generate the application key:**

   ```bash
   php artisan key:generate
   ```

6. **Configure the database in `.env`.**

   Example for MySQL:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_pos
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Example for SQLite:

   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

7. **Run the database migrations:**

   ```bash
   php artisan migrate
   ```

8. **Create the public storage link:**

   ```bash
   php artisan storage:link
   ```

   If Laravel reports that the link already exists, it is already configured.

9. **Start the Laravel server:**

   ```bash
   php artisan serve
   ```

10. **Start Vite in another terminal:**

   ```bash
   npm run dev
   ```

11. **Open the application:**

   ```text
   http://127.0.0.1:8000
   ```

## Application Pages

| URL | Page |
|---|---|
| `/` | Home and authentication redirect |
| `/about` | About page |
| `/login` | Staff login |
| `/register` | Staff registration |
| `/dashboard` | POS dashboard |
| `/products` | Product list |
| `/products/create` | Create product |
| `/products/{product}/edit` | Edit product |
| `/customers` | Customer list |
| `/customers/create` | Create customer |
| `/customers/{customer}/edit` | Edit customer |
| `/users` | Staff accounts |
| `/users/create` | Create staff account |
| `/users/{user}/edit` | Edit staff account |
| `/sales` | Sales history |
| `/sales/create` | Record a sale |
| `/sales/{sale}` | Transaction details |
| `/profile` | Edit profile |
| `/profile/password` | Change password |

All management pages are protected by Laravel's `auth` middleware.

## Database Tables

### `users`

Stores staff usernames, full names, email addresses, hashed passwords, avatar paths, and timestamps. Usernames are unique.

### `customers`

Stores customer names, email addresses, phone numbers, customer types, credit limits, balances, archive status, and timestamps.

### `products`

Stores product names, categories, descriptions, prices, stock quantities, image paths, archive status, and timestamps.

### `sales`

Stores the customer assigned to a transaction, the staff member who recorded it, total amount, payment method, cash received, change, and timestamps.

### `sale_items`

Stores the products included in each transaction, including quantity, unit price, subtotal, and timestamps.

The project uses a normalized sales design where one sale can contain multiple sale items.

## Database Relationships

- A customer can have many sales.
- A staff user can record many sales.
- A sale can contain many sale items.
- Each sale item belongs to one product.
- Product stock is reduced when a sale is successfully recorded.
- Customer and product archive status preserves historical records.

## Image Uploads

Product and staff avatar uploads are validated as image files and stored using Laravel's public storage disk.

Uploaded files are stored under:

```text
storage/app/public/products
storage/app/public/avatars
```

They are displayed through the public storage link:

```blade
asset('storage/' . $filePath)
```

Staff members without an uploaded avatar use the default placeholder image.

## Useful Commands

```bash
php artisan migrate:status
php artisan route:list
php artisan optimize:clear
php artisan storage:link
```

## Testing Checklist

- Log in using a registered staff account.
- Confirm that guests cannot access management pages.
- Create, edit, and archive a product.
- Upload and display a product image.
- Create, edit, archive, restore, and delete a customer.
- Create, edit, and delete a staff account.
- Upload and display a staff avatar.
- Record a sale with an optional customer.
- Confirm that product stock decreases after recording a sale.
- Try selling more than the available stock and confirm that the sale is rejected.
- Check sales history and transaction details.
- Test CSV export and PDF receipt download.
- Change the logged-in user's password.
- Confirm that the logged-in user cannot delete their own account.
