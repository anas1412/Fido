<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="/public/images/logo.png?raw=true" width="100" alt="Fido Logo">
  </a>
</p>

<h1 align="center">Fido: Automating Accounting with Ease</h1>

Fido is a freelancing project dashboard platform tailored to meet the unique needs of a Tunisian accountant. Designed with simplicity, compliance, and efficiency in mind, Fido uses the latest technologies to enhance accounting workflows, automate essential tasks, and provide professionals with tools to manage reports and tax obligations.

## Key Technologies

-   **[Laravel](https://laravel.com/):** Scalable PHP framework.
-   **[Livewire](https://livewire.com/):** Real-time web development.
-   **[TailwindCSS](https://tailwindcss.com/)::** Utility-first CSS for rapid design.
-   **[FilamentPHP](https://filamentphp.com/):** Elegant admin panel (v4).
-   **[SQLite](https://www.sqlite.org/):** Lightweight, serverless database.

## Quick Start Guide (for Windows)

Getting started with Fido is designed to be as simple as possible. Just follow these two steps:

1.  **Download the Project:**
    -   Download the project files as a ZIP and extract them to a folder on your computer.
    -   Alternatively, if you have Git installed, you can clone the repository:
        ```bash
        git clone https://github.com/anas1412/Fido.git
        cd Fido
        ```

2.  **Run the Server:**
    -   Double-click the `server.bat` file.

That's it! The script will automatically handle everything for you:
-   It will download and set up the correct version of PHP if you don't have it.
-   It will download and configure Composer (the PHP package manager).
-   It will install all necessary dependencies.
-   It will create and set up the database.
-   It will start the application server using Laravel's built-in development server and open it in your web browser.

## Desktop Application (Electron)

The Fido dashboard can also be run as a standalone desktop application using Electron. This provides a more integrated, native-like experience.

### Prerequisites

Make sure you have [Node.js](https://nodejs.org/) and npm installed on your system.

### Running in Development

1.  **Install Dependencies:**
    Open a terminal in the project root and run:
    ```bash
    npm install
    ```

2.  **Start the Application:**
    Once the installation is complete, run:
    ```bash
    npm start
    ```
    This will launch the Electron application and the necessary PHP server in development mode.

### Building for Production

To package the application into a distributable executable (`.exe` for Windows), run the following command:

```bash
npm run dist
```

This command uses `electron-builder` to create a production-ready build. The final executable and associated files will be located in the `dist` directory.

### Building the Demo Version

To package a special demo version of the application, which uses the `.env.demo` configuration and seeds the database with sample data, run the following command:

```bash
npm run dist:demo
```

This command is ideal for creating a distributable installer for trial or demonstration purposes. The final executable will also be located in the `dist` directory.

## Database Seeding

After setting up the project and running migrations, you can seed your database with initial data.

### Initial Settings

To seed essential settings like tax rates and company information (which are required for the application to function correctly), run:

```bash
php artisan migrate:fresh --seed
```

### Admin Account

To create the default administrator account, use the following command. This will only create the account if an admin user with the specified email does not already exist. The credentials are configured in your `.env` file.

-   **Email:** `admin@fido.tn` (configurable in `.env`)
-   **Password:** `password` (configurable in `.env`)

```bash
php artisan seed:admin
```

### Demo Data

To seed demo data, including a demo user, sample clients, honoraires, and debit notes, use:

-   **Demo User Email:** `demo@fido.tn` (configurable in `.env`)
-   **Demo User Password:** `password` (configurable in `.env`)

```bash
php artisan seed:demo
```

## Fido’s Key Features

### Current Features

-   **Client & Invoice Management:** Track your clients and manage their invoices and fee notes (honoraires) all in one place.
-   **Customizable Document Templates:** Tailor the design of your fee notes, invoices, and debit notes to match your professional brand.
-   **One-Click PDF Downloads:** Generate and download PDF versions of all your reports and financial documents for easy printing, emailing, or archiving.
-   **Database Backup & Restore:** Create, download, apply, and import database backups directly from the admin panel.
-   **Employees Accounts Management:** Easily manage employees access on the dashboard.
-   **Professional Fees Report:** Generate and export detailed professional fees reports.
-   **Withholding Tax Statement:** Simplifies management of withholding tax obligations.
-   **Debit Note Statement:** Manage and record debit transactions efficiently. (Report generation is Work in Progress)
-   **Basic Reports:** Generate and export essential accounting reports.

### Todo Features

-   **Advanced Invoicing and Reports:** Generate and export customized accounting reports and invoices. (Some parts are Work in Progress)
-   **Clients and Projects Management:** Easily track clients and engagements.
-   **Custom Reports and Advanced Analytics:** Enhanced data insights for better decision-making.
-   **ERP and CRM Integrations:** Connect with ERP & CRM systems for seamless data sharing and 24/7 support.
-   **Kanban Workflow:** Visual task and project management.
-   **Video Conferencing and Meeting Scheduling:** Tools to manage client meetings in-app.
-   **Calendar and Push Notifications:** Stay on top of deadlines and important events.
-   **Payment Solutions:** Secure client invoicing and payment processing options.

## Visual Insights (Freemium Version)

<p align="center">
  <img src="Demo1.png?raw=true" width="80%" alt="Demo Screenshot 1">
  <img src="Demo2.png?raw=true" width="80%" alt="Demo Screenshot 2">
</p>

## Troubleshooting

### `SQLSTATE[HY000]: General error: 26 file is not a database`

This error indicates that the `database.sqlite` file is corrupted. To fix this, you can manually recreate the file.

1.  **Delete the corrupted file:**
    ```bash
    del database.sqlite
    ```

2.  **Create an empty database file:**
    ```bash
    fsutil file createnew database.sqlite 0
    ```

3.  **Run the migrations:**
    ```bash
    php artisan migrate --seed
    ```

## License

Fido is built on the Laravel framework, licensed under the [MIT license](https://opensource.org/licenses/MIT).
