# Project Management System - PT. Indonesia Gadai Oke

Sistem manajemen project internal berbasis web dengan role-based access control (RBAC) dan RESTful API menggunakan Laravel 11, Blade Templates, dan Tailwind CSS.

---

## 📋 Table of Contents

- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
  - [Local Development](#local-development)
  - [VPS Production](#vps-production)
- [Features](#features)
- [API Documentation](#api-documentation)
- [User Roles](#user-roles)
- [Database Schema](#database-schema)
- [Development](#development)
- [Troubleshooting](#troubleshooting)

---

## 🚀 Tech Stack

- **Backend:** Laravel 11, PHP 8.2
- **Frontend:** Blade Templates, Tailwind CSS 3.4, Alpine.js 3.15
- **Database:** PostgreSQL
- **Authentication:** Laravel Breeze (Session-based) + Laravel Sanctum (API Token)
- **Build Tool:** Vite 5.4.8

---

## ⚙️ Requirements

- PHP >= 8.1
- Composer
- Node.js >= 18.x & NPM
- PostgreSQL >= 14
- Git

---

## 📦 Installation

### Local Development

#### 1. Clone Repository

```bash
git clone https://github.com/cuatiawww/project-management.git
cd project-management
```

#### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

#### 3. Setup Environment

```bash
# Copy file .env
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

#### 4. Konfigurasi Database

Edit file `.env`:

```env
APP_NAME="Project Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=project_management_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

#### 5. Setup Database

```bash
# Buat database (masuk ke PostgreSQL dulu)
psql -U postgres
CREATE DATABASE project_management_db;
\q

# Jalankan migration dan seeder
php artisan migrate
php artisan db:seed
```

#### 6. Run Development Server

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite Dev Server
npm run dev
```

#### 7. Akses Aplikasi

- **Web App:** http://localhost:8000
- **API Base URL:** http://localhost:8000/api

---

### VPS Production Setup

#### 1. Persiapan VPS (Ubuntu 22.04)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx

# Install PHP 8.2 + Extensions
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mbstring \
  php8.2-xml php8.2-zip php8.2-curl php8.2-pgsql php8.2-bcmath php8.2-gd \
  php8.2-intl -y

# Install PostgreSQL
sudo apt install postgresql postgresql-contrib -y
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Install Composer
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Install Node.js 20.x
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y

# Install Git
sudo apt install git -y
```

#### 2. Setup Database PostgreSQL

```bash
# Masuk ke PostgreSQL
sudo -u postgres psql

# Buat database dan user
CREATE DATABASE project_management_db;
CREATE USER pm_user WITH PASSWORD 'secure_password';
GRANT ALL PRIVILEGES ON DATABASE project_management_db TO pm_user;
ALTER DATABASE project_management_db OWNER TO pm_user;
\q

# Edit konfigurasi PostgreSQL (sesuaikan versi)
sudo nano /etc/postgresql/14/main/pg_hba.conf

# Ubah 'peer' menjadi 'md5' pada baris:
# local   all   postgres   md5
# local   all   all        md5

# Restart PostgreSQL
sudo systemctl restart postgresql
```

#### 3. Clone dan Setup Project

```bash
# Buat folder project
sudo mkdir -p /var/www/project-management
sudo chown -R $USER:$USER /var/www/project-management

# Clone dari GitHub
cd /var/www
git clone https://github.com/cuatiawww/project-management.git project-management

# Masuk ke folder project
cd /var/www/project-management

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Setup .env
cp .env.example .env
nano .env
```

Edit `.env` untuk production:

```env
APP_NAME="Project Management"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-vps-ip

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=project_management_db
DB_USERNAME=pm_user
DB_PASSWORD=secure_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

```bash
# Generate key dan setup
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data /var/www/project-management
sudo chmod -R 755 /var/www/project-management
sudo chmod -R 775 /var/www/project-management/storage
sudo chmod -R 775 /var/www/project-management/bootstrap/cache
```

#### 4. Konfigurasi Nginx

```bash
sudo nano /etc/nginx/sites-available/project-management
```

Isi dengan:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-vps-ip;
    root /var/www/project-management/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Aktifkan konfigurasi:

```bash
sudo ln -s /etc/nginx/sites-available/project-management /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

#### 5. Update Script (Otomatis)

Buat file update script:

```bash
nano /root/update-project.sh
```

Isi dengan:

```bash
#!/bin/bash
cd /var/www/project-management
echo "Pulling latest code..."
git pull origin main
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader
npm install
npm run build
echo "Running migrations..."
php artisan migrate --force
echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Setting permissions..."
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage bootstrap/cache
echo "Restarting services..."
sudo systemctl restart php8.2-fpm nginx
echo "Update complete!"
```

```bash
chmod +x /root/update-project.sh
```

**Cara update setelah push ke GitHub:**

```bash
/root/update-project.sh
```

---

## 🎯 Features

### ✅ Web Application

- **Authentication & Authorization**
  - Login/Logout dengan session-based auth
  - Role-based access control (Admin, Project Manager, Member)
  - Profile management

- **User Management (Admin Only)**
  - CRUD users lengkap
  - Assign/change user roles
  - Activate/deactivate users
  - Search dan filter users

- **Project Management**
  - Create dan kelola projects
  - Assign team members
  - Track progress otomatis
  - Status management
  - Project dashboard

- **Dashboard**
  - Role-based dashboard (Admin, PM, Member)
  - Statistics dan analytics
  - Recent activities

### ✅ RESTful API

- **Authentication:** Token-based (Laravel Sanctum)
- **Response Format:** JSON
- **Authorization:** Role-based per endpoint
- **Validation:** Input validation di semua endpoint
- **Error Handling:** Consistent error responses

---

## 📡 API Documentation

### Base URL

- **Local:** `http://localhost:8000/api`
- **Production:** `http://your-vps-ip/api`

### Authentication

API menggunakan **Bearer Token Authentication** dengan Laravel Sanctum.

#### Login untuk Mendapatkan Token

```http
POST /api/v1/login
Content-Type: application/json

{
  "email": "ptigo.karir@gmail.com",
  "password": "password123"
}
```

**Response:**

```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Ptigo Admin",
      "email": "ptigo.karir@gmail.com",
      "role": "admin"
    },
    "token": "1|abcdef123456...",
    "token_type": "Bearer"
  }
}
```

**Gunakan token di header untuk request selanjutnya:**

```http
Authorization: Bearer 1|abcdef123456...
```

---

### API Endpoints

#### 1. Health Check

```http
GET /api/health
```

**Response:**

```json
{
  "success": true,
  "message": "API is running",
  "timestamp": "2025-09-29T10:30:00Z",
  "version": "v1"
}
```

---

#### 2. Get Current User

```http
GET /api/v1/me
Authorization: Bearer {token}
```

**Response:**

```json
{
  "success": true,
  "message": "User retrieved successfully",
  "data": {
    "id": 1,
    "name": "Ptigo Admin",
    "email": "ptigo.karir@gmail.com",
    "role": "admin",
    "role_display": "Administrator"
  }
}
```

---

#### 3. List Projects

```http
GET /api/v1/projects?status=in_progress&search=website&page=1
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (optional): planning, in_progress, completed, on_hold
- `search` (optional): Search by name or description
- `page` (optional): Page number (default: 1)

**Response:**

```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Website Redesign",
        "description": "Redesign company website",
        "status": "in_progress",
        "status_display": "In Progress",
        "start_date": "2025-09-01",
        "end_date": "2025-12-31",
        "progress": 45,
        "is_active": true,
        "project_manager": {
          "id": 2,
          "name": "John Manager",
          "email": "john@example.com"
        },
        "members_count": 5,
        "created_at": "2025-09-01T10:00:00Z"
      }
    ],
    "total": 10,
    "per_page": 10,
    "last_page": 1
  }
}
```

**Authorization Rules:**
- Admin: Lihat semua projects
- Project Manager: Lihat projects yang dia manage
- Member: Lihat projects yang dia ikuti

---

#### 4. Get Project Detail

```http
GET /api/v1/projects/{id}
Authorization: Bearer {token}
```

**Response:**

```json
{
  "success": true,
  "message": "Project retrieved successfully",
  "data": {
    "id": 1,
    "name": "Website Redesign",
    "description": "Redesign company website with modern UI/UX",
    "status": "in_progress",
    "status_display": "In Progress",
    "start_date": "2025-09-01",
    "end_date": "2025-12-31",
    "progress": 45,
    "is_active": true,
    "project_manager": {
      "id": 2,
      "name": "John Manager",
      "email": "john@example.com",
      "role": "project_manager"
    },
    "members": [
      {
        "id": 3,
        "name": "Alice Developer",
        "email": "alice@example.com",
        "role": "member"
      }
    ],
    "created_by": {
      "id": 1,
      "name": "Ptigo Admin",
      "email": "ptigo.karir@gmail.com"
    },
    "created_at": "2025-09-01T10:00:00Z",
    "updated_at": "2025-09-15T14:30:00Z"
  }
}
```

---

#### 5. Create Project

```http
POST /api/v1/projects
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Mobile App Development",
  "description": "Develop iOS and Android app",
  "status": "planning",
  "start_date": "2025-10-01",
  "end_date": "2026-03-31",
  "project_manager_id": 2,
  "members": [3, 4, 5],
  "is_active": true
}
```

**Field Validation:**
- `name`: required, string, max:255
- `description`: optional, string
- `status`: required, in: planning, in_progress, completed, on_hold
- `start_date`: optional, date, format: Y-m-d
- `end_date`: optional, date, after: start_date
- `project_manager_id`: required for Admin, auto-filled for PM
- `members`: optional, array of user IDs
- `is_active`: optional, boolean (default: true)

**Response:**

```json
{
  "success": true,
  "message": "Project created successfully",
  "data": {
    "id": 11,
    "name": "Mobile App Development",
    "status": "planning",
    "project_manager": {...},
    "members": [...]
  }
}
```

**Authorization:**
- Admin: Boleh create, wajib pilih PM
- Project Manager: Boleh create, otomatis jadi PM
- Member: Tidak boleh create (403 Forbidden)

---

#### 6. Update Project

```http
PUT /api/v1/projects/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Project Name",
  "description": "Updated description",
  "status": "in_progress",
  "start_date": "2025-09-01",
  "end_date": "2025-12-31",
  "project_manager_id": 2,
  "members": [3, 4, 5, 6],
  "is_active": true
}
```

**Response:**

```json
{
  "success": true,
  "message": "Project updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Project Name",
    ...
  }
}
```

**Authorization:**
- Admin: Boleh update semua projects
- Project Manager: Hanya boleh update projects yang dia manage
- Member: Tidak boleh update (403 Forbidden)

---

#### 7. Delete Project

```http
DELETE /api/v1/projects/{id}
Authorization: Bearer {token}
```

**Response:**

```json
{
  "success": true,
  "message": "Project deleted successfully"
}
```

**Authorization:**
- Admin: Boleh delete semua projects
- Project Manager: Hanya boleh delete projects yang dia manage
- Member: Tidak boleh delete (403 Forbidden)

---

#### 8. Update Project Status (Member)

```http
PATCH /api/v1/projects/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "completed",
  "notes": "All tasks finished successfully"
}
```

**Response:**

```json
{
  "success": true,
  "message": "Project status updated successfully",
  "data": {
    "id": 1,
    "status": "completed",
    "updated_by": "Alice Member"
  }
}
```

**Authorization:**
- Member yang tergabung dalam project bisa update status

---

#### 9. Get Statistics

```http
GET /api/v1/statistics/projects
Authorization: Bearer {token}
```

**Response:**

```json
{
  "success": true,
  "message": "Statistics retrieved successfully",
  "data": {
    "total_projects": 25,
    "by_status": {
      "planning": 5,
      "in_progress": 12,
      "completed": 6,
      "on_hold": 2
    },
    "active_projects": 22,
    "inactive_projects": 3,
    "your_projects": 8
  }
}
```

---

#### 10. Logout

```http
POST /api/v1/logout
Authorization: Bearer {token}
```

**Response:**

```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### Error Responses

