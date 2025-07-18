<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'version',
        'item_code',
        'item_description_original',
        'item_description',
        'item_description_translated',
        'quantity',
        'base_price',
        'total_price',
        'currency',
        'best_customs_code_matches',
        'country_of_origin',
        'quantity_type',
        'num_packages',
        'tariff_privilege',
        'weight_gross',
        'weight_net',
        'slot_number',
        'weight_gross_locked',
        'weight_net_locked',
        'num_packages_locked',
    ];

    protected $casts = [
        'best_customs_code_matches' => 'array',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
