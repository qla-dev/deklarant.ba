<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Invoice;use Illuminate\Support\Facades\Log;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'owner',
        'address', 
        'avatar',
        'tax_id',
        'contact_email', 
        'contact_phone'
    ];
    protected $casts = [
        'created_at' => 'datetime',
    ];
    

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getAnnualProfitAvg()
    {
        $now = Carbon::now();
        $created = Carbon::parse($this->created_at);
        Log::info('Supplier created_at:', ['supplier_id' => $this->id, 'created_at' => $created->toDateTimeString()]);


        $yearsActive = max($created->diffInYears($now), 1);

        $totalProfit = Invoice::where('supplier_id', $this->id)
                                ->sum('total_price');

        return round($totalProfit / $yearsActive, 2);
    }

    public function getLastYearProfit()
    {
        $start = Carbon::now()->subYear()->startOfYear();
        $end = Carbon::now()->subYear()->endOfYear();

        return $this->invoices()
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('total_price');
    }

    public function getCurrentYearProfit()
    {
        $start = Carbon::now()->startOfYear();   // January 1st this year
        $end = Carbon::now();                    // Today

        return $this->invoices()
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('total_price');
    }

    public function getProfitPercentageChange()
    {
        $lastYear = $this->getLastYearProfit();
        $thisYear = $this->getCurrentYearProfit();

        // Avoid division by zero
        if ($lastYear == 0) {
            return $thisYear > 0 ? 100 : 0;
        }

        return (($thisYear - $lastYear) / $lastYear) * 100;
    }


}

