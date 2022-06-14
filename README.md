# PHP Finance management application
Laravel based web application

## Installation

Run commands below in app root folder

```bash
composer install
npm install
npm run dev
```

Rename `.env.example` into `.env` and fill in required fields

Run commands below in app's root folder

```bash
php artisan key:generate
php artisan config:cache
php artisan migrate
php artisan project:setup
```

## Deployment

Run command below to deploy app at `http://127.0.0.1:8000`
```bash
php artisan serve
```
Or link index page to `public/index.php`

Read <a href="https://laravel.com/docs/9.x">laravel docs</a> for additional information
