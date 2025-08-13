// =================================================================
//  1. IMPORTS
//     Modules are imported in order: Node built-ins, then Electron.
// =================================================================

const path = require('path');
const { spawn, spawnSync } = require('child_process');
const fs = require('fs');
const { app, BrowserWindow, dialog, ipcMain, net } = require('electron');

// =================================================================
//  2. ENVIRONMENT SETUP & SANITIZATION
// =================================================================

// Determine if we are in development or packaged mode
const isDev = !app.isPackaged;

// Define the correct path to the .env file for both dev and prod
const envPath = isDev
    ? path.join(__dirname, '.env')
    : path.join(process.resourcesPath, 'app', '.env');

// Load environment variables from the .env file
require('dotenv').config({ path: envPath });

// --- CRITICAL FIX: Sanitize the environment ---
// Expand the %APPDATA% variable and update process.env immediately.
// This ensures the main process and all child processes have a consistent,
// fully resolved path for the database.
if (process.platform === 'win32' && process.env.DB_DATABASE && process.env.DB_DATABASE.startsWith('%APPDATA%')) {
    process.env.DB_DATABASE = path.join(process.env.APPDATA, process.env.DB_DATABASE.substring('%APPDATA%'.length));
}

// =================================================================
//  3. GLOBAL VARIABLES & PATHS
// =================================================================

let mainWindow;
let phpServerProcess;
let getPort; // Will be dynamically imported in app.whenReady()

// --- Path Definitions (now simpler, relying on the sanitized environment) ---
const artisanCwd = isDev ? __dirname : path.join(process.resourcesPath, 'app');
const phpExecutable = isDev ? path.join(__dirname, 'php', 'php.exe') : path.join(process.resourcesPath, 'app', 'php', 'php.exe');
const artisanScript = path.join(artisanCwd, 'artisan');
const dbPath = process.env.DB_DATABASE; // This is now the fully resolved path
const storagePath = dbPath ? path.dirname(dbPath) : null; // Derive storage path

// =================================================================
//  4. HELPER FUNCTIONS (ARTISAN & PHP SERVER)
// =================================================================

function getPhpEnvironment() {
    // This environment is now passed to all child processes
    // ensuring they know the correct DB path and storage path.
    return { ...process.env, APP_STORAGE_PATH: storagePath };
}

function runArtisanCommand(args) {
    return new Promise((resolve, reject) => {
        const commandProcess = spawn(phpExecutable, [artisanScript, ...args], {
            cwd: artisanCwd,
            env: getPhpEnvironment(),
            windowsHide: true
        });

        let stdout = '', stderr = '';
        commandProcess.stdout.on('data', (data) => (stdout += data.toString()));
        commandProcess.stderr.on('data', (data) => (stderr += data.toString()));

        commandProcess.on('error', reject);
        commandProcess.on('close', (code) => {
            if (code === 0) {
                console.log(`Command '${args.join(' ')}' completed successfully.`);
                resolve(stdout.trim());
            } else {
                reject(new Error(`Command '${args.join(' ')}' failed with exit code ${code}. Stderr: ${stderr.trim()}`));
            }
        });
    });
}

function startPhpServer() {
    return new Promise(async (resolve, reject) => {
        try {
            const port = await getPort({ port: 8000 });
            const serverUrl = `http://127.0.0.1:${port}`;
            
            console.log(`Starting PHP server...`);
            phpServerProcess = spawn(phpExecutable, [artisanScript, 'serve', `--port=${port}`], {
                cwd: artisanCwd,
                env: getPhpEnvironment()
            });
            
            phpServerProcess.on('error', reject);
            phpServerProcess.on('close', (code) => {
                if (code !== 0 && !app.isQuitting) reject(new Error(`PHP server process exited unexpectedly with code ${code}.`));
            });

            const onServerOutput = (data) => {
                const message = data.toString();
                if (message.includes('Server running on')) {
                    console.log(`PHP server started successfully at ${serverUrl}`);
                    phpServerProcess.stdout.removeListener('data', onServerOutput); // Clean up listener
                    resolve(serverUrl);
                }
            };
            phpServerProcess.stdout.on('data', onServerOutput);
            phpServerProcess.stderr.on('data', (data) => console.error(`PHP Server Stderr: ${data}`)); // Log errors
        } catch (err) {
            reject(err);
        }
    });
}

