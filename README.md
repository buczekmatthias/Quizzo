# Quizzo

A template for quiz posting application.

## Stack

- Laravel
- Vue
- Inertia.js
- Tailwind
- TypeScript
- PostgreSQL
- shadcn/vue
- Lucide Vue
- Wayfinder

## Installation

### Clone repository

```
git clone https://github.com/buczekmatthias/Quizzo.git
```

### Change directory

```
cd Quizzo
```

### Install dependencies

```
composer install
```

```
npm install
```

### Create .env from .env.example

```
copy .env.example .env
```

### Generate app key

```
php artisan key:generate
```

### Fill database credentials in .env

```
DB_USERNAME=
DB_PASSWORD=
```

### Start your postgres server and create database matching the name from .env file

### Start application server

```
composer run dev
```

### Open browser and head to http://localhost:8000

### Login using these admin account credentials

```
test@example.com

password
```
