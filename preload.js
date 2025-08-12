const { contextBridge, ipcRenderer } = require('electron');

// Expose a secure API to the renderer process (your webpage)
contextBridge.exposeInMainWorld('electronAPI', {
  // This function will be callable from your webpage as window.electronAPI.downloadPDF(url)
  downloadPDF: (url) => ipcRenderer.invoke('download-pdf', url)
});