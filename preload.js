const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('electronAPI', {
  downloadPDF: (url) => ipcRenderer.send('download-pdf')
});