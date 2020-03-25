<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchasing;
use DB;

class ReportController extends Controller
{
    public function index(){
    	return view('report.index');
    }

    public function sumPurchasingByYear(Request $request){

        if($request->ajax()) {

            $array_tahun = array();
            $array_bulan = array();
            $array_data = array();

            if($request->tahun == ''){
                $data_tahun = DB::table('purchasings')
                        ->join('products', 'products.id', '=', 'purchasings.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw('SUM(purchasings.quantity) AS sum, products.name AS produk, categories.name, categories.id AS category_id, YEAR(purchasings.created_at) AS year'))
                        //->where(DB::raw('YEAR(purchasings.created_at)'), '=',  '2020')
                        ->whereYear('purchasings.created_at', '=', date('Y'))
                        ->groupBy('categories.name')
                        ->get();
            }else{
                $data_tahun = DB::table('purchasings')
                        ->join('products', 'products.id', '=', 'purchasings.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw('SUM(purchasings.quantity) AS sum, products.name AS produk, categories.name, categories.id AS category_id, YEAR(purchasings.created_at) AS year'))
                        ->where(DB::raw('YEAR(purchasings.created_at)'), '=',  $request->tahun)
                        ->groupBy('categories.name')
                        ->get();
            }
            
            foreach($data_tahun as $dt){
                $jumlah = intval($dt->sum);
                $data_year['name'] = $dt->name;
                $data_year['y'] = $jumlah;
                $data_year['drilldown'] = $dt->name;

                array_push($array_tahun, $data_year);
            }

            $data_y = [
                [
                    'colorByPoint' => true,
                    'name' => 'Kategori Barang',
                    'data' => $array_tahun
                ]
            ];
            
            foreach($data_tahun as $dt){

                if($request->tahun == ''){

                    $data_bulan = DB::table('purchasings')
                            ->join('products', 'products.id', '=', 'purchasings.product_id')
                            ->join('categories', 'categories.id', '=', 'products.category_id')
                            ->select(DB::raw('SUM(purchasings.quantity) as sum, products.name AS produk, categories.name, YEAR(purchasings.created_at) AS year'))
                            ->where(DB::raw('categories.id'), '=',  $dt->category_id)
                            //->where(DB::raw('YEAR(purchasings.created_at)'), '=',  '2020')
                            ->whereYear('purchasings.created_at', '=', date('Y'))
                            ->groupBy('products.name')
                            ->get();

                }else{
                    $data_bulan = DB::table('purchasings')
                            ->join('products', 'products.id', '=', 'purchasings.product_id')
                            ->join('categories', 'categories.id', '=', 'products.category_id')
                            ->select(DB::raw('SUM(purchasings.quantity) as sum, products.name AS produk, categories.name, YEAR(purchasings.created_at) AS year'))
                            ->where(DB::raw('categories.id'), '=',  $dt->category_id)
                            ->where(DB::raw('YEAR(purchasings.created_at)'), '=',  $request->tahun)
                            ->groupBy('products.name')
                            ->get();
                }

                $data_month_h['colorByPoint'] = true;
                $data_month_h['name'] = $dt->name;
                $data_month_h['id'] = $dt->name;
                $data_month_h['data'] = array();
        
                foreach($data_bulan as $dm){
                    $jumlah = intval($dm->sum);
                    $data_month = $dm->produk;
                    $data_month = $dm->sum;
                    $data_month_h['data'][] = [$dm->produk, $jumlah];

                }

                array_push($array_bulan, $data_month_h);
            }

            $array_hm['barang'] = $array_bulan;

            array_push($array_data, $array_hm);

            $response = array(
                'kategori' => $data_y,
                'barang' => $array_bulan,
            );

            echo json_encode($response);
        }
    }

    public function sumPurchasingTest(){

        $array_tahun = array();
        $array_bulan = array();
        $array_data = array();

        $data_tahun = DB::table('purchasings')
                ->join('products', 'products.id', '=', 'purchasings.product_id')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->select(DB::raw('SUM(purchasings.quantity) AS sum, products.name AS produk, categories.name, categories.id AS category_id, YEAR(purchasings.created_at) AS year'))
                ->where(DB::raw('YEAR(purchasings.created_at)'), '=',  '2020')
                ->groupBy('categories.name')
                ->get();
        
        foreach($data_tahun as $dt){
            $jumlah = intval($dt->sum);
            $data_year['name'] = $dt->name;
            $data_year['y'] = $jumlah;
            $data_year['drilldown'] = $dt->name;

            array_push($array_tahun, $data_year);
        }

        $data_y = [
            [
                'name' => 'Kategori Barang',
                'data' => $array_tahun
            ]
        ];
        
        foreach($data_tahun as $dt){

            $data_bulan = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw('SUM(purchasings.quantity) as sum, products.name AS produk, categories.name, YEAR(purchasings.created_at) AS year'))
                    ->where(DB::raw('categories.id'), '=',  $dt->category_id)
                    ->where(DB::raw('YEAR(purchasings.created_at)'), '=',  '2020')
                    ->groupBy('products.name')
                    ->get();

            $data_month_h['name'] = $dt->name;
            $data_month_h['id'] = $dt->name;
            $data_month_h['data'] = array();
    
            foreach($data_bulan as $dm){
                $jumlah = intval($dm->sum);
                $data_month = $dm->produk;
                $data_month = $dm->sum;
                $data_month_h['data'][] = [$dm->produk, $jumlah];

            }

            array_push($array_bulan, $data_month_h);
        }

        $array_hm['barang'] = $array_bulan;

        array_push($array_data, $array_hm);

        $response = array(
            'kategori' => $data_y,
            'barang' => $array_bulan,
        );

        echo json_encode($response);
        
    }

    public function sumPurchasingByMonth(){

        $data = DB::table('purchasings')
                    ->select(DB::raw('sum(total_price) as sum, MONTH(created_at) AS month, YEAR(created_at) AS year'))
                    ->where(DB::raw('YEAR(created_at)'), '=',  '2020')
                    ->groupBy('year')
                    ->get();
        
        echo json_encode($data);
    }

    public function sumSaleByCategoryByMonth(Request $request){
        if($request->ajax()) {
            
        }
    }
}
