const { app, BrowserWindow, ipcMain, Tray, Notification, Menu, desktopCapturer, shell, powerMonitor, dialog } = require('electron');
// const { GlobalKeyboardListener } = require('node-global-key-listener');
const { uIOhook, UiohookKey } = require('uiohook-napi');
const path = require('path');
const fs = require('fs');
const dns = require('dns');
const sharp = require('sharp');

let fetch;
let tray = null;
let mainWindow = null;
let settingsWindow = null;
let isTracking = false;
let isLoggedIn = false;
let logIntervalId = null;
let sendLogsIntervalId = null;
let userStartMinute = null;
let keyboardActivity = 0;
let mouseActivity = 0;
let startTime = null;
let userStartTime = null;
let jobWorkDeliveryHourlyBidId = null;
let token = null; // Global token for resume functionality
let globalKeyboardListener = null;
let isSystemSleeping = false;
let wasTrackingBeforeSleep = false;

const IDLE_THRESHOLD = 600;

const userDataPath = app.getPath('userData');
const logBuildFilePath = path.join(userDataPath, 'startup.log');

console.log(process.env.NODE_ENV);
let API_URL = "https://titanlance.com/api";
// if (process.env.NODE_ENV === 'development')
//     API_URL = "http://wiser2.test/api";

console.log('API URL:', API_URL);

// Single-instance lock
const gotTheLock = app.requestSingleInstanceLock();

if (!gotTheLock) {
    app.quit();
} else {
    app.on('second-instance', (event, argv) => {
        if (process.platform === 'win32' && argv.length > 1) {
            const protocolUrl = argv.find(arg => arg.startsWith('wiserlance://'));
            if (protocolUrl) handleCustomProtocol(protocolUrl);
        }
        if (mainWindow) {
            if (mainWindow.isMinimized()) mainWindow.restore();
            mainWindow.focus();
            mainWindow.show();
        }
        showNotification('Application Already Running', 'The application is already open.');
    });

    app.whenReady().then(async () => {
        app.setAsDefaultProtocolClient('wiserlance');
        await initFetch();
        createWindow();
        createTray();
        initializeKeyboardListener();
        setupPowerMonitor();

        if (process.platform === 'win32') {
            const protocolUrl = process.argv.find(arg => arg.startsWith('wiserlance://'));
            if (protocolUrl) handleCustomProtocol(protocolUrl);
        }
    });
}

function handleCustomProtocol(url) {
    console.log('Custom protocol invoked with URL:', url);
    if (mainWindow) {
        mainWindow.show();
        mainWindow.focus();
        mainWindow.webContents.send('protocol-invoked', url);
    }
}

const logFilePath = path.join(app.getPath('userData'), 'loggedMinutes.json');
let loggedMinutes = new Set();
const activityLog = [];
let notificationsEnabled = true;

async function initFetch() {
    fetch = (await import('node-fetch')).default;
}

function readLoggedMinutes() {
    try {
        const data = fs.readFileSync(logFilePath, 'utf8');
        return JSON.parse(data);
    } catch (err) {
        return [];
    }
}

function writeLoggedMinutes(data) {
    try {
        fs.writeFileSync(logFilePath, JSON.stringify(data, null, 2));
        console.log('Logged to file:', JSON.stringify(data, null, 2));
    } catch (err) {
        console.error('Error writing to log file:', err.message);
    }
}

function showNotification(title, body) {
    if (notificationsEnabled) {
        new Notification({ title, body }).show();
    }
}

function createWindow() {
    mainWindow = new BrowserWindow({
        width: 400,
        height: 600,
        maximizable: false, // Disable maximize button
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            contextIsolation: true,
            enableRemoteModule: false,
            nodeIntegration: false,
        },
        autoHideMenuBar: true,
        show: false,
    });

    mainWindow.loadFile('src/views/login.html');

    mainWindow.once('ready-to-show', () => {
        mainWindow.show();
    });

    ipcMain.on('get-activity', (event, arg) => {
        event.reply('activity-data', { activityLevel: arg });
    });

    mainWindow.on('close', (event) => {
        if (app.isQuiting) return;
        event.preventDefault();
        mainWindow.hide();
        if (!tray) createTray();
        showNotification('App Minimized', 'Time Tracker is running in the background. Click the tray icon to open.');
    });

    mainWindow.on('minimize', (event) => {
        event.preventDefault();
        mainWindow.hide();
        if (!tray) createTray();
    });
}

