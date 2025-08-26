<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class IsSqliteFile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // First, check if the value is actually an uploaded file.
        if (! $value instanceof UploadedFile) {
            // This case should ideally not be hit if 'file' rule is also used, but it's safe to have.
            $fail('The :attribute must be a file.'); // Generic failure
            return;
        }

        // Now, perform the specific check on the file's extension.
        if ($value->getClientOriginalExtension() !== 'sqlite') {
            $fail(__('The backup file must be a .sqlite file.'));
        }
    }
}