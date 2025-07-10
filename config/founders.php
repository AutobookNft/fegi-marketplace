<?php

/**
 * @Oracode FlorenceEGI Founders System Configuration with AlgoKit Microservice
 * 🎯 Purpose: Complete configuration for founders certificate system
 * 🧱 Core Logic: Blockchain settings, microservice integration, GDPR compliance
 * 🛡️ Security: Environment-based secrets, production-ready settings
 *
 * @package Config
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 2.0.0 (FlorenceEGI - AlgoKit Integration)
 * @date 2025-07-08
 * @purpose Configuration bridge between Laravel and AlgoKit microservice
 */

return [

    // ========================================
    // ROUND CONFIGURATION
    // ========================================

    'round_title' => env('FOUNDERS_ROUND_TITLE', 'Padri Fondatori - Round 1'),
    'round_description' => env('FOUNDERS_ROUND_DESCRIPTION', 'I primi sostenitori del Nuovo Rinascimento Ecologico Digitale'),
    'total_tokens' => env('FOUNDERS_TOTAL_TOKENS', 40),
    'price_eur' => env('FOUNDERS_PRICE_EUR', 250.00),
    'currency' => env('FOUNDERS_CURRENCY', 'EUR'),

    // ========================================
    // ALGOKIT MICROSERVICE CONFIGURATION
    // ========================================

    'algokit_microservice' => [
        'url' => env('ALGOKIT_MICROSERVICE_URL', 'http://localhost:3000'),
        'timeout' => env('ALGOKIT_MICROSERVICE_TIMEOUT', 30),
        'retries' => env('ALGOKIT_MICROSERVICE_RETRIES', 3),
        'retry_delay' => env('ALGOKIT_MICROSERVICE_RETRY_DELAY', 1000), // milliseconds
        'health_check_interval' => env('ALGOKIT_HEALTH_CHECK_INTERVAL', 300), // seconds

        // Security settings
        'api_key' => env('ALGOKIT_API_KEY'), // Optional API key for microservice
        'verify_ssl' => env('ALGOKIT_VERIFY_SSL', true),

        // Monitoring
        'enable_monitoring' => env('ALGOKIT_ENABLE_MONITORING', true),
        'log_requests' => env('ALGOKIT_LOG_REQUESTS', true),
    ],

    // ========================================
    // ALGORAND NETWORK CONFIGURATION
    // ========================================

    'algorand' => [
        'network' => env('ALGORAND_NETWORK', 'testnet'),

        // Network configurations (used by microservice)
        'testnet' => [
            'algod_url' => env('ALGOD_TESTNET_URL', 'https://testnet-api.algonode.cloud'),
            'indexer_url' => env('INDEXER_TESTNET_URL', 'https://testnet-idx.algonode.cloud'),
            'explorer_url' => env('EXPLORER_TESTNET_URL', 'https://testnet.algoexplorer.io'),
        ],

        'mainnet' => [
            'algod_url' => env('ALGOD_MAINNET_URL', 'https://mainnet-api.algonode.cloud'),
            'indexer_url' => env('INDEXER_MAINNET_URL', 'https://mainnet-idx.algonode.cloud'),
            'explorer_url' => env('EXPLORER_MAINNET_URL', 'https://algoexplorer.io'),
        ],

        // Treasury configuration (handled by microservice)
        'treasury_address' => env('ALGORAND_TREASURY_ADDRESS', ''),

        // API settings
        'api_timeout' => env('ALGORAND_API_TIMEOUT', 30),
        'api_retries' => env('ALGORAND_API_RETRIES', 3),
        'api_retry_delay' => env('ALGORAND_API_RETRY_DELAY', 1000),
    ],

    // ========================================
    // ASA (ALGORAND STANDARD ASSET) CONFIGURATION
    // ========================================

    'asa_config' => [
        'total' => 1, // NFT standard
        'decimals' => 0, // NFT standard
        'default_frozen' => false,
        'unit_name' => 'FEG{index}', // Placeholder replaced with index
        'asset_name' => 'FlorenceEGI Padre Fondatore #{index}',
        'description' => 'Certificato Padre Fondatore del Nuovo Rinascimento Ecologico Digitale',
        'metadata_template_url' => env('ASA_METADATA_URL', 'https://florenceegi.it/certificates/{index}/metadata.json'),
        'image_url' => env('ASA_IMAGE_URL', 'https://florenceegi.it/images/certificates/{index}.png'),

        // Asset management addresses (all set to treasury for now)
        'manager_address' => env('ASA_MANAGER_ADDRESS', env('ALGORAND_TREASURY_ADDRESS', '')),
        'reserve_address' => env('ASA_RESERVE_ADDRESS', env('ALGORAND_TREASURY_ADDRESS', '')),
        'freeze_address' => env('ASA_FREEZE_ADDRESS', env('ALGORAND_TREASURY_ADDRESS', '')),
        'clawback_address' => env('ASA_CLAWBACK_ADDRESS', env('ALGORAND_TREASURY_ADDRESS', '')),
    ],

    // ========================================
    // PDF CERTIFICATE CONFIGURATION
    // ========================================

    'certificate' => [
        'template_path' => 'pdf.founder-certificate',
        'storage_disk' => env('CERTIFICATE_STORAGE_DISK', 'local'),
        'storage_path' => 'certificates',
        'filename_template' => 'florenceegi-certificate-{index}-{timestamp}.pdf',
        'pdf_format' => 'A4',
        'pdf_orientation' => 'portrait',

        // Brand configuration
        'brand' => [
            'logo_path' => env('BRAND_LOGO_PATH', '/images/florenceegi-logo.png'),
            'colors' => [
                'primary' => '#F59E0B', // Amber-500
                'secondary' => '#10B981', // Emerald-500
                'accent' => '#3B82F6', // Blue-500
                'text' => '#1F2937', // Gray-800
            ],
            'fonts' => [
                'heading' => 'Playfair Display',
                'body' => 'Inter',
                'mono' => 'JetBrains Mono',
            ]
        ]
    ],

    // ========================================
    // GDPR & COMPLIANCE CONFIGURATION
    // ========================================

    'gdpr' => [
        'data_retention_days' => env('GDPR_DATA_RETENTION_DAYS', 1825), // 5 years
        'anonymization_delay_days' => env('GDPR_ANONYMIZATION_DELAY', 30),
        'enable_right_to_be_forgotten' => env('GDPR_RIGHT_TO_BE_FORGOTTEN', true),
        'require_explicit_consent' => env('GDPR_REQUIRE_EXPLICIT_CONSENT', true),
        'consent_version' => env('GDPR_CONSENT_VERSION', '1.0'),

        // Contact information
        'data_controller' => [
            'name' => env('GDPR_CONTROLLER_NAME', 'FlorenceEGI'),
            'email' => env('GDPR_CONTROLLER_EMAIL', 'privacy@florenceegi.it'),
            'address' => env('GDPR_CONTROLLER_ADDRESS', 'Via Roma 123, 00100 Roma (RM), Italia'),
        ]
    ],

    // ========================================
    // ARTEFACTS & SHIPPING CONFIGURATION
    // ========================================

    'artefacts' => [
        'type' => 'prisma_olografico',
        'description' => 'Prisma olografico commemorativo con QR code',
        'supplier' => [
            'name' => env('ARTEFACT_SUPPLIER_NAME', ''),
            'contact' => env('ARTEFACT_SUPPLIER_CONTACT', ''),
            'lead_time_days' => env('ARTEFACT_LEAD_TIME_DAYS', 14),
        ],

        // Shipping configuration
        'shipping' => [
            'default_carrier' => env('SHIPPING_DEFAULT_CARRIER', 'poste_italiane'),
            'tracking_enabled' => env('SHIPPING_TRACKING_ENABLED', true),
            'insurance_enabled' => env('SHIPPING_INSURANCE_ENABLED', true),
            'signature_required' => env('SHIPPING_SIGNATURE_REQUIRED', false),
        ]
    ],

    // ========================================
    // MONITORING & LOGGING
    // ========================================

    'monitoring' => [
        'enable_blockchain_monitoring' => env('ENABLE_BLOCKCHAIN_MONITORING', true),
        'enable_performance_monitoring' => env('ENABLE_PERFORMANCE_MONITORING', true),
        'slack_webhook_url' => env('MONITORING_SLACK_WEBHOOK'),
        'alert_on_microservice_down' => env('ALERT_ON_MICROSERVICE_DOWN', true),
        'health_check_endpoints' => [
            'microservice' => '/health',
            'database' => '/health/database',
            'blockchain' => '/health/blockchain',
        ]
    ],

    // ========================================
    // ENVIRONMENT-SPECIFIC OVERRIDES
    // ========================================

    'environment_overrides' => [
        'production' => [
            'algokit_microservice.timeout' => 60,
            'algokit_microservice.retries' => 5,
            'algorand.network' => 'mainnet',
            'certificate.storage_disk' => 's3',
            'monitoring.enable_blockchain_monitoring' => true,
        ],

        'testing' => [
            'total_tokens' => 5, // Reduced for testing
            'price_eur' => 1.00, // Minimal price for tests
            'algorand.network' => 'testnet',
            'algokit_microservice.timeout' => 10,
            'gdpr.data_retention_days' => 1,
        ]
    ],

    // ========================================
    // VALIDATION RULES CONFIGURATION
    // ========================================

    'validation' => [
        'investor_name' => 'required|string|min:2|max:200',
        'investor_email' => 'required|email|max:200',
        'investor_phone' => 'nullable|string|max:50',
        'investor_address' => 'nullable|string|max:1000',

        // Rate limiting
        'rate_limit' => [
            'max_attempts' => env('VALIDATION_RATE_LIMIT_ATTEMPTS', 5),
            'decay_minutes' => env('VALIDATION_RATE_LIMIT_DECAY', 60),
        ],
    ],

    // ========================================
    // EMAIL NOTIFICATION CONFIGURATION
    // ========================================

    'email' => [
        'from_email' => env('MAIL_FROM_ADDRESS', 'certificates@florenceegi.it'),
        'from_name' => env('MAIL_FROM_NAME', 'FlorenceEGI'),
        'template' => env('EMAIL_TEMPLATE', 'emails.founder-certificate'),
        'enabled' => env('FEATURE_EMAIL_NOTIFICATIONS', true),

        // Email templates
        'templates' => [
            'certificate_issued' => 'emails.certificate-issued',
            'certificate_shipped' => 'emails.certificate-shipped',
            'welcome' => 'emails.welcome',
        ],

        // Email settings
        'queue' => env('EMAIL_QUEUE', true),
        'retry_attempts' => env('EMAIL_RETRY_ATTEMPTS', 3),
        'timeout' => env('EMAIL_TIMEOUT', 30),
        'attachments_name' => [
            'certificate' => 'florenceegi-certificate-{index}.pdf',
            'artefact' => 'florenceegi-artefact-{index}.png',
        ],
    ],

    // ========================================
    // FEATURE FLAGS
    // ========================================

    'features' => [
        'enable_secondary_market' => env('FEATURE_SECONDARY_MARKET', false),
        'enable_batch_minting' => env('FEATURE_BATCH_MINTING', false),
        'enable_automatic_transfers' => env('FEATURE_AUTO_TRANSFERS', true),
        'enable_email_notifications' => env('FEATURE_EMAIL_NOTIFICATIONS', true),
        'enable_sms_notifications' => env('FEATURE_SMS_NOTIFICATIONS', false),
        'enable_webhook_callbacks' => env('FEATURE_WEBHOOK_CALLBACKS', false),
    ]
];