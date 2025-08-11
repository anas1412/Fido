const { app, BrowserWindow, dialog } = require('electron');
const path = require('path');
const { spawn } = require('child_process');

let mainWindow;
let phpServerProcess;
let getPort; // Will be dynamically imported

const isDev = !app.isPackaged;

// Determine the path to the PHP executable
const phpPath = isDev
  ? path.join(__dirname, 'php', 'php.exe')
  : path.join(process.resourcesPath, 'php', 'php.exe');

// The artisan script path
const artisanPath = path.join(__dirname, 'artisan');

function startPhpServer() {
  return new Promise(async (resolve, reject) => {
    try {
      const port = await getPort({ port: 8000 });
      const serverUrl = `http://127.0.0.1:${port}`;

      console.log(`Starting PHP server on port ${port}...`);
      console.log(`PHP executable path: ${phpPath}`);

      // Note: We are running 'php artisan serve'
      phpServerProcess = spawn(phpPath, [artisanPath, 'serve', `--port=${port}`], {
        cwd: __dirname,
        windowsHide: true,
      });

      let serverStarted = false;
      const onServerOutput = (data) => {
        const message = data.toString();
        if (message.includes('Server running on') && !serverStarted) {
          serverStarted = true;
          console.log(`PHP server started successfully at ${serverUrl}`);
          resolve(serverUrl);
        }
      };

      phpServerProcess.stdout.on('data', (data) => {
        const message = data.toString();
        console.log(`PHP Server stdout: ${message}`);
        onServerOutput(data);
      });

      phpServerProcess.stderr.on('data', (data) => {
        const message = data.toString();
        console.error(`PHP Server stderr: ${message}`);
        onServerOutput(data);
      });

      phpServerProcess.on('error', (err) => {
        console.error('Failed to start PHP server process.', err);
        reject(err);
      });

      phpServerProcess.on('close', (code) => {
        console.log(`PHP server process exited with code ${code}`);
      });

    } catch (err) {
      console.error('Failed to start PHP server.', err);
      reject(err);
    }
  });
}

function createWindow(serverUrl) {
  mainWindow = new BrowserWindow({
    width: 1200,
    height: 800,
    show: false, // Hide the window initially
    backgroundColor: '#2e2c29', // Set a background color
    autoHideMenuBar: true, // Hide the menu bar
    icon: path.join(__dirname, 'public', 'images', 'favicon.ico'), // Set the application icon
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
    },
  });

  // Show the window only when the content is ready
  mainWindow.once('ready-to-show', () => {
    mainWindow.show();
  });

  

  // Handle PDF downloads
  mainWindow.webContents.session.on('will-download', (event, item, webContents) => {
    event.preventDefault();
    dialog.showSaveDialog({
      title: 'Save PDF',
      defaultPath: item.getFilename(),
      filters: [{ name: 'PDF Documents', extensions: ['pdf'] }]
    }).then(({ filePath }) => {
      if (filePath) {
        item.setSavePath(filePath);
        item.once('done', (event, state) => {
          if (state === 'completed') {
            dialog.showMessageBox(mainWindow, {
              title: 'Download Complete',
              message: `File saved to ${filePath}`
            });
          } else {
            dialog.showErrorBox('Download Failed', `Failed to download file: ${state}`);
          }
        });
      }
    });
  });

  mainWindow.loadURL(serverUrl);

  if (isDev) {
    mainWindow.webContents.openDevTools();
  }

  mainWindow.on('closed', () => {
    mainWindow = null;
  });
}

app.whenReady().then(async () => {
  try {
    // Dynamically import get-port
    getPort = (await import('get-port')).default;

    const serverUrl = await startPhpServer();
    createWindow(serverUrl);

    app.on('activate', () => {
      if (BrowserWindow.getAllWindows().length === 0) {
        createWindow(serverUrl); // Re-create window, server should still be running
      }
    });

  } catch (err) {
    console.error("Application startup error:", err);
    // You might want to show an error dialog to the user here
    app.quit();
  }
});

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
});

app.on('will-quit', () => {
  if (phpServerProcess) {
    console.log('Stopping PHP server...');
    // Forcefully kill the process and its children on Windows
    if (process.platform === 'win32') {
      spawn('taskkill', ['/pid', phpServerProcess.pid, '/f', '/t']);
    } else {
      phpServerProcess.kill();
    }
  }
});