function createTray() {
    const trayIconPath = getAssetPath("trayIcon.png");
    tray = new Tray(trayIconPath);

    tray.setToolTip('Time Tracker - Running in background');
    tray.on('click', () => {
        if (mainWindow.isVisible()) {
            mainWindow.hide();
        } else {
            mainWindow.show();
            mainWindow.focus();
        }
    });

    updateTrayMenu();
}

function updateTrayMenu() {
    if (!tray) return;

    const contextMenu = Menu.buildFromTemplate([
        {
            label: 'Show Window',
            click: () => {
                if (mainWindow) {
                    mainWindow.show();
                    mainWindow.focus();
                }
            },
        },
        {
            label: isTracking ? '✓ Tracking Active' : '⏸ Not Tracking',
            enabled: false,
        },
        { type: 'separator' },
        {
            label: 'Pause Tracking',
            visible: isTracking,
            click: () => pauseTracking(),
        },
        {
            label: 'Resume Tracking',
            visible: !isTracking && isLoggedIn && !!jobWorkDeliveryHourlyBidId,
            click: () => resumeTracking(),
        },
        { type: 'separator' },
        {
            label: isTracking ? 'Cannot Quit While Tracking' : 'Quit Application',
            enabled: !isTracking,
            click: () => {
                if (isTracking) {
                    showNotification('Cannot Quit', 'Please stop time tracking before quitting.');
                    return;
                }
                app.isQuiting = true;
                cleanup();
                app.quit();
            },
        },
    ]);

    tray.setContextMenu(contextMenu);
    tray.setToolTip(`Time Tracker - ${isTracking ? 'Tracking' : 'Idle'}`);
}

function setupPowerMonitor() {
    powerMonitor.on('suspend', () => {
        console.log('System is going to sleep...');
        isSystemSleeping = true;
        wasTrackingBeforeSleep = isTracking;
        if (isTracking) pauseTracking();
        showNotification('System Sleep', 'Time tracker paused due to system sleep');
    });

    powerMonitor.on('resume', () => {
        console.log('System has resumed from sleep');
        isSystemSleeping = false;
        setTimeout(() => {
            if (wasTrackingBeforeSleep && !isTracking) {
                resumeTracking();
                showNotification('System Resumed', 'Time tracker resumed after system wake');
            }
        }, 2000);
    });

    powerMonitor.on('lock-screen', () => {
        console.log('Screen locked');
        showNotification('Screen Locked', 'Time tracker continues running');
    });

    powerMonitor.on('unlock-screen', () => {
        console.log('Screen unlocked');
        showNotification('Screen Unlocked', 'Welcome back! Time tracker is running');
    });

    powerMonitor.on('shutdown', (event) => {
        console.log('System shutting down');
        event.preventDefault();
        if (isTracking) {
            try { logMinuteActivityToFile(jobWorkDeliveryHourlyBidId); } catch (e) { }
        }
        cleanup();
        app.quit();
    });
}

function pauseTracking() {
    if (!isTracking) return;
    console.log('Pausing tracking...');
    isTracking = false;

    if (logIntervalId) { clearInterval(logIntervalId); logIntervalId = null; }
    if (sendLogsIntervalId) { clearInterval(sendLogsIntervalId); sendLogsIntervalId = null; }

    updateTrayMenu();
    if (mainWindow && mainWindow.webContents) {
        mainWindow.webContents.send('tracking-paused');
    }
}

function resumeTracking() {
    if (isTracking || !jobWorkDeliveryHourlyBidId || !token) return;
    console.log('Resuming tracking...');
    isTracking = true;
    startActivityLogging(token, jobWorkDeliveryHourlyBidId);
    updateTrayMenu();
    if (mainWindow && mainWindow.webContents) {
        mainWindow.webContents.send('tracking-resumed');
    }
}

function cleanup() {
    console.log('Cleaning up...');
    if (logIntervalId) clearInterval(logIntervalId);
    if (sendLogsIntervalId) clearInterval(sendLogsIntervalId);

    try { uIOhook.stop(); } catch (e) { }

    if (tray) { tray.destroy(); tray = null; }
}

// function cleanup() {
//     console.log('Cleaning up...');
//     if (logIntervalId) clearInterval(logIntervalId);
//     if (sendLogsIntervalId) clearInterval(sendLogsIntervalId);

