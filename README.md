## Laravel

Starter kit laravel

## Requirement
-   PHP >= 7.3|^8.0
-   Web server XAMPP or Laragon

## How to install
-   Clone project `https://github.com/indra-yana/product-api.git`.
-   Open terminal (Git Bash or CMD) and go to the project root directory
-   Run `composer install`
-   Start web server if user XAMPP, Laragon, PHP Artisan Serve, etc
-   Create database on PHPMyAdmin with name `product_db`
-   Copy .env.example with command `cp .env.example .env` using git bash command
-   Setup database on .env file
-   Example:
    -   DB_CONNECTION=mysql
    -   DB_HOST=127.0.0.1
    -   DB_PORT=3306
    -   DB_DATABASE=product_db
    -   DB_USERNAME=root
    -   DB_PASSWORD=
-   run `php artisan key:generate`
-   run `php artisan migrate --seed`
-   Import postman collection `Product.postman_collection.json`
-   Untuk generate token authorization hit endpoint login lalu setup header Authorization dengan type bearer pada root Folder Collection
-   Done!

## User Login

**Administrator:**

```bash
email: admin@laravel.com
password: secret
```

**Member:**

```bash
email: member@laravel.com
password: secret
```
