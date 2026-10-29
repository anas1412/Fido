const crypto = require('crypto');
const path = require('path');
const fs = require('fs');

// ---------------------------------------------------------------------------
// Registry helpers — load/save the companies.json file
// ---------------------------------------------------------------------------

function getRegistryPath() {
  const { app } = require('electron');
  return path.join(app.getPath('userData'), 'fido-companies.json');
}

function loadRegistry() {
  const fp = getRegistryPath();
  try {
    if (!fs.existsSync(fp)) return [];
    const raw = fs.readFileSync(fp, 'utf8');
    return JSON.parse(raw);
  } catch (e) {
    if (e.code === 'ENOENT') return [];
    throw new Error('Erreur de lecture du registre des sociétés');
  }
}

function saveRegistry(companies) {
  const fp = getRegistryPath();
  const tmp = fp + '.tmp';
  fs.writeFileSync(tmp, JSON.stringify(companies, null, 2), 'utf8');
  fs.renameSync(tmp, fp);
}

function sanitize(c) {
  const { passwordHash, salt, ...rest } = c;
  return rest;
}

function generateId() {
  return crypto.randomUUID
    ? crypto.randomUUID()
    : 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
        const r = (Math.random() * 16) | 0;
        return (c === 'x' ? r : (r & 0x3) | 0x8).toString(16);
      });
}

// ---------------------------------------------------------------------------
// Exported API
// ---------------------------------------------------------------------------

function listCompanies() {
  return loadRegistry().map(sanitize);
}

function createCompany(name, password) {
  if (!name || name.trim().length < 2) {
    throw new Error('Le nom de la société doit contenir au moins 2 caractères');
  }
  const trimmedName = name.trim();
  const companies = loadRegistry();

  // Check duplicate name (case-insensitive)
  if (companies.some(c => c.name.toLowerCase() === trimmedName.toLowerCase())) {
    throw new Error('Une société avec ce nom existe déjà');
  }

  const id = generateId();
  const salt = crypto.randomBytes(16).toString('hex');
  let passwordHash = '';
  if (password && password.length > 0) {
    if (password.length < 4) {
      throw new Error('Le mot de passe doit contenir au moins 4 caractères');
    }
    passwordHash = crypto.pbkdf2Sync(password, salt, 100000, 64, 'sha512').toString('hex');
  }

  const entry = {
    id,
    name: trimmedName,
    dbFile: `fido-company-${id}.db`,
    passwordHash,
    salt,
    created_at: new Date().toISOString()
  };

  companies.push(entry);
  saveRegistry(companies);

  return sanitize(entry);
}

function deleteCompany(id) {
  const companies = loadRegistry();
  const idx = companies.findIndex(c => c.id === id);
  if (idx === -1) throw new Error('Société introuvable');
  const [removed] = companies.splice(idx, 1);
  saveRegistry(companies);
  // Optionally remove the DB file (skip if missing)
  try {
    const { app } = require('electron');
    const dbPath = path.join(app.getPath('userData'), removed.dbFile);
    if (fs.existsSync(dbPath)) fs.unlinkSync(dbPath);
  } catch { /* ignore */ }
  return { success: true };
}

function validatePassword(id, password) {
  const companies = loadRegistry();
  const entry = companies.find(c => c.id === id);
  if (!entry) return { valid: false, error: 'Société introuvable' };

  // No password set → any input works (or blank)
  if (!entry.passwordHash) return { valid: true, company: sanitize(entry) };

  const hash = crypto.pbkdf2Sync(password || '', entry.salt, 100000, 64, 'sha512').toString('hex');
  if (hash !== entry.passwordHash) {
    return { valid: false, error: 'Mot de passe incorrect' };
  }

  return { valid: true, company: sanitize(entry) };
}

function getCompanyById(id) {
  const companies = loadRegistry();
  const entry = companies.find(c => c.id === id);
  return entry ? sanitize(entry) : null;
}

function getCompanyDbPath(id) {
  const { app } = require('electron');
  const companies = loadRegistry();
  const entry = companies.find(c => c.id === id);
  if (!entry) throw new Error('Société introuvable');
  return path.join(app.getPath('userData'), entry.dbFile);
}

function getDbPathForCompany(companyId) {
  const { app } = require('electron');
  const companies = loadRegistry();
  const entry = companies.find(c => c.id === companyId);
  if (!entry) throw new Error('Société introuvable');
  return path.join(app.getPath('userData'), entry.dbFile);
}

module.exports = {
  listCompanies,
  createCompany,
  deleteCompany,
  validatePassword,
  getCompanyById,
  getCompanyDbPath,
  getDbPathForCompany,
  loadRegistry,
  saveRegistry
};
