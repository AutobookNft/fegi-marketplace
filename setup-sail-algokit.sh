#!/bin/bash

# 🎯 FlorenceEGI Laravel Sail + AlgoKit Setup Script
# Autore: Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
# Versione: 1.0.0 (Laravel Sail + AlgoKit Integration)
# Data: 2025-07-08
# Purpose: Setup automatico microservice AlgoKit per Laravel Sail

set -e  # Exit on any error

echo "🚀 Setting up FlorenceEGI Laravel Sail + AlgoKit Integration..."
echo "================================================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# ========================================
# STEP 1: Verifica Prerequisiti
# ========================================

print_info "Checking prerequisites..."

# Verifica Docker
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

# Verifica Docker Compose
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Verifica che siamo in un progetto Laravel
if [ ! -f "artisan" ]; then
    print_error "This script must be run from a Laravel project root directory."
    exit 1
fi

# Verifica Sail
if [ ! -f "vendor/laravel/sail/runtimes/8.4/Dockerfile" ]; then
    print_warning "Laravel Sail not found. Installing..."
    composer require laravel/sail --dev
    php artisan sail:install
fi

print_status "Prerequisites check completed"

# ========================================
# STEP 2: Crea Struttura Directory AlgoKit
# ========================================

print_info "Creating AlgoKit microservice directory structure..."

# Crea directory
mkdir -p docker/algokit
mkdir -p storage/algokit
mkdir -p storage/logs/algokit

# Set permissions
chmod -R 755 storage/algokit
chmod -R 755 storage/logs/algokit

print_status "Directory structure created"

# ========================================
# STEP 3: Richiedi Configurazione Treasury
# ========================================

print_info "Treasury wallet configuration required..."

echo ""
echo "🏦 TREASURY WALLET SETUP"
echo "========================"
echo ""
echo "You need an Algorand wallet mnemonic (25 words) for the treasury."
echo "This will be used to mint and manage founder certificates."
echo ""

# Check if .env exists and has TREASURY_MNEMONIC
if [ -f ".env" ] && grep -q "TREASURY_MNEMONIC=" .env; then
    current_mnemonic=$(grep "TREASURY_MNEMONIC=" .env | cut -d'=' -f2- | tr -d '"')
    if [ "$current_mnemonic" != "" ] && [ "$current_mnemonic" != "abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon art" ]; then
        print_status "Treasury mnemonic already configured in .env"
        USE_EXISTING_MNEMONIC=true
    else
        USE_EXISTING_MNEMONIC=false
    fi
else
    USE_EXISTING_MNEMONIC=false
fi

if [ "$USE_EXISTING_MNEMONIC" = false ]; then
    echo "Options:"
    echo "1. Enter your existing mnemonic (25 words)"
    echo "2. Generate new wallet with Algorand Wallet or MyAlgo"
    echo "3. Use default test mnemonic (ONLY for testing)"
    echo ""
    read -p "Choose option (1-3): " WALLET_OPTION

    case $WALLET_OPTION in
        1)
            echo ""
            echo "Enter your 25-word mnemonic (separated by spaces):"
            read -r TREASURY_MNEMONIC

            # Basic validation - count words
            WORD_COUNT=$(echo "$TREASURY_MNEMONIC" | wc -w)
            if [ "$WORD_COUNT" -ne 25 ]; then
                print_error "Invalid mnemonic: expected 25 words, got $WORD_COUNT"
                exit 1
            fi
            ;;
        2)
            print_info "Please:"
            print_info "1. Create wallet at https://wallet.myalgo.com/ or use Algorand Mobile Wallet"
            print_info "2. Save your 25-word mnemonic securely"
            print_info "3. Run this script again with option 1"
            exit 0
            ;;
        3)
            print_warning "Using DEFAULT TEST MNEMONIC - DO NOT USE IN PRODUCTION!"
            TREASURY_MNEMONIC="abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon art"
            ;;
        *)
            print_error "Invalid option selected"
            exit 1
            ;;
    esac
fi

# ========================================
# STEP 4: Crea File di Configurazione
# ========================================

print_info "Creating configuration files..."

# Backup existing docker-compose.yml
if [ -f "docker-compose.yml" ]; then
    cp docker-compose.yml docker-compose.yml.backup
    print_status "Backed up existing docker-compose.yml"
fi

# Create Dockerfile for AlgoKit
cat > docker/algokit/Dockerfile << 'EOL'
# 🎯 FlorenceEGI AlgoKit Microservice - Container Native
FROM node:18-bullseye AS base

