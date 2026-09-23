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

# 💡 Smart Lighting Control Platform

A full-stack **IoT Smart Lighting Control Platform** built with **Laravel 12, MySQL, MQTT, ESP32, and AI**.

The platform allows users to manage smart lighting devices, control lights remotely, monitor device status, create schedules, automate lighting actions, view activity logs, and interact with an AI assistant.

---

## 📌 Project Overview

**Smart Lighting Control Platform** is a web-based IoT management system designed to connect a Laravel application with physical smart lighting devices through the **MQTT protocol**.

The system follows this architecture:

```text
User
  ↓
Web Dashboard
  ↓
Laravel 12
  ↓
Device Control Service
  ↓
MQTT Broker
  ↓
ESP32
  ↓
Smart Light
```

For device status:

```text
Smart Light
  ↓
ESP32
  ↓
MQTT Broker
  ↓
Laravel MQTT Listener
  ↓
Database
  ↓
Dashboard
```

The project is designed as a portfolio-ready system demonstrating skills in:

* Full-Stack Web Development
* Laravel 12
* REST API
* MySQL Database Design
* IoT Development
* MQTT Communication
* ESP32
* Real-Time Device Control
* Authentication & Authorization
* Automation
* Scheduling
* AI Integration
* Testing
* Software Architecture

---

# ✨ Features

## 🔐 Authentication

* User registration
* User login
* User logout
* Password protection
* Session management
* Authenticated dashboard
* User-based access control

---

## 📊 Dashboard

The dashboard provides an overview of the smart lighting system.

### Dashboard information

* Total Homes
* Total Rooms
* Total Devices
* Online Devices
* Offline Devices
* Lights ON
* Lights OFF
* Recent Activities
* Device status
* Automation status
* Schedule information

Example:

```text
┌───────────────────────────────────────────────┐
│              Smart Lighting Dashboard         │
├───────────────┬───────────────┬───────────────┤
│ Homes         │ Rooms         │ Devices       │
│ 3             │ 12            │ 25            │
├───────────────┼───────────────┼───────────────┤
│ Online        │ Lights ON     │ Lights OFF    │
│ 21            │ 15            │ 10            │
└───────────────┴───────────────┴───────────────┘
```

---

# 🏠 Home Management

Users can manage multiple homes.

### Features

* Create Home
* View Home
* Edit Home
* Delete Home
* Manage rooms
* Manage devices inside each home

Example:

```text
Home
 ├── Living Room
 │    ├── Ceiling Light
 │    └── Floor Light
 │
 ├── Bedroom
 │    ├── Main Light
 │    └── Bed Light
 │
 └── Kitchen
      └── Kitchen Light
```

---

# 🚪 Room Management

Rooms organize smart devices inside a home.

### Features

* Create rooms
* Edit rooms
* Delete rooms
* View room devices
* Control devices by room

---

# 💡 Smart Device Management

Users can manage smart lighting devices.

### Device information

* Device Name
* Device ID
* Device Type
* Room
* Home
* Status
* Power State
* MQTT Topic
* Last Seen
* Created Date

Example:

```text
Device:
    Name: Living Room Light
    Device ID: LIGHT-001
    Type: Smart Light
    Status: Online
    Power: ON
    MQTT Topic: smart-lighting/LIGHT-001
```

---

# 🔘 Device ON/OFF Control

Users can control smart lights directly from the dashboard.

```text
Dashboard
    ↓
Click ON
    ↓
Laravel
    ↓
DeviceControlService
    ↓
MQTT
    ↓
ESP32
    ↓
Relay
    ↓
Light ON
```

For OFF:

```text
Dashboard
    ↓
Click OFF
    ↓
Laravel
    ↓
MQTT
    ↓
ESP32
    ↓
Relay
    ↓
Light OFF
```

---

# 📡 MQTT Integration

The platform uses **MQTT** for communication between Laravel and IoT devices.

### MQTT Architecture

```text
Laravel Application
       │
       │ Publish
       ▼
 MQTT Broker
       │
       │ Subscribe
       ▼
     ESP32
       │
       ▼
 Smart Light
```

For device status:

```text
ESP32
  │
  │ Publish Status
  ▼
MQTT Broker
  │
  ▼
Laravel MQTT Listener
  │
  ▼
Database
  │
  ▼
Dashboard
```

### Example MQTT Topics

```text
smart-lighting/LIGHT-001/command
smart-lighting/LIGHT-001/status
smart-lighting/LIGHT-001/state
```

### Example command

```json
{
    "device_id": "LIGHT-001",
    "action": "ON"
}
```

Example OFF command:

