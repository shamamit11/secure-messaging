# Secure Messaging API in Laravel 11

This repository contains a Laravel application using SQLite as the database.

## Prerequisites

-   PHP 8.2^

## Getting Started

1. **Clone the Repository**

    ```bash
    git clone https://github.com/shamamit11/secure-messaging.git

    cd secure-messaging

    ```

2. **Installation**

-   Please follow the instruction to setup SQlite database while running the migration.

    ```bash
    composer install

    cp .env.example .env

    php artisan migrate

    ```

3. **Run the application**

    ```bash
    php artisan serve

    ```

4. **Access the Application**

-   This application uses Laravel Request Docs for API Documentation.

-   Open your web browser and navigate to http://localhost:8000/request-docs to view the API.

-   The api endpoint must contain /api. For example: http://localhost:8000/api/message

-   You can use postman to test the api endpoints.

-   To create a message:
    Endpoint: http://localhost:8000/api/message <br>
    Method: Post <br>
    Required Body Parameters: text (string), recipient (string | email) <br>

-   To read a message:
    Endpoint: http://localhost:8000/api/message/read <br>
    Method: Post <br>
    Required Body Parameters: messageId (int), decryptionKey (string) <br>

-   Once you create a message, you will receive messageId and decryptionKey which can be used to read a message.

-   This application uses SoftDeletes for the demo purpose.

5. **Running Tests**

    ```bash
    ./vendor/bin/pest

    ```
