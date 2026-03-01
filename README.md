# Japura Quiz 🎓

Gamified MCQ web application for Sri Lankan students at [japura.lk](https://japura.lk).

## Tech Stack

- **Backend:** Laravel 11
- **Frontend Interactivity:** Livewire 3 + Volt
- **Styling:** Tailwind CSS 3
- **Auth:** Laravel Breeze (Livewire stack)
- **Database:** MySQL

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8+

## Setup Instructions

```bash
# 1. Clone the repository
git clone https://github.com/webclanka/japura.git
cd japura

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies and build assets
npm install && npm run build

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure your database in .env, then run migrations
php artisan migrate

# 7. Start the development server
php artisan serve
```

## Development

```bash
# Run the dev server with hot module replacement
npm run dev

# Run PHP tests
php artisan test
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
