#!/bin/bash

set -euo pipefail

# =============================================
# Go to project root (where composer.json lives)
# =============================================
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

cd "$PROJECT_ROOT" || { echo "❌ Failed to cd to project root"; exit 1; }

echo "📂 Project root: $PROJECT_ROOT"

# =============================================
# 1. Check PHP
# =============================================
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed or not in PATH"
    exit 1
fi

echo "✅ PHP is installed"
php --version

# =============================================
# 2. Check / Install Composer
# =============================================
if command -v composer &> /dev/null; then
    echo "✅ Composer is already installed globally"
    COMPOSER_CMD="composer"

elif [[ -f "composer.phar" ]]; then
    echo "✅ Using existing local composer.phar"
    COMPOSER_CMD="php composer.phar"

else
    echo "⚠️  Composer not found. Installing locally..."

    # Download installer
    php -r "
        copy('https://getcomposer.org/installer', 'composer-setup.php');
    "

    # Run installer quietly
    php composer-setup.php --quiet
    rm composer-setup.php

    if [[ -f "composer.phar" ]]; then
        chmod +x composer.phar
        echo "✅ Composer installed successfully as composer.phar"
        COMPOSER_CMD="php composer.phar"
    else
        echo "❌ Failed to install Composer" >&2
        exit 1
    fi
fi

# =============================================
# 3. Run Composer
# =============================================
echo "🚀 Running Composer in $PROJECT_ROOT"

if [[ ! -f "composer.json" ]]; then
    echo "❌ No composer.json found in project root!"
    exit 1
fi

# Run 'install' by default, or accept user arguments
if [[ $# -gt 0 ]]; then
    echo "📦 Running: $COMPOSER_CMD $*"
    $COMPOSER_CMD "$@"
else
    echo "📦 Installing/updating dependencies..."
    $COMPOSER_CMD install --no-interaction --prefer-dist --optimize-autoloader
    echo "✅ Dependencies installed successfully!"
fi