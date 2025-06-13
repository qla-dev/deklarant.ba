<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;
use App\Models\Supplier;
use App\Services\AiService; 

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'importer_id',
        'file_name',
        'total_price',
        'date_of_issue',
        'country_of_origin',
        'scan_time', // When this is null it means this file wasn't processed by AI
        'task_id', // When this is null it means this file wasn't processed by AI
        'incoterm',
        'invoice_number',
        'internal_status'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

  public function overallStatus()
{
    // Ako nije pokrenut AI proces (nema task_id), tretiraj kao "Odbijena"
    if (!$this->task_id) {
        return 'Odbijena';
    }

    return match ((int) $this->internal_status) {
        0 => 'U obradi',
        1 => 'Draft',
        2 => 'Spremljena',
        3 => 'Odbijena',
        default => 'Nepoznato'
    };
}

}
