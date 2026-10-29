const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('api', {
  // Companies
  getCompanies: () => ipcRenderer.invoke('companies:list'),
  createCompany: (name, password) => ipcRenderer.invoke('companies:create', name, password),
  loginCompany: (id, password) => ipcRenderer.invoke('companies:login', id, password),
  getCurrentCompany: () => ipcRenderer.invoke('companies:current'),
  startMainApp: (companyId) => ipcRenderer.invoke('main:start', companyId),

  // Clients
  getClients: () => ipcRenderer.invoke('clients:getAll'),
  getClient: (id) => ipcRenderer.invoke('clients:get', id),
  createClient: (data) => ipcRenderer.invoke('clients:create', data),
  updateClient: (id, data) => ipcRenderer.invoke('clients:update', id, data),
  deleteClient: (id) => ipcRenderer.invoke('clients:delete', id),

  // Honoraires
  getHonoraires: (fiscalYear) => ipcRenderer.invoke('honoraires:getAll', fiscalYear),
  getHonoraire: (id) => ipcRenderer.invoke('honoraires:get', id),
  createHonoraire: (data) => ipcRenderer.invoke('honoraires:create', data),
  updateHonoraire: (id, data) => ipcRenderer.invoke('honoraires:update', id, data),
  deleteHonoraire: (id) => ipcRenderer.invoke('honoraires:delete', id),

  // Invoices
  getInvoices: (fiscalYear) => ipcRenderer.invoke('invoices:getAll', fiscalYear),
  getInvoice: (id) => ipcRenderer.invoke('invoices:get', id),
  getInvoiceItems: (invoiceId) => ipcRenderer.invoke('invoices:getItems', invoiceId),
  createInvoice: (data) => ipcRenderer.invoke('invoices:create', data),
  updateInvoice: (id, data) => ipcRenderer.invoke('invoices:update', id, data),
  deleteInvoice: (id) => ipcRenderer.invoke('invoices:delete', id),

  // Note De Debits
  getNoteDeDebits: (fiscalYear) => ipcRenderer.invoke('noteDeDebits:getAll', fiscalYear),
  getNoteDeDebit: (id) => ipcRenderer.invoke('noteDeDebits:get', id),
  createNoteDeDebit: (data) => ipcRenderer.invoke('noteDeDebits:create', data),
  updateNoteDeDebit: (id, data) => ipcRenderer.invoke('noteDeDebits:update', id, data),
  deleteNoteDeDebit: (id) => ipcRenderer.invoke('noteDeDebits:delete', id),

  // Settings
  getCompanySettings: () => ipcRenderer.invoke('settings:getCompany'),
  saveCompanySettings: (data) => ipcRenderer.invoke('settings:saveCompany', data),
  getTaxSettings: () => ipcRenderer.invoke('settings:getTaxes'),
  saveTaxSettings: (data) => ipcRenderer.invoke('settings:saveTaxes', data),
  getFiscalYear: () => ipcRenderer.invoke('settings:getFiscalYear'),
  saveFiscalYear: (year) => ipcRenderer.invoke('settings:saveFiscalYear', year),

  // Dashboard
  getDashboardStats: (fiscalYear) => ipcRenderer.invoke('dashboard:stats', fiscalYear),
  getMonthlyChartData: (entity, fiscalYear) => ipcRenderer.invoke('dashboard:monthlyChart', entity, fiscalYear),

  // Reports
  getHonoraireReport: (startDate, endDate) => ipcRenderer.invoke('reports:honoraire', startDate, endDate),
  getRetenueSourceReport: (startDate, endDate, rsRate) => ipcRenderer.invoke('reports:retenueSource', startDate, endDate, rsRate),
  getNoteDeDebitReport: (startDate, endDate) => ipcRenderer.invoke('reports:noteDeDebit', startDate, endDate),

  // PDF
  savePDF: (htmlContent, suggestedName) => ipcRenderer.invoke('pdf:save', htmlContent, suggestedName),
  printPDF: (htmlContent) => ipcRenderer.invoke('pdf:print', htmlContent),

  // Backup
  createBackup: () => ipcRenderer.invoke('backup:create'),
  getBackups: () => ipcRenderer.invoke('backup:list'),
  restoreBackup: (filePath) => ipcRenderer.invoke('backup:restore', filePath),
  deleteBackup: (filePath) => ipcRenderer.invoke('backup:delete', filePath),
  openBackupFolder: () => ipcRenderer.invoke('backup:openFolder'),

  // App
  getAppInfo: () => ipcRenderer.invoke('app:info')
});
