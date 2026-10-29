const Database = require('better-sqlite3');
const path = require('path');
const fs = require('fs');

let db;

function initDB(dbPath) {
  db = new Database(dbPath);
  db.pragma('journal_mode = WAL');
  db.pragma('foreign_keys = ON');

  const schema = fs.readFileSync(path.join(__dirname, '..', 'database', 'schema.sql'), 'utf8');
  db.exec(schema);

  seedDefaults();
  return db;
}

function seedDefaults() {
  const companyCount = db.prepare('SELECT COUNT(*) as count FROM company_settings').get();
  if (companyCount.count === 0) {
    db.prepare('INSERT INTO company_settings (company_name) VALUES (?)').run('Ma Société');
  }

  const taxCount = db.prepare('SELECT COUNT(*) as count FROM tax_settings').get();
  if (taxCount.count === 0) {
    db.prepare('INSERT INTO tax_settings (tva, rs, tf) VALUES (?, ?, ?)').run(0.19, 0.03, 1.00);
  }

  const fiscalCount = db.prepare('SELECT COUNT(*) as count FROM fiscal_year_settings').get();
  if (fiscalCount.count === 0) {
    const year = new Date().getFullYear();
    db.prepare('INSERT INTO fiscal_year_settings (year) VALUES (?)').run(year);
  }
}

function getDB() { return db; }

function generateNote(prefix, table) {
  const currentYear = new Date().getFullYear().toString();
  let note;
  let exists;
  do {
    const count = db.prepare(`SELECT COUNT(*) as c FROM ${table} WHERE note LIKE ?`).get(`${currentYear}%`);
    const seq = String((count.c || 0) + 1).padStart(4, '0');
    note = seq + currentYear;
    exists = db.prepare(`SELECT COUNT(*) as c FROM ${table} WHERE note = ?`).get(note).c > 0;
  } while (exists);
  return note;
}

function setDB(newDb) { db = newDb; }

module.exports = { initDB, getDB, generateNote, setDB };
