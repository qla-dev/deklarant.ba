<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;
use App\Models\Supplier;
use App\Models\Importer;

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
        'internal_status',
        'total_weight_net',
        'total_weight_gross',
        'total_num_packages',
        'incoterm_destination'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

      public function importer()
    {
        return $this->belongsTo(Importer::class);
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

    public function getStatusFromAI()
    {
        if (!$this->task_id) {
            return null;
        }
        return app(AiService::class)->getTaskStatus($this->task_id);
    }

    public function getTaskResultFromAI()
    {
        if (!$this->task_id) {
            return null;
        }
        return app(AiService::class)->getTaskResult($this->task_id);
    }

    public function setInternalStatusFromAiResult()
    {
        $result = $this->getStatusFromAI();

        if ($result === null) {
            $this->internal_status = 3;
            $this->save();
            return;
        }

        switch ($result['status']) {
            case 'pending':
                $this->internal_status = 0;
                break;
            case 'processing':
                $this->internal_status = 0;
                break;
            case 'failed':
                $this->internal_status = 3;
                break;
            case 'completed':
                $this->internal_status = 1;
                break;
            default:
                return; // Don't change anything
        }

        $this->save();
    }

    public function updateInternalStatusIfNecessary()
    {
        if ($this->internal_status == 0) {
            $this->setInternalStatusFromAiResult();
        }
    }
}
