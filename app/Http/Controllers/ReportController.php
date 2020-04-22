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
                        ->select(DB::raw('SUM(purchasings.quantity) AS sum, products.name AS produk, categories.name, categories.id AS category_id, YEAR(purchasings.order_date) AS year'))
                        ->whereYear('purchasings.order_date', '=', date('Y'))
                        ->groupBy('categories.id')
                        ->get();
            }else{
                $data_tahun = DB::table('purchasings')
                        ->join('products', 'products.id', '=', 'purchasings.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw('SUM(purchasings.quantity) AS sum, products.name AS produk, categories.name, categories.id AS category_id, YEAR(purchasings.order_date) AS year'))
                        ->where(DB::raw('YEAR(purchasings.order_date)'), '=',  $request->tahun)
                        ->groupBy('categories.id')
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
                            ->select(DB::raw('SUM(purchasings.quantity) as sum, products.name AS produk, categories.name, YEAR(purchasings.order_date) AS year'))
                            ->where(DB::raw('categories.id'), '=',  $dt->category_id)
                            ->whereYear('purchasings.order_date', '=', date('Y'))
                            ->groupBy('products.id')
                            ->get();

                }else{
                    $data_bulan = DB::table('purchasings')
                            ->join('products', 'products.id', '=', 'purchasings.product_id')
                            ->join('categories', 'categories.id', '=', 'products.category_id')
                            ->select(DB::raw('SUM(purchasings.quantity) as sum, products.name AS produk, categories.name, YEAR(purchasings.order_date) AS year'))
                            ->where(DB::raw('categories.id'), '=',  $dt->category_id)
                            ->where(DB::raw('YEAR(purchasings.order_date)'), '=',  $request->tahun)
                            ->groupBy('products.id')
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
                ->select(DB::raw('SUM(purchasings.quantity) AS sum, products.name AS produk, categories.name, categories.id AS category_id, YEAR(purchasings.order_date) AS year'))
                ->where(DB::raw('YEAR(purchasings.order_date)'), '=',  '2020')
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
                    ->select(DB::raw('SUM(purchasings.quantity) as sum, products.name AS produk, categories.name, YEAR(purchasings.order_date) AS year'))
                    ->where(DB::raw('categories.id'), '=',  $dt->category_id)
                    ->where(DB::raw('YEAR(purchasings.order_date)'), '=',  '2020')
                    ->groupBy('products.id')
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
            $bulan_k = array();
            if($request->tahun == ''){
                if($request->semester == ''){
                    if(date('M') <= 6){
                        $semester = [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6'
                        ];
                    }else{
                        $semester = [
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12'
                        ];
                    }
                }else{
                    if($request->semester == '1'){
                        $semester = [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6'
                        ];
                    }else{
                        $semester = [
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12'
                        ];
                    }
                }
                $data_category = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw('categories.name AS Kategori, MONTH(purchasings.order_date) AS bulan'))
                    ->whereYear(DB::raw('purchasings.order_date'), '=',  date('Y'))
                    ->whereIn(DB::raw('MONTH(purchasings.order_date)'), $semester)
                    ->groupBy('Kategori')
                    ->get();
            }else{
                if($request->semester == ''){
                    if(date('M') <= 6){
                        $semester = [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6'
                        ];
                    }else{
                        $semester = [
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12'
                        ];
                    }
                }else{
                    if($request->semester == '1'){
                        $semester = [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6'
                        ];
                    }else{
                        $semester = [
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12'
                        ];
                    }
                }
                $data_category = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw('purchasings.order_date, categories.name AS Kategori, MONTH(purchasings.order_date) AS bulan'))
                    ->whereYear(DB::raw('purchasings.order_date'), '=',  $request->tahun)
                    ->whereIn(DB::raw('MONTH(purchasings.order_date)'), $semester)
                    ->groupBy('Kategori')
                    ->get();
            }
            foreach ($data_category as $c) {
                if($request->tahun == ''){
                    if($request->semester == ''){
                        if(date('M') <= 6){
                            $semester = [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6'
                            ];
                        }else{
                            $semester = [
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12'
                            ];
                        }
                    }else{
                        if($request->semester == '1'){
                            $semester = [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6'
                            ];
                        }else{
                            $semester = [
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12'
                            ];
                        }
                    }
                    $data_bulan = DB::table('purchasings')
                        ->join('products', 'products.id', '=', 'purchasings.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw("SUM(purchasings.quantity) AS jumlah, categories.name AS Kategori, MONTH(purchasings.order_date) AS bulan"))
                        ->whereYear(DB::raw('purchasings.order_date'), '=',  date('Y'))
                        ->where(DB::raw('categories.name'), '=', $c->Kategori)
                        ->whereIn(DB::raw('MONTH(purchasings.order_date)'), $semester)
                        ->groupBy('bulan')
                        ->get();
                }else{
                    if($request->semester == ''){
                        if(date('M') <= 6){
                            $semester = [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6'
                            ];
                        }else{
                            $semester = [
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12'
                            ];
                        }
                    }else{
                        if($request->semester == '1'){
                            $semester = [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6'
                            ];
                        }else{
                            $semester = [
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12'
                            ];
                        }
                    }
                    $data_bulan = DB::table('purchasings')
                        ->join('products', 'products.id', '=', 'purchasings.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw("SUM(purchasings.quantity) AS jumlah, categories.name AS Kategori, MONTH(purchasings.order_date) AS bulan"))
                        ->whereYear(DB::raw('purchasings.order_date'), '=', $request->tahun)
                        ->where(DB::raw('categories.name'), '=', $c->Kategori)
                        ->whereIn(DB::raw('MONTH(purchasings.order_date)'), $semester)
                        ->groupBy('bulan')
                        ->get();
                }
                $data_month_h['name'] = $c->Kategori;
                $data_month_h['data'] = array();
                foreach($data_bulan as $dm){
                    if($request->tahun == ''){
                        if($dm->bulan == 1){
                            $data_b = 'Januari';
                        }else if($dm->bulan == 2){
                            $data_b = 'Februari';
                        }else if($dm->bulan == 3){
                            $data_b = 'Maret';
                        }else if($dm->bulan == 4){
                            $data_b = 'April';
                        }else if($dm->bulan == 5){
                            $data_b = 'Mei';
                        }else if($dm->bulan == 6){
                            $data_b = 'Juni';
                        }else if($dm->bulan == 7){
                            $data_b = 'Juli';
                        }else if($dm->bulan == 8){
                            $data_b = 'Agustus';
                        }else if($dm->bulan == 9){
                            $data_b = 'September';
                        }else if($dm->bulan == 10){
                            $data_b = 'Oktober';
                        }else if($dm->bulan == 11){
                            $data_b = 'Nopember';
                        }else if($dm->bulan == 12){
                            $data_b = 'Desember';
                        }
                        $jumlah = intval($dm->jumlah);
                        $data_month_h['data'][] = [
                            'name' => $data_b,
                            'y' => $jumlah,
                            'drilldown' => $c->Kategori.' '.$request->semester.' '.$data_b.' '.date('Y')
                        ];
                    }else{
                        if($dm->bulan == 1){
                            $data_b = 'Januari';
                        }else if($dm->bulan == 2){
                            $data_b = 'Februari';
                        }else if($dm->bulan == 3){
                            $data_b = 'Maret';
                        }else if($dm->bulan == 4){
                            $data_b = 'April';
                        }else if($dm->bulan == 5){
                            $data_b = 'Mei';
                        }else if($dm->bulan == 6){
                            $data_b = 'Juni';
                        }else if($dm->bulan == 7){
                            $data_b = 'Juli';
                        }else if($dm->bulan == 8){
                            $data_b = 'Agustus';
                        }else if($dm->bulan == 9){
                            $data_b = 'September';
                        }else if($dm->bulan == 10){
                            $data_b = 'Oktober';
                        }else if($dm->bulan == 11){
                            $data_b = 'Nopember';
                        }else if($dm->bulan == 12){
                            $data_b = 'Desember';
                        }
                        $jumlah = intval($dm->jumlah);
                        $data_month_h['data'][] = [
                            'name' => $data_b,
                            'y' => $jumlah,
                            'drilldown' => $c->Kategori.' '.$request->semester.' '.$data_b.' '.$request->tahun
                        ];
                    }
                }
                array_push($array_bulan, $data_month_h);
            }

            if($request->tahun == ''){
                if($request->semester == '1'){
                    $semester = [
                        '1',
                        '2',
                        '3',
                        '4',
                        '5',
                        '6'
                    ];
                }else{
                    $semester = [
                        '7',
                        '8',
                        '9',
                        '10',
                        '11',
                        '12'
                    ];
                }
                $data_category = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw("categories.name AS Kategori, MONTH(purchasings.order_date) AS bulan"))
                    ->whereYear(DB::raw('purchasings.order_date'), '=',  date('Y'))
                    ->whereIn(DB::raw('MONTH(purchasings.order_date)'), $semester)
                    //->groupBy('purchasings.order_date')
                    ->get();
                foreach ($data_category as $c) {
                    $data_bulan = DB::table('purchasings')
                        ->join('products', 'products.id', '=', 'purchasings.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw("SUM(purchasings.quantity) AS jumlah, categories.name AS Kategori, MONTH(purchasings.order_date) AS bulan, purchasings.order_date AS tanggal"))
                        ->whereYear(DB::raw('purchasings.order_date'), '=', date('Y'))
                        ->where(DB::raw('MONTH(purchasings.order_date)'), '=', $c->bulan)
                        ->where(DB::raw('categories.name'), $c->Kategori)
                        ->groupBy('tanggal')
                        ->get();
                    if($c->bulan == 1){
                        $data_b = 'Januari';
                    }else if($c->bulan == 2){
                        $data_b = 'Februari';
                    }else if($c->bulan == 3){
                        $data_b = 'Maret';
                    }else if($c->bulan == 4){
                        $data_b = 'April';
                    }else if($c->bulan == 5){
                        $data_b = 'Mei';
                    }else if($c->bulan == 6){
                        $data_b = 'Juni';
                    }else if($c->bulan == 7){
                        $data_b = 'Juli';
                    }else if($c->bulan == 8){
                        $data_b = 'Agustus';
                    }else if($c->bulan == 9){
                        $data_b = 'September';
                    }else if($c->bulan == 10){
                        $data_b = 'Oktober';
                    }else if($c->bulan == 11){
                        $data_b = 'Nopember';
                    }else if($c->bulan == 12){
                        $data_b = 'Desember';
                    }
        
                    $data_month_k['name'] = $c->Kategori;
                    $data_month_k['id'] = $c->Kategori.' '.$request->semester.' '.$data_b.' '.date('Y');
                    $data_month_k['data'] = array();
            
                    foreach($data_bulan as $dm){
                        $jumlah = intval($dm->jumlah);
                        $data_month_k['data'][] = [
                            'name' => $dm->tanggal,
                            'y' => $jumlah
                        ];
                    }
        
                    array_push($bulan_k, $data_month_k);
                }
            }else{
                if($request->semester == '1'){
                    $semester = [
                        '1',
                        '2',
                        '3',
                        '4',
                        '5',
                        '6'
                    ];
                }else{
                    $semester = [
                        '7',
                        '8',
                        '9',
                        '10',
                        '11',
                        '12'
                    ];
                }

                $data_category = DB::table('purchasings')
                    ->join('products', 'products.id', '=', 'purchasings.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw("categories.name AS Kategori, MONTH(purchasings.order_date) AS bulan"))
                    ->whereYear(DB::raw('purchasings.order_date'), '=',  $request->tahun)
                    ->whereIn(DB::raw('MONTH(purchasings.order_date)'), $semester)
                    //->groupBy('purchasings.order_date')
                    ->get();
                foreach ($data_category as $c) {
                    $data_bulan = DB::table('purchasings')
                        ->join('products', 'products.id', '=', 'purchasings.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw("SUM(purchasings.quantity) AS jumlah, categories.name AS Kategori, MONTH(purchasings.order_date) AS bulan, purchasings.order_date AS tanggal"))
                        ->whereYear(DB::raw('purchasings.order_date'), '=',  $request->tahun)
                        ->where(DB::raw('MONTH(purchasings.order_date)'), '=', $c->bulan)
                        ->where(DB::raw('categories.name'), $c->Kategori)
                        ->groupBy('tanggal')
                        ->get();
                    if($c->bulan == 1){
                        $data_b = 'Januari';
                    }else if($c->bulan == 2){
                        $data_b = 'Februari';
                    }else if($c->bulan == 3){
                        $data_b = 'Maret';
                    }else if($c->bulan == 4){
                        $data_b = 'April';
                    }else if($c->bulan == 5){
                        $data_b = 'Mei';
                    }else if($c->bulan == 6){
                        $data_b = 'Juni';
                    }else if($c->bulan == 7){
                        $data_b = 'Juli';
                    }else if($c->bulan == 8){
                        $data_b = 'Agustus';
                    }else if($c->bulan == 9){
                        $data_b = 'September';
                    }else if($c->bulan == 10){
                        $data_b = 'Oktober';
                    }else if($c->bulan == 11){
                        $data_b = 'Nopember';
                    }else if($c->bulan == 12){
                        $data_b = 'Desember';
                    }
                    $data_month_k['name'] = $c->Kategori;
                    $data_month_k['id'] = $c->Kategori.' '.$request->semester.' '.$data_b.' '.$request->tahun;
                    $data_month_k['data'] = array();
            
                    foreach($data_bulan as $dm){
                        $jumlah = intval($dm->jumlah);
                        $data_month_k['data'][] = [
                            'name' => $dm->tanggal,
                            'y' => $jumlah
                        ];
                    }
                    array_push($bulan_k, $data_month_k);
                }
            }


            $response = array(
                'bulan' => $array_bulan,
                'tanggal' => $bulan_k
            );
            echo json_encode($response);
        }
    }

    public function sumPurchasingByMonthTest(){

        $array_tahun = array();
        $array_bulan = array();
        $array_data = array();

        $semester = '1';

        if($semester == '1'){
            $bulan = [
                '1',
                '2',
                '3',
                '4',
                '5',
                '6'
            ];
        }else{
            $bulan = [
                '7',
                '8',
                '9',
                '10',
                '11',
                '12'
            ];
        }

        $data_category = DB::table('purchasings')
                ->join('products', 'products.id', '=', 'purchasings.product_id')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->select(DB::raw('categories.id, categories.name AS Kategori'))
                ->whereYear(DB::raw('purchasings.order_date'), '=',  date('Y'))
                ->whereIn(DB::raw('MONTH(purchasings.order_date)'), $bulan)
                ->groupBy('categories.id')
                ->get();

        foreach ($data_category as $c) {

            $semester = '1';

            if($semester == '1'){
                $bulan = [
                    '1',
                    '2',
                    '3',
                    '4',
                    '5',
                    '6'
                ];
            }else{
                $bulan = [
                    '7',
                    '8',
                    '9',
                    '10',
                    '11',
                    '12'
                ];
            }

            $data_bulan = DB::table('purchasings')
                ->join('products', 'products.id', '=', 'purchasings.product_id')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->select(DB::raw("categories.id, categories.name AS Kategori,
                        SUM(IF(MONTH(purchasings.order_date) = '1', purchasings.quantity, 0)) AS Januari,
                        SUM(IF(MONTH(purchasings.order_date) = '2', purchasings.quantity, 0)) AS Februari,
                        SUM(IF(MONTH(purchasings.order_date) = '3', purchasings.quantity, 0)) AS Maret,
                        SUM(IF(MONTH(purchasings.order_date) = '4', purchasings.quantity, 0)) AS April,
                        SUM(IF(MONTH(purchasings.order_date) = '5', purchasings.quantity, 0)) AS Mei,
                        SUM(IF(MONTH(purchasings.order_date) = '6', purchasings.quantity, 0)) AS Juni,
                        SUM(IF(MONTH(purchasings.order_date) = '7', purchasings.quantity, 0)) AS Juli,
                        SUM(IF(MONTH(purchasings.order_date) = '8', purchasings.quantity, 0)) AS Agustus,
                        SUM(IF(MONTH(purchasings.order_date) = '9', purchasings.quantity, 0)) AS September,
                        SUM(IF(MONTH(purchasings.order_date) = '10', purchasings.quantity, 0)) AS Oktober,
                        SUM(IF(MONTH(purchasings.order_date) = '11', purchasings.quantity, 0)) AS Nopember,
                        SUM(IF(MONTH(purchasings.order_date) = '12', purchasings.quantity, 0)) AS Desember"))
                ->where(DB::raw('categories.id'), '=',  $c->id)
                ->whereYear(DB::raw('purchasings.order_date'), '=',  date('Y'))
                ->whereIn(DB::raw('MONTH(purchasings.order_date)'), $bulan)
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

    public function sumSaleByYear(Request $request){

        if($request->ajax()) {
    
            $array_tahun = array();
            $array_bulan = array();
            $array_data = array();
    
            if($request->tahun == ''){
                $data_tahun = DB::table('sales')
                        ->join('products', 'products.id', '=', 'sales.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw('SUM(sales.quantity) AS sum, products.name AS produk, categories.name, categories.id AS category_id, YEAR(sales.order_date) AS year'))
                        ->whereYear('sales.order_date', '=', date('Y'))
                        ->groupBy('categories.id')
                        ->get();
            }else{
                $data_tahun = DB::table('sales')
                        ->join('products', 'products.id', '=', 'sales.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw('SUM(sales.quantity) AS sum, products.name AS produk, categories.name, categories.id AS category_id, YEAR(sales.order_date) AS year'))
                        ->where(DB::raw('YEAR(sales.order_date)'), '=',  $request->tahun)
                        ->groupBy('categories.id')
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
    
                    $data_bulan = DB::table('sales')
                            ->join('products', 'products.id', '=', 'sales.product_id')
                            ->join('categories', 'categories.id', '=', 'products.category_id')
                            ->select(DB::raw('SUM(sales.quantity) as sum, products.name AS produk, categories.name, YEAR(sales.order_date) AS year'))
                            ->where(DB::raw('categories.id'), '=',  $dt->category_id)
                            ->whereYear('sales.order_date', '=', date('Y'))
                            ->groupBy('products.id')
                            ->get();
    
                }else{
                    $data_bulan = DB::table('sales')
                            ->join('products', 'products.id', '=', 'sales.product_id')
                            ->join('categories', 'categories.id', '=', 'products.category_id')
                            ->select(DB::raw('SUM(sales.quantity) as sum, products.name AS produk, categories.name, YEAR(sales.order_date) AS year'))
                            ->where(DB::raw('categories.id'), '=',  $dt->category_id)
                            ->where(DB::raw('YEAR(sales.order_date)'), '=',  $request->tahun)
                            ->groupBy('products.id')
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
    
    public function sumSaleByMonth(Request $request){
        if($request->ajax()) {
            $array_tahun = array();
            $array_bulan = array();
            $array_data = array();
            $bulan_k = array();
            if($request->tahun == ''){
                if($request->semester == ''){
                    if(date('M') <= 6){
                        $semester = [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6'
                        ];
                    }else{
                        $semester = [
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12'
                        ];
                    }
                }else{
                    if($request->semester == '1'){
                        $semester = [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6'
                        ];
                    }else{
                        $semester = [
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12'
                        ];
                    }
                }
                $data_category = DB::table('sales')
                    ->join('products', 'products.id', '=', 'sales.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw('categories.name AS Kategori, MONTH(sales.order_date) AS bulan'))
                    ->whereYear(DB::raw('sales.order_date'), '=',  date('Y'))
                    ->whereIn(DB::raw('MONTH(sales.order_date)'), $semester)
                    ->groupBy('Kategori')
                    ->get();
            }else{
                if($request->semester == ''){
                    if(date('M') <= 6){
                        $semester = [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6'
                        ];
                    }else{
                        $semester = [
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12'
                        ];
                    }
                }else{
                    if($request->semester == '1'){
                        $semester = [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6'
                        ];
                    }else{
                        $semester = [
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12'
                        ];
                    }
                }
                $data_category = DB::table('sales')
                    ->join('products', 'products.id', '=', 'sales.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw('sales.order_date, categories.name AS Kategori, MONTH(sales.order_date) AS bulan'))
                    ->whereYear(DB::raw('sales.order_date'), '=',  $request->tahun)
                    ->whereIn(DB::raw('MONTH(sales.order_date)'), $semester)
                    ->groupBy('Kategori')
                    ->get();
            }
            foreach ($data_category as $c) {
                if($request->tahun == ''){
                    if($request->semester == ''){
                        if(date('M') <= 6){
                            $semester = [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6'
                            ];
                        }else{
                            $semester = [
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12'
                            ];
                        }
                    }else{
                        if($request->semester == '1'){
                            $semester = [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6'
                            ];
                        }else{
                            $semester = [
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12'
                            ];
                        }
                    }
                    $data_bulan = DB::table('sales')
                        ->join('products', 'products.id', '=', 'sales.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw("SUM(sales.quantity) AS jumlah, categories.name AS Kategori, MONTH(sales.order_date) AS bulan"))
                        ->whereYear(DB::raw('sales.order_date'), '=',  date('Y'))
                        ->where(DB::raw('categories.name'), '=', $c->Kategori)
                        ->whereIn(DB::raw('MONTH(sales.order_date)'), $semester)
                        ->groupBy('bulan')
                        ->get();
                }else{
                    if($request->semester == ''){
                        if(date('M') <= 6){
                            $semester = [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6'
                            ];
                        }else{
                            $semester = [
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12'
                            ];
                        }
                    }else{
                        if($request->semester == '1'){
                            $semester = [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6'
                            ];
                        }else{
                            $semester = [
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12'
                            ];
                        }
                    }
                    $data_bulan = DB::table('sales')
                        ->join('products', 'products.id', '=', 'sales.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw("SUM(sales.quantity) AS jumlah, categories.name AS Kategori, MONTH(sales.order_date) AS bulan"))
                        ->whereYear(DB::raw('sales.order_date'), '=', $request->tahun)
                        ->where(DB::raw('categories.name'), '=', $c->Kategori)
                        ->whereIn(DB::raw('MONTH(sales.order_date)'), $semester)
                        ->groupBy('bulan')
                        ->get();
                }
                $data_month_h['name'] = $c->Kategori;
                $data_month_h['data'] = array();
                foreach($data_bulan as $dm){
                    if($request->tahun == ''){
                        if($dm->bulan == 1){
                            $data_b = 'Januari';
                        }else if($dm->bulan == 2){
                            $data_b = 'Februari';
                        }else if($dm->bulan == 3){
                            $data_b = 'Maret';
                        }else if($dm->bulan == 4){
                            $data_b = 'April';
                        }else if($dm->bulan == 5){
                            $data_b = 'Mei';
                        }else if($dm->bulan == 6){
                            $data_b = 'Juni';
                        }else if($dm->bulan == 7){
                            $data_b = 'Juli';
                        }else if($dm->bulan == 8){
                            $data_b = 'Agustus';
                        }else if($dm->bulan == 9){
                            $data_b = 'September';
                        }else if($dm->bulan == 10){
                            $data_b = 'Oktober';
                        }else if($dm->bulan == 11){
                            $data_b = 'Nopember';
                        }else if($dm->bulan == 12){
                            $data_b = 'Desember';
                        }
                        $jumlah = intval($dm->jumlah);
                        $data_month_h['data'][] = [
                            'name' => $data_b,
                            'y' => $jumlah,
                            'drilldown' => $c->Kategori.' '.$request->semester.' '.$data_b.' '.date('Y')
                        ];
                    }else{
                        if($dm->bulan == 1){
                            $data_b = 'Januari';
                        }else if($dm->bulan == 2){
                            $data_b = 'Februari';
                        }else if($dm->bulan == 3){
                            $data_b = 'Maret';
                        }else if($dm->bulan == 4){
                            $data_b = 'April';
                        }else if($dm->bulan == 5){
                            $data_b = 'Mei';
                        }else if($dm->bulan == 6){
                            $data_b = 'Juni';
                        }else if($dm->bulan == 7){
                            $data_b = 'Juli';
                        }else if($dm->bulan == 8){
                            $data_b = 'Agustus';
                        }else if($dm->bulan == 9){
                            $data_b = 'September';
                        }else if($dm->bulan == 10){
                            $data_b = 'Oktober';
                        }else if($dm->bulan == 11){
                            $data_b = 'Nopember';
                        }else if($dm->bulan == 12){
                            $data_b = 'Desember';
                        }
                        $jumlah = intval($dm->jumlah);
                        $data_month_h['data'][] = [
                            'name' => $data_b,
                            'y' => $jumlah,
                            'drilldown' => $c->Kategori.' '.$request->semester.' '.$data_b.' '.$request->tahun
                        ];
                    }
                }
                array_push($array_bulan, $data_month_h);
            }

            if($request->tahun == ''){
                if($request->semester == '1'){
                    $semester = [
                        '1',
                        '2',
                        '3',
                        '4',
                        '5',
                        '6'
                    ];
                }else{
                    $semester = [
                        '7',
                        '8',
                        '9',
                        '10',
                        '11',
                        '12'
                    ];
                }
                $data_category = DB::table('sales')
                    ->join('products', 'products.id', '=', 'sales.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw("categories.name AS Kategori, MONTH(sales.order_date) AS bulan"))
                    ->whereYear(DB::raw('sales.order_date'), '=',  date('Y'))
                    ->whereIn(DB::raw('MONTH(sales.order_date)'), $semester)
                    //->groupBy('sales.order_date')
                    ->get();
                foreach ($data_category as $c) {
                    $data_bulan = DB::table('sales')
                        ->join('products', 'products.id', '=', 'sales.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw("SUM(sales.quantity) AS jumlah, categories.name AS Kategori, MONTH(sales.order_date) AS bulan, sales.order_date AS tanggal"))
                        ->whereYear(DB::raw('sales.order_date'), '=', date('Y'))
                        ->where(DB::raw('MONTH(sales.order_date)'), '=', $c->bulan)
                        ->where(DB::raw('categories.name'), $c->Kategori)
                        ->groupBy('tanggal')
                        ->get();
                    if($c->bulan == 1){
                        $data_b = 'Januari';
                    }else if($c->bulan == 2){
                        $data_b = 'Februari';
                    }else if($c->bulan == 3){
                        $data_b = 'Maret';
                    }else if($c->bulan == 4){
                        $data_b = 'April';
                    }else if($c->bulan == 5){
                        $data_b = 'Mei';
                    }else if($c->bulan == 6){
                        $data_b = 'Juni';
                    }else if($c->bulan == 7){
                        $data_b = 'Juli';
                    }else if($c->bulan == 8){
                        $data_b = 'Agustus';
                    }else if($c->bulan == 9){
                        $data_b = 'September';
                    }else if($c->bulan == 10){
                        $data_b = 'Oktober';
                    }else if($c->bulan == 11){
                        $data_b = 'Nopember';
                    }else if($c->bulan == 12){
                        $data_b = 'Desember';
                    }
        
                    $data_month_k['name'] = $c->Kategori;
                    $data_month_k['id'] = $c->Kategori.' '.$request->semester.' '.$data_b.' '.date('Y');
                    $data_month_k['data'] = array();
            
                    foreach($data_bulan as $dm){
                        $jumlah = intval($dm->jumlah);
                        $data_month_k['data'][] = [
                            'name' => $dm->tanggal,
                            'y' => $jumlah
                        ];
                    }
        
                    array_push($bulan_k, $data_month_k);
                }
            }else{
                if($request->semester == '1'){
                    $semester = [
                        '1',
                        '2',
                        '3',
                        '4',
                        '5',
                        '6'
                    ];
                }else{
                    $semester = [
                        '7',
                        '8',
                        '9',
                        '10',
                        '11',
                        '12'
                    ];
                }

                $data_category = DB::table('sales')
                    ->join('products', 'products.id', '=', 'sales.product_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->select(DB::raw("categories.name AS Kategori, MONTH(sales.order_date) AS bulan"))
                    ->whereYear(DB::raw('sales.order_date'), '=',  $request->tahun)
                    ->whereIn(DB::raw('MONTH(sales.order_date)'), $semester)
                    //->groupBy('sales.order_date')
                    ->get();
                foreach ($data_category as $c) {
                    $data_bulan = DB::table('sales')
                        ->join('products', 'products.id', '=', 'sales.product_id')
                        ->join('categories', 'categories.id', '=', 'products.category_id')
                        ->select(DB::raw("SUM(sales.quantity) AS jumlah, categories.name AS Kategori, MONTH(sales.order_date) AS bulan, sales.order_date AS tanggal"))
                        ->whereYear(DB::raw('sales.order_date'), '=',  $request->tahun)
                        ->where(DB::raw('MONTH(sales.order_date)'), '=', $c->bulan)
                        ->where(DB::raw('categories.name'), $c->Kategori)
                        ->groupBy('tanggal')
                        ->get();
                    if($c->bulan == 1){
                        $data_b = 'Januari';
                    }else if($c->bulan == 2){
                        $data_b = 'Februari';
                    }else if($c->bulan == 3){
                        $data_b = 'Maret';
                    }else if($c->bulan == 4){
                        $data_b = 'April';
                    }else if($c->bulan == 5){
                        $data_b = 'Mei';
                    }else if($c->bulan == 6){
                        $data_b = 'Juni';
                    }else if($c->bulan == 7){
                        $data_b = 'Juli';
                    }else if($c->bulan == 8){
                        $data_b = 'Agustus';
                    }else if($c->bulan == 9){
                        $data_b = 'September';
                    }else if($c->bulan == 10){
                        $data_b = 'Oktober';
                    }else if($c->bulan == 11){
                        $data_b = 'Nopember';
                    }else if($c->bulan == 12){
                        $data_b = 'Desember';
                    }
                    $data_month_k['name'] = $c->Kategori;
                    $data_month_k['id'] = $c->Kategori.' '.$request->semester.' '.$data_b.' '.$request->tahun;
                    $data_month_k['data'] = array();
            
                    foreach($data_bulan as $dm){
                        $jumlah = intval($dm->jumlah);
                        $data_month_k['data'][] = [
                            'name' => $dm->tanggal,
                            'y' => $jumlah
                        ];
                    }
                    array_push($bulan_k, $data_month_k);
                }
            }


            $response = array(
                'bulan' => $array_bulan,
                'tanggal' => $bulan_k
            );
            echo json_encode($response);
        }
    }
}
