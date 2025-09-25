<?php

return [
    'honoraires' => env('FEATURE_HONORAIRES', true),
    'note_de_debit' => env('FEATURE_NOTE_DE_DEBIT', true),
    'invoices' => env('FEATURE_INVOICES', true),
    'honoraire_reports' => env('FEATURE_HONORAIRE_REPORTS', true),
    'retenue_a_la_source_report' => env('FEATURE_RETENUE_A_LA_SOURCE_REPORT', true),
    'note_de_debit_report' => env('FEATURE_NOTE_DE_DEBIT_REPORT', true),
];