// =================================================================
//  5. MAIN WINDOW & IPC HANDLERS
// =================================================================

function createWindow(serverUrl) {
    mainWindow = new BrowserWindow({
        width: 1200, height: 800, show: false, backgroundColor: '#2e2c29',
        autoHideMenuBar: true,
        icon: path.join(__dirname, 'public', 'images', 'favicon.ico'), // This works for both dev/prod
        webPreferences: {
            nodeIntegration: false, contextIsolation: true,
            preload: path.join(__dirname, 'preload.js')
        },
    });
    
    mainWindow.once('ready-to-show', () => mainWindow.show());
    mainWindow.loadURL(serverUrl);
    if (isDev) mainWindow.webContents.openDevTools();
    mainWindow.on('closed', () => { mainWindow = null; });
}

ipcMain.on('download-pdf', (event, url) => {
    // This logic is robust and correct. No changes needed.
    const potentialFilename = path.basename(url) + '.pdf';
    
    const filePath = dialog.showSaveDialogSync(mainWindow, {
        title: 'Save PDF',
        defaultPath: `Honoraire_${potentialFilename}`,
        filters: [{ name: 'PDF Documents', extensions: ['pdf'] }]
    });

    if (!filePath) return;

    const request = net.request(url);
    request.on('response', (response) => {
        if (response.statusCode === 200) {
            const fileStream = fs.createWriteStream(filePath);
            response.pipe(fileStream); // Simpler way to stream data
            fileStream.on('finish', () => {
                dialog.showMessageBox(mainWindow, { title: 'Download Complete', message: `File saved to ${filePath}` });
            });
            fileStream.on('error', (err) => dialog.showErrorBox('Save Failed', `An error occurred while saving the file: ${err.message}`));
        } else {
            dialog.showErrorBox('Download Failed', `The server responded with an error: ${response.statusCode}`);
        }
    });
    request.on('error', (err) => dialog.showErrorBox('Download Failed', `An error occurred making the request: ${err.message}`));
    request.end();
});

// =================================================================
//  6. APPLICATION LIFECYCLE
// =================================================================

app.whenReady().then(async () => {
    try {
        getPort = (await import('get-port')).default;

        if (!storagePath || !dbPath) {
            throw new Error("Database path is not defined in the .env file.");
        }

        // Ensure the directory for the database exists.
        if (!fs.existsSync(storagePath)) {
            console.log(`Storage path not found. Creating directory: ${storagePath}`);
            fs.mkdirSync(storagePath, { recursive: true });
        }
        
        // First-time setup for a user if the database file is missing.
        if (!fs.existsSync(dbPath)) {
            console.log('Database not found. Running first-time user setup...');
            fs.writeFileSync(dbPath, ''); // Create blank file
            await runArtisanCommand(['key:generate', '--force']);
            await runArtisanCommand(['migrate', '--force']);
            await runArtisanCommand(['db:seed', '--force']);
            console.log('Database created and seeded successfully.');
        } else {
            console.log('Existing database found.');
        }

        const serverUrl = await startPhpServer();
        createWindow(serverUrl);
    } catch (err) {
        console.error("Fatal application startup error:", err);
        dialog.showErrorBox('Application Startup Error', `A critical error occurred: ${err.message}. The application will now exit.`);
        app.quit();
    }
});

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') app.quit();
});

app.on('will-quit', () => {
    if (phpServerProcess) {
        console.log(`Stopping PHP server (PID: ${phpServerProcess.pid})...`);
        if (process.platform === 'win32') {
            spawnSync('taskkill', ['/pid', phpServerProcess.pid, '/f', '/t']);
        } else {
            phpServerProcess.kill();
        }
        console.log('PHP server stopped.');
    }
});

app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0 && app.isReady()) {
        // Re-running the startup logic on macOS if the app is activated
        // after all windows are closed can be complex. For now, we do nothing
        // as the primary platform is Windows.
    }
});