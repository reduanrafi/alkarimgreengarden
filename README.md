# Green Garden Website

A full-featured fashion e-commerce platform built with Laravel 12, Blade, Tailwind CSS, and MySQL.

## Features

### Customer Features
- **Homepage** – Hero banner, category showcase, featured products
- **Product Browsing** – Category filtering, advanced search (name, category, fabric, color, price range)
- **Product Details** – Image gallery, stock status, related products
- **Shopping Cart** – Session-based cart with CRUD operations
- **Wishlist** – Add/remove products, move to cart
- **Product Reviews** – Star rating and comments, edit/delete own reviews
- **Checkout** – Shipping form, coupon code, order summary
- **Order Management** – Order history, detail with status timeline, success page
- **Coupon System** – Apply discount codes during checkout
- **User Profile** – Update name, email, password

### Admin Panel
- **Dashboard** – Stats cards (products, categories, orders, customers, revenue, low stock), recent orders, latest customers, low stock alerts
- **Category Management** – CRUD with soft delete/restore, image upload, search
- **Product Management** – CRUD with advanced fields (fabric, color, print, sizes, discount price, stock, featured, gallery images)
- **Order Management** – View/update status and payment status, print invoice, search/filter
- **Coupon Management** – CRUD with code, type (fixed/percentage), usage limits, expiry
- **Customer Management** – View customers with order history and total spent
- **Reports** – Sales summary, top selling products, low stock alerts, order status summary
- **Settings** – Store info, logo, favicon, social links
- **Profile** – Admin name/email/password update

### Additional Features
- **Email Notifications** – Order placed and status update emails
- **SEO** – Meta tags, Open Graph, Twitter Cards, canonical URLs, robots.txt, XML sitemap
- **Responsive Design** – Desktop, tablet, mobile friendly
- **Animations** – Scroll fade-in, smooth hover effects
- **Security** – Admin middleware, CSRF, form validation, mass assignment protection

## Requirements

- PHP 8.2+
- Composer
- MySQL (MariaDB 10.4+)
- Node.js & npm
- XAMPP / Laragon / Valet

## Installation

### 1. Clone the repository
```bash
git clone <repository-url> fashion
cd fashion
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Install JavaScript dependencies
```bash
npm install
```

### 4. Environment setup
```bash
cp .env.example .env
```
Edit `.env` and configure:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fashion
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@email.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generate application key
```bash
php artisan key:generate
```

### 6. Create database
Create a MySQL database named `fashion` (or your configured name).

### 7. Run migrations and seeders
```bash
php artisan migrate --seed
```

### 8. Create storage link
```bash
php artisan storage:link
```

### 9. Build frontend assets
```bash
npm run build
```

### 10. Start the server
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000` in your browser.

## Default Accounts

### Admin Login
- **Email:** admin@fashion.test
- **Password:** password

### Customer Registration
Register a new account at `/register` to test customer features.

## Deployment Checklist

### Shared Hosting / cPanel

1. **Upload files** – Upload all project files to your server (excluding `node_modules`, `.git`, etc.)
2. **Environment** – Copy `.env.example` to `.env` and update:
   - `APP_URL` – Your domain URL
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - Database credentials
   - Mail credentials
3. **Directory permissions** – Set `storage/` and `bootstrap/cache/` to 775
4. **Optimization** – Run on server:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```
5. **Storage link** – Run `php artisan storage:link`
6. **Database** – Import your database or run `php artisan migrate --seed`
7. **Queue** – For email notifications, configure queue worker or set `QUEUE_CONNECTION=sync`
8. **Cron job** – (Optional) Add Laravel scheduler

### .env Production Configuration
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@email.com
```

## Folder Structure

```
fashion/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   └── ...             # Frontend controllers
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/           # Form request validation
│   ├── Mail/                   # Email Mailable classes
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic services
├── bootstrap/app.php           # App configuration
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── public/
│   ├── build/                  # Compiled assets
│   ├── storage/                # Storage symlink
│   ├── robots.txt
│   └── sitemap.xml (via route)
├── resources/
│   └── views/
│       ├── admin/              # Admin panel views
│       │   ├── layouts/
│       │   ├── components/
│       │   ├── dashboard/
│       │   ├── categories/
│       │   ├── products/
│       │   ├── orders/
│       │   ├── customers/
│       │   ├── coupons/
│       │   ├── settings/
│       │   ├── profile/
│       │   └── reports/
│       ├── components/         # Shared Blade components
│       ├── layouts/            # Frontend layout
│       ├── home/               # Homepage
│       ├── products/           # Product pages
│       ├── cart/               # Cart pages
│       ├── checkout/           # Checkout pages
│       ├── orders/             # Order pages
│       ├── wishlist/           # Wishlist pages
│       ├── search/             # Search page
│       ├── contact/            # Contact page
│       ├── about/              # About page
│       └── emails/             # Email templates
├── routes/
│   ├── web.php                 # Web routes
│   ├── sitemap.php             # Sitemap route
│   └── auth.php                # Auth routes
└── package.json
```

## Optimization Commands

```bash
# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear cache (development)
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild all caches
php artisan optimize
```

## Testing Checklist

### Frontend
- [ ] Homepage renders with hero, categories, featured products
- [ ] Product listing with category filter
- [ ] Product detail with gallery, stock info, related products
- [ ] Cart: add, update quantity, remove, clear
- [ ] Checkout: shipping form, coupon apply, place order
- [ ] Wishlist: add/remove, move to cart
- [ ] Reviews: submit, view on product page, delete
- [ ] Search: by name, category, fabric, color, price range
- [ ] Contact form submit
- [ ] About page renders
- [ ] User registration, login, profile update
- [ ] Order history and detail with status timeline
- [ ] Responsive: mobile, tablet, desktop

### Admin Panel
- [ ] Login with admin credentials
- [ ] Dashboard stats display correctly
- [ ] Categories: create, edit, soft delete, restore, search
- [ ] Products: create with all fields, edit, delete, gallery management
- [ ] Orders: filter, view, update status/payment, invoice print
- [ ] Coupons: create, edit, delete, apply in checkout
- [ ] Customers: view list, detail with order history
- [ ] Settings: update store info, logo upload
- [ ] Profile: update admin name/email/password

### Security
- [ ] Non-admin users get 403 on `/admin/*`
- [ ] Guest users redirected to login for protected pages
- [ ] CSRF protection on all forms
- [ ] Image upload validation (type, size)
- [ ] SQL injection prevention (parameterized queries)

## License

This project is for educational/portfolio purposes.
