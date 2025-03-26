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
        'item_code',
        'item_description_original',
        'item_description',
        'quantity',
        'base_price',
        'total_price',
        'currency',
        'best_customs_code_matches',
    ];

    protected $casts = [
        'best_customs_code_matches' => 'array',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