//     if (globalKeyboardListener) {
//         try { globalKeyboardListener.stopListening(); } catch (e) {
//             console.error('Error stopping keyboard listener:', e);
//         }
//     }

//     if (tray) { tray.destroy(); tray = null; }
// }

// IPC Handlers
ipcMain.handle('set-start-time', (event, time) => {
    startTime = new Date(time);
    console.log(`Start time set to: ${startTime}`);
    return { success: true };
});

ipcMain.handle('get-start-time', () => {
    if (!startTime) return { success: false, message: 'Start time is not set' };
    return { success: true, startTime };
});

ipcMain.on('open-timesheet-url', (event, timesheetUrl) => {
    if (timesheetUrl) shell.openExternal(timesheetUrl);
    else console.error('No timesheet URL available');
});

ipcMain.on('open-chat-url', (event, url) => {
    shell.openExternal(url);
});

app.on('window-all-closed', (event) => {
    if (app.isQuiting) return;
    event.preventDefault();
    console.log('All windows closed, keeping app running in background');
});

app.on('activate', () => {
    if (mainWindow) mainWindow.show();
    else createWindow();
});

app.on('before-quit', (event) => {
    if (!app.isQuiting) {
        event.preventDefault();
        return;
    }
    console.log('App is quitting, performing cleanup...');
    cleanup();
});

function createSettingsWindow() {
    if (!settingsWindow) {
        settingsWindow = new BrowserWindow({
            width: 300,
            height: 300,
            frame: false,
            resizable: false,
            parent: mainWindow,
            modal: true,
            webPreferences: {
                preload: path.join(__dirname, 'preload.js'),
                contextIsolation: true,
                enableRemoteModule: false,
            },
        });

        settingsWindow.loadFile('src/views/settings-window.html');

        ipcMain.on('close-settings-window', () => {
            if (settingsWindow) settingsWindow.close();
        });

        settingsWindow.on('closed', () => {
            settingsWindow = null;
        });
    }
}

ipcMain.on('open-settings-window', createSettingsWindow);
ipcMain.on('toggle-notifications', (event, value) => { notificationsEnabled = value; });

ipcMain.on('logout', () => {
    console.log('Logout requested');

    if (isTracking) {
        if (logIntervalId) clearInterval(logIntervalId);
        if (sendLogsIntervalId) clearInterval(sendLogsIntervalId);
    }

    isLoggedIn = false;
    isTracking = false;
    token = null;
    jobWorkDeliveryHourlyBidId = null;
    activityLog.length = 0;
    loggedMinutes.clear();

    if (settingsWindow) settingsWindow.close();

    if (mainWindow) {
        mainWindow.webContents.executeJavaScript('localStorage.clear()');
        mainWindow.loadFile('src/views/login.html');
    }

    updateTrayMenu();
});

// ============================================================
// FIXED: Keyboard and Mouse Activity Tracking
// Fixes:
// 1. Fixed typo "VL_RBUTTON" -> "VK_RBUTTON"
// 2. Added middle mouse button (VK_MBUTTON)
// 3. Mouse only counted on DOWN state (no double counting)
// 4. Removed broken before-input-event mouse listener
// ============================================================
// function initializeKeyboardListener() {
//     try {
//         globalKeyboardListener = new GlobalKeyboardListener();

//         globalKeyboardListener.addListener((e) => {
//             if (!isTracking) return;

//             if (e.rawKey && e.rawKey._nameRaw) {
//                 const keyName = e.rawKey._nameRaw;

//                 if (
//                     keyName === "VK_LBUTTON" ||
//                     keyName === "VK_RBUTTON" ||  // Fixed: was VL_RBUTTON (typo)
//                     keyName === "VK_MBUTTON"     // Added: middle mouse button
//                 ) {
//                     if (e.state === "DOWN") {
//                         mouseActivity++;
//                         console.log(`Mouse click [${keyName}] | Total: ${mouseActivity}`);
//                     }
//                 } else {
//                     if (e.state === "DOWN") {
//                         keyboardActivity++;
//                         console.log(`Key press [${keyName}] | Total: ${keyboardActivity}`);
//                     }
//                 }
//             }
//         });

//         console.log('Keyboard/mouse listener initialized successfully');
//     } catch (error) {
//         console.error('Error initializing keyboard listener:', error);
//     }
// }

