<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\User;
use App\Models\TariffRate;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;

class StatsController extends Controller
{
    /**
     * Get statistics for suppliers, invoices, invoices by country, and remaining scans.
     */
    public function getStatistics()
{
    // Count total users
    $userCount = User::count();

    // Count total suppliers
    $supplierCount = Supplier::count();

    // Count total invoices
    $invoiceCount = Invoice::count();

    // Count invoices grouped by country
    $invoicesByCountry = Invoice::selectRaw('country_of_origin, COUNT(*) as count')
                                ->groupBy('country_of_origin')
                                ->pluck('count', 'country_of_origin');

    return response()->json([
        'total_users' => $userCount,
        'total_suppliers' => $supplierCount,
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
        $user = User::findOrFail($id);
        // Fetch all invoices for the given user
        $invoices = Invoice::where('user_id', $id)->get();

        // Extract unique supplier IDs from invoices
        $supplierIds = $invoices->pluck('supplier_id')->unique();

        // Fetch suppliers by their IDs
        $suppliers = Supplier::whereIn('id', $supplierIds)->get(['id', 'name', 'owner', 'avatar']);

        $supplierCount = Supplier::whereIn('id', function ($query) use ($user) {
            $query->select('supplier_id')->from('invoices')->where('user_id', $user->id);
        })->count();

        $invoiceCount = Invoice::where('user_id', $user->id)->count();

        $invoicesByCountry = Invoice::where('user_id', $user->id)
            ->selectRaw('country_of_origin, COUNT(*) as count')
            ->groupBy('country_of_origin')
            ->pluck('count', 'country_of_origin');

        $usedScans = Invoice::where('user_id', $user->id)
            ->whereNotNull('task_id')
            ->count();

        $totalScans = Package::whereIn('id', function ($query) use ($user) {
            $query->select('package_id')->from('user_packages')->where('user_id', $user->id);
        })->sum('available_scans');

        $remainingScans = max($totalScans - $usedScans, 0);

        $topSuppliers = Supplier::select('suppliers.id', 'suppliers.name', 'suppliers.owner')
            ->join('invoices', 'suppliers.id', '=', 'invoices.supplier_id')
            ->where('invoices.user_id', $user->id)
            ->groupBy('suppliers.id', 'suppliers.name', 'suppliers.owner')
            ->get()
            ->map(function ($supplier) {
                $fullSupplier = Supplier::find($supplier->id);
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'owner' => $supplier->owner,
                    'annual_profit' => $fullSupplier ? $fullSupplier->getAnnualProfitAvg() : 0
                ];
            })
            ->sortByDesc('annual_profit')
            ->values();

        $latestSuppliers = Supplier::select('suppliers.id', 'suppliers.name', 'suppliers.owner')
            ->selectSub(function ($query) use ($user) {
                $query->from('invoices')
                    ->whereColumn('supplier_id', 'suppliers.id')
                    ->where('user_id', $user->id)
                    ->selectRaw('MAX(created_at)');
            }, 'latest_invoice_date')
            ->whereIn('id', function ($query) use ($user) {
                $query->select('supplier_id')
                        ->from('invoices')
                        ->where('user_id', $user->id);
            })
            ->orderByDesc('latest_invoice_date')
            ->get();
            

        $itemCodes = Invoice::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->with('items')
            ->get()
            ->flatMap(fn($invoice) => $invoice->items->pluck('item_code'))
            ->unique()
            ->filter()
            ->values();
        
        // Count how many times each item_code appears across all invoice_items
        $itemCodeCounts = InvoiceItem::whereIn('item_code', $itemCodes)
            ->selectRaw('item_code, COUNT(*) as total')
            ->groupBy('item_code')
            ->pluck('total', 'item_code'); // returns ['ABC123' => 7, 'XYZ456' => 12, ...]
        
        $latestTariffs = TariffRate::whereIn('item_code', $itemCodes)
            ->get(['id', 'item_code', 'name', 'tariff_rate']) // include item_code for matching
            ->map(function ($tariff) use ($itemCodeCounts) {
                return [
                    'id' => $tariff->id,
                    'name' => $tariff->name,
                    'tariff_rate' => $tariff->tariff_rate,
                    'item_usage_count' => $itemCodeCounts[$tariff->item_code] ?? 0
                ];
            })
            ->values();
        

        // ✅ Supplier profit change section
        $supplierProfitChanges = Supplier::whereIn('id', function ($query) use ($user) {
            $query->select('supplier_id')->from('invoices')->where('user_id', $user->id);
        })->get()
        ->map(function ($supplier) {
            return [
                'supplier_id' => $supplier->id,
                'name' => $supplier->name,
                'owner' => $supplier->owner,
                'last_year_profit' => $supplier->getLastYearProfit(),
                'current_year_profit' => $supplier->getCurrentYearProfit(),
                'percentage_change' => round($supplier->getProfitPercentageChange(), 2)."%",
            ];
        });

        return response()->json([
            'user_id' => $user->id,
            'total_suppliers' => $supplierCount,
            'suppliers' => $suppliers,
            'total_invoices' => $invoiceCount,
            'invoices_by_country' => $invoicesByCountry,
            'used_scans' => $usedScans,
            'remaining_scans' => $remainingScans,
            'top_suppliers' => $topSuppliers,
            'latest_suppliers' => $latestSuppliers,
            'latest_tariffs' => $latestTariffs,
            'supplier_profit_changes' => $supplierProfitChanges,
        ], 200);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'error' => 'User not found',
            'message' => $e->getMessage()
        ], 404);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Failed to retrieve user statistics',
            'message' => $e->getMessage()
        ], 500);
    }
}



    /**
     * Get statistics for a specific supplier by ID.
     */
    public function getSupplierStatisticsById($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Count invoices associated with the supplier
            $invoiceCount = Invoice::where('supplier_id', $supplier->id)->count();

            // Count invoices grouped by country for the supplier
            $invoicesByCountry = Invoice::where('supplier_id', $supplier->id)
                                        ->selectRaw('country_of_origin, COUNT(*) as count')
                                        ->groupBy('country_of_origin')
                                        ->pluck('count', 'country_of_origin');

            // Count distinct users associated with this supplier via invoices
            $userCount = User::whereIn('id', function ($query) use ($supplier) {
                $query->select('user_id')->from('invoices')->where('supplier_id', $supplier->id);
            })->count();

            return response()->json([
                'supplier_id' => $supplier->id,
                'total_users' => $userCount,
                'total_invoices' => $invoiceCount,
                'invoices_by_country' => $invoicesByCountry
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Supplier not found',
                'message' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve supplier statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get annual profit for a specific supplier.
     */
    public function getSupplierLastYearProfit($supplierId)
{
    $supplier = Supplier::find($supplierId);

    if (!$supplier) {
        return response()->json(['error' => 'Supplier not found'], 404);
    }

    $profit = $supplier->getLastYearProfit();

    return response()->json([
        'supplier_id' => $supplier->id,
        'last_year_profit' => $profit
    ]);
}


    public function getSupplierAnnualProfit($supplierId)
{
    $supplier = Supplier::find($supplierId);

    if (!$supplier) {
        return response()->json(['error' => 'Supplier not found'], 404);
    }

    $now = Carbon::now();
    $supplierCreationDate = Carbon::parse($supplier->created_at);
    $yearsActive = max($supplierCreationDate->diffInYears($now), 1);

    $totalProfit = Invoice::where('supplier_id', $supplier->id)->sum('total_price');

    // Calculate the average annual profit
    $annualProfitAvg = $totalProfit / $yearsActive;

    return response()->json([
        'supplier_id' => $supplier->id,
        'total_profit' => $totalProfit,
        'years_active' => $yearsActive,
        'annual_profit_avg' => round($annualProfitAvg, 2)
    ]);
}

    public function getSupplierProfitChange($supplierId)
    {
        $supplier = Supplier::find($supplierId);

        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        return response()->json([
            'supplier_id' => $supplier->id,
            'last_year_profit' => $supplier->getLastYearProfit(),
            'current_year_profit' => $supplier->getCurrentYearProfit(),
            'percentage_change' => round($supplier->getProfitPercentageChange(), 2)
        ]);
    }


}
