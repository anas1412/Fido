require('dotenv').config();
// main.cjs
const { app, BrowserWindow, dialog, ipcMain, net } = require('electron');
const path = require('path');
const { spawn, spawnSync } = require('child_process');
const fs = require('fs');

function expandAppDataPath(dbPathString) {
    if (process.platform === 'win32' && dbPathString.startsWith('%APPDATA%')) {
        return path.join(process.env.APPDATA, dbPathString.substring('%APPDATA%'.length));
    }
    return dbPathString;
}


// --- Global Variables, Paths, Env, and Helper Functions are all correct ---
let mainWindow; let phpServerProcess; let getPort;
const isDev = !app.isPackaged;
const artisanCwd = isDev ? __dirname : path.join(process.resourcesPath, 'app');
const storagePath = isDev ? path.join(__dirname, 'storage') : path.join(app.getPath('userData'), 'storage');
const phpExecutable = isDev ? path.join(__dirname, 'php', 'php.exe') : path.join(process.resourcesPath, 'app', 'php', 'php.exe');
const artisanScript = path.join(artisanCwd, 'artisan');
const dbPath = path.join(storagePath, 'database.sqlite');

// Ensure the directory for the database exists before starting the server.
if (!fs.existsSync(path.dirname(dbPath))) {
    fs.mkdirSync(path.dirname(dbPath), { recursive: true });
}


function runArtisanCommand(args) { /* ... same as your file ... */ }
function startPhpServer() { /* ... same as your file ... */ }


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
            preload: path.join(__dirname, 'preload.js')
        },
    });

    // We no longer need the will-download handler here.
    // The entire logic is now in the ipcMain handler below.

    mainWindow.once('ready-to-show', () => {
        mainWindow.show();
    });

    mainWindow.loadURL(serverUrl);
    if (isDev) mainWindow.webContents.openDevTools();
    mainWindow.on('closed', () => { mainWindow = null; });
}

// --- THIS IS THE NEW, DEFINITIVE DOWNLOAD HANDLER ---
ipcMain.on('download-pdf', (event, url) => {
    console.log(`Main process received download request for: ${url}`);
    
    // 1. Get the filename from the URL. A more robust regex could be used if needed.
    const urlParts = url.split('/');
    const potentialFilename = urlParts[urlParts.length - 1] + '.pdf'; // Guess a filename

    // 2. Use the synchronous dialog to get a save path from the user *before* we download.
    const filePath = dialog.showSaveDialogSync(mainWindow, {
        title: 'Save PDF',
        defaultPath: `Honoraire_${potentialFilename}`,
        filters: [{ name: 'PDF Documents', extensions: ['pdf'] }]
    });

    // 3. If the user cancelled, do nothing.
    if (!filePath) {
        console.log('User cancelled the save dialog.');
        return;
    }

    // 4. Perform the download entirely in the main process.
    const request = net.request(url);
    
    request.on('response', (response) => {
        if (response.statusCode === 200) {
            const fileStream = fs.createWriteStream(filePath);
            
            response.on('data', (chunk) => {
                fileStream.write(chunk);
            });

            response.on('end', () => {
                fileStream.end();
                console.log('Download completed successfully.');
                dialog.showMessageBox(mainWindow, { title: 'Download Complete', message: `File saved to ${filePath}` });
            });

            response.on('error', (err) => {
                console.error('Error during download response:', err);
                dialog.showErrorBox('Download Failed', 'An error occurred while downloading the file.');
            });
        } else {
            console.error(`Server responded with status: ${response.statusCode}`);
            dialog.showErrorBox('Download Failed', `The server responded with an error: ${response.statusCode}`);
        }
    });

    request.on('error', (err) => {
        console.error('Error making download request:', err);
        dialog.showErrorBox('Download Failed', 'An error occurred while trying to connect to the server.');
    });

    request.end();
});

// --- Your app.whenReady() and other lifecycle events are all correct ---
// --- They do not need to be changed ---

