const { app, BrowserWindow, ipcMain, dialog } = require('electron');
const path = require('path');
const fs = require('fs');
const { initDB } = require('./src/db.cjs');
const companyManager = require('./src/company-manager.cjs');
const { registerIpcHandlers, setCurrentCompanyId } = require('./src/ipc-handlers.cjs');

let mainWindow;

function createCompanyWindow() {
  const win = new BrowserWindow({
    width: 580, height: 640, resizable: false,
    backgroundColor: '#09090b', autoHideMenuBar: true,
    webPreferences: {
      nodeIntegration: false, contextIsolation: true,
      preload: path.join(__dirname, 'preload.js')
    }
  });
  win.loadFile(path.join(__dirname, 'renderer', 'company.html'));
  win.on('closed', () => { if (!mainWindow) app.quit(); });
  return win;
}

function createMainWindow() {
  mainWindow = new BrowserWindow({
    width: 1400, height: 1000, minWidth: 1100, minHeight: 700,
    backgroundColor: '#09090b', autoHideMenuBar: true,
    icon: path.join(__dirname, 'assets', 'icons', 'icon.ico'),
    webPreferences: {
      nodeIntegration: false, contextIsolation: true,
      preload: path.join(__dirname, 'preload.js')
    }
  });
  mainWindow.loadFile(path.join(__dirname, 'renderer', 'index.html'));
  mainWindow.on('closed', () => { mainWindow = null; });
}

// ---------------------------------------------------------------------------
// Migration: auto-import existing single-company DB
// ---------------------------------------------------------------------------
function runMigration(userDataPath) {
  const oldDbPath = path.join(userDataPath, 'fido.db');
  const registryPath = path.join(userDataPath, 'fido-companies.json');
  if (fs.existsSync(oldDbPath) && !fs.existsSync(registryPath)) {
    // Use loadRegistry/saveRegistry directly to avoid createCompany validation quirks
    const companies = companyManager.loadRegistry(); // will be empty
    if (companies.length === 0) {
      try {
        const newCompany = companyManager.createCompany('Ma Société', '');
        const newDbPath = path.join(userDataPath, newCompany.dbFile);
        fs.copyFileSync(oldDbPath, newDbPath);
        console.log('Migration: imported existing fido.db as "Ma Société"');
      } catch (e) {
        console.error('Migration failed:', e.message);
      }
    }
  }
}

// ---------------------------------------------------------------------------
// App lifecycle
// ---------------------------------------------------------------------------
app.whenReady().then(async () => {
  const userDataPath = app.getPath('userData');

  // 1. Run migration before anything else
  runMigration(userDataPath);

  // 2. Register ALL IPC handlers.
  //    Company handlers (list, create, login) don't need a DB.
  //    App handlers (clients, honoraires, etc.) won't be called until main app loads.
  registerIpcHandlers(ipcMain, null);

  // 3. Show company selector (this calls only company IPC handlers)
  createCompanyWindow();

  // 4. IPC handler called by company.html after successful login
  ipcMain.handle('main:start', (_, companyId) => {
    setCurrentCompanyId(companyId);
    const dbPath = companyManager.getCompanyDbPath(companyId);
    initDB(dbPath);

    // Close all company windows
    BrowserWindow.getAllWindows().forEach(w => {
      if (w !== mainWindow) w.close();
    });

    createMainWindow();
    return { success: true };
  });
});

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') app.quit();
});

app.on('activate', () => {
  if (BrowserWindow.getAllWindows().length === 0) {
    createCompanyWindow();
  }
});
