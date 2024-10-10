<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://github.com/anas1412/Fido/blob/main/public/images/logo.png?raw=true" width="100" alt="Fido Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<!-- <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a> -->
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Fido

Fido is a web application for accountants. It's user friendly, intuitive and built with amazing tools such as:

-   [Laravel](https://laravel.com/).
-   [TailwindCSS](https://tailwindcss.com/).
-   [FilamentPHP](https://filamentphp.com/).

## Demo Screenshots

<p align="center">
  <img src="https://github.com/anas1412/Fido/blob/main/Demo1.png?raw=true" width="45%" alt="Demo Screenshot 1">
  <img src="https://github.com/anas1412/Fido/blob/main/Demo2.png?raw=true" width="45%" alt="Demo Screenshot 2">
</p>

## Prerequisites

1. **Install WAMP or Alternative:**

    - Download and install WAMP from [here](https://www.wampserver.com/en/).
    - Ensure you have installed **VC Redist AIO** before installing WAMP. Download it [here](https://github.com/abbodi1406/vcredist/releases).

2. **Install Composer:**

    - Download and install Composer from [here](https://getcomposer.org/download/).
    - During installation, make sure to:
        - Select **"Add to PATH"**.
        - Choose **PHP 8.2** as the PHP version.

3. **Install Git:**
    - Download and install Git from [here](https://git-scm.com/downloads).

## Setting Up the Project

1. **Clone the Repository:**

    - Open the **WAMP** installation directory, typically located at `C:\wamp64\www\`.
    - Open a Command Prompt in this directory and run:
        ```bash
        git clone https://github.com/anas1412/Fido.git
        ```

2. **Set Up Environment Configuration:**

    - Navigate to the `Fido` folder in the **www** directory.
    - Copy the `.env.example` file and rename it to `.env`.
    - Make the necessary changes to the `.env` file, such as setting up your database credentials.

3. **Install Dependencies and Set Up the Database:**
    - Inside the `Fido` folder, open a Command Prompt and run the following commands:
        ```bash
        composer install
        php artisan migrate
        php artisan db:seed
        ```

## Running the Web App Locally

There are two ways to run the Fido web app locally:

### Development Mode

-   To run the web app in development mode, simply use:
    ```bash
    php artisan serve
    ```
-   The web application will be running on `localhost:8000` or `127.0.0.1:8000`

### Production Mode

For production, follow these additional steps:

1. **Set Up Apache Virtual Host:**

    - Open the `httpd-vhosts.conf` file located at:
        ```
        C:\wamp64\bin\apache\apache2.4.xx\conf\extra\httpd-vhosts.conf
        ```
    - Add the following configuration:
        ```apache
        <VirtualHost *:80>
            ServerName fido.local
            DocumentRoot "C:/wamp64/www/Fido/public"
            <Directory "C:/wamp64/www/Fido/public">
                AllowOverride All
                Require all granted
            </Directory>
        </VirtualHost>
        ```

2. **Update the Hosts File:**

    - Add the following line to your hosts file located at `C:\Windows\System32\drivers\etc\hosts`:
        ```
        127.0.0.1 fido.local
        ```

3. **Auto Start WAMP on Startup:**
    - Ensure that WAMP is set to auto-start on system startup for smooth operation.

## Default Users

The database seeder will create the following users:

1. **Admin User:**

    - Email: `admin@mail.com`
    - Password: `admin123`

2. **Normal User:**
    - Email: `user@mail.com`
    - Password: `user123`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
