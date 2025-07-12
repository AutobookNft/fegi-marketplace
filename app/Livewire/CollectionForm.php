<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Collection;
use App\Models\CertificateBenefit;
use Illuminate\Support\Str;

/**
 * @Oracode Collection Form Component
 * 🎯 Purpose: Interactive form for creating/editing collections with real-time validation
 * 🧱 Core Logic: Real-time validation, metadata handling, business rules
 * 🛡️ Security: Input sanitization, validation, CSRF protection
 *
 * @package App\Livewire
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Collections Management)
 * @date 2025-07-11
 * @purpose Interactive collection management form
 */
class CollectionForm extends Component
{
    // Collection properties
    public $collectionId = null;
    public $name = '';
    public $description = '';
    public $total_tokens = 40;
    public $base_price = 250.00;
    public $currency = 'EUR';
    public $treasury_address = '';
    public $status = 'draft';
    public $event_date = '';
    public $sale_start_date = '';
    public $sale_end_date = '';
    public $allow_wallet_payments = true;
    public $allow_fiat_payments = true;
    public $min_symbolic_price = 0.001;
    public $requires_shipping = true;

    // Metadata properties
    public $metadata_title = '';
    public $metadata_description = '';
    public $metadata_image = '';
    public $metadata_external_url = '';
    public $metadata_properties = [];

    // Shipping info properties
    public $shipping_supplier_name = '';
    public $shipping_supplier_contact = '';
    public $shipping_estimated_delivery_days = 7;

    // Certificate benefits
    public $selectedBenefits = [];
    public $availableBenefits = [];

