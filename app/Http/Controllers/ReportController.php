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
                'colorByPoint' => true,
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

    public function sumPurchasingByMonth(Request $request){

        if($request->ajax()) {

            $array_tahun = array();
            $array_bulan = array();
            $array_data = array();

            if($request->tahun == ''){
                $data_category = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw('categories.id, categories.name AS Kategori'))
                    ->whereYear(DB::raw('purchasings.created_at'), '=',  date('Y'))
                    ->groupBy('categories.id')
                    ->get();
            }else{
                $data_category = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw('categories.id, categories.name AS Kategori'))
                    ->whereYear(DB::raw('purchasings.created_at'), '=',  $request->tahun)
                    ->groupBy('categories.id')
                    ->get();
            }

            foreach ($data_category as $c) {

                if($request->tahun == ''){
                    $data_bulan = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw("categories.id, categories.name AS Kategori,
                            SUM(IF(MONTH(purchasings.created_at) = '1', purchasings.quantity, 0)) AS Januari,
                            SUM(IF(MONTH(purchasings.created_at) = '2', purchasings.quantity, 0)) AS Februari,
                            SUM(IF(MONTH(purchasings.created_at) = '3', purchasings.quantity, 0)) AS Maret,
                            SUM(IF(MONTH(purchasings.created_at) = '4', purchasings.quantity, 0)) AS April,
                            SUM(IF(MONTH(purchasings.created_at) = '5', purchasings.quantity, 0)) AS Mei,
                            SUM(IF(MONTH(purchasings.created_at) = '6', purchasings.quantity, 0)) AS Juni,
                            SUM(IF(MONTH(purchasings.created_at) = '7', purchasings.quantity, 0)) AS Juli,
                            SUM(IF(MONTH(purchasings.created_at) = '8', purchasings.quantity, 0)) AS Agustus,
                            SUM(IF(MONTH(purchasings.created_at) = '9', purchasings.quantity, 0)) AS September,
                            SUM(IF(MONTH(purchasings.created_at) = '10', purchasings.quantity, 0)) AS Oktober,
                            SUM(IF(MONTH(purchasings.created_at) = '11', purchasings.quantity, 0)) AS Nopember,
                            SUM(IF(MONTH(purchasings.created_at) = '12', purchasings.quantity, 0)) AS Desember"))
                    ->where(DB::raw('categories.id'), '=',  $c->id)
                    ->whereYear(DB::raw('purchasings.created_at'), '=',  date('Y'))
                    ->groupBy('categories.id')
                    ->get();
                }else{
                    $data_bulan = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw("categories.id, categories.name AS Kategori,
                            SUM(IF(MONTH(purchasings.created_at) = '1', purchasings.quantity, 0)) AS Januari,
                            SUM(IF(MONTH(purchasings.created_at) = '2', purchasings.quantity, 0)) AS Februari,
                            SUM(IF(MONTH(purchasings.created_at) = '3', purchasings.quantity, 0)) AS Maret,
                            SUM(IF(MONTH(purchasings.created_at) = '4', purchasings.quantity, 0)) AS April,
                            SUM(IF(MONTH(purchasings.created_at) = '5', purchasings.quantity, 0)) AS Mei,
                            SUM(IF(MONTH(purchasings.created_at) = '6', purchasings.quantity, 0)) AS Juni,
                            SUM(IF(MONTH(purchasings.created_at) = '7', purchasings.quantity, 0)) AS Juli,
                            SUM(IF(MONTH(purchasings.created_at) = '8', purchasings.quantity, 0)) AS Agustus,
                            SUM(IF(MONTH(purchasings.created_at) = '9', purchasings.quantity, 0)) AS September,
                            SUM(IF(MONTH(purchasings.created_at) = '10', purchasings.quantity, 0)) AS Oktober,
                            SUM(IF(MONTH(purchasings.created_at) = '11', purchasings.quantity, 0)) AS Nopember,
                            SUM(IF(MONTH(purchasings.created_at) = '12', purchasings.quantity, 0)) AS Desember"))
                    ->where(DB::raw('categories.id'), '=',  $c->id)
                    ->whereYear(DB::raw('purchasings.created_at'), '=',  $request->tahun)
                    ->groupBy('categories.id')
                    ->get();
                }

                $data_month_h['name'] = $c->Kategori;
                $data_month_h['data'] = array();

                foreach($data_bulan as $dm){
                    if($request->semester == ''){
                        if(date('M') <= 6){
                            $semester = [
                                intval($dm->Januari),
                                intval($dm->Februari),
                                intval($dm->Maret),
                                intval($dm->April),
                                intval($dm->Mei),
                                intval($dm->Juni),
                            ];
                        }else{
                            $semester = [
                                intval($dm->Juli),
                                intval($dm->Agustus),
                                intval($dm->September),
                                intval($dm->Oktober),
                                intval($dm->Nopember),
                                intval($dm->Desember),
                            ];
                        }
                    }else{
                        if($request->semester == '1'){
                            $semester = [
                                intval($dm->Januari),
                                intval($dm->Februari),
                                intval($dm->Maret),
                                intval($dm->April),
                                intval($dm->Mei),
                                intval($dm->Juni),
                            ];
                        }else{
                            $semester = [
                                intval($dm->Juli),
                                intval($dm->Agustus),
                                intval($dm->September),
                                intval($dm->Oktober),
                                intval($dm->Nopember),
                                intval($dm->Desember),
                            ];
                        }
                    }
                    $data_month_h['data'] = $semester;
                }

                array_push($array_bulan, $data_month_h);
            }

            if($request->semester == '1'){
                $bulan = [
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                ];
            }else{
                $bulan = [
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'Nopember',
                    'Desember',
                ];
            }

            $response = array(
                'kategori_barang' => $array_bulan,
                'bulan' => $bulan
            );
            
            echo json_encode($response);
            
        }
        
    }

    public function sumPurchasingByMonthTest(){

        $array_tahun = array();
        $array_bulan = array();
        $array_data = array();

        $data_category = DB::table('purchasings')
                ->join('products', 'products.id', '=', 'purchasings.product_id')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->select(DB::raw('categories.id, categories.name AS Kategori'))
                ->whereYear(DB::raw('purchasings.created_at'), '=',  date('Y'))
                ->groupBy('categories.id')
                ->get();

        foreach ($data_category as $c) {

            $data_bulan = DB::table('purchasings')
                ->join('products', 'products.id', '=', 'purchasings.product_id')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->select(DB::raw("categories.id, categories.name AS Kategori,
                        SUM(IF(MONTH(purchasings.created_at) = '1', purchasings.quantity, 0)) AS Januari,
                        SUM(IF(MONTH(purchasings.created_at) = '2', purchasings.quantity, 0)) AS Februari,
                        SUM(IF(MONTH(purchasings.created_at) = '3', purchasings.quantity, 0)) AS Maret,
                        SUM(IF(MONTH(purchasings.created_at) = '4', purchasings.quantity, 0)) AS April,
                        SUM(IF(MONTH(purchasings.created_at) = '5', purchasings.quantity, 0)) AS Mei,
                        SUM(IF(MONTH(purchasings.created_at) = '6', purchasings.quantity, 0)) AS Juni,
                        SUM(IF(MONTH(purchasings.created_at) = '7', purchasings.quantity, 0)) AS Juli,
                        SUM(IF(MONTH(purchasings.created_at) = '8', purchasings.quantity, 0)) AS Agustus,
                        SUM(IF(MONTH(purchasings.created_at) = '9', purchasings.quantity, 0)) AS September,
                        SUM(IF(MONTH(purchasings.created_at) = '10', purchasings.quantity, 0)) AS Oktober,
                        SUM(IF(MONTH(purchasings.created_at) = '11', purchasings.quantity, 0)) AS Nopember,
                        SUM(IF(MONTH(purchasings.created_at) = '12', purchasings.quantity, 0)) AS Desember"))
                ->where(DB::raw('categories.id'), '=',  $c->id)
                ->whereYear(DB::raw('purchasings.created_at'), '=',  date('Y'))
                ->groupBy('categories.id')
                ->get();

            $data_month_h['name'] = $c->Kategori;
            $data_month_h['data'] = array();

            foreach($data_bulan as $dm){
                $data_month_h['data'] = [
                    intval($dm->Januari),
                    intval($dm->Februari),
                    intval($dm->Maret),
                    intval($dm->April),
                    intval($dm->Mei),
                    intval($dm->Juni),
                    intval($dm->Juli),
                    intval($dm->Agustus),
                    intval($dm->September),
                    intval($dm->Oktober),
                    intval($dm->Nopember),
                    intval($dm->Desember),
                ];
            }

            array_push($array_bulan, $data_month_h);
        }

        $semester = '1';

        if($semester == '1'){
            $bulan = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
            ];
        }else{
            $bulan = [
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'Nopember',
                'Desember',
            ];
        }

        $response = array(
            'kategori_barang' => $array_bulan,
            'bulan' => $bulan
        );
        
        echo json_encode($response);        
        
    }
}