function initializeKeyboardListener() {
    try {
        uIOhook.on('keydown', (e) => {
            if (!isTracking) return;
            keyboardActivity++;
            console.log(`Key press | Total: ${keyboardActivity}`);
        });

        uIOhook.on('mousedown', (e) => {
            if (!isTracking) return;
            mouseActivity++;
            console.log(`Mouse click | Total: ${mouseActivity}`);
        });

        uIOhook.start();
        console.log('Keyboard/mouse listener initialized successfully');
    } catch (error) {
        console.error('Error initializing keyboard listener:', error);
    }
}


// Login
ipcMain.on('login', async (event, credentials) => {
    try {
        const response = await fetch(`${API_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(credentials),
        });

        if (!response.ok) throw new Error(await response.text() || 'Invalid login credentials');

        const data = await response.json();
        token = data.data.access_token; // Store globally
        isLoggedIn = true;

        updateTrayMenu();
        event.sender.send('login-success', token);
        mainWindow.loadFile('src/views/jobs.html');
    } catch (error) {
        console.error('Login error:', error.message);
        event.sender.send('login-error', error.message);
    }
});

ipcMain.on('fetch-jobs', async (event, tokenParam) => {
    try {
        const response = await fetch(`${API_URL}/user/jobs`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${tokenParam}`,
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) throw new Error(await response.text() || 'Error fetching jobs');
        const data = await response.json();
        event.sender.send('jobs-success', data);
    } catch (error) {
        event.sender.send('jobs-error', error.message);
    }
});

ipcMain.on('fetch-job-details', async (event, jobId, tokenParam) => {
    try {
        const response = await fetch(`${API_URL}/user/jobs/${jobId}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${tokenParam}`,
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) throw new Error(await response.text() || 'Error fetching job details');
        const data = await response.json();
        event.sender.send('job-details-success', data);
    } catch (error) {
        event.sender.send('job-details-error', error.message);
    }
});

ipcMain.handle('start-tracking', async (event, tokenParam, jobId) => {
    try {
        if (!tokenParam) throw new Error('Missing token');

        token = tokenParam; // Store globally
        isTracking = true;

        console.log('Starting tracking for job:', jobId);

        const response = await fetch(`${API_URL}/user/start-tracking`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${tokenParam}`,
            },
            body: JSON.stringify({ job_id: jobId, timezone: 'local' }),
        });

        if (!response.ok) {
            const errorData = await response.json();
            if (response.status === 409) {
                console.error('Conflict error:', errorData);
                isTracking = false;
                updateTrayMenu();
                return { success: false, error: errorData.message };
            }
            throw new Error(errorData.message || 'Failed to start tracking');
        }

        const data = await response.json();
        jobWorkDeliveryHourlyBidId = data.data.job_work_delivery_hourly_bid_id;
        console.log('Started Tracking. Hourly Bid ID:', jobWorkDeliveryHourlyBidId);

        startActivityLogging(tokenParam, jobWorkDeliveryHourlyBidId);
        updateTrayMenu();
        showNotification('Tracking Started', 'Time tracking is now active in the background');

        return { success: true, job_work_delivery_hourly_bid_id: jobWorkDeliveryHourlyBidId };
    } catch (error) {
        console.error('Error starting tracking:', error);
        isTracking = false;
        updateTrayMenu();
        return { success: false, error: error.message };
    }
});

ipcMain.handle('stop-tracking', async (event, tokenParam, jobId, elapsedTime, memoInput) => {
    isTracking = false;
    console.log('stop tracking', jobId);

    if (logIntervalId) clearInterval(logIntervalId);
    if (sendLogsIntervalId) clearInterval(sendLogsIntervalId);

    updateTrayMenu();

    try {
        const requestBody = {
            job_work_delivery_hourly_bid_id: jobId,
            elapsed_time: elapsedTime,
            activity: activityLog,
            memoInput: memoInput,
        };
        console.log(requestBody);

        const response = await fetch(`${API_URL}/user/stop-tracking`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${tokenParam}`,
            },
            body: JSON.stringify(requestBody),
        });

        if (!response.ok) throw new Error(await response.text() || 'Failed to stop tracking');

        activityLog.splice(0);
        loggedMinutes = new Set();
        writeLoggedMinutes([]);
        jobWorkDeliveryHourlyBidId = null;

        showNotification('Tracking Stopped', 'Time tracking has been stopped');

        return { success: true, data: await response.json() };
    } catch (error) {
        console.error('Error stopping tracking:', error);
        return { success: false, error: error.message };
    }
});

