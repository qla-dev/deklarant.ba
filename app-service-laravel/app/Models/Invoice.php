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
        'file_name',
        'total_price',
        'date_of_issue',
        'country_of_origin',
        'scan_time',
        'task_id' // When this is null it means this file wasn't processed by AI
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