# Install Python 3.10+ and AlgoKit prerequisites
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    python3-dev \
    python3-venv \
    pipx \
    git \
    curl \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

# Configure pipx PATH
ENV PATH="/root/.local/bin:$PATH"

# Install AlgoKit CLI via pipx (recommended method)
RUN pipx install algokit

# Verify AlgoKit installation
RUN algokit --version

WORKDIR /app

# Create non-root user for security
RUN groupadd -r algokit && useradd -r -g algokit algokit

# Create necessary directories
RUN mkdir -p /app/storage /app/logs /app/config \
    && chown -R algokit:algokit /app

# Copy package files for dependency caching
COPY package*.json ./

# Install Node.js dependencies
RUN npm ci --only=production && npm cache clean --force

# Copy application code
COPY --chown=algokit:algokit . .

# Switch to algokit user
USER algokit

# Environment variables
ENV NODE_ENV=production
ENV PORT=3000
ENV LOG_LEVEL=info

# Expose port
EXPOSE 3000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:3000/health || exit 1

# Startup command
CMD ["node", "microservice-server.js"]

# Labels
LABEL maintainer="Padmin D. Curtis (AI Partner OS3.0)"
LABEL version="1.0.0"
LABEL description="FlorenceEGI AlgoKit Microservice for Algorand blockchain operations"
EOL

print_status "AlgoKit Dockerfile created"

# ========================================
# STEP 5: Aggiorna .env
# ========================================

print_info "Updating .env configuration..."

# Add AlgoKit configuration to .env if not exists
if ! grep -q "ALGOKIT_MICROSERVICE_URL" .env 2>/dev/null; then
cat >> .env << EOL

# ========================================
# ALGOKIT MICROSERVICE CONFIGURATION
# ========================================
ALGOKIT_MICROSERVICE_URL=http://algokit-service:3000
ALGOKIT_MICROSERVICE_TIMEOUT=30
ALGOKIT_MICROSERVICE_RETRIES=3
ALGOKIT_PORT=3000
ALGOKIT_LOG_LEVEL=info

# ========================================
# ALGORAND CONFIGURATION
# ========================================
ALGORAND_NETWORK=testnet
ALGOD_SERVER=https://testnet-api.algonode.cloud
ALGOD_PORT=443
ALGOD_TOKEN=
INDEXER_SERVER=https://testnet-idx.algonode.cloud
INDEXER_PORT=443
INDEXER_TOKEN=

# ========================================
# FOUNDERS CONFIGURATION
# ========================================
FOUNDERS_TOTAL_TOKENS=40
FOUNDERS_PRICE_EUR=250.00
FOUNDERS_CURRENCY=EUR
EOL
fi

# Update or add treasury mnemonic
if [ "$USE_EXISTING_MNEMONIC" = false ]; then
    if grep -q "TREASURY_MNEMONIC=" .env; then
        # Replace existing
        sed -i "s/TREASURY_MNEMONIC=.*/TREASURY_MNEMONIC=\"$TREASURY_MNEMONIC\"/" .env
    else
        # Add new
        echo "TREASURY_MNEMONIC=\"$TREASURY_MNEMONIC\"" >> .env
    fi
    print_status ".env updated with treasury mnemonic"
fi

# ========================================
# STEP 6: Mostra Istruzioni Finali
# ========================================

echo ""
echo "🎉 Setup completed successfully!"
echo "================================="
echo ""
print_status "AlgoKit microservice configured"
print_status "Docker Compose updated"
print_status "Environment variables configured"
echo ""
print_info "Next steps:"
echo ""
echo "1. Copy the provided files to their locations:"
echo "   - docker-compose.yml (updated)"
echo "   - docker/algokit/package.json"
echo "   - docker/algokit/microservice-server.js"
echo "   - app/Services/AlgorandService.php (updated)"
echo "   - config/founders.php (updated)"
echo ""
echo "2. Build and start the containers:"
echo "   ./vendor/bin/sail down"
echo "   ./vendor/bin/sail up --build -d"
echo ""
echo "3. Test the integration:"
echo "   curl http://localhost:3000/health"
echo "   ./vendor/bin/sail artisan route:list | grep founders"
echo ""
echo "4. Access your application:"
echo "   http://localhost (Laravel)"
echo "   http://localhost:3000/health (AlgoKit service)"
echo ""
print_warning "Important security notes:"
echo "- Keep your treasury mnemonic secure and backed up"
echo "- Never commit the real mnemonic to version control"
echo "- Use testnet for development, mainnet for production"
echo ""
print_status "Setup completed! You're ready to mint founder certificates."
