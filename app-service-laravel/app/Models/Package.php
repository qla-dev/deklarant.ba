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
        'available_scans',
        'page_limit',
        'document_history',
        'description',
        'icon',
        'speed_limit',
        'duration',
        'token_price',
        'price_monthly',
        'price_yearly',
        'declaration_token_cost',
        'declaration_price',
        'extra_page_token_cost',
        'extra_page_price',
        'simultaneous_logins',
    ];

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class);
    }


    public static function getAllPackages()
{
    return self::all();
}

}

