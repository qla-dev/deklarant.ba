<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'address', 
        'tax_id', 
        'contact_email', 
        'contact_phone'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}

