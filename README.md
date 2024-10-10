# Supplier Products Application

This repository contains a full-stack application with a Laravel backend and a Vue.js frontend.

## Prerequisites

- PHP >= 7.3
- Composer
- Node.js >= 12.x
- npm or yarn

## Project Setup

### Backend

1. Navigate to the backend directory:

   ```sh
   cd backend
   ```

2. Install PHP dependencies using Composer:

   ```sh
   composer install
   ```

3. Copy the example environment file and configure it:

   ```sh
   cp .env.example .env
   ```

4. Generate the application key:

   ```sh
   php artisan key:generate
   ```

5. Run the database migrations:

   ```sh
   php artisan migrate
   ```

6. Start the Laravel development server:

   ```sh
   php artisan serve
   ```

### Frontend

1. Navigate to the frontend directory:

   ```sh
   cd frontend
   ```

2. Install JavaScript dependencies:

   ```sh
   npm install
   ```

3. Compile and hot-reload for development:

   ```sh
   npm run dev
   ```

## Running the Application

1. Ensure the backend server is running:

   ```sh
   php artisan serve
   ```

2. Ensure the frontend development server is running:

   ```sh
   npm run dev
   ```

3. Open your browser and navigate to the frontend URL (usually `http://localhost:5173`).

## Additional Commands

### Backend

- Run tests:

  ```sh
  phpunit
  ```

### Frontend

- Compile and minify for production:

  ```sh
  npm run build
  ```

- Lint with ESLint:

  ```sh
  npm run lint
  ```

## Recommended IDE Setup

- [VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (and disable Vetur).
