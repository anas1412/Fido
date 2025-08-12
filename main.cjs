// main.cjs

const { app, BrowserWindow, dialog } = require('electron');
const path = require('path');
const { spawn } = require('child_process');
const fs = require('fs');

// --- Global Variables ---
let mainWindow;
let phpServerProcess;
let getPort; // Will be dynamically imported

const isDev = !app.isPackaged;

// --- Helper function to run Artisan commands ---
// This avoids code duplication for migrate, seed, key:generate, etc.
function runArtisanCommand(args, cwd) {
  const phpExecutable = isDev
    ? path.join(__dirname, 'php', 'php.exe')
    : path.join(process.resourcesPath, 'php', 'php.exe');

  return new Promise((resolve, reject) => {
    // We add windowsHide: true to prevent a console window from flashing.
    const commandProcess = spawn(phpExecutable, args, { cwd, windowsHide: true });

    let stdout = '';
    let stderr = '';

    commandProcess.stdout.on('data', (data) => (stdout += data.toString()));
    commandProcess.stderr.on('data', (data) => (stderr += data.toString()));

    commandProcess.on('error', (err) => {
      // This catches errors like EACCES (permission denied) or ENOENT (file not found).
      console.error(`Failed to spawn command: ${phpExecutable} ${args.join(' ')}`, err);
      reject(err);
    });

    commandProcess.on('close', (code) => {
      console.log(`Command '${args.join(' ')}' stdout:\n${stdout}`);
      if (stderr) {
        console.error(`Command '${args.join(' ')}' stderr:\n${stderr}`);
      }
      
      if (code === 0) {
        console.log(`Command '${args.join(' ')}' completed successfully.`);
        resolve(stdout);
      } else {
        const errorMessage = `Command '${args.join(' ')}' failed with exit code ${code}. Stderr: ${stderr}`;
        console.error(errorMessage);
        reject(new Error(errorMessage));
      }
    });
  });
}


// --- Main Application Functions ---

function startPhpServer() {
  return new Promise(async (resolve, reject) => {
    try {
      const port = await getPort({ port: 8000 });
      const serverUrl = `http://127.0.0.1:${port}`;

      const phpExecutable = isDev
        ? path.join(__dirname, 'php', 'php.exe')
        : path.join(process.resourcesPath, 'php', 'php.exe');
      
      const serverArgs = ['artisan', 'serve', `--port=${port}`];
      
      // The working directory should be where your artisan file is located.
      // For a packaged app, it's the root of the resources directory.
      // In development, it's the project root.
      const cwd = isDev ? __dirname : process.resourcesPath;

      console.log(`Starting PHP server: ${phpExecutable} ${serverArgs.join(' ')} in ${cwd}`);

      // Spawn the PHP server and assign it to the GLOBAL variable. This was the source of your error.
      phpServerProcess = spawn(phpExecutable, serverArgs, { cwd });

      // BEST PRACTICE: Attach the 'error' handler IMMEDIATELY after spawning.
      // This will catch errors if the process fails to launch at all.
      phpServerProcess.on('error', (err) => {
        console.error('Failed to start PHP server process.', err);
        reject(err);
      });
      
      // BEST PRACTICE: If the server process closes unexpectedly, reject the promise.
      phpServerProcess.on('close', (code) => {
        if (code !== 0) {
          const errorMessage = `PHP server process exited unexpectedly with code ${code}.`;
          console.error(errorMessage);
          reject(new Error(errorMessage));
        }
      });

      // Listen to output to know when the server is ready.
      const onServerOutput = (data) => {
        const message = data.toString();
        // Pipe output to the main console for debugging.
        process.stdout.write(message); 

        if (message.includes('Server running on')) {
          console.log(`PHP server started successfully at ${serverUrl}`);
          // Remove listeners we no longer need to avoid memory leaks
          phpServerProcess.stdout.removeListener('data', onServerOutput);
          phpServerProcess.stderr.removeListener('data', onServerOutput);
          resolve(serverUrl);
        }
      };

      phpServerProcess.stdout.on('data', onServerOutput);
      phpServerProcess.stderr.on('data', onServerOutput);

    } catch (err) {
      console.error('An error occurred in startPhpServer.', err);
      reject(err);
    }
  });
}

function createWindow(serverUrl) {
  mainWindow = new BrowserWindow({
    width: 1200,
    height: 800,
    show: false,
    backgroundColor: '#2e2c29',
    autoHideMenuBar: true,
    icon: path.join(__dirname, 'public', 'images', 'favicon.ico'),
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
    },
  });

  mainWindow.once('ready-to-show', () => {
    mainWindow.show();
  });

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
            dialog.showMessageBox(mainWindow, { title: 'Download Complete', message: `File saved to ${filePath}` });
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


// --- Application Lifecycle Events ---

app.whenReady().then(async () => {
  try {
    getPort = (await import('get-port')).default;

    const appInitializedFlagPath = path.join(app.getPath('userData'), '.app_initialized');
    
    // In a packaged app, the CWD for artisan should be the resources path.
    const artisanCwd = isDev ? __dirname : process.resourcesPath;

    // Run first-time setup if needed
    if (!fs.existsSync(appInitializedFlagPath)) {
      console.log('Performing first-time application setup...');
      
      const envExamplePath = path.join(artisanCwd, '.env.example');
      const envFilePath = path.join(artisanCwd, '.env');

      if (fs.existsSync(envExamplePath) && !fs.existsSync(envFilePath)) {
        console.log('Creating .env from .env.example...');
        fs.copyFileSync(envExamplePath, envFilePath);
      }
      
      await runArtisanCommand(['key:generate'], artisanCwd);
      await runArtisanCommand(['migrate', '--force'], artisanCwd);
      await runArtisanCommand(['db:seed'], artisanCwd);

      fs.writeFileSync(appInitializedFlagPath, 'initialized');
      console.log('Application setup complete.');
    } else {
      console.log('Application already initialized. Skipping setup.');
    }

    // Start the server and create the window
    const serverUrl = await startPhpServer();
    createWindow(serverUrl);

    app.on('activate', () => {
      if (process.platform === 'darwin' && BrowserWindow.getAllWindows().length === 0) {
        createWindow(serverUrl);
      }
    });

  } catch (err) {
    console.error("Fatal application startup error:", err);
    dialog.showErrorBox('Application Startup Error', `A critical error occurred: ${err.message}. The application will now exit.`);
    app.quit();
  }
});

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
});

// BEST PRACTICE: Ensure the PHP server is terminated when the app quits.
app.on('will-quit', () => {
  if (phpServerProcess) {
    console.log(`Stopping PHP server (PID: ${phpServerProcess.pid})...`);
    // On Windows, /t (tree) kills the main process AND any child processes it spawned.
    // This is crucial for cleanly shutting down `php artisan serve`.
    // On other platforms, `kill()` is usually sufficient.
    if (process.platform === 'win32') {
      spawn('taskkill', ['/pid', phpServerProcess.pid, '/f', '/t']);
    } else {
      phpServerProcess.kill();
    }
  }
});