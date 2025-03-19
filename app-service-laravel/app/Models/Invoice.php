<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;
use App\Models\Supplier;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'total_price',
        'date_of_issue',
        'country_of_origin'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function suppliers()
    {
        return $this->belongsTo(Supplier::class);
    }
}