```json
{
    "device_id": "LIGHT-001",
    "action": "OFF"
}
```

---

# 🤖 AI Assistant

The platform includes an AI Assistant designed to help users interact with the smart lighting system.

Example commands:

```text
"Turn on the living room light."

"Turn off the bedroom light."

"Which lights are currently online?"

"Show me the lights in the kitchen."

"Schedule the bedroom light for 10 PM."

"How many lights are currently ON?"
```

The AI layer can understand user requests and connect them with platform actions.

Example:

```text
User
 ↓
AI Assistant
 ↓
Understand Intent
 ↓
Device / Schedule / Automation Service
 ↓
Laravel
 ↓
MQTT
 ↓
Smart Device
```

---

# ⏰ Scheduling

Users can create schedules for automatic lighting control.

### Example

```text
Schedule:
    Device: Bedroom Light
    Action: ON
    Time: 18:30
    Days: Monday - Friday
```

Another example:

```text
Schedule:
    Device: Bedroom Light
    Action: OFF
    Time: 23:00
    Days: Every Day
```

### Scheduling flow

```text
Scheduled Time
      ↓
Laravel Scheduler
      ↓
Schedule Service
      ↓
MQTT
      ↓
ESP32
      ↓
Light
```

---

# ⚙️ Automation

Automation allows lighting actions to happen automatically based on predefined conditions.

Example:

```text
IF
    Time = 18:00

THEN
    Living Room Light = ON
```

Another example:

```text
IF
    Time = 23:00

THEN
    All Bedroom Lights = OFF
```

---

# 📝 Activity Logs

The platform records important actions performed by users and devices.

Example:

```text
[18:30] Living Room Light turned ON
[18:45] Bedroom Light turned ON
[22:00] Kitchen Light turned OFF
[23:00] Bedroom Light turned OFF
```

Activity logs can be used for:

* Monitoring
* Debugging
* Security
* User activity tracking
* Device troubleshooting

---

# 📈 Reports

The system can provide useful information about the lighting platform.

Possible reports include:

* Device activity
* Light ON/OFF activity
* Device status
* User activity
* Automation activity
* Schedule execution
* System activity

---

# 🔌 ESP32 Integration

The physical IoT layer can be implemented using an **ESP32**.

Basic architecture:

```text
ESP32
 │
 ├── Wi-Fi
 │
 ├── MQTT Client
 │
 ├── GPIO
 │
 └── Relay
      │
      └── Smart Light
```

ESP32 responsibilities:

1. Connect to Wi-Fi
2. Connect to MQTT Broker
3. Subscribe to command topics
4. Receive ON/OFF commands
5. Control GPIO / Relay
6. Publish device status
7. Send heartbeat / last-seen information

---

# 🧪 Fake Device Testing

Before connecting a physical ESP32, the project can use a simulated/fake device.

Example:

```text
Web Dashboard
      ↓
Laravel
      ↓
MQTT
      ↓
Fake Device
      ↓
Status Response
```

This makes it possible to test the application without physical hardware.

---

# 🌐 REST API

The platform provides API endpoints for communication with devices and external applications.

Example API structure:

```text
/api
 ├── auth
 ├── homes
 ├── rooms
 ├── devices
 ├── devices/{device}/on
 ├── devices/{device}/off
 ├── devices/{device}/status
 ├── mqtt
 └── ai
```

Example request:

```http
POST /api/devices/{device}/on
```

Example response:

```json
{
    "success": true,
    "message": "Device turned on successfully."
}
```

---

# 🗄️ Database

The project uses **MySQL** as the primary database.

Main entities include:

```text
Users
  │
  └── Homes
        │
        └── Rooms
              │
              └── Devices
                    │
                    └── Device States
```

Other system data includes:

```text
Schedules
Automations
Activity Logs
Device States
```

---

# 🏗️ System Architecture

High-level architecture:

```text
                     ┌───────────────────┐
                     │       User        │
                     └─────────┬─────────┘
                               │
                               ▼
                     ┌───────────────────┐
                     │   Web Dashboard   │
                     └─────────┬─────────┘
                               │
                               ▼
                     ┌───────────────────┐
                     │    Laravel 12     │
                     │   Application     │
                     └─────────┬─────────┘
                               │
              ┌────────────────┼────────────────┐
              │                │                │
              ▼                ▼                ▼
        ┌──────────┐    ┌────────────┐    ┌──────────┐
        │  MySQL   │    │ AI Service │    │ Scheduler│
        └──────────┘    └────────────┘    └────┬─────┘
                                                │
                                                ▼
                                       ┌────────────────┐
                                       │  MQTT Broker   │
                                       └───────┬────────┘
                                               │
                                               ▼
                                       ┌────────────────┐
                                       │     ESP32      │
                                       └───────┬────────┘
                                               │
                                               ▼
                                       ┌────────────────┐
                                       │  Smart Light   │
                                       └────────────────┘
```

