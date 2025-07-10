#!/bin/bash

# 📦 FlorenceEGI Project Backup Script
# 🎯 Crea backup completo del progetto escludendo node_modules
# 🌟 AutobookNft - Fabio Cherici

# Colori per output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configurazione
PROJECT_NAME="fegi-marketplace"
BACKUP_DIR="$HOME/backups"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_NAME="${PROJECT_NAME}_backup_${TIMESTAMP}"
SOURCE_DIR="$(pwd)"

echo -e "${BLUE}🚀 FlorenceEGI Project Backup Script${NC}"
echo -e "${BLUE}======================================${NC}"
echo ""

# Crea directory backup se non esiste
if [ ! -d "$BACKUP_DIR" ]; then
    echo -e "${YELLOW}📁 Creando directory backup: $BACKUP_DIR${NC}"
    mkdir -p "$BACKUP_DIR"
fi

# Percorso completo backup
BACKUP_PATH="$BACKUP_DIR/$BACKUP_NAME.tar.gz"

echo -e "${BLUE}📋 Informazioni Backup:${NC}"
echo -e "   Source: ${GREEN}$SOURCE_DIR${NC}"
echo -e "   Target: ${GREEN}$BACKUP_PATH${NC}"
echo -e "   Time:   ${GREEN}$(date)${NC}"
echo ""

# Lista delle directory/file da escludere
EXCLUDE_PATTERNS=(
    "node_modules"
    ".git"
    "vendor"
    ".env"
    ".env.*"
    "storage/logs/*.log"
    "storage/framework/cache/*"
    "storage/framework/sessions/*"
    "storage/framework/views/*"
    "bootstrap/cache/*.php"
    ".phpunit.cache"
    "coverage"
    ".DS_Store"
    "Thumbs.db"
    "*.tmp"
    "*.bak"
    "*.backup"
    ".vscode"
    ".idea"
    "docker-compose.override.yml"
    "npm-debug.log"
    "yarn-error.log"
    ".history"
    "temp"
    "tmp"
)

# Costruisci parametri exclude per tar
EXCLUDE_ARGS=""
for pattern in "${EXCLUDE_PATTERNS[@]}"; do
    EXCLUDE_ARGS="$EXCLUDE_ARGS --exclude=$pattern"
done

echo -e "${YELLOW}⚡ Avvio backup...${NC}"
echo -e "${YELLOW}🚫 Escludendo:${NC}"
for pattern in "${EXCLUDE_PATTERNS[@]}"; do
    echo -e "   - ${RED}$pattern${NC}"
done
echo ""

# Esegui backup
echo -e "${BLUE}📦 Creando archivio compresso...${NC}"
if tar -czf "$BACKUP_PATH" $EXCLUDE_ARGS -C "$(dirname "$SOURCE_DIR")" "$(basename "$SOURCE_DIR")"; then

    # Calcola dimensione file
    BACKUP_SIZE=$(du -h "$BACKUP_PATH" | cut -f1)

    echo -e "${GREEN}✅ Backup completato con successo!${NC}"
    echo ""
    echo -e "${BLUE}📊 Statistiche Backup:${NC}"
    echo -e "   File:     ${GREEN}$BACKUP_PATH${NC}"
    echo -e "   Size:     ${GREEN}$BACKUP_SIZE${NC}"
    echo -e "   Created:  ${GREEN}$(date)${NC}"
    echo ""

    # Mostra contenuto archivio (prime 20 righe)
    echo -e "${BLUE}📋 Contenuto archivio (anteprima):${NC}"
    tar -tzf "$BACKUP_PATH" | head -20

    # Conta totale file
    TOTAL_FILES=$(tar -tzf "$BACKUP_PATH" | wc -l)
    echo -e "   ... e altri $(($TOTAL_FILES - 20)) file"
    echo -e "   ${GREEN}Totale: $TOTAL_FILES file${NC}"
    echo ""

    # Suggerimenti
    echo -e "${YELLOW}💡 Suggerimenti:${NC}"
    echo -e "   • Backup salvato in: ${GREEN}$BACKUP_PATH${NC}"
    echo -e "   • Per estrarre: ${BLUE}tar -xzf $BACKUP_PATH${NC}"
    echo -e "   • Per listare: ${BLUE}tar -tzf $BACKUP_PATH${NC}"
    echo ""

    # Verifica integrità
    echo -e "${BLUE}🔍 Verifica integrità archivio...${NC}"
    if tar -tzf "$BACKUP_PATH" > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Archivio integro e valido${NC}"
    else
        echo -e "${RED}❌ Errore: Archivio corrotto!${NC}"
        exit 1
    fi

else
    echo -e "${RED}❌ Errore durante la creazione del backup!${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}🎉 Backup completato con successo!${NC}"
echo -e "${BLUE}🔗 Repository GitHub: https://github.com/AutobookNft${NC}"
echo ""
