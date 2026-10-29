const { JSDOM } = require('jsdom');
const path = require('path');
const fs = require('fs');

async function generatePDF(htmlContent, outputPath, mainWindow) {
  // Simple PDF via data URL for printing — uses Electron's built-in print
  if (!outputPath && mainWindow) {
    const dataUrl = 'data:text/html;charset=utf-8,' + encodeURIComponent(htmlContent);
    const { BrowserWindow } = require('electron');
    const pdfWin = new BrowserWindow({
      width: 800, height: 600, show: false,
      webPreferences: { nodeIntegration: false, contextIsolation: true }
    });
    await pdfWin.loadURL(dataUrl);
    const pdfData = await pdfWin.webContents.printToPDF({
      pageSize: 'A4',
      printBackground: true,
      margins: { top: 0, bottom: 0, left: 0, right: 0 }
    });
    pdfWin.close();

    if (outputPath) {
      fs.writeFileSync(outputPath, pdfData);
    } else {
      // Return buffer for direct save
      return pdfData;
    }
  }

  // Save directly to file
  if (outputPath) {
    const { BrowserWindow } = require('electron');
    const pdfWin = new BrowserWindow({
      width: 800, height: 600, show: false,
      webPreferences: { nodeIntegration: false, contextIsolation: true }
    });
    const dataUrl = 'data:text/html;charset=utf-8,' + encodeURIComponent(htmlContent);
    await pdfWin.loadURL(dataUrl);
    const pdfData = await pdfWin.webContents.printToPDF({
      pageSize: 'A4',
      printBackground: true,
      margins: { top: 0, bottom: 0, left: 0, right: 0 }
    });
    pdfWin.close();
    fs.writeFileSync(outputPath, pdfData);
  }

  return { success: true };
}

module.exports = { generatePDF };
