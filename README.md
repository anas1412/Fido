<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="/public/images/logo.png?raw=true" width="100" alt="Fido Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

# Fido: Automating Accounting with Ease

Fido is a freelancing project dashboard platform tailored to meet the unique needs of a Tunisian accountant. Designed with simplicity, compliance, and efficiency in mind, Fido uses the latest technologies to enhance accounting workflows, automate essential tasks, and provide professionals with tools to manage reports and tax obligations.

## Key Technologies

-   **[Laravel](https://laravel.com/):** Scalable PHP framework.
-   **[Livewire](https://livewire.com/):** Real-time web development.
-   **[TailwindCSS](https://tailwindcss.com/):** Utility-first CSS for rapid design.
-   **[FilamentPHP](https://filamentphp.com/):** Elegant admin panel.
-   **[SQLite](https://www.sqlite.org/):** Lightweight, serverless database.

## Fido’s Key Features

### Current Features

-   **Employees Accounts Management:** Easily manage employees access on the dashboard.
-   **Professional Fees Report:** Generate and export detailed professional fees reports.
-   **Withholding Tax Statement:** Simplifies management of withholding tax obligations.
-   **Debit Note Statement:** Manage and record debit transactions efficiently.
-   **Basic Reports:** Generate and export essential accounting reports.

### Todo Features

-   **Advanced Invoicing and Reports:** Generate and export customized accounting reports and invoices.
-   **Clients and Projects Management:** Easily track clients and engagements.
-   **Custom Reports and Advanced Analytics:** Enhanced data insights for better decision-making.
-   **ERP and CRM Integrations:** Connect with ERP & CRM systems for seamless data sharing and 24/7 support.
-   **Automated Backups:** Regular data backup and restore.
-   **Kanban Workflow:** Visual task and project management.
-   **Video Conferencing and Meeting Scheduling:** Tools to manage client meetings in-app.
-   **Calendar and Push Notifications:** Stay on top of deadlines and important events.
-   **Payment Solutions:** Secure client invoicing and payment processing options.

## Visual Insights (Freemium Version)

<p align="center">
  <img src="Demo1.png?raw=true" width="80%" alt="Demo Screenshot 1">
  <img src="Demo2.png?raw=true" width="80%" alt="Demo Screenshot 2">
  <!-- <img src="DB_schema.png?raw=true" width="80%" alt="SQLite_DB_schema"> -->
</p>

## System Requirements

-   **Git:** [Install Git](https://git-scm.com/downloads)
-   **Composer:** [Install Composer](https://getcomposer.org/download/) (choose the php.exe inside PHP8.2 folder inside the project folder)

## Quick Start Guide

### Clone the Repository

    git clone https://github.com/anas1412/Fido.git
    cd Fido

### Environment Setup

**Copy the example environment file and configure your settings:**

    cp .env.example .env

**Install Dependencies and Initialize Database**

    composer install
    php artisan migrate --seed

## Running Fido Locally

### Development Mode

**Start the development server**

    php artisan serve

Or double click on `server.bat` in the project directory.
Access the application at http://localhost:8000.

### Production Mode

Configure Apache for production:

1.  **Virtual Host Configuration:**

    Typically located in `/etc/apache2/sites-available` or `/etc/nginx/sites-available`. Create a new file named `fido.conf` and add the following content:

    ```apache
        <VirtualHost *:80>
            ServerName fido.local
            DocumentRoot "/home/username/Fido/public"
            <Directory "/home/username/Fido/public">
                AllowOverride All
                Require all granted
             </Directory>
        </VirtualHost>
    ```

Replace `username` with your actual username.

2.  **Finalize Setup**

    ```
    sudo a2ensite fido.conf
    sudo nano /etc/hosts
    ```

    Add the following line:

    ```
    127.0.0.1   fido.local
    ```

    Restart Apache:

    ```
    sudo systemctl restart apache2
    ```

    Access the application at http://fido.local.

### Default User Accounts

The database seeder will create the following users:

-   **Admin User:**

    -   Email: `admin@mail.com`
    -   Password: `admin123`

-   **Normal User:**
    -   Email: `user@mail.com`
    -   Password: `user123`

## License

Fido is built on the Laravel framework, licensed under the [MIT license](https://opensource.org/licenses/MIT).
