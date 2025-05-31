<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TariffRate extends Model
{
    use HasFactory;

    protected $table = 'tariff_rates';

    protected $fillable = [
        'item_code',
        'version',
        'name',
        'tariff_rate',
        'EU',
        'CEFTA',
        'IRN',
        'TUR',
        'CHE_LIE',
        'ISL',
        'NOR',
        'section',
        'head',
        'english_name'
    ];

    public function scopeLatestByName($query, $name)
    {
        return $query->where('name', $name)
            ->orderByDesc('version')
            ->first();
    }
}
