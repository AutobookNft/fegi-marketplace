<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CertificateBenefit;

/**
 * @Oracode Certificate Benefit Seeder for FlorenceEGI Founders System
 * 🎯 Purpose: Popolare benefici di default per i certificati Padre Fondatore
 * 🧱 Core Logic: Benefici prestigiosi e di valore per early adopters
 * 🛡️ Security: Seeding sicuro con validazione
 *
 * @package Database\Seeders
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Certificate Benefits)
 * @date 2025-07-11
 * @purpose Seed default benefits for FlorenceEGI founder certificates
 */
class CertificateBenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $benefits = [
            [
                'title' => 'Prisma Olografico FlorenceEGI',
                'description' => 'Oggetto fisico esclusivo con tecnologia olografica personalizzata che certifica lo status di Padre Fondatore. Realizzato con materiali sostenibili e tecnologia all\'avanguardia.',
                'category' => 'physical',
                'icon' => 'gem',
                'color' => '#8B5CF6',
                'metadata' => [
                    'material' => 'Cristallo ecologico con ologramma integrato',
                    'dimensions' => '10x10x15 cm',
                    'weight' => '500g',
                    'personalization' => 'Nome inciso e numero certificato',
                    'packaging' => 'Scatola di legno sostenibile con certificato di autenticità'
                ],
                'sort_order' => 1,
                'created_by' => 'System',
            ],
            [
                'title' => 'Zero Fee su Ecosistema FlorenceEGI',
                'description' => 'Accesso gratuito e senza commissioni a tutte le piattaforme attuali e future dell\'ecosistema FlorenceEGI, inclusi marketplace, exchange, servizi DeFi e applicazioni IoT.',
                'category' => 'utility',
                'icon' => 'zap',
                'color' => '#F59E0B',
                'metadata' => [
                    'platforms_included' => [
                        'FlorenceEGI Marketplace',
                        'FlorenceEGI Exchange',
                        'FlorenceEGI DeFi Suite',
                        'Future IoT Applications'
                    ],
                    'savings_estimate' => 'Fino a €10,000+ annui in commissioni risparmiate',
                    'validity' => 'Lifetime',
                    'transferable' => true
                ],
                'sort_order' => 2,
                'created_by' => 'System',
            ],
            [
                'title' => 'Accesso VIP Eventi FlorenceEGI',
                'description' => 'Partecipazione prioritaria con accesso VIP a tutti gli eventi, conferenze, meetup e summit organizzati da FlorenceEGI nel mondo. Include networking esclusivo con il team e advisor.',
                'category' => 'vip',
                'icon' => 'crown',
                'color' => '#DC2626',
                'metadata' => [
                    'event_types' => [
                        'Conferenze blockchain',
                        'Summit sostenibilità',
                        'Meetup tecnici',
                        'Networking dinner',
                        'Workshop esclusivi'
                    ],
                    'perks' => [
                        'Fast track registration',
                        'VIP lounge access',
                        'Meet & greet con speakers',
                        'Materiali esclusivi',
                        'Travel discounts'
                    ],
                    'estimated_value' => '€500-2000 per evento'
                ],
                'sort_order' => 3,
                'created_by' => 'System',
            ],
            [
                'title' => 'Governance Token Priority',
                'description' => 'Accesso prioritario ai token di governance EGI con diritti di voto potenziati nelle decisioni strategiche dell\'ecosistema. Include staking rewards esclusivi.',
                'category' => 'digital',
                'icon' => 'vote-yea',
                'color' => '#059669',
                'metadata' => [
                    'voting_power' => '2x standard voting weight',
                    'allocation_priority' => 'Guaranteed allocation in governance token sales',
                    'staking_bonus' => '+25% APY on governance token staking',
                    'proposal_rights' => 'Ability to submit governance proposals',
                    'early_access' => '30 days before public governance features'
                ],
                'sort_order' => 4,
                'created_by' => 'System',
            ],
            [
                'title' => 'NFT Collection Whitelist Permanente',
                'description' => 'Accesso garantito e permanente alle whitelist di tutte le future collezioni NFT esclusive di FlorenceEGI, incluse partnerships e collaborazioni speciali.',
                'category' => 'exclusive',
                'icon' => 'star',
                'color' => '#7C3AED',
                'metadata' => [
                    'collections_included' => 'Tutte le collezioni future FlorenceEGI',
                    'mint_price' => 'Prezzo riservato ai founder (sconto 30-50%)',
                    'early_access' => '48h prima del mint pubblico',
                    'exclusive_traits' => 'Trait speciali riservati ai Padri Fondatori',
                    'transferable' => false
                ],
                'sort_order' => 5,
                'created_by' => 'System',
            ],
            [
                'title' => 'Consulenza Strategica Personale',
                'description' => 'Sessione di consulenza annuale 1-on-1 con il team FlorenceEGI per progetti blockchain, sostenibilità e innovazione digitale. Include review di business plan e networking strategico.',
                'category' => 'vip',
                'icon' => 'user-tie',
                'color' => '#0F172A',
                'metadata' => [
                    'session_duration' => '2 ore per sessione',
                    'frequency' => 'Annuale',
                    'participants' => 'Founder, CTO, Head of Strategy',
                    'deliverables' => [
                        'Analisi personalizzata',
                        'Roadmap suggerita',
                        'Connessioni network',
                        'Follow-up report'
                    ],
                    'market_value' => '€2,500 per sessione'
                ],
                'sort_order' => 6,
                'created_by' => 'System',
            ],
            [
                'title' => 'Beta Access Esclusivo',
                'description' => 'Accesso anticipato a tutte le nuove features, piattaforme e servizi FlorenceEGI in fase beta. Include feedback diretto al team di sviluppo e influence sul product development.',
                'category' => 'digital',
                'icon' => 'flask',
                'color' => '#EA580C',
                'metadata' => [
                    'access_timeline' => '30-60 giorni prima del release pubblico',
                    'feedback_channels' => 'Slack privato, monthly calls, surveys',
                    'influence_level' => 'Direct input on features and roadmap',
                    'recognition' => 'Credits nelle release notes',
                    'nda_required' => true
                ],
                'valid_from' => now(),
                'sort_order' => 7,
                'created_by' => 'System',
            ],
            [
                'title' => 'Revenue Sharing Program',
                'description' => 'Partecipazione esclusiva al programma di revenue sharing dell\'ecosistema FlorenceEGI con distribuzione trimestrale dei profitti basata sul numero di certificati posseduti.',
                'category' => 'utility',
                'icon' => 'chart-line',
                'color' => '#16A34A',
                'metadata' => [
                    'distribution_frequency' => 'Trimestrale',
                    'revenue_percentage' => '2% dei ricavi netti dell\'ecosistema',
                    'payment_method' => 'Token EGI o stablecoin',
                    'minimum_payout' => '€50 per trimestre',
                    'transparency' => 'Report dettagliati sui ricavi'
                ],
                'valid_from' => now()->addMonths(6), // Inizia dopo 6 mesi
                'sort_order' => 8,
                'created_by' => 'System',
            ]
        ];

        foreach ($benefits as $benefit) {
            CertificateBenefit::create($benefit);
        }
    }
}
