# Project Management System - PT. Indonesia Gadai Oke

Sistem manajemen project internal berbasis web dengan role-based access control (RBAC) menggunakan Laravel 11, Blade Templates, dan Tailwind CSS.

## Tech Stack

- **Backend:** Laravel 11, PHP 8.1+
- **Frontend:** Blade Templates, Tailwind CSS 3.4, Alpine.js 3.15
- **Database:** MySQL
- **Build Tool:** Vite 5.4.8
- **Authentication:** Laravel Breeze (Session-based)

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL
- Git

## Cara Install & Run

### 1. Clone Repository
```bash
git clone <repository-url>
cd project-management
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### 3. Setup Environment
```bash
# Copy file .env
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env`:
```env
DB_CONNECTION=postgresqk
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_management_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Setup Database
```bash
# Buat database
postgresql -u root -p
CREATE DATABASE project_management_db;
EXIT;

# Jalankan migration dan seeder
php artisan migrate
php artisan db:seed
```

### 6. Run Development Server
```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite Dev Server
npm run dev
```

### 7. Login Credentials

**Admin Account:**
- Email: `ptigo.karir@gmail.com`
- Password: `password123`


### Database Relationships

```
User ──┬─< Project (as project_manager)
       ├─< Project (as creator)
       └─< ProjectMember (many-to-many with Projects)

Role ──< User (one-to-many)

Project ──┬─> User (project_manager)
          ├─> User (created_by)
          └─< ProjectMember (many-to-many with Users)
```

## Arsitektur Sistem

### 1. Pola Arsitektur: MVC (Model-View-Controller)

```
┌─────────────────────────────────────────┐
│           Browser/Client                │
└─────────────┬───────────────────────────┘
              │
              ↓
┌─────────────────────────────────────────┐
│  Routes (web.php, auth.php)             │
│  - Role-based routing                   │
│  - Middleware authentication            │
└─────────────┬───────────────────────────┘
              │
              ↓
┌─────────────────────────────────────────┐
│  Middleware Layer                       │
│  - CheckRole (RBAC)                     │
│  - Auth                                 │
│  - CSRF Protection                      │
└─────────────┬───────────────────────────┘
              │
              ↓
┌─────────────────────────────────────────┐
│  Controllers                            │
│  ├─ Admin/DashboardController           │
│  ├─ Admin/UserController                │
│  ├─ Admin/ProjectController             │
│  └─ Auth/ProfileController              │
└─────────────┬───────────────────────────┘
              │
              ↓
┌─────────────────────────────────────────┐
│  Models (Eloquent ORM)                  │
│  ├─ User (with role helpers)            │
│  ├─ Role                                │
│  └─ Project (with progress logic)       │
└─────────────┬───────────────────────────┘
              │
              ↓
┌─────────────────────────────────────────┐
│  Views (Blade Templates)                │
│  ├─ layouts/admin.blade.php             │
│  ├─ admin/dashboard.blade.php           │
│  ├─ admin/users/*.blade.php             │
│  └─ admin/projects/*.blade.php          │
└─────────────┬───────────────────────────┘
              │
              ↓
┌─────────────────────────────────────────┐
│  Database (MySQL)                       │
│  - Users, Roles, Projects, Members      │
└─────────────────────────────────────────┘
```

### 2. Role-Based Access Control (RBAC)

**3 Level Access:**

#### Administrator
- Hak Akses:
  - Buat dan kelola user (CRUD)
  - Assign role ke user
  - Lihat dan kelola semua project
  - Akses penuh ke dashboard admin
- Dashboard: `/admin/dashboard`

#### Project Manager
- Hak Akses:
  - Buat project baru
  - Kelola project yang di-assign
  - Invite dan manage team members
  - Assign task ke members
  - Monitor progress project
- Dashboard: `/project-manager/dashboard`

#### Member
- Hak Akses:
  - Lihat project yang diikuti
  - Lihat task yang di-assign
  - Update status task
  - View-only access
- Dashboard: `/member/dashboard`

### 3. Security Features

- **Authentication:** Session-based dengan Laravel Breeze
- **Authorization:** Role-based middleware (`CheckRole`)
- **CSRF Protection:** Semua form protected
- **Password Hashing:** Bcrypt
- **Input Validation:** Server-side validation
- **SQL Injection Prevention:** Eloquent ORM
- **Session Regeneration:** Setelah login

## Features Implemented

### ✅ Authentication & Authorization
- Login/Logout dengan session-based auth
- Role-based access control (3 levels)
- Profile management

### ✅ User Management (Admin)
- CRUD users lengkap
- Assign/change user roles
- Activate/deactivate users
- Search dan filter users
- User detail page

### ✅ Project Management (Admin)
- Create project dengan PM assignment
- Assign multiple team members
- Project list dengan filter
- Progress tracking otomatis
- Status management

### ✅ Dashboard
- Admin dashboard dengan statistics
- User analytics
- Recent activities
- System status monitoring

## Development Notes

### Commands yang Berguna

```bash
# Clear all cache
php artisan optimize:clear

# Run specific seeder
php artisan db:seed --class=RoleSeeder

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName

# View routes
php artisan route:list

# Check routes by name
php artisan route:list --name=admin
```

### Build untuk Production

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set environment to production
APP_ENV=production
APP_DEBUG=false
```


## Troubleshooting

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
npm run dev
# atau
npm run build
```

### Error: Session expired
```bash
php artisan session:clear
```
**Last Updated:** September 2025
