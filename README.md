# Laravel Ticket Application

A Laravel-based ticketing system with real-time notifications using Laravel Reverb.

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & npm
- MySQL or compatible database
- [Laravel Reverb](https://laravel.com/docs/11.x/broadcasting#reverb-server)

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/ticket-application.git
cd ticket-application
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Copy and Configure Environment File

```bash
cp .env.example .env
```
Edit `.env` and set your database, mail, and Reverb credentials:

```
APP_URL=http://localhost
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_pass

BROADCAST_DRIVER=reverb

REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Build Frontend Assets

```bash
npm run build
# or for development:
npm run dev
```

### 8. Start Laravel Reverb Server

```bash
php artisan reverb:start
```

### 9. Start Laravel Development Server

```bash
php artisan serve
```

### 10. (Optional) Start Queue Worker

If you use queues for broadcasting or mail:

```bash
php artisan queue:work
```

---

## Usage

- Visit [http://localhost:8000](http://localhost:8000) in your browser.
- Agents can log in and manage tickets.
- Customers can create and check ticket status.
- Agents receive real-time notifications for new tickets.

---

## Troubleshooting

- If broadcasting does not work, ensure:
  - `BROADCAST_DRIVER=reverb` is set in `.env`
  - Reverb server is running
  - Queue worker is running (if using queues)
  - Config cache is cleared: `php artisan config:clear`

---

## License

MIT