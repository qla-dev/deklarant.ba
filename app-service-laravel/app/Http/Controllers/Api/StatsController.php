<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

            // Get supplier count via invoices (distinct suppliers associated with the user)
            $supplierCount = Supplier::whereIn('id', function ($query) use ($user) {
                $query->select('supplier_id')->from('invoices')->where('user_id', $user->id);
            })->count();

            // Count invoices associated with the user
            $invoiceCount = Invoice::where('user_id', $user->id)->count();

            // Count invoices grouped by country for the user
            $invoicesByCountry = Invoice::where('user_id', $user->id)
                                        ->selectRaw('country_of_origin, COUNT(*) as count')
                                        ->groupBy('country_of_origin')
                                        ->pluck('count', 'country_of_origin');


            // Used scans = number of invoices for the user where scanned = 1
            $usedScans = Invoice::where('user_id', $user->id)
                                ->where('scanned', 1)
                                ->count();


            // Calculate remaining scans for the user via `UserPackage`
            $totalScans = Package::whereIn('id', function ($query) use ($user) {
                $query->select('package_id')->from('user_packages')->where('user_id', $user->id);
            })->sum('available_scans');

            $remainingScans = max($totalScans - $usedScans, 0);

            return response()->json([
                'user_id' => $user->id,
                'total_suppliers' => $supplierCount,
                'total_invoices' => $invoiceCount,
                'invoices_by_country' => $invoicesByCountry,
                'used_scans' => $usedScans,
                'remaining_scans' => $remainingScans
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

        $oneYearAgo = Carbon::now()->subYear(); // Get date one year ago

        // Sum total invoice prices for the past year for this supplier
        $lastYearProfit = Invoice::where('supplier_id', $supplier->id)
                            ->where('created_at', '>=', $oneYearAgo)
                            ->sum('total_price');

        return response()->json([
            'supplier_id' => $supplier->id,
            'annual_profit' => $lastYearProfit
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
    
    // Calculate the number of full years since supplier was created
    $yearsActive = $supplierCreationDate->diffInYears($now);
    
    // Ensure at least 1 year (avoid division by zero)
    $yearsActive = max($yearsActive, 1);

    // Sum total invoice prices for all time for this supplier
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

}
