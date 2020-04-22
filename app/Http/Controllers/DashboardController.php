<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\DB;
use App\Sale;
use App\Supplier;

class DashboardController extends Controller
{
    public function index(){
        $sale = Sale::selectRaw('SUM(total_price) AS revenue')
                    ->whereYear('created_at', '=', date('Y'))
                    ->first();
        $revenue = $sale->revenue;
        $customer = Sale::groupBy('customer_name')->get();
        $customerCount = $customer->count();
        $supplier = Supplier::all();
        $supplierCount = $supplier->count();
        return view('dashboard.index', ['revenue' => $revenue, 'customer' => $customerCount, 'supplier' => $supplierCount]);
    }
}