#### 401 Unauthorized

```json
{
  "message": "Unauthenticated."
}
```

**Cause:** Token tidak valid atau tidak ada.

---

#### 403 Forbidden

```json
{
  "success": false,
  "message": "Unauthorized. Only Admin and Project Manager can create projects."
}
```

**Cause:** User tidak punya akses untuk action ini.

---

#### 404 Not Found

```json
{
  "success": false,
  "message": "Project not found"
}
```

---

#### 422 Validation Error

```json
{
  "message": "The name field is required. (and 1 more error)",
  "errors": {
    "name": ["The name field is required."],
    "status": ["The status field is required."]
  }
}
```

---

### Testing API

#### Menggunakan cURL

```bash
# Login
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"ptigo.karir@gmail.com","password":"password123"}'

# Get Projects (ganti YOUR_TOKEN)
curl -X GET http://localhost:8000/api/v1/projects \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Create Project
curl -X POST http://localhost:8000/api/v1/projects \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Project",
    "status": "planning",
    "project_manager_id": 2,
    "members": [3, 4]
  }'
```

#### Menggunakan Postman

1. Import collection dari repository: `postman_collection.json`
2. Set environment variable `base_url` = `http://localhost:8000`
3. Login untuk mendapatkan token (otomatis tersimpan di environment)
4. Test endpoints lainnya

