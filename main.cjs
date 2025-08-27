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
let currentEnvPath;

if (isDev) {
    const isDemo = process.env.APP_DEMO_BUILD === 'true';
    currentEnvPath = isDemo ? path.join(__dirname, '.env.demo') : path.join(__dirname, '.env');
} else {
    const demoEnvInPackage = path.join(process.resourcesPath, 'app', '.env.demo');
    if (fs.existsSync(demoEnvInPackage)) {
        currentEnvPath = demoEnvInPackage;
    } else {
        currentEnvPath = path.join(process.resourcesPath, 'app', '.env');
    }
}
const isDemoBuild = currentEnvPath.endsWith('.env.demo');

require('dotenv').config({ path: currentEnvPath, override: true });
console.log('isDemoBuild:', isDemoBuild);
console.log('DB_DATABASE after dotenv load:', process.env.DB_DATABASE);

// =================================================================
//  3. GLOBAL VARIABLES & PATHS
// =================================================================

let mainWindow;
let loadingWindow;
let phpServerProcess;
let getPort;

const artisanCwd = isDev ? __dirname : path.join(process.resourcesPath, 'app');
const phpExecutable = isDev ? path.join(__dirname, 'php', 'php.exe') : path.join(process.resourcesPath, 'app', 'php', 'php.exe');
const artisanScript = path.join(artisanCwd, 'artisan');

let storagePath = path.join(artisanCwd, 'storage');

// =================================================================
//  4. HELPER FUNCTIONS
// =================================================================

function getPhpEnvironment(dbPathToUse) {
    const env = {
        ...process.env,
        APP_NAME: isDemoBuild ? 'Fido Demo' : 'Fido',
        APP_STORAGE_PATH: storagePath,
        DB_DATABASE: dbPathToUse || process.env.DB_DATABASE
    };

    if (dbPathToUse) {
        env.DB_DATABASE = dbPathToUse;
    }

    return env;
}

function runArtisanCommand(args, dbPathToUse = null) {
    return new Promise((resolve, reject) => {
        console.log(`Running Artisan Command: php ${artisanScript} ${args.join(' ')}`);
        const commandProcess = spawn(phpExecutable, [artisanScript, ...args], {
            cwd: artisanCwd,
            env: getPhpEnvironment(dbPathToUse),
            windowsHide: true
        });
        let stdout = '', stderr = '';
        commandProcess.stdout.on('data', (data) => (stdout += data.toString()));
        commandProcess.stderr.on('data', (data) => (stderr += data.toString()));

        let timeoutId = setTimeout(() => {
            commandProcess.kill();
            reject(new Error(`Command '${args.join(' ')}' timed out. Stderr: ${stderr.trim()}`));
        }, 120000);

        commandProcess.on('exit', (code) => {
            clearTimeout(timeoutId);
            if (code === 0) {
                console.log(`Command '${args.join(' ')}' completed successfully.`);
                resolve(stdout.trim());
            } else {
                console.error(`Command '${args.join(' ')}' failed with exit code ${code}.`);
                console.error(`Stdout: ${stdout.trim()}`);
                console.error(`Stderr: ${stderr.trim()}`);
                reject(new Error(`Command '${args.join(' ')}' failed with exit code ${code}. Stdout: ${stdout.trim()} Stderr: ${stderr.trim()}`));
            }
        });
        commandProcess.on('error', reject);
    });
}

