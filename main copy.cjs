// main.cjs
const { app, BrowserWindow, dialog } = require('electron');
const path = require('path');
const { spawn, spawnSync } = require('child_process');
const fs = require('fs');

// --- Global Variables ---
let mainWindow;
let phpServerProcess;
let getPort;

const isDev = !app.isPackaged;

// Determine paths based on development or packaged app
const artisanCwd = isDev ? __dirname : path.join(process.resourcesPath, 'app');
const storagePath = isDev ? path.join(__dirname, 'storage') : path.join(app.getPath('userData'), 'storage');
const phpExecutable = isDev ? path.join(__dirname, 'php', 'php.exe') : path.join(process.resourcesPath, 'app', 'php', 'php.exe');
const artisanScript = path.join(artisanCwd, 'artisan');

// Environment variables to pass to PHP
const phpEnv = {
    ...process.env,
    APP_STORAGE_PATH: storagePath,
    DB_DATABASE: path.join(storagePath, 'database.sqlite')
};


// --- Helper function to run Artisan commands ---
function runArtisanCommand(args) {
    const commandArgs = [artisanScript, ...args];

    return new Promise((resolve, reject) => {
        const commandProcess = spawn(phpExecutable, commandArgs, { cwd: artisanCwd, env: phpEnv, windowsHide: true });

        let stdout = '';
        let stderr = '';

        commandProcess.stdout.on('data', (data) => (stdout += data.toString()));
        commandProcess.stderr.on('data', (data) => (stderr += data.toString()));

        commandProcess.on('error', (err) => {
            console.error(`Failed to spawn command: ${phpExecutable} ${commandArgs.join(' ')}`, err);
            reject(err);
        });

        commandProcess.on('close', (code) => {
            const stdoutStr = stdout.trim();
            const stderrStr = stderr.trim();
            if (stdoutStr) console.log(`Command '${args.join(' ')}' stdout:\n${stdoutStr}`);
            if (stderrStr) console.error(`Command '${args.join(' ')}' stderr:\n${stderrStr}`);
            
            if (code === 0) {
                console.log(`Command '${args.join(' ')}' completed successfully.`);
                resolve(stdoutStr);
            } else {
                const errorMessage = `Command '${args.join(' ')}' failed with exit code ${code}. Stderr: ${stderrStr}`;
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
            const serverArgs = [artisanScript, 'serve', `--port=${port}`];

            console.log(`Starting PHP server: ${phpExecutable} ${serverArgs.join(' ')} in ${artisanCwd}`);
            phpServerProcess = spawn(phpExecutable, serverArgs, { cwd: artisanCwd, env: phpEnv });

            phpServerProcess.on('error', (err) => {
                console.error('Failed to start PHP server process.', err);
                reject(err);
            });

            phpServerProcess.on('close', (code) => {
                if (code !== 0) {
                    const errorMessage = `PHP server process exited unexpectedly with code ${code}.`;
                    console.error(errorMessage);
                    if (!app.isQuitting) reject(new Error(errorMessage));
                }
            });

            const onServerOutput = (data) => {
                const message = data.toString();
                process.stdout.write(message);
                if (message.includes('Server running on')) {
                    console.log(`PHP server started successfully at ${serverUrl}`);
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
        icon: isDev ? path.join(__dirname, 'public', 'images', 'favicon.ico') : path.join(process.resourcesPath, 'app', 'public', 'images', 'favicon.ico'),
        webPreferences: {
            nodeIntegration: false,
            contextIsolation: true,
        },
    });

    mainWindow.once('ready-to-show', () => {
        mainWindow.show();
    });
    
    // --- ROBUST PDF DOWNLOAD HANDLER ---
    mainWindow.webContents.session.on('will-download', (event, item, webContents) => {
        // 1. Prevent Electron from automatically handling the download
        event.preventDefault();

        // 2. Prompt the user for a save location
        dialog.showSaveDialog({
            title: 'Save PDF',
            defaultPath: item.getFilename(),
            filters: [{ name: 'PDF Documents', extensions: ['pdf'] }]
        }).then(({ filePath, canceled }) => {
            // 3. If the user cancels the dialog, we must cancel the download
            if (canceled) {
                console.log('User cancelled the download.');
                item.cancel();
                return;
            }

            // 4. Set the final save path for the download item
            console.log('Setting save path to:', filePath);
            item.setSavePath(filePath);

            // 5. Listen for the 'done' event to give feedback to the user
            item.once('done', (e, state) => {
                if (state === 'completed') {
                    console.log('Download completed successfully.');
                    dialog.showMessageBox(mainWindow, { title: 'Download Complete', message: `File saved to ${filePath}` });
                } else {
                    console.error(`Download failed: ${state}`);
                    dialog.showErrorBox('Download Failed', `Failed to download file: ${state}`);
                }
            });
        }).catch(err => {
            console.error('Error in save dialog:', err);
        });
    });

    mainWindow.loadURL(serverUrl);
    if (isDev) mainWindow.webContents.openDevTools();
    mainWindow.on('closed', () => { mainWindow = null; });
}

// --- Application Lifecycle Events ---
app.whenReady().then(async () => {
    try {
        getPort = (await import('get-port')).default;
        const appInitializedFlagPath = path.join(app.getPath('userData'), '.initialized');
        
        if (!fs.existsSync(storagePath)) {
            console.log(`Creating writable storage directory at: ${storagePath}`);
            fs.mkdirSync(storagePath, { recursive: true });
            const originalStorage = isDev ? path.join(__dirname, 'storage') : path.join(process.resourcesPath, 'app', 'storage');
            fs.cpSync(originalStorage, storagePath, { recursive: true, filter: (src) => !path.basename(src).endsWith('.gitignore') });
        }
        
        if (!fs.existsSync(phpEnv.DB_DATABASE)) {
            console.log(`Creating SQLite database at: ${phpEnv.DB_DATABASE}`);
            fs.writeFileSync(phpEnv.DB_DATABASE, '');
        }

        if (!fs.existsSync(appInitializedFlagPath)) {
            console.log('Performing first-time application setup...');
            const envExamplePath = path.join(artisanCwd, '.env.example');
            const envFilePath = path.join(artisanCwd, '.env');
            if (fs.existsSync(envExamplePath) && !fs.existsSync(envFilePath)) {
                fs.copyFileSync(envExamplePath, envFilePath);
            }
            
            await runArtisanCommand(['migrate', '--force']);
            await runArtisanCommand(['key:generate', '--force']);
            await runArtisanCommand(['db:seed', '--force']);

            fs.writeFileSync(appInitializedFlagPath, 'true');
            console.log('Application setup complete.');
        } else {
            console.log('Application already initialized. Skipping setup.');
        }

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