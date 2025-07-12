<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @Oracode Collection Model for FlorenceEGI Founders System
 * 🎯 Purpose: Gestione eventi/stock di certificati Padri Fondatori
 * 🧱 Core Logic: Collection management, token tracking, metadata handling
 * 🛡️ Security: Fillable fields, proper casting, validation
 *
 * @package App\Models
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Collections Management)
 * @date 2025-07-11
 * @purpose Complete collection management for founder certificates
 */
class Collection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'total_tokens',
        'available_tokens',
        'base_price',
        'currency',
        'metadata',
        'asset_id',
        'treasury_address',
        'status',
        'event_date',
        'sale_start_date',
        'sale_end_date',
        'certificates_issued',
        'total_revenue',
        'allow_wallet_payments',
        'allow_fiat_payments',
        'min_symbolic_price',
        'requires_shipping',
        'shipping_info'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'metadata' => 'array',
        'shipping_info' => 'array',
        'event_date' => 'datetime',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'base_price' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'min_symbolic_price' => 'decimal:6',
        'allow_wallet_payments' => 'boolean',
        'allow_fiat_payments' => 'boolean',
        'requires_shipping' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = Str::slug($collection->name);
            }
            // Inizializza available_tokens = total_tokens alla creazione
            if (empty($collection->available_tokens)) {
                $collection->available_tokens = $collection->total_tokens;
            }
        });
    }

    /**
     * Founder certificates that belong to this collection.
     */
    public function founderCertificates(): HasMany
    {
        return $this->hasMany(FounderCertificate::class);
    }

    /**
     * Certificate benefits associated with this collection.
     */
    public function certificateBenefits(): BelongsToMany
    {
        return $this->belongsToMany(CertificateBenefit::class, 'collection_certificate_benefit')
            ->withPivot(['custom_configuration', 'is_highlighted', 'sort_order'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /**
     * Active certificate benefits for this collection.
     */
    public function activeBenefits(): BelongsToMany
    {
        return $this->certificateBenefits()
            ->where('certificate_benefits.is_active', true)
            ->where(function ($query) {
                $now = now()->toDateString();
                $query->where(function ($q) use ($now) {
                    $q->whereNull('certificate_benefits.valid_from')
                        ->orWhere('certificate_benefits.valid_from', '<=', $now);
                })->where(function ($q) use ($now) {
                    $q->whereNull('certificate_benefits.valid_until')
                        ->orWhere('certificate_benefits.valid_until', '>=', $now);
                });
            });
    }

    /**
     * Scope per collections attive
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope per collections in vendita
     */
    public function scopeOnSale($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('sale_start_date')
                    ->orWhere('sale_start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('sale_end_date')
                    ->orWhere('sale_end_date', '>=', now());
            });
    }

    /**
     * Verifica se la collection è disponibile per l'acquisto
     */
    public function isAvailableForPurchase(): bool
    {
        return $this->status === 'active' &&
            $this->available_tokens > 0 &&
            ($this->sale_start_date === null || $this->sale_start_date <= now()) &&
            ($this->sale_end_date === null || $this->sale_end_date >= now());
    }

    /**
     * Ottieni la percentuale di completamento
     */
    public function getCompletionPercentage(): float
    {
        if ($this->total_tokens === 0) {
            return 0;
        }
        return ($this->certificates_issued / $this->total_tokens) * 100;
    }

    /**
     * Ottieni il numero di certificati rimanenti
     */
    public function getRemainingCertificates(): int
    {
        return $this->total_tokens - $this->certificates_issued;
    }

    /**
     * Ottieni il revenue medio per certificato
     */
    public function getAverageRevenuePerCertificate(): float
    {
        if ($this->certificates_issued === 0) {
            return 0;
        }
        return $this->total_revenue / $this->certificates_issued;
    }

    /**
     * Verifica se la collection è in evento dal vivo
     */
    public function isLiveEvent(): bool
    {
        if ($this->event_date === null) {
            return false;
        }

        $now = now();
        $eventStart = $this->event_date;
        $eventEnd = $this->event_date->copy()->addHours(4); // Assume 4 ore per evento

        return $now >= $eventStart && $now <= $eventEnd;
    }

    /**
     * Ottieni lo stato formattato
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Bozza',
            'active' => 'Attiva',
            'paused' => 'In Pausa',
            'completed' => 'Completata',
            'cancelled' => 'Annullata',
            default => 'Sconosciuto'
        };
    }

    /**
     * Ottieni il colore del badge per lo stato
     */
    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'active' => 'bg-green-100 text-green-800',
            'paused' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Decrementa i token disponibili (quando si emette un certificato)
     */
    public function decrementAvailableTokens(): void
    {
        if ($this->available_tokens > 0) {
            $this->decrement('available_tokens');
            $this->increment('certificates_issued');
        }
    }

    /**
     * Incrementa il revenue totale
     */
    public function addRevenue(float $amount): void
    {
        $this->increment('total_revenue', $amount);
    }
}
