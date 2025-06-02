<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Importer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\User;
use App\Models\TariffRate;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;

class StatsController extends Controller
{

    protected function resolveEntityFromRequest(Request $request)
{
    $path = $request->path();

    if (str_contains($path, 'importers')) {
        return [
            'model' => Importer::class,
            'label' => 'Uvoznik'
        ];
    }

    return [
        'model' => Supplier::class,
        'label' => 'Dobavljač'
    ];
}

    protected $modelUser = User::class;
    protected $modelSupplier = Supplier::class;
    protected $modelImporter = Importer::class;
    protected $modelInvoice = Invoice::class;
    protected $modelPackage = Package::class;
    protected $modelInvoiceItem = InvoiceItem::class;
    protected $modelTariffRate = TariffRate::class;
    /**
     * Get statistics for suppliers, invoices, invoices by country, and remaining scans.
     */
    public function getStatistics()
{
    // Count total users
    $userCount = $this->modelUser::count();

    // Count total suppliers
    $supplierCount = $this->modelSupplier::count();
    $importerCount = $this->modelImporter::count();

    // Count total invoices
    $invoiceCount = $this->modelInvoice::count();

    // Count invoices grouped by country
    $invoicesByCountry = $this->modelInvoice::selectRaw('country_of_origin, COUNT(*) as count')
                                ->groupBy('country_of_origin')
                                ->pluck('count', 'country_of_origin');

    return response()->json([
        'total_users' => $userCount,
        'total_suppliers' => $supplierCount,
        'total_importers' => $importerCount,
        'total_invoices' => $invoiceCount,
        'invoices_by_country' => $invoicesByCountry
    ]);
}


    /**
     * Get statistics for a specific user by ID.
     */
    public function getUserStatisticsById($id)
{
    try {
        $user = $this->modelUser::findOrFail($id);

        $invoiceCount = $this->modelInvoice::where('user_id', $user->id)->count();

        $invoicesByCountry = $this->modelInvoice::where('user_id', $user->id)
            ->selectRaw('country_of_origin, COUNT(*) as count')
            ->groupBy('country_of_origin')
            ->pluck('count', 'country_of_origin');

        $usedScans = $this->modelInvoice::where('user_id', $user->id)
            ->whereNotNull('task_id')
            ->count();
        $totalScans = $this->modelPackage::whereIn('id', function ($query) use ($user) {
            $query->select('package_id')->from('user_packages')->where('user_id', $user->id);
        })->sum('available_scans');

        $remainingScans = $user->getRemainingScans();

        $itemCodes = $this->modelInvoice::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->with('items')
            ->get()
            ->flatMap(fn($invoice) => $invoice->items->pluck('item_code'))
            ->unique()
            ->filter()
            ->values();

        $itemCodeCounts = $this->modelInvoiceItem::whereIn('item_code', $itemCodes)
            ->selectRaw('item_code, COUNT(*) as total')
            ->groupBy('item_code')
            ->pluck('total', 'item_code');

        $latestTariffs = $this->modelTariffRate::whereIn('item_code', $itemCodes)
            ->get(['id', 'item_code', 'name', 'tariff_rate'])
            ->map(function ($tariff) use ($itemCodeCounts) {
                return [
                    'id' => $tariff->id,
                    'name' => $tariff->name,
                    'tariff_rate' => $tariff->tariff_rate,
                    'item_usage_count' => $itemCodeCounts[$tariff->item_code] ?? 0
                ];
            })
            ->values();

        // Generate stats for suppliers and importers
        $supplierStats = $this->getEntityStats($user, Supplier::class, 'supplier_id');
        $importerStats = $this->getEntityStats($user, Importer::class, 'importer_id');

        return response()->json([
            'user_id' => $user->id,
            'total_invoices' => $invoiceCount,
            'invoices_by_country' => $invoicesByCountry,
            'used_scans' => $usedScans,
            'remaining_scans' => $remainingScans,
            'latest_tariffs' => $latestTariffs,

            // Both entity stats
            'supplier_stats' => $supplierStats,
            'importer_stats' => $importerStats,
        ], 200);

    } catch (ModelNotFoundException $e) {
    return response()->json([
        'error' => 'Korisnik nije pronađen',
        'message' => $e->getMessage()
    ], 404);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Neuspješno preuzimanje statistike korisnika',
            'message' => $e->getMessage()
        ], 500);
    }

}

