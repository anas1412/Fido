# Plan for Client Invoice Management Feature

This document outlines the plan to add a client invoice management feature to the Fido application.

## 1. Feature Overview

The goal is to create a new section for managing invoices. This will allow the primary user (an accountant) to create, view, edit, delete, and download invoices on behalf of their clients.

## 2. Database Schema Changes

Two new tables will be created in the database:

### `invoices` table

This table will store the main invoice information.

| Column | Type | Description |
|---:|:---|:---|
| `id` | `bigint`, unsigned, auto-increment | Primary key |
| `client_id` | `bigint`, unsigned | Foreign key to the `clients` table |
| `client_name` | `varchar` | The name of the client's client |
| `client_mf` | `varchar` | The tax ID of the client's client |
| `invoice_number` | `varchar` | Unique, sequential invoice number (e.g., `INV-2025-001`) |
| `date` | `date` | The date of the invoice |
| `total_hors_taxe` | `decimal` | Total amount before tax |
| `tva` | `decimal` | VAT amount |
| `montant_ttc` | `decimal` | Total amount after tax (`total_hors_taxe` + `tva`) |
| `timbre_fiscal` | `decimal` | Fiscal stamp amount |
| `net_a_payer` | `decimal` | Net amount to be paid (`montant_ttc` + `timbre_fiscal`) |
| `status` | `varchar` | The status of the invoice (e.g., `draft`, `sent`, `paid`, `overdue`) |
| `created_at` | `timestamp` | Timestamp of creation |
| `updated_at` | `timestamp` | Timestamp of last update |

### `invoice_items` table

This table will store the individual line items for each invoice.

| Column | Type | Description |
|---:|:---|:---|
| `id` | `bigint`, unsigned, auto-increment | Primary key |
| `invoice_id` | `bigint`, unsigned | Foreign key to the `invoices` table |
| `object` | `varchar` | Description of the line item |
| `quantity` | `int` | The quantity of the item |
| `single_price` | `decimal` | The price per unit |
| `total_price` | `decimal` | The total for the line item (`quantity` * `single_price`) |
| `created_at` | `timestamp` | Timestamp of creation |
| `updated_at` | `timestamp` | Timestamp of last update |

## 3. Model Creation

Two new Eloquent models will be created:

- **`Invoice.php`**: This model will correspond to the `invoices` table.
- **`InvoiceItem.php`**: This model will correspond to the `invoice_items` table.

### Relationships

- The `Invoice` model will have a `hasMany` relationship with the `InvoiceItem` model.
- The `Invoice` model will have a `belongsTo` relationship with the `Client` model.
- The `Client` model will have a `hasMany` relationship with the `Invoice` model.

## 4. Filament Resource

A new Filament resource named `InvoiceResource` will be created.

### Form

The form for creating and editing invoices will include:

- A `Select` field to choose the client.
- A `TextInput` for the invoice number (which will be auto-generated and disabled).
- `DatePicker` fields for the issue date and due date.
- A `Repeater` field for adding/editing invoice items. The repeater will contain fields for the description, quantity, and unit price.
- A `Select` field for the invoice status.

### Table

The table for listing invoices will have the following columns:

- Invoice Number
- Client Name
- Date
- Net to Pay
- Status

### Actions

The following actions will be available:

- **View:** View the details of an invoice.
- **Edit:** Edit an existing invoice.
- **Delete:** Delete an invoice.
- **Download PDF:** Download the invoice as a PDF file.

## 5. PDF Generation

A new Blade view will be created to format the invoice for PDF output. A new route will be created to handle the PDF download, which will use the `barryvdh/laravel-dompdf` package to generate the PDF.

## 6. Sequential Invoice Numbering

A system will be implemented to generate sequential and unique invoice numbers. This will be similar to the existing system for "notes d'honoraire" and will ensure that each invoice has a unique, non-editable number (e.g., `INV-2025-001`).

## 7. Dashboard Integration (Optional)

If time permits, a new widget can be added to the Filament dashboard to display key metrics related to invoices, such as:

- Total amount invoiced

## 8. Client Resource Integration

To display a client's invoices on the "Détails du client" page, a new relation manager will be created and integrated with the `ClientResource`.

### `InvoicesRelationManager`

- A new `InvoicesRelationManager` class will be created.
- This relation manager will display a table of the client's invoices.
- The table will have columns for the invoice number, date, total amount, and status.
- It will have actions to create, view, edit, and delete invoices directly from the client's detail page.

### `ClientResource` Modifications

The `getRelations()` method in `app/Filament/Resources/ClientResource.php` will be updated to include the new `InvoicesRelationManager`.

```php
public static function getRelations(): array
{
    return [
        HonorairesRelationManager::class,
        NoteDeDebitsRelationManager::class,
        InvoicesRelationManager::class, // Add this line
    ];
}
```

## 9. Implementation Status

**Completed:**

*   **Database Schema:** The `invoices` and `invoice_items` tables have been created.
*   **Models:** The `Invoice` and `InvoiceItem` models have been created with the correct relationships.
*   **Filament Resource:** The `InvoiceResource` has been created with:
    *   A wizard-based form structure for creating and editing invoices.
    *   Automatic generation of unique, sequential invoice numbers.
    *   Real-time calculation of `total_price` for each invoice item.
    *   Automatic calculation of `total_hors_taxe`, `tva`, `montant_ttc`, `timbre_fiscal`, and `net_a_payer`.
    *   Improved UI/UX for repeater fields using a grid layout.
*   **Client Resource Integration:** The `InvoicesRelationManager` has been created and added to the `ClientResource`, and its form has been converted to a wizard with similar automatic calculation and UI/UX improvements.
*   **Sequential Invoice Numbering:** The system for generating unique, sequential invoice numbers has been implemented in the forms.

**Pending:**

*   **PDF Generation:** The PDF download functionality has not been implemented.
*   **Dashboard Integration:** The dashboard widget for displaying invoice metrics has not been implemented.

## 10. Localization/Translation

All new user-facing strings introduced by the invoice management feature (e.g., field labels, button texts, messages) have been implemented using Laravel's localization features, ensuring they are translatable into supported languages (Arabic and French).