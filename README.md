# lumen-scaffold

RESTful API backend service scaffold with basic features

Based on [Lumen 8.x](https://lumen.laravel.com/)

Frontend admin dashboard: [vue-admin](https://github.com/dodowhat/vue-admin)

## Features

- Managing Admin User and Normal User in independent tables
- JWT token authentication for Admin User and Normal User separately
- Admin User RBAC(Role Based Access Control)

## Delevelopment

### System Requirements

Reference [Lumen Installation](https://lumen.laravel.com/docs/8.x/installation#installation)

### Downloading Project

    git clone https://github.com/dodowhat/lumen-scaffold

### Installing Dependencies

    cd lumen-scaffold
    composer install

### Configure Database

1. Copy `.env.example` to `.env`

2. Edit database configuration in `.env`

3. Create database manually

### Runing Database Migration & Seeders

    php artisan migrate --seed

### Runing Project

    php -S 127.0.0.1:8888 -t public
