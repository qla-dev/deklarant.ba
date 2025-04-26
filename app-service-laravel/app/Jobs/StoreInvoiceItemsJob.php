<?php

namespace App\Jobs;

use App\Models\InvoiceItem;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreInvoiceItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoiceId;
    protected $items;

    public function __construct($invoiceId, $items)
    {
        $this->invoiceId = $invoiceId;
        $this->items = $items;
    }

    public function handle()
    {
        foreach ($this->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $this->invoiceId,
                'item_code' => $item['item_code'],
                'item_description_original' => $item['item_description_original'],
                'item_description' => $item['item_description'],
                'quantity' => $item['quantity'],
                'base_price' => $item['base_price'],
                'total_price' => $item['total_price'],
                'currency' => $item['currency'],
                'version' => $item['version'],
                'best_customs_code_matches' => $item['best_customs_code_matches'],
            ]);
        }
    }
}
