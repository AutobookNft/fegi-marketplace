# 🎯 FlorenceEGI AlgoKit Microservice - Seguendo Documentazione Ufficiale
# Autore: Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
# Versione: 1.0.0 (Seguendo https://dev.algorand.co/getting-started/algokit-quick-start/)
# Data: 2025-07-08
# Purpose: Dockerfile che segue esattamente i prerequisiti AlgoKit ufficiali

# Partire da Python 3.12+ come richiesto dalla documentazione
FROM python:3.12-slim

# Installa prerequisiti di sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

# Installa Node.js 18+ per il microservice Express
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Installa pipx come indicato nella documentazione
RUN pip install pipx
RUN pipx ensurepath

# Configura PATH per pipx
ENV PATH="/root/.local/bin:$PATH"

# Installa AlgoKit esattamente come nella documentazione
RUN pipx install algokit

# Verifica installazione come nella documentazione
RUN algokit --version

WORKDIR /app

# Crea utente non-root per sicurezza
RUN groupadd -r algokit && useradd -r -g algokit algokit
RUN mkdir -p /app/storage /app/logs && chown -R algokit:algokit /app

# Copia package.json per installazione dependencies Node.js
COPY package*.json ./

# Installa dependencies Node.js per il microservice
RUN npm install --omit=dev && npm cache clean --force

# Copia codice microservice
COPY --chown=algokit:algokit . .

# Switch a utente non-root
USER algokit

# Variabili ambiente
ENV NODE_ENV=production
ENV PORT=3000
ENV LOG_LEVEL=info

EXPOSE 3000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:3000/health || exit 1

# Avvio microservice
CMD ["node", "microservice-server.js"]

# Labels
LABEL maintainer="Padmin D. Curtis (AI Partner OS3.0)"
LABEL version="1.0.0"
LABEL description="FlorenceEGI AlgoKit Microservice seguendo documentazione ufficiale"
