const { getDB, generateNote } = require('./db.cjs');
const { generatePDF } = require('./pdf.cjs');
const companyManager = require('./company-manager.cjs');
const path = require('path');
const fs = require('fs');

// Current company ID — set by main.cjs after login
let currentCompanyId = null;

function setCurrentCompanyId(id) { currentCompanyId = id; }

function registerIpcHandlers(ipcMain, mainWindow) {
  const db = getDB;

  ipcMain.handle('companies:list', () => companyManager.listCompanies());

  ipcMain.handle('companies:create', (_, name, password) => {
    return companyManager.createCompany(name, password);
  });

  ipcMain.handle('companies:login', (_, id, password) => {
    const result = companyManager.validatePassword(id, password);
    if (result.valid) {
      setCurrentCompanyId(id);
    }
    return result;
  });

  ipcMain.handle('companies:current', () => {
    if (!currentCompanyId) return null;
    return companyManager.getCompanyById(currentCompanyId);
  });

  // ================================================================
  // CLIENTS
  // ================================================================
  ipcMain.handle('clients:getAll', () => {
    return db().prepare('SELECT * FROM clients ORDER BY name ASC').all();
  });

  ipcMain.handle('clients:get', (_, id) => {
    return db().prepare('SELECT * FROM clients WHERE id = ?').get(id);
  });

  ipcMain.handle('clients:create', (_, data) => {
    const stmt = db().prepare('INSERT INTO clients (name, address, city, phone, mf) VALUES (?, ?, ?, ?, ?)');
    const result = stmt.run(data.name, data.address || '', data.city || 'Hammamet', data.phone || '', data.mf || '');
    return { id: result.lastInsertRowid };
  });

  ipcMain.handle('clients:update', (_, id, data) => {
    db().prepare('UPDATE clients SET name=?, address=?, city=?, phone=?, mf=?, updated_at=datetime(\'now\',\'localtime\') WHERE id=?')
      .run(data.name, data.address || '', data.city || 'Hammamet', data.phone || '', data.mf || '', id);
    return { success: true };
  });

  ipcMain.handle('clients:delete', (_, id) => {
    db().prepare('DELETE FROM clients WHERE id = ?').run(id);
    return { success: true };
  });

  // ================================================================
  // HONORAIRES
  // ================================================================
  ipcMain.handle('honoraires:getAll', (_, fiscalYear) => {
    const year = fiscalYear || new Date().getFullYear();
    return db().prepare(`SELECT h.*, c.name as client_name, c.mf as client_mf
      FROM honoraires h LEFT JOIN clients c ON h.client_id = c.id
      WHERE strftime('%Y', h.date) = ?
      ORDER BY h.date DESC, h.note DESC`).all(String(year));
  });

  ipcMain.handle('honoraires:get', (_, id) => {
    return db().prepare('SELECT h.*, c.name as client_name, c.mf as client_mf, c.address as client_address FROM honoraires h LEFT JOIN clients c ON h.client_id = c.id WHERE h.id = ?').get(id);
  });

  ipcMain.handle('honoraires:create', (_, data) => {
    const note = generateNote('H', 'honoraires');
    const taxes = db().prepare('SELECT * FROM tax_settings LIMIT 1').get();
    const tva = data.exonere_tva ? 0 : data.montantHT * taxes.tva;
    const montantTTC = data.montantHT + tva;
    const rs = data.exonere_rs ? 0 : montantTTC * taxes.rs;
    const tf = data.exonere_tf ? 0 : taxes.tf;
    const netapayer = montantTTC - rs + tf;

    const stmt = db().prepare(`INSERT INTO honoraires
      (note, object, date, montantHT, tva, montantTTC, rs, tf, netapayer, client_id, exonere_tf, exonere_rs, exonere_tva)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`);
    const result = stmt.run(note, data.object || '', data.date, data.montantHT, tva, montantTTC, rs, tf, netapayer,
      data.client_id, data.exonere_tf ? 1 : 0, data.exonere_rs ? 1 : 0, data.exonere_tva ? 1 : 0);
    return { id: result.lastInsertRowid, note, tva, montantTTC, rs, tf, netapayer };
  });

  ipcMain.handle('honoraires:update', (_, id, data) => {
    const taxes = db().prepare('SELECT * FROM tax_settings LIMIT 1').get();
    const tva = data.exonere_tva ? 0 : data.montantHT * taxes.tva;
    const montantTTC = data.montantHT + tva;
    const rs = data.exonere_rs ? 0 : montantTTC * taxes.rs;
    const tf = data.exonere_tf ? 0 : taxes.tf;
    const netapayer = montantTTC - rs + tf;

    db().prepare(`UPDATE honoraires SET
      object=?, date=?, montantHT=?, tva=?, montantTTC=?, rs=?, tf=?, netapayer=?, client_id=?,
      exonere_tf=?, exonere_rs=?, exonere_tva=?, updated_at=datetime('now','localtime')
      WHERE id=?`)
      .run(data.object || '', data.date, data.montantHT, tva, montantTTC, rs, tf, netapayer,
        data.client_id, data.exonere_tf ? 1 : 0, data.exonere_rs ? 1 : 0, data.exonere_tva ? 1 : 0, id);
    return { success: true, tva, montantTTC, rs, tf, netapayer };
  });

  ipcMain.handle('honoraires:delete', (_, id) => {
    db().prepare('DELETE FROM honoraires WHERE id = ?').run(id);
    return { success: true };
  });

  // ================================================================
  // INVOICES
  // ================================================================
  ipcMain.handle('invoices:getAll', (_, fiscalYear) => {
    const year = fiscalYear || new Date().getFullYear();
    return db().prepare(`SELECT i.*, c.name as client_name_ref
      FROM invoices i LEFT JOIN clients c ON i.client_id = c.id
      WHERE strftime('%Y', i.date) = ?
      ORDER BY i.date DESC, i.invoice_number DESC`).all(String(year));
  });

  ipcMain.handle('invoices:get', (_, id) => {
    return db().prepare('SELECT i.*, c.name as client_name_ref, c.mf as client_mf_ref, c.address as client_address_ref FROM invoices i LEFT JOIN clients c ON i.client_id = c.id WHERE i.id = ?').get(id);
  });

  ipcMain.handle('invoices:getItems', (_, invoiceId) => {
    return db().prepare('SELECT * FROM invoice_items WHERE invoice_id = ? ORDER BY id ASC').all(invoiceId);
  });

  ipcMain.handle('invoices:create', (_, data) => {
    const currentYear = new Date().getFullYear().toString();
    const count = db().prepare("SELECT COUNT(*) as c FROM invoices WHERE invoice_number LIKE ?").get('%' + currentYear);
    const seq = String((count.c || 0) + 1).padStart(4, '0');
    const invoiceNumber = seq + currentYear;

    const stmt = db().prepare(`INSERT INTO invoices
      (client_id, client_name, client_mf, client_address, invoice_number, date,
       total_hors_taxe, tva, montant_ttc, timbre_fiscal, net_a_payer)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`);
    const result = stmt.run(data.client_id, data.client_name || '', data.client_mf || '', data.client_address || '',
      invoiceNumber, data.date, data.total_hors_taxe, data.tva, data.montant_ttc, data.timbre_fiscal, data.net_a_payer);

    const invoiceId = result.lastInsertRowid;

    if (data.items && data.items.length > 0) {
      const itemStmt = db().prepare('INSERT INTO invoice_items (invoice_id, object, quantity, single_price, total_price) VALUES (?, ?, ?, ?, ?)');
      for (const item of data.items) {
        itemStmt.run(invoiceId, item.object, item.quantity, item.single_price, item.total_price);
      }
    }

    return { id: invoiceId, invoice_number: invoiceNumber };
  });

  ipcMain.handle('invoices:update', (_, id, data) => {
    db().prepare(`UPDATE invoices SET
      client_id=?, client_name=?, client_mf=?, client_address=?, date=?,
      total_hors_taxe=?, tva=?, montant_ttc=?, timbre_fiscal=?, net_a_payer=?,
      updated_at=datetime('now','localtime') WHERE id=?`)
      .run(data.client_id, data.client_name || '', data.client_mf || '', data.client_address || '',
        data.date, data.total_hors_taxe, data.tva, data.montant_ttc, data.timbre_fiscal, data.net_a_payer, id);

    db().prepare('DELETE FROM invoice_items WHERE invoice_id = ?').run(id);
    if (data.items && data.items.length > 0) {
      const itemStmt = db().prepare('INSERT INTO invoice_items (invoice_id, object, quantity, single_price, total_price) VALUES (?, ?, ?, ?, ?)');
      for (const item of data.items) {
        itemStmt.run(id, item.object, item.quantity, item.single_price, item.total_price);
      }
    }
    return { success: true };
  });

  ipcMain.handle('invoices:delete', (_, id) => {
    db().prepare('DELETE FROM invoices WHERE id = ?').run(id);
    return { success: true };
  });

  // ================================================================
  // NOTE DE DEBITS
  // ================================================================
  ipcMain.handle('noteDeDebits:getAll', (_, fiscalYear) => {
    const year = fiscalYear || new Date().getFullYear();
    return db().prepare(`SELECT nd.*, c.name as client_name, c.mf as client_mf
      FROM note_de_debits nd LEFT JOIN clients c ON nd.client_id = c.id
      WHERE strftime('%Y', nd.date) = ?
      ORDER BY nd.date DESC, nd.note DESC`).all(String(year));
  });

  ipcMain.handle('noteDeDebits:get', (_, id) => {
    return db().prepare('SELECT nd.*, c.name as client_name, c.mf as client_mf FROM note_de_debits nd LEFT JOIN clients c ON nd.client_id = c.id WHERE nd.id = ?').get(id);
  });

  ipcMain.handle('noteDeDebits:create', (_, data) => {
    const note = generateNote('ND', 'note_de_debits');
    const stmt = db().prepare('INSERT INTO note_de_debits (note, client_id, amount, description, date) VALUES (?, ?, ?, ?, ?)');
    const result = stmt.run(note, data.client_id, data.amount, data.description || '', data.date);
    return { id: result.lastInsertRowid, note };
  });

  ipcMain.handle('noteDeDebits:update', (_, id, data) => {
    db().prepare("UPDATE note_de_debits SET client_id=?, amount=?, description=?, date=?, updated_at=datetime('now','localtime') WHERE id=?")
      .run(data.client_id, data.amount, data.description || '', data.date, id);
    return { success: true };
  });

  ipcMain.handle('noteDeDebits:delete', (_, id) => {
    db().prepare('DELETE FROM note_de_debits WHERE id = ?').run(id);
    return { success: true };
  });

  // ================================================================
  // SETTINGS
  // ================================================================
  ipcMain.handle('settings:getCompany', () => {
    return db().prepare('SELECT * FROM company_settings LIMIT 1').get() || {};
  });

  ipcMain.handle('settings:saveCompany', (_, data) => {
    const existing = db().prepare('SELECT id FROM company_settings LIMIT 1').get();
    if (existing) {
      db().prepare(`UPDATE company_settings SET
        company_name=?, slogan=?, mf_number=?, location=?, address_line1=?, address_line2=?,
        phone1=?, phone2=?, phone3=?, fax=?, email=? WHERE id=?`)
        .run(data.company_name, data.slogan || '', data.mf_number || '', data.location || '',
          data.address_line1 || '', data.address_line2 || '', data.phone1 || '', data.phone2 || '',
          data.phone3 || '', data.fax || '', data.email || '', existing.id);
    } else {
      db().prepare(`INSERT INTO company_settings
        (company_name, slogan, mf_number, location, address_line1, address_line2, phone1, phone2, phone3, fax, email)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`)
        .run(data.company_name, data.slogan || '', data.mf_number || '', data.location || '',
          data.address_line1 || '', data.address_line2 || '', data.phone1 || '', data.phone2 || '',
          data.phone3 || '', data.fax || '', data.email || '');
    }
    return { success: true };
  });

  ipcMain.handle('settings:getTaxes', () => {
    return db().prepare('SELECT * FROM tax_settings LIMIT 1').get() || { tva: 0.19, rs: 0.03, tf: 1.00 };
  });

  ipcMain.handle('settings:saveTaxes', (_, data) => {
    const existing = db().prepare('SELECT id FROM tax_settings LIMIT 1').get();
    if (existing) {
      db().prepare('UPDATE tax_settings SET tva=?, rs=?, tf=? WHERE id=?').run(data.tva, data.rs, data.tf, existing.id);
    } else {
      db().prepare('INSERT INTO tax_settings (tva, rs, tf) VALUES (?, ?, ?)').run(data.tva, data.rs, data.tf);
    }
    return { success: true };
  });

  ipcMain.handle('settings:getFiscalYear', () => {
    const row = db().prepare('SELECT * FROM fiscal_year_settings LIMIT 1').get();
    return row || { year: new Date().getFullYear() };
  });

  ipcMain.handle('settings:saveFiscalYear', (_, year) => {
    const existing = db().prepare('SELECT id FROM fiscal_year_settings LIMIT 1').get();
    if (existing) {
      db().prepare('UPDATE fiscal_year_settings SET year=? WHERE id=?').run(year, existing.id);
    } else {
      db().prepare('INSERT INTO fiscal_year_settings (year) VALUES (?)').run(year);
    }
    return { success: true };
  });

  // ================================================================
  // DASHBOARD
  // ================================================================
  ipcMain.handle('dashboard:stats', (_, fiscalYear) => {
    const year = String(fiscalYear || new Date().getFullYear());

    const totalClients = db().prepare('SELECT COUNT(*) as c FROM clients').get().c;

    const honorairesCount = db().prepare("SELECT COUNT(*) as c FROM honoraires WHERE strftime('%Y', date) = ?").get(year).c;
    const noteDeDebitsCount = db().prepare("SELECT COUNT(*) as c FROM note_de_debits WHERE strftime('%Y', date) = ?").get(year).c;

    const honorairesTotals = db().prepare(`SELECT
      COALESCE(SUM(montantHT),0) as totalHT, COALESCE(SUM(tva),0) as totalTVA,
      COALESCE(SUM(rs),0) as totalRS, COALESCE(SUM(montantTTC),0) as totalTTC,
      COALESCE(SUM(tf),0) as totalTF, COALESCE(SUM(netapayer),0) as totalNet
      FROM honoraires WHERE strftime('%Y', date) = ?`).get(year);

    return { totalClients, honorairesCount, noteDeDebitsCount, ...honorairesTotals };
  });

  ipcMain.handle('dashboard:monthlyChart', (_, entity, fiscalYear) => {
    const year = String(fiscalYear || new Date().getFullYear());
    let table;
    switch (entity) {
      case 'honoraires': table = 'honoraires'; break;
      case 'notes': table = 'note_de_debits'; break;
      case 'invoices': table = 'invoices'; break;
      default: return [];
    }
    return db().prepare(`SELECT
      strftime('%m', date) as month, COUNT(*) as count
      FROM ${table} WHERE strftime('%Y', date) = ?
      GROUP BY month ORDER BY month`).all(year);
  });

  // ================================================================
  // REPORTS
  // ================================================================
  ipcMain.handle('reports:honoraire', (_, startDate, endDate) => {
    const rows = db().prepare(`SELECT h.*, c.name as client_name
      FROM honoraires h LEFT JOIN clients c ON h.client_id = c.id
      WHERE h.date >= ? AND h.date <= ? ORDER BY h.date ASC, h.note ASC`).all(startDate, endDate);
    const totals = db().prepare(`SELECT
      COALESCE(SUM(montantHT),0) as totalHT, COALESCE(SUM(tva),0) as totalTVA,
      COALESCE(SUM(rs),0) as totalRS, COALESCE(SUM(montantTTC),0) as totalTTC,
      COALESCE(SUM(tf),0) as totalTF, COALESCE(SUM(netapayer),0) as totalNet
      FROM honoraires WHERE date >= ? AND date <= ?`).get(startDate, endDate);
    return { rows, totals };
  });

  ipcMain.handle('reports:retenueSource', (_, startDate, endDate, rsRate) => {
    const rows = db().prepare(`SELECT h.*, c.name as client_name, c.mf as client_mf
      FROM honoraires h LEFT JOIN clients c ON h.client_id = c.id
      WHERE h.rs > 0 AND h.date >= ? AND h.date <= ? ORDER BY c.name ASC`).all(startDate, endDate);
    const totals = db().prepare(`SELECT
      COALESCE(SUM(montantTTC),0) as totalTTC, COALESCE(SUM(rs),0) as totalRS
      FROM honoraires WHERE rs > 0 AND date >= ? AND date <= ?`).get(startDate, endDate);
    return { rows, totals, rsRate };
  });

  ipcMain.handle('reports:noteDeDebit', (_, startDate, endDate) => {
    const rows = db().prepare(`SELECT nd.*, c.name as client_name, c.mf as client_mf
      FROM note_de_debits nd LEFT JOIN clients c ON nd.client_id = c.id
      WHERE nd.date >= ? AND nd.date <= ? ORDER BY nd.date ASC`).all(startDate, endDate);
    const totals = db().prepare(`SELECT COALESCE(SUM(amount),0) as totalAmount
      FROM note_de_debits WHERE date >= ? AND date <= ?`).get(startDate, endDate);
    return { rows, totals };
  });

  // ================================================================
  // PDF
  // ================================================================
  ipcMain.handle('pdf:save', async (_, htmlContent, suggestedName) => {
    const { dialog } = require('electron');
    const result = await dialog.showSaveDialog(mainWindow, {
      title: 'Enregistrer le PDF',
      defaultPath: suggestedName || 'document.pdf',
      filters: [{ name: 'PDF', extensions: ['pdf'] }]
    });
    if (result.canceled) return { canceled: true };
    await generatePDF(htmlContent, result.filePath);
    return { success: true, filePath: result.filePath };
  });

  ipcMain.handle('pdf:print', async (_, htmlContent) => {
    await generatePDF(htmlContent, null, mainWindow);
    return { success: true };
  });

  // ================================================================
  // BACKUP
  // ================================================================
  const getBackupDir = () => {
    const dir = path.join(require('electron').app.getPath('userData'), 'backups');
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
    return dir;
  };

  ipcMain.handle('backup:create', () => {
    const backupDir = getBackupDir();
    const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
    const backupPath = path.join(backupDir, `backup-${timestamp}.sqlite`);
    const dbPath = db().name;
    fs.copyFileSync(dbPath, backupPath);
    return { success: true, path: backupPath };
  });

  ipcMain.handle('backup:list', () => {
    const backupDir = getBackupDir();
    try {
      return fs.readdirSync(backupDir)
        .filter(f => f.endsWith('.sqlite'))
        .map(f => {
          const fullPath = path.join(backupDir, f);
          const stat = fs.statSync(fullPath);
          return { name: f, path: fullPath, size: stat.size, date: stat.mtime.toISOString() };
        })
        .sort((a, b) => new Date(b.date) - new Date(a.date));
    } catch { return []; }
  });

  ipcMain.handle('backup:restore', (_, filePath) => {
    const dbPath = db().name;
    db().close();
    fs.copyFileSync(filePath, dbPath);
    const Database = require('better-sqlite3');
    const newDb = new Database(dbPath);
    newDb.pragma('journal_mode = WAL');
    newDb.pragma('foreign_keys = ON');
    require('./db.cjs').setDB(newDb);
    return { success: true };
  });

  ipcMain.handle('backup:delete', (_, filePath) => {
    if (fs.existsSync(filePath)) fs.unlinkSync(filePath);
    return { success: true };
  });

  ipcMain.handle('backup:openFolder', () => {
    const { shell } = require('electron');
    shell.openPath(getBackupDir());
  });

  // ================================================================
  // APP INFO
  // ================================================================
  ipcMain.handle('app:info', () => {
    const pkg = require('../package.json');
    const dbPath = db().name;
    const dbSize = fs.existsSync(dbPath) ? fs.statSync(dbPath).size : 0;
    return {
      version: pkg.version,
      name: pkg.productName || pkg.name,
      dbPath,
      dbSize,
      backupDir: getBackupDir()
    };
  });
}

module.exports = { registerIpcHandlers, setCurrentCompanyId }; // setCurrentCompanyId used by main.cjs
