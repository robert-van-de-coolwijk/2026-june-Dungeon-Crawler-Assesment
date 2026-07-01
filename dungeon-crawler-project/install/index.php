<?php

/**
 * Setup Script - Executes install.sh from the same folder
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

// Check if it's executable
if (!is_executable($installScript)) {
    echo "⚠️  Making install.sh executable...\n";
    chmod($installScript, 0755);
}

// Execute the shell script
echo "📂 Executing install.sh...\n\n";

$returnCode = 0;
$output = [];
$lastLine = exec("bash " . escapeshellarg($installScript) . " 2>&1", $output, $returnCode);

// Print output from the shell script
foreach ($output as $line) {
    echo $line . "\n";
}

echo "\n";

if ($returnCode === 0) {
    echo "✅ Setup completed successfully!\n";
} else {
    echo "❌ Setup failed with exit code: $returnCode\n";
    exit($returnCode);
}