
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
| Filament | ^3.2 | A framework for building admin panels. |
| Barryvdh Laravel Dompdf | ^2.2 | A library for generating PDF documents. |
| Pest | ^2.0 | A testing framework for PHP. |

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