---

## 👥 User Roles

### Administrator

**Default Account:**
- Email: `ptigo.karir@gmail.com`
- Password: `password123`

**Akses:**
- Kelola semua users (CRUD)
- Assign roles
- Kelola semua projects
- Dashboard admin
- Full API access

**Dashboard:** `/admin/dashboard`

---

### Project Manager

**Akses:**
- Buat project baru
- Kelola projects yang di-assign
- Manage team members
- Monitor progress
- API: Create, Read, Update, Delete (own projects only)

**Dashboard:** `/project-manager/dashboard`

---

### Member

**Akses:**
- Lihat projects yang diikuti
- Lihat tasks
- Update status tasks
- API: Read (own projects), Update status

**Dashboard:** `/member/dashboard`

---

## 🗄️ Database Schema

### Users Table

```sql
- id (PK)
- name
- email (unique)
- password
- role_id (FK -> roles.id)
- is_active
- timestamps
```

### Roles Table

```sql
- id (PK)
- name (admin, project_manager, member)
- display_name
- timestamps
```

### Projects Table

```sql
- id (PK)
- name
- description
- status (planning, in_progress, completed, on_hold)
- start_date
- end_date
- progress (0-100)
- project_manager_id (FK -> users.id)
- created_by (FK -> users.id)
- is_active
- timestamps
```