function startActivityLogging(tokenParam, jobWorkDeliveryHourlyBidId) {
    if (logIntervalId) clearInterval(logIntervalId);
    if (sendLogsIntervalId) clearInterval(sendLogsIntervalId);

    console.log('Starting activity logging for job:', jobWorkDeliveryHourlyBidId);

    const now = new Date();
    userStartTime = now;
    userStartMinute = now.getMinutes();

    const minutes = now.getMinutes();
    const seconds = now.getSeconds();
    const milliseconds = now.getMilliseconds();

    const remainder = minutes % 10;
    const minutesToNextBoundary = remainder === 0 ? 10 : (10 - remainder);

    const boundaryDate = new Date(now);
    boundaryDate.setMinutes(minutes + minutesToNextBoundary);
    boundaryDate.setSeconds(seconds);
    boundaryDate.setMilliseconds(milliseconds);

    const delayToNext10MinuteMark = boundaryDate.getTime() - now.getTime();
    console.log('Delay to next 10-minute mark:', delayToNext10MinuteMark,
        '| boundaryDate:', boundaryDate.toLocaleTimeString());

    // Log every minute
    logIntervalId = setInterval(() => {
        if (!isTracking) return;
        logMinuteActivityToFile(jobWorkDeliveryHourlyBidId);
    }, 60 * 1000);

    // Send logs at every 10-minute boundary
    setTimeout(() => {
        if (!isTracking) return;

        setTimeout(() => {
            handleBoundaryLog(tokenParam, jobWorkDeliveryHourlyBidId);

            sendLogsIntervalId = setInterval(() => {
                if (!isTracking) return;
                handleBoundaryLog(tokenParam, jobWorkDeliveryHourlyBidId);
            }, 10 * 60 * 1000);

        }, 2000);

    }, delayToNext10MinuteMark);
}

function handleBoundaryLog(tokenParam, jobWorkDeliveryHourlyBidId) {
    const now = new Date();
    const currentMinute = now.getMinutes();

    logMinuteActivityToFile(jobWorkDeliveryHourlyBidId);
    console.log('Now:', now.toString(), '| currentMinute:', currentMinute);

    const remainder = currentMinute % 10;
    const timeToNextBoundary = remainder === 0 ? 0 : (10 - remainder);
    console.log('timeToNextBoundary:', timeToNextBoundary);

    if (timeToNextBoundary === 0) {
        captureScreenshotAndLogs(tokenParam, jobWorkDeliveryHourlyBidId, true);
    } else if (timeToNextBoundary <= 1) {
        captureScreenshotAndLogs(tokenParam, jobWorkDeliveryHourlyBidId, false);
    }
}

async function logMinuteActivityToFile(jobWorkDeliveryHourlyBidId) {
    if (!isTracking) {
        console.log('Skipping log as tracking is inactive.');
        return;
    }

    try {
        const now = new Date();
        const currentMinute = now.getMinutes();

        if (!mainWindow || !mainWindow.webContents) {
            console.log('MainWindow not available, skipping log');
            return;
        }

        const response = await mainWindow.webContents.executeJavaScript("window.api.getStartTime()");
        if (!response.success) {
            console.error(response.message);
            return;
        }

        const startTime = new Date(response.startTime);
        if (!startTime || now <= startTime) {
            console.log(`Skipping log for job start minute: ${currentMinute}`);
            return;
        }

        if (loggedMinutes.has(currentMinute)) {
            console.log(`Skipping already logged minute: ${currentMinute}`);
            return;
        }

        loggedMinutes.add(currentMinute);

        const existingLogs = readLoggedMinutes();
        existingLogs.push({
            job_work_delivery_hourly_bid_id: jobWorkDeliveryHourlyBidId,
            time: now.toISOString(),
            keyboardActivity: keyboardActivity || 0,
            mouseActivity: mouseActivity || 0,
        });

        keyboardActivity = 0;
        mouseActivity = 0;

        writeLoggedMinutes(existingLogs);

    } catch (error) {
        console.error('Error logging activity:', error.message);
    }
}

