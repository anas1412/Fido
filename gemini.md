# Gemini Project Analysis: Fido

This document provides a summary of the technologies used, project structure, and the overall purpose of the "Fido" application, as analyzed by the Gemini CLI.

## Project Overview

The project, named "Fido," is a web application built using the Laravel framework. Its primary function appears to be managing clients, invoices (honoraires), credit notes (note de debit), and other financial data. The application includes a comprehensive administration panel built with Filament, which allows for easy management of the application's resources.

## Core Technologies

### Backend

| Technology | Version | Description |
|---|---|---|
| PHP | ^8.2 | The core programming language. |
| Laravel | ^11.0 | The underlying web application framework. |
| Livewire | ^3.4 | A full-stack framework for building dynamic interfaces. |
| Volt | ^1.0 | A functional, class-based API for Livewire. |
| Filament | ^4.0 | A framework for building admin panels. |
| Barryvdh Laravel Dompdf | ^2.2 | A library for generating PDF documents. |
| Pest | ^2.0 | A testing framework for PHP. |

### Desktop

| Technology | Version | Description |
|---|---|---|
| Electron | ^37.2.6 | A framework for creating native applications with web technologies. |
| Electron Builder | ^26.0.12 | A solution to package and build a ready for distribution Electron app. |

### Frontend

| Technology | Version | Description |
|---|---|---|
| Vite | ^5.0 | A modern frontend build tool. |
| Tailwind CSS | ^3.1.0 | A utility-first CSS framework. |
| PostCSS | ^8.4.31 | A tool for transforming CSS with JavaScript. |
| Autoprefixer | ^10.4.2 | A PostCSS plugin to parse CSS and add vendor prefixes. |
| Axios | ^1.6.4 | A promise-based HTTP client for the browser and Node.js. |

## Project Structure

The project follows a standard Laravel application structure:

-   **`app/`**: Contains the core application code, including:
    -   **`Filament/`**: Resources, pages, and widgets for the admin panel.
    -   **`Http/Controllers/`**: Handles HTTP requests.
    -   **`Models/`**: Defines the application's data models (`Client`, `Honoraire`, etc.).
    -   **`Providers/`**: Service providers for bootstrapping the application.
-   **`config/`**: Stores all of the application's configuration files.
-   **`database/`**: Contains database migrations, seeders, and factories.
-   **`public/`**: The web server's document root. Contains the `index.php` file and compiled assets.
-   **`resources/`**: Contains frontend assets (CSS, JavaScript) and Blade templates.
-   **`routes/`**: Defines all of the application's routes.
-   **`storage/`**: Contains cached framework files, session files, and logs.
-   **`tests/`**: Contains the application's automated tests.

## Development To-Do List

Here is a prioritized list of recommended improvements and fixes to enhance the Fido application.

### High Priority

| Task | Type | Description |
|---|---|---|
| **Sequential Document Numbering** | `Fix` | Implement a system to ensure all fee notes and debit notes have unique, sequential, and non-editable numbers (e.g., `FACT-2024-001`). This is a critical legal and accounting requirement. |
| **Database-driven Tax & Fiscal Year** | `Fix` | Move tax rates and fiscal year settings from hardcoded `config` files to the database. Create a settings page in Filament to allow the accountant to manage these values directly. |
| **Use Database Transactions** | `Fix` | Wrap all financial document creation logic (invoices, notes, etc.) in `DB::transaction()` blocks to ensure data integrity and prevent partial, corrupt records from being saved if an error occurs. |
| **Database Backup & Restore** | `New Feature` | Implement a comprehensive database backup and restore functionality. |

### Medium Priority

| Task | Type | Description |
|---|---|---|
| **True Financial Dashboard** | `New Feature` | Enhance the main dashboard to show key financial KPIs: total invoiced (YTD/QTD), total outstanding fees, total withholding tax, and charts for revenue per client and monthly revenue trends. |
| **Document Statuses & Client Portal** | `New Feature` | Add statuses (`Draft`, `Sent`, `Paid`, `Overdue`) to fee notes. This improves workflow and can be the foundation for a simple client portal where they can view and download their documents. |
| **Robust PDF Generation** | `Fix` | For large reports (like annual statements), use a queued job to generate PDFs in the background. This prevents UI freezes and server timeouts, improving user experience. |

### Low Priority

| Task | Type | Description |
|---|---|---|
| **Expense Tracking** | `New Feature` | Add a new section for managing business expenses (rent, supplies, etc.). This provides a complete picture of profitability and is essential for tax declarations. |

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
    php artisan migrate
    ```