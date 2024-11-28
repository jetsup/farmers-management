# Dairy Farmer Management System

## Introduction

This is a simple dairy farmer management system that allows dairy farmers to manage their cows, milk production, and sales. The system allows farmers to add cows, record milk production, and record sales. The system also provides a summary of the total milk production and sales.

## Features

-   Add cows
-   Record milk production
-   Record sales
-   View total milk production
-   View total sales

## Technologies

-   Laravel
-   MySQL/MariaDB
-   Bootstrap
-   Vite

## Installation

1. Clone the repository

    ```bash
    git clone https://www.github.com/jetsup/farmers-management.git
    ```

2. Navigate to the project directory

    ```bash
    cd farmers-management
    ```

3. Install dependencies

    ```bash
    composer install
    npm install
    ```

4. Create a `.env` file

    ```bash
    cp .env.example .env
    ```

5. Generate an application key

    ```bash
    php artisan key:generate
    ```

6. Create a database and update the `.env` file with the database credentials

7. Run the migrations

    ```bash
    php artisan migrate
    ```

8. Start the development servers on two separate terminals

    ```bash
    php artisan serve
    ```

    ```bash
    npm run dev
    ```

9. Visit the application on your browser

    ```bash
    http://localhost:8000
    ```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