---

# 🛠️ Tech Stack

## Backend

* PHP
* Laravel 12
* Laravel Eloquent ORM
* Laravel Artisan
* Laravel Scheduler
* REST API

## Frontend

* Blade
* HTML5
* CSS3
* JavaScript
* Vite
* Responsive UI

## Database

* MySQL

## IoT

* ESP32
* Wi-Fi
* Relay Module
* Smart Light

## Communication

* MQTT
* MQTT Broker

## AI

* AI API integration
* AI Assistant
* Natural language device control

## Development Tools

* Git
* GitHub
* Laragon
* Composer
* Node.js
* NPM

---

# 📂 Project Structure

```text
Smart-Lighting-Control-Platform/
│
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── MqttReceiveCommand.php
│   │
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── MqttController.php
│   │
│   ├── Models/
│   │
│   └── Services/
│       ├── AiService.php
│       ├── DeviceControlService.php
│       └── MqttService.php
│
├── bootstrap/
│
├── config/
│   └── mqtt.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── api.php
│   ├── console.php
│   └── web.php
│
├── tests/
│
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

# ⚙️ Installation

## 1. Clone Repository

```bash
git clone https://github.com/phindalin29-rgb/Smart-Lighting-Control-Platform.git
```

Enter the project:

```bash
cd Smart-Lighting-Control-Platform
```

---

## 2. Install PHP Dependencies

```bash
composer install
```

---

## 3. Install Frontend Dependencies

```bash
npm install
```

---

## 4. Create Environment File

Copy `.env.example` to `.env`.

### Windows

```bash
cp .env.example .env
```

Or manually create:

```text
.env
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

# 🗄️ Database Configuration

Create a MySQL database.

Example:

```text
Database:
db_smart_lighting
```

Then configure `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

If seeders are available:

```bash
php artisan db:seed
```

Or:

```bash
php artisan migrate --seed
```

---

# 🔗 Storage Link

Run:

```bash
php artisan storage:link
```

---

# 🎨 Build Frontend

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

---

# 🚀 Run Laravel

Start the Laravel development server:

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

---

# 📡 MQTT Configuration

Configure MQTT in `.env`.

Example:

```env
MQTT_HOST=
MQTT_PORT=1883
MQTT_USERNAME=
MQTT_PASSWORD=
MQTT_CLIENT_ID=smart-lighting
MQTT_CONNECT_TIMEOUT=3
MQTT_API_TOKEN=
```

Never commit real MQTT credentials to GitHub.

---

# 🤖 AI Configuration

Configure AI credentials only inside `.env`.

Example:

```env
AI_API_KEY=
AI_MODEL=
AI_API_URL=
AI_TIMEOUT=30
```

Do not put real API keys inside:

```text
README.md
.env.example
GitHub repository
Frontend JavaScript
```

---

# 🔒 Environment & Security

Sensitive configuration is intentionally excluded from Git.

The repository uses:

```text
.env
.env.backup
.env.production
```

inside `.gitignore`.

The repository provides:

```text
.env.example
```

as a safe configuration template.

### Never commit

```text
.env
.env.backup
API keys
Passwords
MQTT credentials
Mail passwords
Database credentials
Private tokens
```

---

# 🧪 Testing

Run Laravel tests:

```bash
php artisan test
```

Or:

```bash
./vendor/bin/phpunit
```

Tests can cover:

* Authentication
* Device control
* MQTT service
* AI service
* API endpoints
* Database relationships
* Automation
* Scheduling

---

# 🔄 Development Workflow

Typical development workflow:

```text
1. Create / update feature
        ↓
2. Test locally
        ↓
3. Run Laravel tests
        ↓
4. Check Git status
        ↓
5. Git add
        ↓
6. Git commit
        ↓
7. Git push
```

Commands:

```bash
git status
git add .
git commit -m "Update Smart Lighting Platform"
git push
```

---

# 📸 Screenshots

Add screenshots of your application here.

## Dashboard

```text
screenshots/dashboard.png
```

## Device Management

```text
screenshots/devices.png
```

## Device Control

```text
screenshots/device-control.png
```

## MQTT Monitoring

```text
screenshots/mqtt.png
```

## AI Assistant

```text
screenshots/ai-assistant.png
```

> Replace these paths with your actual screenshot files.

---

# 🎥 Demo

Add your project demo here:

```text
Live Demo: YOUR_DEMO_URL
Video Demo: YOUR_VIDEO_URL
```

---

# 📋 Example Use Case

Imagine a smart home with three rooms:

```text
🏠 Smart Home

