# Smart Lighting Control Platform

Full-stack IoT smart lighting platform built with Laravel, MySQL, MQTT, and Blade.

## Features

- Authentication
- Home, Room, Device CRUD
- ON/OFF device control
- MQTT command publishing and status updates
- Online/Offline monitoring
- Scheduling and Automation rules
- AI Assistant
- Activity history
- Responsive dashboard

## Quick Start

```bash
composer install
php artisan key:generate
php artisan migrate --force
php artisan serve
```

## MQTT

- ESP32 status updates: `POST /api/mqtt/status`
- Laravel command publishing: `POST /api/mqtt/command`
- Listen for status updates: `php artisan mqtt:listen`

## Automation

- `routes/console.php` runs Schedule and Automation checks every minute.
- Configure `MQTT_HOST`, `MQTT_PORT`, and `MQTT_API_TOKEN` in `.env`.
