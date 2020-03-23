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

    public function sumPurchasingByYear(){

        $data = Purchasing::groupBy('year')
        ->selectRaw('sum(total_price) as sum, YEAR(created_at) AS year')
        ->pluck('sum','year');

        $array_data_tahun = array();
        $array_data_tahun_detail = array();
        $array_tahun = array();
        $array_data_bulan = array();
        $array_data_bulan_detail = array();
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

        

        $data_y = [['name' => 'Tahun', 'data' => $array_tahun]];

        //$array_hy['tahun'] = $data_y;

        //$array_tahun = $array_hy['tahun'];

        //array_push($array_data, $array_hy);

        $data_bulan = DB::table('purchasings')
                    ->select(DB::raw('sum(total_price) as sum, MONTHNAME(created_at) AS month, YEAR(created_at) AS year'))
                    ->groupBy('month')
                    ->get();
        
        foreach($data_tahun as $dt){

            $data_bulan2 = DB::table('purchasings')
                    ->select(DB::raw('sum(total_price) as sum, MONTHNAME(created_at) AS month, YEAR(created_at) AS year'))
                    ->where(DB::raw('YEAR(created_at)'), '=',  $dt->year)
                    ->groupBy('month')
                    ->get();

            $data_month_h['name'] = $dt->year;
            $data_month_h['id'] = $dt->year;
            $data_month_h['data'] = array();
    
            foreach($data_bulan2 as $dm){
                //dd($dm->month);
                $data_month = $dm->month;
                $data_month = $dm->sum;

                $data_month_h['data'][] = [$dm->month, $dm->sum];
                //$data_month_h['data'][] = array_push($array_bulan, $data_month_h);

            }

            array_push($array_bulan, $data_month_h);
        }

        // $data_m = ['name' => 'Bulan', 'data' => $array_bulan];

        $array_hm['bulan'] = $array_bulan;

        array_push($array_data, $array_hm);

        $stuff = array(
            'tahun' => $data_y,
            'bulan' => $array_bulan,
        );

        echo json_encode($stuff);
    }

    public function sumPurchasingByMonth(){

        $year = '2020';
        // $data = Purchasing::groupBy('month')
        // ->where('YEAR(created_at)', $year)
        // ->selectRaw('sum(total_price) as sum, MONTH(created_at) AS month, YEAR(created_at) AS year')
        // ->get();
        //->pluck('sum','month');

        $data= DB::table('purchasings')
                    ->select(DB::raw('sum(total_price) as sum, MONTH(created_at) AS month, YEAR(created_at) AS year'))
                    ->where(DB::raw('YEAR(created_at)'), '=',  '2020')
                    ->groupBy('year')
                    ->get();

        $stuff = array(
            'employees' => array(
                
            ),
            'tes' => array(
                
            )
        );
        
        echo json_encode($stuff);

        //echo json_encode($data);
    }
}
