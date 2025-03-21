# Task Management System

A Laravel-based task management system with user authentication, task CRUD operations, and responsive design.

## Features

- User Authentication (Register, Login, Logout)
- Task Management (Create, Read, Update, Delete)
- Task Status Tracking (Pending, Completed)
- Mobile-friendly Responsive Design
- AJAX for better UX
- Soft Deletes with restoration capability

## Setup Instructions

1. Clone the repository
2. Install dependencies
    composer install
    npm install
3. Set up environment file
    cp .env.example .env
    php artisan key
4. Set up database in .env file
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=tasks
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
5. Run migrations and seed the database
6. Build assets
    npm run dev
7. Serve the application

## Testing
