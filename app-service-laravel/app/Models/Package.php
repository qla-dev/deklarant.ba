<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserPackage;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'price', 
        'available_scans'
    ];

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class);
    }
}