function startPhpServer(dbPathToUseForServer) {
    return new Promise(async (resolve, reject) => {
        try {
            const port = await getPort({ port: 8000 });
            const serverUrl = `http://127.0.0.1:${port}`;

            console.log(`Starting PHP server...`);
            phpServerProcess = spawn(phpExecutable, [artisanScript, 'serve', `--port=${port}`], {
                cwd: artisanCwd,
                env: getPhpEnvironment(dbPathToUseForServer)
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
        webPreferences: { nodeIntegration: true },
    });
    loadingWindow.loadFile(path.join(__dirname, 'loading.html'));
    loadingWindow.once('ready-to-show', () => loadingWindow.show());
    loadingWindow.on('closed', () => { loadingWindow = null; });
}

function createMainWindow(serverUrl) {
    mainWindow = new BrowserWindow({
        width: 1600, height: 1200, show: false, backgroundColor: '#2e2c29',
        autoHideMenuBar: true,
        icon: path.join(__dirname, 'public', 'images', 'favicon.ico'),
        webPreferences: { nodeIntegration: false, contextIsolation: true, preload: path.join(__dirname, 'preload.js') },
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

        // Demo build: copy .env.demo to .env for artisan usage
        if (isDemoBuild && !isDev) {
            const demoEnvFile = path.join(artisanCwd, '.env.demo');
            const tempEnvFile = path.join(artisanCwd, '.env');
            console.log(`Demo build detected. Copying ${demoEnvFile} to ${tempEnvFile} for setup...`);
            fs.copyFileSync(demoEnvFile, tempEnvFile);
        }

        const appName = isDemoBuild ? 'Fido Demo' : 'Fido';
        const userDataPath = path.join(app.getPath('appData'), appName);
        storagePath = path.join(userDataPath, 'storage');

        const dbName = process.env.DB_DATABASE || 'database.sqlite';
        const fullDbPath = path.isAbsolute(dbName) ? dbName : path.join(userDataPath, dbName);

        // Ensure storage directories
        const requiredStorageDirs = [
            storagePath,
            path.join(storagePath, 'framework', 'views'),
            path.join(storagePath, 'framework', 'cache', 'data'),
            path.join(storagePath, 'framework', 'sessions'),
            path.join(storagePath, 'logs')
        ];
        requiredStorageDirs.forEach(dir => { if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true }); });

        // Ensure DB directory
        const dbDir = path.dirname(fullDbPath);
        if (!fs.existsSync(dbDir)) fs.mkdirSync(dbDir, { recursive: true });

        let wasDbCreated = false;
        if (!fs.existsSync(fullDbPath)) {
            console.log(`Database file not found at ${fullDbPath}. Creating empty file...`);
            fs.writeFileSync(fullDbPath, '');
            console.log('Empty database file created.');
            wasDbCreated = true;
        } else {
            console.log(`Existing database file found at ${fullDbPath}.`);
        }

        // Run migrations/seeding
        if (wasDbCreated) {
            console.log('Running key:generate, migrate:fresh, and initial seeding...');
            await runArtisanCommand(['key:generate', '--force'], fullDbPath);
            await runArtisanCommand(['migrate:fresh', '--force'], fullDbPath);
            await runArtisanCommand(['db:seed', '--force'], fullDbPath);

            if (isDemoBuild) {
                console.log('Running demo data seeder...');
                await runArtisanCommand(['seed:demo'], fullDbPath);
            } else {
                console.log('Running admin user seeder...');
                await runArtisanCommand(['seed:admin'], fullDbPath);
            }
        } else {
            console.log('Skipping fresh setup. Running migrate to ensure up-to-date...');
            await runArtisanCommand(['migrate', '--force'], fullDbPath);
        }

        const serverUrl = await startPhpServer(fullDbPath);

        const pollServer = async () => {
            const pollUrl = serverUrl + '/dashboard/login';
            try {
                const response = await axios.get(pollUrl, { timeout: 5000 });
                if (response.status === 200) {
                    if (loadingWindow) loadingWindow.close();
                    createMainWindow(serverUrl);
                    mainWindow.once('ready-to-show', () => mainWindow.show());
                } else {
                    setTimeout(pollServer, 500);
                }
            } catch {
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

app.on('window-all-closed', () => { if (process.platform !== 'darwin') app.quit(); });

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

app.on('activate', () => { if (BrowserWindow.getAllWindows().length === 0 && app.isReady()) app.whenReady(); });

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
