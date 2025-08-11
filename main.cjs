const { app, BrowserWindow, dialog } = require('electron');
const path = require('path');
const { spawn } = require('child_process');
const fs = require('fs');

let mainWindow;
let phpServerProcess;
let getPort; // Will be dynamically imported

const isDev = !app.isPackaged;

// Determine the path to the PHP executable
const phpPath = isDev
  ? path.join(__dirname, 'php', 'php.exe')
  : path.join(process.resourcesPath, 'php', 'php.exe');

// The artisan script path
const artisanPath = path.join(process.resourcesPath, 'artisan');

function startPhpServer() {
  return new Promise(async (resolve, reject) => {
    try {
      const port = await getPort({ port: 8000 });
      const serverUrl = `http://127.0.0.1:${port}`;

      console.log(`Starting PHP server on port ${port}...`);
      console.log(`PHP executable path: ${phpPath}`);

      // Note: We are running 'php artisan serve'
      phpServerProcess = spawn(phpPath, [artisanPath, 'serve', `--port=${port}`], {
        cwd: process.resourcesPath,
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

    // Define the path for the application initialized flag file
    const appInitializedFlagPath = path.join(app.getPath('userData'), '.app_initialized');
    const envFilePath = path.join(process.resourcesPath, '.env');
    const envExamplePath = path.join(process.resourcesPath, '.env.example');

    // Function to initialize the application (DB migrations, seeding, key generation)
    async function initializeApplication() {
      if (fs.existsSync(appInitializedFlagPath)) {
        console.log('Application already initialized. Skipping setup.');
        return;
      }

      console.log('Initializing application (DB migrations, seeding, key generation)...');
      try {
        // Copy .env.example to .env if .env does not exist
        if (!fs.existsSync(envFilePath)) {
          console.log('Copying .env.example to .env...');
          fs.copyFileSync(envExamplePath, envFilePath);
        }

        // Run migrations
        console.log('Running migrations...');
        const migrateProcess = spawn(phpPath, [artisanPath, 'migrate', '--force'], {
          cwd: process.resourcesPath,
          windowsHide: true,
        });

        await new Promise((resolve, reject) => {
          migrateProcess.stdout.on('data', (data) => console.log(`Migrate stdout: ${data.toString()}`));
          migrateProcess.stderr.on('data', (data) => console.error(`Migrate stderr: ${data.toString()}`));
          migrateProcess.on('close', (code) => {
            if (code === 0) {
              console.log('Migrations completed successfully.');
              resolve();
            } else {
              reject(new Error(`Migrations failed with code ${code}`));
            }
          });
          migrateProcess.on('error', (err) => reject(err));
        });

        // Run seeders
        console.log('Running database seeders...');
        const seedProcess = spawn(phpPath, [artisanPath, 'db:seed'], {
          cwd: process.resourcesPath,
          windowsHide: true,
        });

        await new Promise((resolve, reject) => {
          seedProcess.stdout.on('data', (data) => console.log(`Seed stdout: ${data.toString()}`));
          seedProcess.stderr.on('data', (data) => console.error(`Seed stderr: ${data.toString()}`));
          seedProcess.on('close', (code) => {
            if (code === 0) {
              console.log('Database seeding completed successfully.');
              resolve();
            } else {
              reject(new Error(`Database seeding failed with code ${code}`));
            }
          });
          seedProcess.on('error', (err) => reject(err));
        });

        // Generate application key
        console.log('Generating application key...');
        const keyGenerateProcess = spawn(phpPath, [artisanPath, 'key:generate'], {
          cwd: process.resourcesPath,
          windowsHide: true,
        });

        await new Promise((resolve, reject) => {
          keyGenerateProcess.stdout.on('data', (data) => console.log(`Key Generate stdout: ${data.toString()}`));
          keyGenerateProcess.stderr.on('data', (data) => console.error(`Key Generate stderr: ${data.toString()}`));
          keyGenerateProcess.on('close', (code) => {
            if (code === 0) {
              console.log('Application key generated successfully.');
              fs.writeFileSync(appInitializedFlagPath, 'true'); // Create flag file
              resolve();
            } else {
              reject(new Error(`Application key generation failed with code ${code}`));
            }
          });
          keyGenerateProcess.on('error', (err) => reject(err));
        });

      } catch (error) {
        console.error('Application initialization failed:', error);
        dialog.showErrorBox('Application Setup Error', `Failed to initialize application: ${error.message}. The application will now exit.`);
        app.quit();
      }
    }

    await initializeApplication(); // Call application initialization

    const serverUrl = await startPhpServer();
    createWindow(serverUrl);

    app.on('activate', () => {
      // On macOS it's common to re-create a window in the app when the
      // dock icon is clicked and there are no other windows open.
      if (process.platform === 'darwin' && BrowserWindow.getAllWindows().length === 0) {
        createWindow(serverUrl);
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
    console.log(`Attempting to kill PHP server process with PID: ${phpServerProcess.pid}`);
    // Forcefully kill the process and its children on Windows
    if (process.platform === 'win32') {
      const killProcess = spawn('taskkill', ['/pid', phpServerProcess.pid, '/f', '/t']);
      killProcess.stdout.on('data', (data) => {
        console.log(`taskkill stdout: ${data.toString()}`);
      });
      killProcess.stderr.on('data', (data) => {
        console.error(`taskkill stderr: ${data.toString()}`);
      });
      killProcess.on('close', (code) => {
        console.log(`taskkill process exited with code ${code}`);
      });
      killProcess.on('error', (err) => {
        console.error('Failed to spawn taskkill process.', err);
      });
    } else {
      phpServerProcess.kill();
    }
  }
});
