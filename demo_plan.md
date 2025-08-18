# Plan for Fido Application Demo Version

This document outlines the strategy for creating a secure, read-only demo mode for the Fido application. The goal is to allow users to explore the full functionality of the application using pre-populated sample data, without being able to modify or delete any information. This will be triggered by logging in with a specific "demo" user account.

---

### 1. Data Seeding Strategy: Populating the Demo

A demo is only useful if it has data to display. We will create a comprehensive set of sample data that showcases the application's features.

**Actions:**

1.  **Create Model Factories:**
    *   Generate `ClientFactory.php` to create fake client records.
    *   Generate `HonoraireFactory.php` to create sample invoices with varied details (different amounts, statuses, dates).
    *   Generate `NoteDeDebitFactory.php` to create sample debit notes.
    *   These factories will use Laravel's built-in Faker library to produce realistic-looking data (names, addresses, amounts, etc.).

2.  **Create a Dedicated Demo User:**
    *   A new seeder will be created, `DemoUserSeeder.php`, to insert a specific user into the `users` table (e.g., `email: demo@fido.com`, `password: password`).
    *   A new migration will be created to add an `is_demo` boolean column to the `users` table. This flag will be set to `true` for the demo user and will be the primary way the application identifies when to activate read-only mode.

3.  **Update the Main Database Seeder:**
    *   The primary `DatabaseSeeder.php` will be updated to call the new factories and the `DemoUserSeeder`. This will ensure that running `php artisan db:seed` populates the entire database with the required sample data for the demo.

---

### 2. Read-Only Mode Implementation

The core of the demo version is preventing data modification. We will use Filament's built-in authorization capabilities to achieve this cleanly.

**Actions:**

1.  **Modify Filament Resources:**
    *   For each Filament resource (`ClientResource`, `HonoraireResource`, `NoteDeDebitResource`, etc.), I will override the default permissions.
    *   The `canCreate()`, `canEdit()`, and `canDelete()` methods within each resource will be modified to check if the authenticated user has the `is_demo` flag. If they do, the method will return `false`, effectively disabling all "Create", "Edit", and "Delete" buttons and actions for that user.

    *Example for `ClientResource.php`*:
    ```php
    // ... inside the ClientResource class

    public static function canCreate(): bool
    {
        return !auth()->user()?->is_demo;
    }

    public function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return !auth()->user()?->is_demo;
    }

    public function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return !auth()->user()?->is_demo;
    }
    ```

2.  **Disable Bulk Actions:**
    *   The same logic will be applied to disable any bulk actions, such as bulk deletion, for the demo user.

---

### 3. User Experience (UX) Enhancements

It must be obvious to the user that they are in a demo environment.

**Actions:**

1.  **Add a "Demo Mode" Banner:**
    *   I will create a new Blade view component for a banner.
    *   This banner will be injected into the main application layout and will be conditionally displayed only when the logged-in user is the demo user.
    *   The banner will contain a clear message, such as **"You are in Demo Mode. Changes will not be saved."**

---

### 4. Demo Reset Mechanism

For a local Electron application, the reset mechanism can be straightforward.

**Action:**

1.  **Leverage Existing Artisan Commands:**
    *   The demo can be reset to its initial state by running `php artisan migrate:fresh --seed` from the command line within the project. This will wipe the SQLite database and re-populate it with the fresh demo data. A simple `.bat` script can be created to simplify this process for the developer.
