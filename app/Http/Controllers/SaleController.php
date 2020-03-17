<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Product;
use App\Faculty;
use Auth;
use Cookie;
use DataTables;
use Validator;
use Illuminate\Support\Facades\Crypt;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Sale::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="'.route('sale.edit', base64_encode($row->id)).'" class="edit btn btn-primary btn-sm editPurchasing">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSale">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->editColumn('product_id', function($data){
                        return $data->product->name .' - '. formatRupiah($data->product->price);
                    })
                    ->editColumn('faculty_id', function($data){
                        return $data->customer_name .' ('. $data->faculty->name .' - '. $data->major->name . ' )';
                    })
                    ->editColumn('total_price', function($data){
                        return formatRupiah($data->total_price);
                    })
                    ->make(true);
        }
      
        return view('sale.index');
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = Product::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<button class="btn btn-xs btn-primary btn-flat selectProduct" data-id="'.$row->id.'" data-code="'.$row->product_number.'" data-name="'.$row->name.'" data-price="'.$row->price.'" data-stock="'.$row->stock.'">Pilih</button>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->editColumn('price', function($data){
                        return formatRupiah($data->price);
                    })
                    ->make(true);
        }

        $faculties = Faculty::all()->pluck('name', 'id');

        return view('sale.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        
        $error = Validator::make($request->all(), [
            'product_id'  => 'required',
            'customer_name' => 'required',
            'faculty_id' => 'required',
            'major_id' => 'required',
            'quantity'    => 'required'
        ], [
            'product_id.required' => 'Kode Barang tidak boleh kosong !',
            'customer_name.required' => 'Nama Customer tidak boleh kosong !',
            'faculty_id.required' => 'Fakultas tidak boleh kosong !',
            'major_id.required' => 'Jurusan tidak boleh kosong !',
            'quantity.required' => 'Quantity tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        Sale::updateOrCreate(['id' => $request->sale_id], [
            'product_id' => $request->product_id,
            'customer_name' => $request->customer_name,
            'faculty_id' => $request->faculty_id,
            'major_id' => $request->major_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total_price' => $request->total_price,
            'user_id' => Auth::user()->id,
        ]);
   
        Cookie::queue('save_sale', 'Data berhasil disimpan.', 500);
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $sale = Sale::with('product')->with('faculty')->with('major')->with('user')->find($id);
        return view('sale.edit', compact('sale'));
    }

    public function destroy($id)
    {
        Sale::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}