private function getEntityStats($user, string $modelClass, string $foreignKey)
{
    $entityKey = $foreignKey; // 'supplier_id' or 'importer_id'
    $table = $modelClass::query()->getModel()->getTable();
    $collectionKey = $table; // 'suppliers' or 'importers'

    $invoices = $this->modelInvoice::where('user_id', $user->id)->get();
    $entityIds = $invoices->pluck($foreignKey)->unique();

    $entities = $modelClass::whereIn('id', $entityIds)->get(['id', 'name', 'owner', 'avatar', 'address','tax_id','contact_email','contact_phone']);

    $entityCount = $modelClass::whereIn('id', function ($query) use ($user, $foreignKey) {
        $query->select($foreignKey)->from('invoices')->where('user_id', $user->id);
    })->count();

    $topEntities = $modelClass::select("{$table}.id", 'name', 'owner')
        ->join('invoices', "{$table}.id", '=', "invoices.{$foreignKey}")
        ->where('invoices.user_id', $user->id)
        ->groupBy("{$table}.id", 'name', 'owner')
        ->get()
        ->map(function ($entity) use ($modelClass, $entityKey) {
            $full = $modelClass::find($entity->id);
            return [
                $entityKey => $entity->id,
                'name' => $entity->name,
                'owner' => $entity->owner,
                'annual_profit' => $full ? $full->getAnnualProfitAvg() : 0,
            ];
        })
        ->sortByDesc('annual_profit')
        ->values();

    $latestEntities = $modelClass::select("id", "name", "owner","address")
        ->selectSub(function ($query) use ($user, $foreignKey, $table) {
            $query->from('invoices')
                ->whereColumn($foreignKey, "{$table}.id")
                ->where('user_id', $user->id)
                ->selectRaw('MAX(created_at)');
        }, 'latest_invoice_date')
        ->whereIn('id', function ($query) use ($user, $foreignKey) {
            $query->select($foreignKey)->from('invoices')->where('user_id', $user->id);
        })
        ->orderByDesc('latest_invoice_date')
        ->get()
        ->map(function ($entity) use ($entityKey) {
            return [
                $entityKey => $entity->id,
                'name' => $entity->name,
                'owner' => $entity->owner,
                'address' => $entity->address,
            ];
        });

    $profitChanges = $modelClass::whereIn('id', function ($query) use ($user, $foreignKey) {
        $query->select($foreignKey)->from('invoices')->where('user_id', $user->id);
    })->get()->map(function ($entity) use ($entityKey) {
        return [
            $entityKey => $entity->id,
            'name' => $entity->name,
            'owner' => $entity->owner,
            'last_year_profit' => $entity->getLastYearProfit(),
            'current_year_profit' => $entity->getCurrentYearProfit(),
            'percentage_change' => round($entity->getProfitPercentageChange(), 2) . "%",
        ];
    });

    return [
        'total_' . $collectionKey => $entityCount,
        $collectionKey => $entities,
        'top_' . $collectionKey => $topEntities,
        'latest_' . $collectionKey => $latestEntities,
        $collectionKey . '_profit_changes' => $profitChanges,
    ];
}




    /**
     * Get statistics for a specific supplier by ID.
     */
    public function getEntityStatisticsById(Request $request, $id)
{
    // Dynamically resolve model and label from the URL
    ['model' => $model, 'label' => $label] = $this->resolveEntityFromRequest($request);

    // Determine the correct foreign key (e.g. 'supplier_id' or 'importer_id')
    $foreignKey = strtolower($label) . '_id';

    try {
        // Fetch entity model (Supplier or Importer)
        $entity = $model::findOrFail($id);

        // Count total invoices for this entity
        $invoiceCount = $this->modelInvoice::where($foreignKey, $entity->id)->count();

        // Group invoices by country for this entity
        $invoicesByCountry = $this->modelInvoice::where($foreignKey, $entity->id)
            ->selectRaw('country_of_origin, COUNT(*) as count')
            ->groupBy('country_of_origin')
            ->pluck('count', 'country_of_origin');

        // Count distinct users that have invoices with this entity
        $userCount = $this->modelUser::whereIn('id', function ($query) use ($foreignKey, $entity) {
            $query->select('user_id')
                ->from('invoices')
                ->where($foreignKey, $entity->id);
        })->count();

        return response()->json([
            "{$foreignKey}" => $entity->id,
            'total_users' => $userCount,
            'total_invoices' => $invoiceCount,
            'invoices_by_country' => $invoicesByCountry
        ], 200);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'error' => "$label nije pronađen.",
            'message' => $e->getMessage()
        ], 404);
    } catch (Exception $e) {
        return response()->json([
            'error' => "Neuspješno preuzeta statistika {$label}a",
            'message' => $e->getMessage()
        ], 500);
    }
}




    /**
     * Get annual profit for a specific supplier.
     */
    public function getEntityLastYearProfit(Request $request, $id)
{
    ['model' => $model, 'label' => $label] = $this->resolveEntityFromRequest($request);

    $foreignKey = strtolower($label) . '_id';
    $entity = $model::find($id);

    if (!$entity) {
        return response()->json(['error' => "$label nije pronađen"], 404);
    }

    $profit = $entity->getLastYearProfit();

    return response()->json([
        "{$foreignKey}" => $entity->id,
        'last_year_profit' => $profit,
    ]);
}



    public function getEntityAnnualProfit(Request $request, $id)
{
    ['model' => $model, 'label' => $label] = $this->resolveEntityFromRequest($request);
    $foreignKey = strtolower($label) . '_id';

    $entity = $model::find($id);

    if (!$entity) {
        return response()->json(['error' => "$label nije pronađen"], 404);
    }

    $now = Carbon::now();
    $created = Carbon::parse($entity->created_at);
    $yearsActive = max($created->diffInYears($now), 1);

    $totalProfit = $this->modelInvoice::where($foreignKey, $entity->id)->sum('total_price');
    $annualProfitAvg = $totalProfit / $yearsActive;

    return response()->json([
        "{$foreignKey}" => $entity->id,
        'total_profit' => $totalProfit,
        'years_active' => $yearsActive,
        'annual_profit_avg' => round($annualProfitAvg, 2),
    ]);
}


    public function getEntityProfitChange(Request $request, $id)
{
    ['model' => $model, 'label' => $label] = $this->resolveEntityFromRequest($request);

    $foreignKey = strtolower($label) . '_id';
    $entity = $model::find($id);

    if (!$entity) {
        return response()->json(['error' => "$label nije pronađen"], 404);
    }

    return response()->json([
        "{$foreignKey}" => $entity->id,
        'last_year_profit' => $entity->getLastYearProfit(),
        'current_year_profit' => $entity->getCurrentYearProfit(),
        'percentage_change' => round($entity->getProfitPercentageChange(), 2),
    ]);
}


public function getAllUserStatistics()
{
    $users = User::all();

    $response = $users->map(function ($user) {
        $totalInvoices = Invoice::where('user_id', $user->id)->count();

        $userPackage = UserPackage::with('package')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->first();

        
        $package = $userPackage?->package;

        return [
            'user_id' => $user->id,
            'total_invoices' => $totalInvoices,
            'used_scans' => $userPackage?->used_scans ?? 0,
            'remaining_scans' => $userPackage?->remaining_scans ?? 0,
            'expiration_date' => $userPackage?->expiration_date,
            'active' => $userPackage?->active ?? 0,
            'assigned_at' => $userPackage?->created_at,
            'package_name' => $package?->name ?? null,
            'package_description' => $package?->description ?? null,
            'page_limit' => $package?->page_limit ?? 0,
            'document_history' => $package?->document_history ?? 0,
        ];
    });

    return response()->json($response);
}



}
