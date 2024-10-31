<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://github.com/anas1412/Fido/blob/main/public/images/logo.png?raw=true" width="100" alt="Fido Logo">
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

# Fido: Revolutionizing Accounting for Professionals

Fido is a SaaS platform tailored to meet the unique needs of Tunisian accountants. Designed with simplicity, compliance, and efficiency in mind, Fido uses the latest technologies to enhance accounting workflows, automate essential tasks, and provide professionals with tools to manage reports, invoices, client interactions, and tax obligations.

## Key Technologies

-   **[Laravel](https://laravel.com/):** Scalable PHP framework.
-   **[TailwindCSS](https://tailwindcss.com/):** Utility-first CSS for rapid design.
-   **[FilamentPHP](https://filamentphp.com/):** Elegant admin panel.
-   **[SQLite](https://www.sqlite.org/):** Lightweight, serverless database.
-   **[Docker](https://www.docker.com/):** Containerized app deployment.
-   **[Kubernetes](https://kubernetes.io/):** Container orchestration.
-   **[ArgoCD](https://argoproj.github.io/argo-cd/):** GitOps continuous delivery for Kubernetes.
-   **[Amazon AWS](https://aws.amazon.com/):** Cloud infrastructure.

## Fido’s Key Features

### Fremium Features

-   **Client and Project Management:** Easily track clients and engagements.
-   **Professional Fees Report:** Generate and export detailed professional fees reports.
-   **Withholding Tax Statement:** Simplifies management of withholding tax obligations.
-   **Debit Note Statement:** Manage and record debit transactions efficiently.
-   **Basic Invoicing and Reports:** Generate and export essential accounting reports and invoices.

### Premium Features

-   **Custom Reports and Advanced Analytics:** Enhanced data insights for better decision-making.
-   **ERP and CRM Integrations:** Connect with ERP systems for seamless data sharing.
-   **Automated Backups:** Regular data backup and restore.
-   **Kanban Workflow:** Visual task and project management.
-   **Video Conferencing and Meeting Scheduling:** Tools to manage client meetings in-app.
-   **Calendar and Push Notifications:** Stay on top of deadlines and important events.
-   **Payment Solutions:** Secure client invoicing and payment processing options.

## Visual Insights

<p align="center">
  <img src="Demo1.png?raw=true" width="45%" alt="Demo Screenshot 1">
  <img src="Demo2.png?raw=true" width="45%" alt="Demo Screenshot 2">
</p>

## System Requirements

-   **PHP 8.2 or higher:** [Download PHP](https://windows.php.net/downloads/releases/php-8.3.13-nts-Win32-vs16-x64.zip)
-   **Composer:** [Install Composer](https://getcomposer.org/download/)
-   **Git:** [Install Git](https://git-scm.com/downloads)

### PHP Configuration

**Edit the `php.ini` File inside your PHP installation directory:**

-   Open the `php.ini` file located in your PHP installation directory you chose (e.g., `C:\xampp\php\php.ini`).
-   Uncomment the following lines by removing the semicolon (;) at the beginning:
    ```ini
    extension=pdo_sqlite
    extension=json
    extension=ctype
    extension=tokenizer
    extension=fileinfo
    extension=mbstring
    extension=openssl
    extension=bcmath
    extension=intl
    ```

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
