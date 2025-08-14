// =================================================================
//  1. IMPORTS
// =================================================================

const path = require('path');
const { spawn, spawnSync } = require('child_process');
const fs = require('fs');
const { app, BrowserWindow, dialog, ipcMain, net } = require('electron');
const axios = require('axios'); // Using axios for polling

// =================================================================
//  2. ENVIRONMENT SETUP & SANITIZATION
// =================================================================

const isDev = !app.isPackaged;
const envPath = isDev ? path.join(__dirname, '.env') : path.join(process.resourcesPath, 'app', '.env');
require('dotenv').config({ path: envPath });

if (process.platform === 'win32' && process.env.DB_DATABASE && process.env.DB_DATABASE.startsWith('%APPDATA%')) {
    process.env.DB_DATABASE = path.join(process.env.APPDATA, process.env.DB_DATABASE.substring('%APPDATA%'.length));
}

// =================================================================
//  3. GLOBAL VARIABLES & PATHS
// =================================================================

let mainWindow;
let loadingWindow; // Window for the loading screen
let phpServerProcess;
let getPort;

const artisanCwd = isDev ? __dirname : path.join(process.resourcesPath, 'app');
const phpExecutable = isDev ? path.join(__dirname, 'php', 'php.exe') : path.join(process.resourcesPath, 'app', 'php', 'php.exe');
const artisanScript = path.join(artisanCwd, 'artisan');
const dbPath = process.env.DB_DATABASE;
const storagePath = dbPath ? path.dirname(dbPath) : null;

// =================================================================
//  4. HELPER FUNCTIONS
// =================================================================

function getPhpEnvironment() {
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
                    phpServerProcess.stdout.removeListener('data', onServerOutput);
                    resolve(serverUrl);
                }
            };
            phpServerProcess.stdout.on('data', onServerOutput);
            phpServerProcess.stderr.on('data', (data) => console.error(`PHP Server Stderr: ${data}`));
        } catch (err) {
            reject(err);
        }
    });
}

// =================================================================
//  5. WINDOW MANAGEMENT
// =================================================================

function createLoadingWindow() {
    loadingWindow = new BrowserWindow({
        width: 400,
        height: 300,
        frame: false,
        resizable: false,
        movable: true,
        backgroundColor: '#2e2c29',
        show: false,
        webPreferences: {
            nodeIntegration: true,
        },
    });
    loadingWindow.loadFile('loading.html');
    loadingWindow.once('ready-to-show', () => {
        loadingWindow.show();
    });
    loadingWindow.on('closed', () => {
        loadingWindow = null;
    });
}

function createMainWindow(serverUrl) {
    mainWindow = new BrowserWindow({
        width: 1200, height: 800, show: false, backgroundColor: '#2e2c29',
        autoHideMenuBar: true,
        icon: path.join(__dirname, 'public', 'images', 'favicon.ico'),
        webPreferences: {
            nodeIntegration: false, contextIsolation: true,
            preload: path.join(__dirname, 'preload.js')
        },
    });
    
    mainWindow.loadURL(serverUrl);
    if (isDev) mainWindow.webContents.openDevTools();
    mainWindow.on('closed', () => { mainWindow = null; });
}

// =================================================================
//  6. APPLICATION LIFECYCLE
// =================================================================

app.whenReady().then(async () => {
    createLoadingWindow();

    try {
        getPort = (await import('get-port')).default;

        if (!storagePath || !dbPath) {
            throw new Error("Database path is not defined in the .env file.");
        }

        if (!fs.existsSync(storagePath)) {
            fs.mkdirSync(storagePath, { recursive: true });
        }
        
        if (!fs.existsSync(dbPath)) {
            console.log('Database not found. Running first-time user setup...');
            fs.writeFileSync(dbPath, '');
            await runArtisanCommand(['key:generate', '--force']);
            await runArtisanCommand(['migrate', '--force']);
            await runArtisanCommand(['db:seed', '--force']);
            console.log('Database created and seeded successfully.');
        } else {
            console.log('Existing database found.');
        }

        const serverUrl = await startPhpServer();

        // Poll the server to see when it's ready
        // Poll the server to see when it's ready
        const pollServer = async () => {
            const pollUrl = serverUrl + '/dashboard/login'; // Use the login page to check for readiness
            console.log(`Polling server at: ${pollUrl}`)
            try {
                const response = await axios.get(pollUrl, { timeout: 1000 });
                if (response.status === 200) {
                    console.log('Server responded with 200 OK. Closing loading screen.');
                    if (loadingWindow) {
                        loadingWindow.close();
                    }
                    createMainWindow(serverUrl);
                    mainWindow.once('ready-to-show', () => mainWindow.show());
                } else {
                    console.log(`Server responded with status: ${response.status}. Retrying...`);
                    setTimeout(pollServer, 500);
                }
            } catch (error) {
                console.error('Polling error:', error.message);
                // Server not ready yet, try again
                setTimeout(pollServer, 500);
            }
        };

        pollServer();

    } catch (err) {
        console.error("Fatal application startup error:", err);
        dialog.showErrorBox('Application Startup Error', `A critical error occurred: ${err.message}. The application will now exit.`);
        if (loadingWindow) loadingWindow.close();
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
        // Re-running startup logic on activate
        app.whenReady();
    }
});

// =================================================================
//  7. IPC HANDLERS
// =================================================================

ipcMain.on('download-pdf', (event, url) => {
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
            response.pipe(fileStream);
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
