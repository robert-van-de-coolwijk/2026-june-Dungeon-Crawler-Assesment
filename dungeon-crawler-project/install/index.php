<?php

/**
 * Setup Script - Executes install.sh with proper environment for Composer
 */

echo "<h3>Install script</h3><pre>";

echo "🚀 Starting setup via PHP...\n\n";

// Get the directory where this PHP script is located
$scriptDir = dirname(__FILE__);

// Path to install.sh
$installScript = $scriptDir . DIRECTORY_SEPARATOR . 'install.sh';

// Check if install.sh exists
if (!file_exists($installScript)) {
    echo "❌ Error: install.sh not found in the same folder!\n";
    echo "Expected location: " . $installScript . "\n";
    exit(1);
}

// Make sure it's executable
if (!is_executable($installScript)) {
    echo "⚠️  Making install.sh executable...\n";
    chmod($installScript, 0755);
}

// Fix for Composer: Set HOME or COMPOSER_HOME
$projectRoot = $scriptDir;  // Adjust if needed

$env = [
    'HOME'          => $projectRoot,           // Most reliable fix
    'COMPOSER_HOME' => $projectRoot . '/.composer',
];

// Ensure the .composer directory exists
if (!is_dir($env['COMPOSER_HOME'])) {
    mkdir($env['COMPOSER_HOME'], 0755, true);
}

echo "📂 Project root: $projectRoot\n";
echo "🏠 HOME set to: {$env['HOME']}\n";
echo "📦 COMPOSER_HOME set to: {$env['COMPOSER_HOME']}\n\n";

// Execute the shell script with proper environment
echo "📂 Executing install.sh...\n\n";

$returnCode = 0;
$output = [];

// Build environment string for bash
$envPrefix = '';
foreach ($env as $key => $value) {
    $envPrefix .= escapeshellarg($key) . '=' . escapeshellarg($value) . ' ';
}

$command = $envPrefix . "bash " . escapeshellarg($installScript);

exec($command . " 2>&1", $output, $returnCode);

// Print output from the shell script
foreach ($output as $line) {
    echo $line . "\n";
}

echo "\n";

if ($returnCode === 0) {
    echo "✅ Setup completed successfully!\n";
} else {
    echo "❌ Setup failed with exit code: $returnCode\n";
}

echo "</pre>";