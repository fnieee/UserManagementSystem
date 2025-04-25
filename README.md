# 🧑‍💼 User Management System (Laravel)

A simple yet powerful user management system built with Laravel. Designed specifically for **admin-only access**, this system allows an administrator to perform full CRUD (Create, Read, Update, Delete) operations through a clean web interface.

---

## ⚙️ Features

- 🔐 Admin-only access to user management
- 🧾 Create, view, update, and delete users
- ✅ Validation: Unique email, phone number, password
- 📦 Clean and structured Laravel MVC architecture
- 🎨 Bootstrap-based frontend

---

## 🚀 Setup Instructions
- Install Dependencies
composer install

- Create and configure .env file
cp .env.example .env

- Update Database
DB_CONNECTION=mysql
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

- Generate app key
php artisan key:generate

- Run migrations
php artisan migrate

- Seed an Admin user (optional)
php artisan tinker

\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'phone_number' => '0123456789',
    'password' => Hash::make('admin123'),
    'status' => 'active',
    'role' => 'admin',
]);

- Start Application
php artisan serve

- Visit http://localhost:8000 and log in as the admin.

## Web Endpoints
- GET /login – Show login form
- POST /login – Log in user
- POST /logout – Log out authenticated user
- GET /register – Show registration form (optional)
- POST /register – Register a new user (optional)
- GET /dashboard – Show admin dashboard
- GET /users – List users (with status filter and pagination)
- GET /users/create – Show form to create a new user
- POST /users – Create and store a new user
- GET /users/{id}/edit – Show form to edit a user
- PUT /users/{id} – Update existing user
- DELETE /users/{id} – Soft delete a user
- POST /users/bulk-delete – Soft delete multiple users at once
- GET /users/export – Export all users to Excel (Under Maintainence)

## API Endpoints (Under Maintainence)
- POST `/api/users` - Create User
- GET `/api/users` - List Users (with `status` filter and pagination)
- GET `/api/users/{id}` - User Details
- DELETE `/api/users/{id}` - Soft Delete User
- POST `/api/users/bulk-delete` - Bulk Delete
- GET `/api/users/export` - Export to Excel


## Assumptions and Design Choices
- Phone number & email must be unique
- Soft deletes ensure users are not permanently removed.
- Admin-only frontend access.
- Login or Register before accessing the system.

## Unit Testing
```bash
php artisan test
