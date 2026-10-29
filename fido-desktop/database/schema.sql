-- ============================================================
-- FIDO - Database Schema
-- ============================================================

CREATE TABLE IF NOT EXISTS company_settings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  company_name TEXT NOT NULL DEFAULT '',
  slogan TEXT DEFAULT '',
  mf_number TEXT DEFAULT '',
  location TEXT DEFAULT '',
  address_line1 TEXT DEFAULT '',
  address_line2 TEXT DEFAULT '',
  phone1 TEXT DEFAULT '',
  phone2 TEXT DEFAULT '',
  phone3 TEXT DEFAULT '',
  fax TEXT DEFAULT '',
  email TEXT DEFAULT ''
);

CREATE TABLE IF NOT EXISTS tax_settings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  tva REAL NOT NULL DEFAULT 0.19,
  rs REAL NOT NULL DEFAULT 0.03,
  tf REAL NOT NULL DEFAULT 1.00
);

CREATE TABLE IF NOT EXISTS fiscal_year_settings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  year INTEGER NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS clients (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  address TEXT DEFAULT '',
  city TEXT DEFAULT 'Hammamet',
  phone TEXT DEFAULT '',
  mf TEXT DEFAULT '',
  created_at TEXT DEFAULT (datetime('now','localtime')),
  updated_at TEXT DEFAULT (datetime('now','localtime'))
);

CREATE TABLE IF NOT EXISTS honoraires (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  note TEXT NOT NULL,
  object TEXT DEFAULT '',
  date TEXT DEFAULT (date('now')),
  montantHT REAL DEFAULT 0,
  tva REAL DEFAULT 0,
  montantTTC REAL DEFAULT 0,
  rs REAL DEFAULT 0,
  tf REAL DEFAULT 0,
  netapayer REAL DEFAULT 0,
  client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
  exonere_tf INTEGER DEFAULT 0,
  exonere_rs INTEGER DEFAULT 0,
  exonere_tva INTEGER DEFAULT 0,
  created_at TEXT DEFAULT (datetime('now','localtime')),
  updated_at TEXT DEFAULT (datetime('now','localtime'))
);

CREATE TABLE IF NOT EXISTS note_de_debits (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  note TEXT NOT NULL,
  client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
  amount REAL DEFAULT 0,
  description TEXT DEFAULT '',
  date TEXT DEFAULT (date('now')),
  created_at TEXT DEFAULT (datetime('now','localtime')),
  updated_at TEXT DEFAULT (datetime('now','localtime'))
);

CREATE TABLE IF NOT EXISTS invoices (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
  client_name TEXT DEFAULT '',
  client_mf TEXT DEFAULT '',
  client_address TEXT DEFAULT '',
  invoice_number TEXT NOT NULL UNIQUE,
  date TEXT DEFAULT (date('now')),
  total_hors_taxe REAL DEFAULT 0,
  tva REAL DEFAULT 0,
  montant_ttc REAL DEFAULT 0,
  timbre_fiscal REAL DEFAULT 0,
  net_a_payer REAL DEFAULT 0,
  created_at TEXT DEFAULT (datetime('now','localtime')),
  updated_at TEXT DEFAULT (datetime('now','localtime'))
);

CREATE TABLE IF NOT EXISTS invoice_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  invoice_id INTEGER REFERENCES invoices(id) ON DELETE CASCADE,
  object TEXT DEFAULT '',
  quantity INTEGER DEFAULT 1,
  single_price REAL DEFAULT 0,
  total_price REAL DEFAULT 0
);
