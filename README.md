# Currency Converter Web Application Setup Instructions

## Overview

This currency converter web application allows users to easily and quickly convert one currency to another based on real-time exchange rates. Users can select the base and target currencies from a list and enter the amount to convert, with the latest rates fetched from a reliable exchange rate API. The application displays the 5 most recent currency conversion records.

## Requirements

-   PHP version: **8.2**
-   Laravel: **11**
-   Vue: **3**
-   Inertia.js: For seamless server-side rendering.

## Installation Steps

1. **Clone the repository:**

    ```bash
    git clone <repository-url>
    cd currency-converter-web-application

    ```

2. composer install

    ```bash
    composer install
    npm install
    ```

3. Copy the `.env.example` file to `.env` and configure your database settings:

    ```bash
    cp .env.example .env
    nano .env
    ```

4. Generate an app key:

    ```bash
    php artisan key:generate
    ```

5. Run database migrations and run application:

    ```bash
    php artisan migrate
    npm run dev
    php artisan serv
    ```

6. database connections:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

7. test the application:

    php artisan test --filter CurrencyControllerTest

8. url and secret key:
   EXCHANGE_RATE_API_KEY=your_api_key_here
   EXCHANGE_RATE_API_URL=https://api.example.com/latest

`https://github.com/akshaybatar/currency-converter-web-application`
