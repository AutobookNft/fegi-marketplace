<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @Oracode Certificate Benefit Model for FlorenceEGI Founders System
 * 🎯 Purpose: Gestione benefici/privilegi configurabili per i certificati
 * 🧱 Core Logic: Benefits configuration, category management, usage tracking
 * 🛡️ Security: Validation, active status, usage limits
 *
 * @package App\Models
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Certificate Benefits System)
 * @date 2025-07-11
 * @purpose Configurable benefits/privileges system for founder certificates
 */
class CertificateBenefit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'category',
        'icon',
        'color',
        'metadata',
        'is_active',
        'sort_order',
        'valid_from',
        'valid_until',
        'max_uses',
        'current_uses',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Collections that have this benefit.
     */
    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_certificate_benefit')
            ->withPivot(['custom_configuration', 'is_highlighted', 'sort_order'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    /**
     * Scope for active benefits.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for benefits by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for valid benefits (within date range).
     */
    public function scopeValid($query)
    {
        $now = now()->toDateString();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('valid_from')
                ->orWhere('valid_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('valid_until')
                ->orWhere('valid_until', '>=', $now);
        });
    }

    /**
     * Scope for benefits with available uses.
     */
    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('max_uses')
                ->orWhereRaw('current_uses < max_uses');
        });
    }

    // ========================================
    // ACCESSORS & MUTATORS
    // ========================================

    /**
     * Get the category label in Italian.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'physical' => 'Oggetto Fisico',
            'digital' => 'Beneficio Digitale',
            'vip' => 'Accesso VIP',
            'utility' => 'Utilità',
            'event' => 'Eventi',
            'discount' => 'Sconto',
            'exclusive' => 'Esclusivo',
            default => ucfirst($this->category)
        };
    }

    /**
     * Get the icon with fallback.
     */
    public function getIconAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        // Default icons based on category
        return match ($this->category) {
            'physical' => 'gift',
            'digital' => 'download',
            'vip' => 'crown',
            'utility' => 'zap',
            'event' => 'calendar',
            'discount' => 'percent',
            'exclusive' => 'star',
            default => 'award'
        };
    }

    /**
     * Get the status badge color for UI.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        if (!$this->is_active) {
            return 'bg-gray-100 text-gray-800';
        }

        if ($this->isExpired()) {
            return 'bg-red-100 text-red-800';
        }

        if ($this->isNearingLimit()) {
            return 'bg-yellow-100 text-yellow-800';
        }

        return 'bg-green-100 text-green-800';
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Check if benefit is currently valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now()->toDateString();

        if ($this->valid_from && $this->valid_from > $now) {
            return false;
        }

        if ($this->valid_until && $this->valid_until < $now) {
            return false;
        }

        return true;
    }

    /**
     * Check if benefit is expired.
     */
    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until < now()->toDateString();
    }

    /**
     * Check if benefit is nearing usage limit.
     */
    public function isNearingLimit(): bool
    {
        if (!$this->max_uses) {
            return false;
        }

        $threshold = $this->max_uses * 0.8; // 80% threshold
        return $this->current_uses >= $threshold;
    }

    /**
     * Check if benefit has available uses.
     */
    public function hasAvailableUses(): bool
    {
        if (!$this->max_uses) {
            return true;
        }

        return $this->current_uses < $this->max_uses;
    }

    /**
     * Increment usage counter.
     */
    public function incrementUsage(): bool
    {
        if (!$this->hasAvailableUses()) {
            return false;
        }

        $this->increment('current_uses');
        return true;
    }

    /**
     * Get usage percentage.
     */
    public function getUsagePercentage(): float
    {
        if (!$this->max_uses) {
            return 0;
        }

        return min(100, ($this->current_uses / $this->max_uses) * 100);
    }

    /**
     * Get remaining uses.
     */
    public function getRemainingUses(): ?int
    {
        if (!$this->max_uses) {
            return null; // Unlimited
        }

        return max(0, $this->max_uses - $this->current_uses);
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    /**
     * Get available benefit categories.
     */
    public static function getCategories(): array
    {
        return [
            'physical' => 'Fisico',
            'digital' => 'Digitale',
            'utility' => 'Utilità',
            'vip' => 'VIP',
            'exclusive' => 'Esclusivo',
        ];
    }

    /**
     * Get available icons for benefits
     */
    public static function getIcons(): array
    {
        return [
            'gem' => '💎 Gem',
            'crown' => '👑 Crown',
            'zap' => '⚡ Zap',
            'star' => '⭐ Star',
            'gift' => '🎁 Gift',
            'calendar' => '📅 Calendar',
            'chart-line' => '📈 Chart',
            'flask' => '🧪 Flask',
            'user-tie' => '🤵 User Tie',
            'trophy' => '🏆 Trophy',
        ];
    }

    /**
     * Get available colors for benefits
     */
    public static function getColors(): array
    {
        return [
            'emerald' => 'Emerald',
            'blue' => 'Blue',
            'purple' => 'Purple',
            'amber' => 'Amber',
            'red' => 'Red',
            'pink' => 'Pink',
            'indigo' => 'Indigo',
            'teal' => 'Teal',
        ];
    }

    /**
     * Get category label
     */
    public function getCategoryLabel(): string
    {
        return self::getCategories()[$this->category] ?? $this->category;
    }

    /**
     * Get icon emoji
     */
    public function getIconEmoji(): string
    {
        $icons = [
            'gem' => '💎',
            'crown' => '👑',
            'zap' => '⚡',
            'star' => '⭐',
            'gift' => '🎁',
            'calendar' => '📅',
            'chart-line' => '📈',
            'flask' => '🧪',
            'user-tie' => '🤵',
            'trophy' => '🏆',
        ];

        return $icons[$this->icon] ?? '🏆';
    }

    /**
     * Get default benefits for new system.
     */
    public static function getDefaultBenefits(): array
    {
        return [
            [
                'title' => 'Prisma Olografico FlorenceEGI',
                'description' => 'Oggetto fisico esclusivo con tecnologia olografica che certifica lo status di Padre Fondatore',
                'category' => 'physical',
                'icon' => 'gem',
                'color' => '#8B5CF6',
            ],
            [
                'title' => 'Zero Fee su Future Piattaforme EGI',
                'description' => 'Accesso gratuito e senza commissioni a tutte le future piattaforme dell\'ecosistema FlorenceEGI',
                'category' => 'utility',
                'icon' => 'zap',
                'color' => '#F59E0B',
            ],
            [
                'title' => 'Accesso VIP Eventi FlorenceEGI',
                'description' => 'Partecipazione prioritaria e accesso VIP a tutti gli eventi, conferenze e meetup organizzati da FlorenceEGI',
                'category' => 'vip',
                'icon' => 'crown',
                'color' => '#DC2626',
            ],
            [
                'title' => 'Governance Token Priority',
                'description' => 'Accesso prioritario ai token di governance e diritto di voto nelle decisioni strategiche',
                'category' => 'digital',
                'icon' => 'vote',
                'color' => '#059669',
            ],
            [
                'title' => 'NFT Collection Whitelist',
                'description' => 'Accesso garantito alle whitelist delle future collezioni NFT esclusive di FlorenceEGI',
                'category' => 'exclusive',
                'icon' => 'star',
                'color' => '#7C3AED',
            ]
        ];
    }
}
