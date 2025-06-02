const fs = require('fs');
const { execSync } = require('child_process');
const path = require('path');

// Directories to run commands in
const serviceDirs = [
    path.resolve(__dirname, 'app-service-laravel'),
    path.resolve(__dirname, 'customs-api'),
];

// Commands to run in each service folder
const artisanCommands = [
    'php artisan config:clear',
    'php artisan cache:clear',
    'php artisan view:clear',
    'php artisan route:clear',
];

// Function to run commands in a given folder
function runCommandsInFolder(folderPath) {
    console.log(`Entering folder: ${folderPath}`);
    try {
        artisanCommands.forEach((command) => {
            console.log(`Running: ${command}`);
            execSync(command, { cwd: folderPath, stdio: 'inherit' });
        });
        console.log(`Finished in: ${folderPath}`);
    } catch (error) {
        console.error(`Error in folder ${folderPath}:`, error.message);
    }
    console.log('-------------------------------');
}

serviceDirs.forEach((servicePath) => {
    if (fs.existsSync(servicePath) && fs.statSync(servicePath).isDirectory()) {
        runCommandsInFolder(servicePath);
    } else {
        console.warn(`Directory not found: ${servicePath}`);
    }
});