├── 🛋️ Living Room
│   └── 💡 Ceiling Light
│
├── 🛏️ Bedroom
│   └── 💡 Bedroom Light
│
└── 🍳 Kitchen
    └── 💡 Kitchen Light
```

The user can open the dashboard and control:

```text
Living Room Light → ON
Bedroom Light     → OFF
Kitchen Light     → ON
```

The commands are sent through:

```text
Laravel
   ↓
MQTT
   ↓
ESP32
   ↓
Relay
   ↓
Light
```

---

# 🎯 Project Goals

The main goals of this project are:

* Build a real-world IoT platform
* Learn Laravel architecture
* Connect web applications with hardware
* Implement MQTT communication
* Control ESP32 devices
* Build real-time device monitoring
* Implement scheduling and automation
* Integrate AI
* Build REST APIs
* Practice database design
* Practice software testing
* Create a professional portfolio project

---

# 🚀 Future Improvements

Planned improvements include:

* [ ] Real-time WebSocket updates
* [ ] Multiple MQTT brokers
* [ ] Energy consumption monitoring
* [ ] Electricity usage reports
* [ ] Mobile application
* [ ] Push notifications
* [ ] Voice control
* [ ] Advanced AI automation
* [ ] AI-based energy optimization
* [ ] Role & Permission Management
* [ ] Multi-user smart home
* [ ] Device grouping
* [ ] Scene management
* [ ] Sensor integration
* [ ] Motion sensors
* [ ] Temperature sensors
* [ ] Light sensors
* [ ] Production deployment
* [ ] Docker support
* [ ] CI/CD pipeline

---

# 🧠 Learning Outcomes

Through this project, the following development concepts are demonstrated:

### Backend

```text
Laravel
MVC
Eloquent
Services
Controllers
Middleware
Validation
Authentication
REST API
Queues
Scheduler
```

### Database

```text
MySQL
Migrations
Models
Relationships
Indexes
CRUD
Database Transactions
```

### IoT

```text
ESP32
Wi-Fi
MQTT
Publish / Subscribe
Device State
Device Commands
```

### Software Engineering

```text
Git
GitHub
Testing
Debugging
Environment Configuration
API Design
Service Architecture
```

### AI

```text
AI API
Natural Language Commands
Intent Detection
Smart Automation
AI Assistant
```

---

# 🏆 Portfolio Highlights

This project demonstrates the ability to build an application that combines:

```text
                 FULL-STACK
                     │
       ┌─────────────┼─────────────┐
       │             │             │
     Laravel       MySQL         Frontend
       │
       ├──────── MQTT ──────── ESP32
       │
       ├──────── REST API
       │
       ├──────── Automation
       │
       ├──────── Scheduling
       │
       └──────── AI Assistant
```

It combines **Web Development + Backend + Database + IoT + MQTT + AI** into one practical system.

---

# 👨‍💻 Author

**Phin Dalin**

GitHub:

https://github.com/phindalin29-rgb

Project:

https://github.com/phindalin29-rgb/Smart-Lighting-Control-Platform

---

# 📄 License

This project is intended for **educational, portfolio, and demonstration purposes**.

You may modify and extend the project for learning and development.

---

# ⭐ Support

If you find this project useful, consider giving the repository a ⭐ on GitHub.

Thank you for checking out **Smart Lighting Control Platform**! 💡🚀

````

### ដាក់ចូល Project

នៅក្នុង Git Bash:

```bash
cd /c/laragon/www/Smart-Lighting-Control-Platform
````

បើ `README.md` មានរួច៖

```bash
code README.md
```

លុប content ចាស់ → paste README ខាងលើ → **Save**។

បន្ទាប់៖

```bash
git status
git add README.md
git commit -m "Create professional project README"
git push
```

ចុងក្រោយ៖

```bash
git status
```

គួរតែឃើញ៖

```text
nothing to commit, working tree clean
```

[មើល GitHub Repository របស់អ្នក](https://github.com/phindalin29-rgb/Smart-Lighting-Control-Platform?utm_source=chatgpt.com)

**ចំណុចបន្ទាប់ដែលគួរធ្វើសម្រាប់ Portfolio:** បន្ថែម `screenshots/` + រូប **Dashboard, Device CRUD, ON/OFF, MQTT និង AI Assistant** ទៅ README ដើម្បីឱ្យ Recruiter ចូល GitHub ហើយឃើញ Project របស់អ្នកភ្លាមៗ។
