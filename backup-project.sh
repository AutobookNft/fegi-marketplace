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
TIMESTAMP=$(date +%Y%m%d_%H%M)
SOURCE_DIR="$(pwd)"

# Percorsi delle cartelle di backup
backup_base_dir="/mnt/c/wsl_backup"
backup_dest_c="${backup_base_dir}/${TIMESTAMP}"
LOG_FILE="../backup_log/logfile.log"  # Relativo alla directory del progetto

# Percorsi delle cartelle di backup aggiuntive
drive_d="/mnt/d/Il\ mio\ Drive/Fegi_Marketplace_backup/${TIMESTAMP}"
drive_e="/mnt/e/Fegi_Marketplace_backup/${TIMESTAMP}"
drive_h="/mnt/h/Fegi_Marketplace_backup/${TIMESTAMP}"

BACKUP_NAME="${PROJECT_NAME}_backup_${TIMESTAMP}"

echo -e "${BLUE}🚀 FlorenceEGI Project Backup Script${NC}"
echo -e "${BLUE}======================================${NC}"
echo ""

# Funzione per verificare se un drive è disponibile
check_drive() {
    local drive_path="$1"
    local drive_letter="$2"
    if [ ! -d "$(dirname "$drive_path")" ]; then
        echo "ATTENZIONE: Drive $drive_letter non disponibile, skip della copia" >> "$LOG_FILE"
        echo -e "${YELLOW}⚠️  Drive $drive_letter non disponibile, skip della copia${NC}"
        return 1
    fi
    return 0
}

# Crea directory di log se non esiste
LOG_DIR="$(dirname "$LOG_FILE")"
if [ ! -d "$LOG_DIR" ]; then
    echo -e "${YELLOW}📁 Creando directory log: $LOG_DIR${NC}"
    mkdir -p "$LOG_DIR"
fi

# Crea directory backup principale se non esiste
if [ ! -d "$backup_base_dir" ]; then
    echo -e "${YELLOW}📁 Creando directory backup: $backup_base_dir${NC}"
    mkdir -p "$backup_base_dir"
fi

# Crea directory backup con timestamp
mkdir -p "$backup_dest_c"

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

# Costruisci comando rsync con esclusioni
RSYNC_EXCLUDES=""
for pattern in "${EXCLUDE_PATTERNS[@]}"; do
    RSYNC_EXCLUDES="$RSYNC_EXCLUDES --exclude=$pattern"
done

# Esegui backup principale
echo -e "${BLUE}📁 Copiando file nella directory di backup...${NC}"
if rsync -av $RSYNC_EXCLUDES "$SOURCE_DIR/" "$backup_dest_c/"; then

    # Calcola dimensione directory
    BACKUP_SIZE=$(du -sh "$backup_dest_c" | cut -f1)

    echo -e "${GREEN}✅ Backup completato con successo!${NC}"
    echo ""
    echo -e "${BLUE}📊 Statistiche Backup:${NC}"
    echo -e "   Directory: ${GREEN}$backup_dest_c${NC}"
    echo -e "   Size:      ${GREEN}$BACKUP_SIZE${NC}"
    echo -e "   Created:   ${GREEN}$(date)${NC}"
    echo ""

    # Conta file copiati
    TOTAL_FILES=$(find "$backup_dest_c" -type f | wc -l)
    TOTAL_DIRS=$(find "$backup_dest_c" -type d | wc -l)
    echo -e "${BLUE}📋 Contenuto backup:${NC}"
    echo -e "   ${GREEN}File: $TOTAL_FILES${NC}"
    echo -e "   ${GREEN}Directory: $TOTAL_DIRS${NC}"
    echo ""

    # Mostra struttura principale
    echo -e "${BLUE}📁 Struttura principale:${NC}"
    ls -la "$backup_dest_c" | head -10
    echo ""

    # Suggerimenti
    echo -e "${YELLOW}💡 Suggerimenti:${NC}"
    echo -e "   • Backup salvato in: ${GREEN}$backup_dest_c${NC}"
    echo -e "   • Per navigare: ${BLUE}cd $backup_dest_c${NC}"
    echo -e "   • Per listare: ${BLUE}ls -la $backup_dest_c${NC}"
    echo ""

    # Verifica integrità (controlla se la directory esiste e ha contenuto)
    echo -e "${BLUE}🔍 Verifica integrità backup...${NC}"
    if [ -d "$backup_dest_c" ] && [ "$(ls -A "$backup_dest_c")" ]; then
        echo -e "${GREEN}✅ Backup integro e valido${NC}"

        # Log del backup principale
        echo "$(date): Backup creato con successo - $backup_dest_c" >> "$LOG_FILE"

        # Copia su drive aggiuntivi
        echo ""
        echo -e "${BLUE}💾 Copia backup su drive aggiuntivi...${NC}"

                # Drive D
        if check_drive "$drive_d" "D"; then
            echo -e "${YELLOW}📁 Copiando su Drive D...${NC}"
            mkdir -p "$(dirname "$drive_d")"
            if rsync -av "$backup_dest_c/" "$drive_d/"; then
                echo -e "${GREEN}✅ Backup copiato su Drive D${NC}"
                echo "$(date): Backup copiato su Drive D - $drive_d" >> "$LOG_FILE"
            else
                echo -e "${RED}❌ Errore copia su Drive D${NC}"
                echo "$(date): ERRORE copia su Drive D" >> "$LOG_FILE"
            fi
        fi

        # Drive E
        if check_drive "$drive_e" "E"; then
            echo -e "${YELLOW}📁 Copiando su Drive E...${NC}"
            mkdir -p "$(dirname "$drive_e")"
            if rsync -av "$backup_dest_c/" "$drive_e/"; then
                echo -e "${GREEN}✅ Backup copiato su Drive E${NC}"
                echo "$(date): Backup copiato su Drive E - $drive_e" >> "$LOG_FILE"
            else
                echo -e "${RED}❌ Errore copia su Drive E${NC}"
                echo "$(date): ERRORE copia su Drive E" >> "$LOG_FILE"
            fi
        fi

        # Drive H
        if check_drive "$drive_h" "H"; then
            echo -e "${YELLOW}📁 Copiando su Drive H...${NC}"
            mkdir -p "$(dirname "$drive_h")"
            if rsync -av "$backup_dest_c/" "$drive_h/"; then
                echo -e "${GREEN}✅ Backup copiato su Drive H${NC}"
                echo "$(date): Backup copiato su Drive H - $drive_h" >> "$LOG_FILE"
            else
                echo -e "${RED}❌ Errore copia su Drive H${NC}"
                echo "$(date): ERRORE copia su Drive H" >> "$LOG_FILE"
            fi
        fi

        else
        echo -e "${RED}❌ Errore: Directory backup vuota o corrotta!${NC}"
        echo "$(date): ERRORE - Directory backup vuota: $backup_dest_c" >> "$LOG_FILE"
        exit 1
    fi

else
    echo -e "${RED}❌ Errore durante la creazione del backup!${NC}"
    echo "$(date): ERRORE durante creazione backup" >> "$LOG_FILE"
    exit 1
fi

echo ""
echo -e "${GREEN}🎉 Backup completato con successo!${NC}"
echo -e "${BLUE}📋 Backup salvato in:${NC}"
echo -e "   • ${GREEN}$backup_dest_c${NC}"
echo -e "${BLUE}📝 Log salvato in: ${GREEN}$LOG_FILE${NC}"
echo -e "${BLUE}🔗 Repository GitHub: https://github.com/AutobookNft${NC}"
echo ""