// --- PASTE IN ALL YOUR OTHER FUNCTIONS AND LIFECYCLE EVENTS HERE ---
// For brevity, I'll add them back in below

// --- Helper function to run Artisan commands ---
function runArtisanCommand(args) {
    const commandArgs = [artisanScript, ...args];
    return new Promise((resolve, reject) => {
        // Create a new env object for the Artisan command, explicitly setting DB_DATABASE
        const artisanEnv = {
            ...process.env, // Inherit existing process environment variables
            DB_DATABASE: expandAppDataPath(process.env.DB_DATABASE), // Expand %APPDATA% in DB_DATABASE
            APP_STORAGE_PATH: storagePath // Also ensure storage path is set
        };

        const commandProcess = spawn(phpExecutable, commandArgs, { cwd: artisanCwd, env: artisanEnv, windowsHide: true });
        let stdout = '', stderr = '';
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
            console.log('DEBUG: process.env.DB_DATABASE before expandAppDataPath:', process.env.DB_DATABASE);
            console.log('DEBUG: process.env.APPDATA before expandAppDataPath:', process.env.APPDATA);
            const phpEnv = {
                ...process.env,
                DB_DATABASE: expandAppDataPath(process.env.DB_DATABASE),
                APP_STORAGE_PATH: storagePath
            };
            phpServerProcess = spawn(phpExecutable, serverArgs, { cwd: artisanCwd, env: phpEnv });
            phpServerProcess.on('error', (err) => {
                console.error('Failed to start PHP server process.', err);
                reject(err);
            });
            phpServerProcess.on('close', (code) => {
                if (code !== 0 && !app.isQuitting) {
                    const errorMessage = `PHP server process exited unexpectedly with code ${code}.`;
                    console.error(errorMessage);
                    reject(new Error(errorMessage));
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
// --- Application Lifecycle Events ---
app.whenReady().then(async () => {
    try {
        getPort = (await import('get-port')).default;

        

        // 1. Define all necessary paths
        const dbDir = path.join(app.getPath('userData'), 'storage');
        const dbPath = path.join(dbDir, 'database.sqlite');
        const envFilePath = path.join(artisanCwd, '.env');
        const envExamplePath = path.join(artisanCwd, '.env.example');

        // 2. Ensure the database directory exists
        if (!fs.existsSync(dbDir)) {
            fs.mkdirSync(dbDir, { recursive: true });
        }

        

        

        


        

        

        // 3. Prepare the .env file
        if (!fs.existsSync(envFilePath)) {
            if (fs.existsSync(envExamplePath)) {
                fs.copyFileSync(envExamplePath, envFilePath);
            } else {
                fs.writeFileSync(envFilePath, '');
            }
        }

        let envContent = fs.readFileSync(envFilePath, 'utf8');
        let lines = envContent.split(/\r?\n/);
        lines = lines.filter(line => 
            !line.startsWith('DB_CONNECTION=') && 
            !line.startsWith('DB_DATABASE=')
        );
        const dbPathForEnv = dbPath.replace(/\\/g, '/');
        lines.push('DB_CONNECTION=sqlite');
        lines.push(`DB_DATABASE=${dbPathForEnv}`);
        fs.writeFileSync(envFilePath, lines.join('\n'));
        console.log(`Ensured ${envFilePath} is configured for AppData database.`);


        // 4. Check if the database itself needs to be created
        if (!fs.existsSync(dbPath)) {
            console.log('Database not found. Running first-time setup...');
            // Create the blank database file
            fs.writeFileSync(dbPath, '');

            // Now that the database file exists, run key:generate, migrate, and seed
            await runArtisanCommand(['key:generate', '--force']);
            await runArtisanCommand(['migrate', '--force']);
            await runArtisanCommand(['db:seed', '--force']);
            console.log('Database created and migrated successfully.');
        } else {
            console.log('Existing database found.');
        }

        // 5. Start the application
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