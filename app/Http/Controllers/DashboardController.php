<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){

        // $chart_options = [
        //     'chart_title' => 'Products by Month',
        //     'chart_type' => 'bar',
        //     'report_type' => 'group_by_date',
        //     'model' => 'App\Product',
        //     'group_by_field' => 'created_at',
        //     'group_by_period' => 'month',
        //     'filter_field' => 'created_at',
        //     'filter_days' => 1000,
        // ];
        // $chart = new LaravelChart($chart_options);

        // $response_year = array();
        // $r_year = array();
        // $response_year_to_month = array();
        // $response_month_to_date = array();

        // $p_year = DB::table('products')
        //              ->select(DB::raw('YEAR(created_at) as monthyear'))
        //              ->groupBy('monthyear')
        //              ->get();
        
        // foreach($p_year as $yy){
        //     $d_year = $yy->monthyear;
        //     array_push($r_year, $d_year);
        // }
        
        // $product_year = DB::table('products')
        //              ->select(DB::raw('count(*) as total, id, name, stock, YEAR(created_at) as monthyear'))
        //              ->groupBy('monthyear')
        //              ->get();

        // foreach($product_year as $py){
        //     $data_year['name'] = $py->monthyear;
        //     $data_year['y'] = $py->total;
        //     $data_year['drilldown'] = $py->monthyear;

        //     array_push($response_year, $data_year);
        // }

        // foreach($product_year as $py){

        //     $data_month = array();

        //     //$data_month['type'] = 'pie';

        //     $product_month = DB::table('products')
        //              ->select(DB::raw('count(*) as total, id, name, stock, YEAR(created_at) as monthyear, MONTH(created_at) as month'))
        //              ->where('created_at', 'like', '%'.$py->monthyear.'%')
        //              ->groupBy('month')
        //              ->get();

        //     foreach ($product_month as $pm) {

        //         $data_month['name'] = $pm->month;
        //         $data_month['y'] = $pm->total;
        //         $data_month['drilldown'] = $pm->month;

        //     } 

        //     array_push($response_year_to_month, $data_month);
        // }

        // foreach($product_month as $pm){
        //     $data_date = array();

        //     $data_date['id'] = $pm->month;
        //     $data_date['data'] = array();

        //     $product_date = DB::table('products')
        //              ->select(DB::raw('count(*) as total, id, name, stock, YEAR(created_at) as monthyear, MONTH(created_at) as month, DATE(created_at) as date'))
        //              ->where('created_at', 'like', '%'.$pm->month.'%')
        //              ->groupBy('date')
        //              ->get();

        //     foreach ($product_date as $pd) {

        //         $data_date_detail['name'] = $pd->date;
        //         $data_date_detail['y'] = $pd->total;
        //         $data_date_detail['drilldown'] = $pd->date;

        //         $data_date['data'][] = $data_date_detail;

        //         // $objDate = new \stdClass();
        //         // $objDate->id = $pm->month;
        //         // $objDate->data = array(
        //         //     $data_date_detail
        //         // );
        //     }

        //     array_push($response_month_to_date, $data_date);
        // }

        // //dd(json_encode($response_year_to_month));

    	// //return view('dashboard.index', ['data_year' => $response_year, 'tahun' => $r_year, 'data_bulan' => $response_year_to_month, 'data_tanggal' => $response_month_to_date]);
    	return view('dashboard.index');
    }
}