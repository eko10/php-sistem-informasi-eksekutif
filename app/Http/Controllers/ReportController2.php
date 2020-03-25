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

            if($request->tahun == 'all'){
                $data_tahun = DB::table('purchasings')
                        ->select(DB::raw('sum(total_price) as sum, YEAR(created_at) AS year'))
                        ->groupBy('year')
                        ->get();
            }else if($request->tahun == ''){
                $data_tahun = DB::table('purchasings')
                        ->select(DB::raw('sum(total_price) as sum, YEAR(created_at) AS year'))
                        ->groupBy('year')
                        ->get();
            }else{
                $data_tahun = DB::table('purchasings')
                        ->select(DB::raw('sum(total_price) as sum, YEAR(created_at) AS year'))
                        ->where(DB::raw('YEAR(created_at)'), '=',  $request->tahun)
                        ->groupBy('year')
                        ->get();
            }
            
            foreach($data_tahun as $dt){
                $data_year['name'] = $dt->year;
                $data_year['y'] = $dt->sum;
                $data_year['drilldown'] = $dt->year;

                array_push($array_tahun, $data_year);
            }

            $data_y = [
                [
                    'name' => 'Year',
                    'data' => $array_tahun
                ]
            ];
            
            foreach($data_tahun as $dt){

                $data_bulan = DB::table('purchasings')
                        ->select(DB::raw('sum(total_price) as sum, MONTHNAME(created_at) AS month, YEAR(created_at) AS year'))
                        ->where(DB::raw('YEAR(created_at)'), '=',  $dt->year)
                        ->groupBy('month')
                        ->get();

                $data_month_h['name'] = $dt->year;
                $data_month_h['id'] = $dt->year;
                $data_month_h['data'] = array();
        
                foreach($data_bulan as $dm){
                    $data_month = $dm->month;
                    $data_month = $dm->sum;
                    $data_month_h['data'][] = [$dm->month, $dm->sum];

                }

                array_push($array_bulan, $data_month_h);
            }

            $array_hm['bulan'] = $array_bulan;

            array_push($array_data, $array_hm);

            $response = array(
                'tahun' => $data_y,
                'bulan' => $array_bulan,
            );

            echo json_encode($response);
        }
    }

    public function sumPurchasingTest(){

        $array_tahun = array();
        $array_bulan = array();
        $array_data = array();

        $data_tahun = DB::table('purchasings')
                    ->select(DB::raw('sum(total_price) as sum, YEAR(created_at) AS year'))
                    ->groupBy('year')
                    ->get();
        
        foreach($data_tahun as $dt){
            $data_year['name'] = $dt->year;
            $data_year['y'] = $dt->sum;
            $data_year['drilldown'] = $dt->year;

            array_push($array_tahun, $data_year);
        }

        $data_y = [
            [
                'name' => 'Year',
                'data' => $array_tahun
            ]
        ];
        
        foreach($data_tahun as $dt){

            $data_bulan = DB::table('purchasings')
                    ->select(DB::raw('sum(total_price) as sum, MONTHNAME(created_at) AS month, YEAR(created_at) AS year'))
                    ->where(DB::raw('YEAR(created_at)'), '=',  $dt->year)
                    ->groupBy('month')
                    ->get();

            $data_month_h['name'] = $dt->year;
            $data_month_h['id'] = $dt->year;
            $data_month_h['data'] = array();
    
            foreach($data_bulan as $dm){
                $data_month = $dm->month;
                $data_month = $dm->sum;
                $data_month_h['data'][] = [$dm->month, $dm->sum];

            }

            array_push($array_bulan, $data_month_h);
        }

        $array_hm['bulan'] = $array_bulan;

        array_push($array_data, $array_hm);

        $response = array(
            'tahun' => $data_y,
            'bulan' => $array_bulan,
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