async function captureScreenshotAndLogs(tokenParam, jobWorkDeliveryHourlyBidId, shouldCaptureScreenshot) {
    if (!isTracking) {
        console.warn('Tracking not active. Skipping screenshot/logs.');
        return;
    }

    try {
        const logsFromFile = readLoggedMinutes();

        if (shouldCaptureScreenshot) {
            console.log('Capturing screenshot...');

            const sources = await desktopCapturer.getSources({
                types: ['screen'],
                thumbnailSize: { width: 1920, height: 1080 },
            });

            if (sources.length === 0) throw new Error('No screen sources available');

            const screenshotBuffer = Buffer.from(sources[0].thumbnail.toPNG());
            const compressedImage = await sharp(screenshotBuffer)
                .resize(920, 470)
                .jpeg({ quality: 70 })
                .toBuffer();

            const screenshotBase64 = compressedImage.toString('base64');
            const screenshotDataURL = `data:image/jpeg;base64,${screenshotBase64}`;

            if (logsFromFile.length > 0) {
                const lastLogIndex = logsFromFile.length - 1;
                const lastLogTime = new Date(logsFromFile[lastLogIndex].time);
                const firstLogTime = new Date(logsFromFile[0].time);
                const elapsedTime = Math.floor((lastLogTime - firstLogTime) / 1000);

                logsFromFile[lastLogIndex] = {
                    ...logsFromFile[lastLogIndex],
                    screenshot: screenshotDataURL,
                    elapsed_time: elapsedTime,
                };

                writeLoggedMinutes(logsFromFile);
            }

            if (mainWindow && mainWindow.webContents) {
                mainWindow.webContents.send('screenshot-captured', {
                    image: screenshotDataURL,
                    timestamp: new Date().toISOString(),
                });
            }
        } else {
            console.log('Sending logs without screenshot.');
        }

        const isOnline = await checkInternetConnectivity();
        if (!isOnline) {
            showNotification('Internet Disconnected', 'Please reconnect to sync your work.');
            console.error('No internet. Storing locally.');
            return;
        }

        console.log('Sending logs:', logsFromFile);

        const formData = new FormData();
        formData.append('job_work_delivery_hourly_bid_id', jobWorkDeliveryHourlyBidId);
        formData.append('logs', JSON.stringify(logsFromFile));

        const response = await fetch(`${API_URL}/user/store-screenshot`, {
            method: 'POST',
            headers: { Authorization: `Bearer ${tokenParam}` },
            body: formData,
        });

        if (!response.ok) throw new Error(`Failed to store data. Status: ${response.status}`);

        const data = await response.json();
        console.log('Data sent successfully:', data);

        if (data.job_work_delivery_hourly_bid_id &&
            data.job_work_delivery_hourly_bid_id !== jobWorkDeliveryHourlyBidId) {
            jobWorkDeliveryHourlyBidId = data.job_work_delivery_hourly_bid_id;
            console.log('Updated jobWorkDeliveryHourlyBidId:', jobWorkDeliveryHourlyBidId);
        }

        loggedMinutes.clear();
        writeLoggedMinutes([]);

        // The server already finalized this session (status, end_time, memo) once the
        // weekly allowed hours were reached. Stop polling locally and tell the renderer
        // so it can update the UI instead of continuing to track unattended.
        if (data.limit_reached) {
            console.log('Weekly limit reached. Auto-stopping tracker.');
            isTracking = false;

            if (logIntervalId) { clearInterval(logIntervalId); logIntervalId = null; }
            if (sendLogsIntervalId) { clearInterval(sendLogsIntervalId); sendLogsIntervalId = null; }

            updateTrayMenu();
            showNotification('Tracking Stopped', 'Maximum limit reached. Tracker turned off automatically by the system.');

            if (mainWindow && mainWindow.webContents) {
                mainWindow.webContents.send('limit-reached', {
                    message: 'Maximum limit reached. Tracker turned off automatically by the system.',
                });
            }
        }

    } catch (error) {
        console.error('Error capturing/storing logs:', error.message);
    }
}

async function checkInternetConnectivity() {
    return new Promise((resolve) => {
        dns.lookup('google.com', (err) => {
            resolve(!(err && err.code === 'ENOTFOUND'));
        });
    });
}

function getAssetPath(...paths) {
    if (app.isPackaged) {
        return path.join(process.resourcesPath, ...paths);
    } else {
        return path.join(__dirname, '..', ...paths);
    }
}