### Project Members Table (Pivot)

```sql
- id (PK)
- project_id (FK -> projects.id)
- user_id (FK -> users.id)
- timestamps
```

---

## 💻 Development

### Run Tests

```bash
php artisan test
```

### Code Style

```bash
# PHP CS Fixer
composer format

# Check code style
composer check-style
```

### Database Commands

```bash
# Fresh migration + seed
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=RoleSeeder

# Rollback last migration
php artisan migrate:rollback
```

### Clear Cache

```bash
# Clear all cache
php artisan optimize:clear

# Clear specific cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### View Routes

```bash
# All routes
php artisan route:list

# Filter by name
php artisan route:list --name=admin

# Filter by path
php artisan route:list --path=api
```

---

## 🐛 Troubleshooting

### Error: "Class not found"

```bash
composer dump-autoload
```

### Error: "View not found"

```bash
php artisan view:clear
```

### Error: CSS tidak muncul

```bash
# Development
npm run dev

# Production
npm run build
```

### Error: Session expired

```bash
php artisan session:clear
```

### Error: Database connection failed

Cek `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=project_management_db
DB_USERNAME=pm_user
DB_PASSWORD=your_password
```

Test koneksi:

```bash
php artisan tinker
DB::connection()->getPdo();
```

### Error 500 di VPS

```bash
# Cek Laravel log
tail -50 /var/www/project-management/storage/logs/laravel.log

# Cek Nginx error log
sudo tail -50 /var/log/nginx/error.log

# Set permission
sudo chown -R www-data:www-data /var/www/project-management
sudo chmod -R 775 storage bootstrap/cache

# Restart services
sudo systemctl restart php8.2-fpm nginx
```

---

## 📝 License

This project is proprietary software developed for PT. Indonesia Gadai Oke.

---

## 📞 Support

For issues or questions, please contact:
- Email: ptigo.karir@gmail.com
- GitHub Issues: [Repository Issues](https://github.com/cuatiawww/project-management/issues)

---

**Last Updated:** September 2025