    // UI state
    public $showAdvancedOptions = false;
    public $showMetadata = false;
    public $showShippingInfo = false;
    public $showBenefits = false;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'total_tokens' => 'required|integer|min:1|max:1000',
        'base_price' => 'required|numeric|min:0.01|max:999999.99',
        'currency' => 'required|string|in:EUR,USD,ALGO',
        'treasury_address' => 'nullable|string|max:255',
        'status' => 'required|in:draft,active,paused,completed,cancelled',
        'event_date' => 'nullable|date',
        'sale_start_date' => 'nullable|date',
        'sale_end_date' => 'nullable|date|after:sale_start_date',
        'allow_wallet_payments' => 'boolean',
        'allow_fiat_payments' => 'boolean',
        'min_symbolic_price' => 'nullable|numeric|min:0.000001|max:1',
        'requires_shipping' => 'boolean',
        'metadata_title' => 'nullable|string|max:255',
        'metadata_description' => 'nullable|string|max:1000',
        'metadata_image' => 'nullable|url',
        'metadata_external_url' => 'nullable|url',
        'shipping_supplier_name' => 'nullable|string|max:255',
        'shipping_supplier_contact' => 'nullable|string|max:255',
        'shipping_estimated_delivery_days' => 'nullable|integer|min:1|max:365',
    ];

    protected $messages = [
        'name.required' => 'Il nome della collection è obbligatorio.',
        'name.max' => 'Il nome non può superare 255 caratteri.',
        'total_tokens.required' => 'Il numero di token è obbligatorio.',
        'total_tokens.min' => 'Deve esserci almeno 1 token.',
        'total_tokens.max' => 'Non possono esserci più di 1000 token.',
        'base_price.required' => 'Il prezzo base è obbligatorio.',
        'base_price.min' => 'Il prezzo deve essere almeno 0.01.',
        'base_price.max' => 'Il prezzo non può superare 999999.99.',
        'sale_end_date.after' => 'La data di fine vendita deve essere successiva a quella di inizio.',
        'metadata_image.url' => 'L\'URL dell\'immagine non è valido.',
        'metadata_external_url.url' => 'L\'URL esterno non è valido.',
    ];

    public function mount($collectionId = null)
    {
        $this->loadAvailableBenefits();

        if ($collectionId) {
            $this->loadCollection($collectionId);
        } else {
            $this->initializeDefaults();
        }
    }

    public function loadAvailableBenefits()
    {
        $this->availableBenefits = \App\Models\CertificateBenefit::where('is_active', true)
            ->orderBy('category')
            ->orderBy('title')
            ->get()
            ->toArray();
    }

    public function loadCollection($collectionId)
    {
        $collection = Collection::findOrFail($collectionId);
        $this->isEditing = true;
        $this->collectionId = $collection->id;

        // Load basic properties
        $this->name = $collection->name;
        $this->description = $collection->description ?? '';
        $this->total_tokens = $collection->total_tokens;
        $this->base_price = $collection->base_price;
        $this->currency = $collection->currency;
        $this->treasury_address = $collection->treasury_address ?? '';
        $this->status = $collection->status;
        $this->event_date = $collection->event_date?->format('Y-m-d\TH:i') ?? '';
        $this->sale_start_date = $collection->sale_start_date?->format('Y-m-d\TH:i') ?? '';
        $this->sale_end_date = $collection->sale_end_date?->format('Y-m-d\TH:i') ?? '';
        $this->allow_wallet_payments = $collection->allow_wallet_payments;
        $this->allow_fiat_payments = $collection->allow_fiat_payments;
        $this->min_symbolic_price = $collection->min_symbolic_price;
        $this->requires_shipping = $collection->requires_shipping;

        // Load metadata
        $metadata = $collection->metadata ?? [];
        $this->metadata_title = $metadata['title'] ?? '';
        $this->metadata_description = $metadata['description'] ?? '';
        $this->metadata_image = $metadata['image'] ?? '';
        $this->metadata_external_url = $metadata['external_url'] ?? '';
        $this->metadata_properties = $metadata['properties'] ?? [];

        // Load shipping info
        $shippingInfo = $collection->shipping_info ?? [];
        $this->shipping_supplier_name = $shippingInfo['supplier_name'] ?? '';
        $this->shipping_supplier_contact = $shippingInfo['supplier_contact'] ?? '';
        $this->shipping_estimated_delivery_days = $shippingInfo['estimated_delivery_days'] ?? 7;

        // Load selected benefits
        $this->selectedBenefits = $collection->certificateBenefits->pluck('id')->toArray();
    }

    public function initializeDefaults()
    {
        $this->treasury_address = config('founders.algorand.treasury_address', '');
        $this->currency = config('founders.currency', 'EUR');
        $this->base_price = config('founders.price_eur', 250.00);
        $this->total_tokens = config('founders.total_tokens', 40);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description ?: null,
            'total_tokens' => $this->total_tokens,
            'base_price' => $this->base_price,
            'currency' => $this->currency,
            'treasury_address' => $this->treasury_address ?: config('founders.algorand.treasury_address'),
            'status' => $this->status,
            'event_date' => $this->event_date ? date('Y-m-d H:i:s', strtotime($this->event_date)) : null,
            'sale_start_date' => $this->sale_start_date ? date('Y-m-d H:i:s', strtotime($this->sale_start_date)) : null,
            'sale_end_date' => $this->sale_end_date ? date('Y-m-d H:i:s', strtotime($this->sale_end_date)) : null,
            'allow_wallet_payments' => $this->allow_wallet_payments,
            'allow_fiat_payments' => $this->allow_fiat_payments,
            'min_symbolic_price' => $this->min_symbolic_price,
            'requires_shipping' => $this->requires_shipping,
        ];

        // Build metadata
        $metadata = [];
        if ($this->metadata_title) $metadata['title'] = $this->metadata_title;
        if ($this->metadata_description) $metadata['description'] = $this->metadata_description;
        if ($this->metadata_image) $metadata['image'] = $this->metadata_image;
        if ($this->metadata_external_url) $metadata['external_url'] = $this->metadata_external_url;
        if (!empty($this->metadata_properties)) $metadata['properties'] = $this->metadata_properties;
        $data['metadata'] = !empty($metadata) ? $metadata : null;

        // Build shipping info
        $shippingInfo = [];
        if ($this->shipping_supplier_name) $shippingInfo['supplier_name'] = $this->shipping_supplier_name;
        if ($this->shipping_supplier_contact) $shippingInfo['supplier_contact'] = $this->shipping_supplier_contact;
        if ($this->shipping_estimated_delivery_days) $shippingInfo['estimated_delivery_days'] = $this->shipping_estimated_delivery_days;
        $data['shipping_info'] = !empty($shippingInfo) ? $shippingInfo : null;

        if ($this->isEditing) {
            $collection = Collection::findOrFail($this->collectionId);

            // Update slug if name changed
            if ($collection->name !== $this->name) {
                $data['slug'] = Str::slug($this->name);
            }

            // Update available tokens if total tokens changed
            if ($collection->total_tokens !== $this->total_tokens) {
                $difference = $this->total_tokens - $collection->total_tokens;
                $data['available_tokens'] = max(0, $collection->available_tokens + $difference);
            }

            $collection->update($data);
            $message = 'Collection aggiornata con successo!';
        } else {
            $data['slug'] = Str::slug($this->name);
            $data['available_tokens'] = $this->total_tokens;
            $data['certificates_issued'] = 0;
            $data['total_revenue'] = 0;

            $collection = Collection::create($data);
            $message = 'Collection creata con successo!';
        }

        // Sync benefits with collection
        if (!empty($this->selectedBenefits)) {
            $collection->certificateBenefits()->sync($this->selectedBenefits);
        } else {
            $collection->certificateBenefits()->detach();
        }

        session()->flash('success', $message);
        return redirect()->route('founders.collections.show', $collection);
    }

    public function toggleAdvancedOptions()
    {
        $this->showAdvancedOptions = !$this->showAdvancedOptions;
    }

    public function toggleMetadata()
    {
        $this->showMetadata = !$this->showMetadata;
    }

    public function toggleShippingInfo()
    {
        $this->showShippingInfo = !$this->showShippingInfo;
    }

    public function toggleBenefits()
    {
        $this->showBenefits = !$this->showBenefits;
    }

    public function addMetadataProperty()
    {
        $this->metadata_properties[] = ['key' => '', 'value' => ''];
    }

    public function removeMetadataProperty($index)
    {
        unset($this->metadata_properties[$index]);
        $this->metadata_properties = array_values($this->metadata_properties);
    }

    public function render()
    {
        return view('livewire.collection-form');
    }
}
