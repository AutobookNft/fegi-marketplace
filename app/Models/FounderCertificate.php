<?php

/**
 * @Oracode Eloquent Model for Founder Certificates
 * 🎯 Purpose: Manage FlorenceEGI Padri Fondatori certificates with complete lifecycle
 * 🧱 Core Logic: ASA tokens, investor data, artifact tracking, GDPR compliance
 * 🛡️ Security: Mass assignment protection, type casting, data validation
 *
 * @package App\Models
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori System)
 * @date 2025-07-05
 * @purpose Eloquent model for founder certificates with prisma lifecycle management
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class FounderCertificate extends Model
{
    use HasFactory;

    /**
     * @Oracode Mass assignable attributes
     * 🎯 Purpose: Define which fields can be mass assigned safely
     */
    protected $fillable = [
        'collection_id',
        'index',
        'certificate_title',
        'base_price',
        'currency',
        'status',
        'metadata',
        'investor_name',
        'investor_email',
        'investor_phone',
        'investor_address',
        'investor_wallet',
        'asa_id',
        'tx_id',
        'issued_at',
        'minted_at',
        'pdf_path',
        'pdf_generated_at',
    ];

    /**
     * @Oracode Attribute casting for type safety
     * 🎯 Purpose: Ensure proper data types for business logic
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'metadata' => 'array',
        'issued_at' => 'datetime',
        'minted_at' => 'datetime',
        'pdf_generated_at' => 'datetime',
        'artifact_ordered' => 'boolean',
        'artifact_ordered_at' => 'datetime',
        'artifact_paid' => 'boolean',
        'artifact_paid_at' => 'datetime',
        'artifact_received' => 'boolean',
        'artifact_received_at' => 'datetime',
        'artifact_shipped' => 'boolean',
        'artifact_shipped_at' => 'datetime',
        'token_transferred' => 'boolean',
        'token_transferred_at' => 'datetime',
    ];

    /**
     * @Oracode Hidden attributes for API responses
     * 🎯 Purpose: Protect sensitive data in JSON responses
     */
    protected $hidden = [
        // No hidden fields for now - all data is business relevant
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * @Oracode Relationship to Collection
     * 🎯 Purpose: Associate certificate with its collection/event
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    // ========================================
    // QUERY SCOPES FOR BUSINESS LOGIC
    // ========================================

    /**
     * @Oracode Scope for certificates ready for artifact ordering
     * 🎯 Purpose: Find certificates that need artifact order to supplier
     */
    public function scopeReadyForArtifactOrder($query)
    {
        return $query->where('artifact_ordered', false);
    }

    /**
     * @Oracode Scope for artifact orders waiting payment
     * 🎯 Purpose: Find ordered artifacts that need payment confirmation
     */
    public function scopeArtifactOrderedNotPaid($query)
    {
        return $query->where('artifact_ordered', true)
            ->where('artifact_paid', false);
    }

    /**
     * @Oracode Scope for artifacts ready for shipping
     * 🎯 Purpose: Find artifacts received by Fabio, ready to ship to investors
     */
    public function scopeReadyForShipping($query)
    {
        return $query->where('artifact_received', true)
            ->where('artifact_shipped', false);
    }

    /**
     * @Oracode Scope for completed certificates
     * 🎯 Purpose: Find fully completed certificate + artifact workflow
     */
    public function scopeCompleted($query)
    {
        return $query->where('artifact_shipped', true)
            ->whereNotNull('tracking_code');
    }

    /**
     * @Oracode Scope for tokens not yet transferred
     * 🎯 Purpose: Find certificates with tokens still in treasury
     */
    public function scopeTokenInTreasury($query)
    {
        return $query->where('token_transferred', false)
            ->whereNotNull('investor_wallet');
    }

    /**
     * @Oracode Scope for draft certificates
     * 🎯 Purpose: Find certificates not yet assigned to investors
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * @Oracode Scope for ready certificates
     * 🎯 Purpose: Find certificates ready for sale
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    /**
     * @Oracode Scope for issued certificates
     * 🎯 Purpose: Find certificates sold to investors
     */
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    /**
     * @Oracode Scope for minted certificates
     * 🎯 Purpose: Find certificates with minted NFT
     */
    public function scopeMinted($query)
    {
        return $query->where('status', 'minted');
    }

    // ========================================
    // BUSINESS LOGIC METHODS
    // ========================================

    /**
     * @Oracode Mark artifact as ordered from supplier
     * 🎯 Purpose: Update artifact order status with timestamp
     */
    public function markArtifactOrdered(): bool
    {
        return $this->update([
            'artifact_ordered' => true,
            'artifact_ordered_at' => now(),
        ]);
    }

    /**
     * @Oracode Mark supplier payment as completed
     * 🎯 Purpose: Update payment status for artifact order
     */
    public function markArtifactPaid(): bool
    {
        return $this->update([
            'artifact_paid' => true,
            'artifact_paid_at' => now(),
        ]);
    }

    /**
     * @Oracode Mark artifact as received by Fabio
     * 🎯 Purpose: Update receipt status when artifact arrives to Fabio
     */
    public function markArtifactReceived(): bool
    {
        return $this->update([
            'artifact_received' => true,
            'artifact_received_at' => now(),
        ]);
    }

    /**
     * @Oracode Mark artifact as shipped to investor
     * 🎯 Purpose: Update shipping status with tracking code
     */
    public function markArtifactShipped(string $trackingCode): bool
    {
        return $this->update([
            'artifact_shipped' => true,
            'artifact_shipped_at' => now(),
            'tracking_code' => $trackingCode,
        ]);
    }

    /**
     * @Oracode Mark token as transferred to investor wallet
     * 🎯 Purpose: Update token transfer status with transaction ID
     */
    public function markTokenTransferred(string $transferTxId): bool
    {
        return $this->update([
            'token_transferred' => true,
            'token_transferred_at' => now(),
            'transfer_tx_id' => $transferTxId,
        ]);
    }

    // ========================================
    // COMPUTED PROPERTIES
    // ========================================

    /**
     * @Oracode Get current artifact status as human readable string
     * 🎯 Purpose: Provide clear status for admin interface
     */
    public function getArtifactStatusAttribute(): string
    {
        if ($this->artifact_shipped) {
            return 'Spedito';
        }

        if ($this->artifact_received) {
            return 'Da Spedire';
        }

        if ($this->artifact_paid) {
            return 'In Produzione';
        }

        if ($this->artifact_ordered) {
            return 'Ordinato';
        }

        return 'Da Ordinare';
    }

    /**
     * @Oracode Get token location as human readable string
     * 🎯 Purpose: Show where the ASA token currently resides
     */
    public function getTokenLocationAttribute(): string
    {
        if ($this->token_transferred) {
            return 'Wallet Investitore';
        }

        if ($this->investor_wallet) {
            return 'Treasury (Da Trasferire)';
        }

        return 'Treasury (Wallet Non Fornito)';
    }

    /**
     * @Oracode Check if certificate workflow is complete
     * 🎯 Purpose: Determine if all steps are finished
     */
    public function getIsCompleteAttribute(): bool
    {
        return $this->artifact_shipped &&
            !empty($this->tracking_code) &&
            ($this->token_transferred || empty($this->investor_wallet));
    }

    /**
     * @Oracode Get status label in Italian
     * 🎯 Purpose: Human readable status for UI
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Bozza',
            'ready' => 'Pronto',
            'issued' => 'Venduto',
            'minted' => 'Mintato',
            'completed' => 'Completato',
            default => 'Sconosciuto'
        };
    }

    /**
     * @Oracode Get status badge color
     * 🎯 Purpose: Color class for status badges
     */
    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'ready' => 'bg-blue-100 text-blue-800',
            'issued' => 'bg-green-100 text-green-800',
            'minted' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * @Oracode Check if certificate is available for purchase
     * 🎯 Purpose: Determine if certificate can be sold
     */
    public function isAvailableForPurchase(): bool
    {
        return $this->status === 'ready' &&
            empty($this->investor_name) &&
            $this->collection &&
            $this->collection->isAvailableForPurchase();
    }

    // ========================================
    // STATIC HELPER METHODS
    // ========================================

    /**
     * @Oracode Get next available certificate index
     * 🎯 Purpose: Find next index for new certificate (1-40)
     */
    public static function getNextAvailableIndex(): ?int
    {
        $maxIndex = config('founders.totalTokens', 40);
        $usedIndexes = self::pluck('index')->toArray();

        for ($i = 1; $i <= $maxIndex; $i++) {
            if (!in_array($i, $usedIndexes)) {
                return $i;
            }
        }

        return null; // All 40 certificates issued
    }

    /**
     * @Oracode Get certificates export data for supplier orders
     * 🎯 Purpose: Export data needed for artifact production orders
     */
    public static function getExportDataForArtifactOrders(): array
    {
        return self::readyForArtifactOrder()
            ->select(['index', 'investor_name', 'investor_email', 'investor_address', 'issued_at'])
            ->orderBy('index')
            ->get()
            ->toArray();
    }
}
