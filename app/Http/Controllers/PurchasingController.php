<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchasing;
use App\Product;
use Auth;
use Cookie;
use DataTables;
use Validator;

class PurchasingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Purchasing::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="'.route('purchasing.edit', base64_encode($row->id)).'" class="edit btn btn-primary btn-sm editPurchasing">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletePurchasing">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->editColumn('product_id', function($data){
                        return $data->product->name .' - '. formatRupiah($data->product->price);
                    })
                    ->editColumn('supplier_id', function($data){
                        return $data->supplier->supplier_name;
                    })
                    ->editColumn('total_price', function($data){
                        return formatRupiah($data->total_price);
                    })
                    ->make(true);
        }
      
        return view('purchasing.index');
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

        return view('purchasing.create');
    }

    public function insert(Request $request)
    {

        //dd($request);
        
        // $error = Validator::make($request->all(), [
        //     'product_id'  => 'required',
        //     'supplier_id' => 'required',
        //     'quantity'    => 'required'
        // ], [
        //     'product_id.required' => 'Kode Barang tidak boleh kosong !',
        //     'supplier_id.required' => 'Supplier tidak boleh kosong !',
        //     'quantity.required' => 'Quantity tidak boleh kosong !'
        // ]);

        $this->validate($request, [
            'product_id'  => 'required',
            'supplier_id' => 'required',
            'quantity'    => 'required',
            'order_date'  => 'required'
        ], [
            'product_id.required' => 'Kode Barang tidak boleh kosong !',
            'supplier_id.required' => 'Supplier tidak boleh kosong !',
            'quantity.required' => 'Quantity tidak boleh kosong !',
            'order_date.required' => 'Tanggal tidak boleh kosong !'
        ]);

        // if($error->fails())
        // {
        //     return response()->json(['errors' => $error->errors()->all()]);
        // }

        //Product::where('id', $product->id)->update(['stock' => $request->product_id]);
        $request->request->add(['user_id' => Auth::user()->id]);
        Purchasing::create($request->all());

        $product = Product::find($request->product_id);
        $stock = $product->stock;
        $stock_update = $stock + $request->quantity;

        $flight = Product::find($product->id);
        $flight->stock = $stock_update;
        $flight->save();
   
        Cookie::queue('save_purchasing', 'Data berhasil disimpan.', 500);
        return redirect('/purchasing')->with('sukses', 'Data berhasil dinput');
        //return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function store(Request $request)
    {

        //dd($request);
        
        $error = Validator::make($request->all(), [
            'product_id'  => 'required',
            'supplier_id' => 'required',
            'quantity'    => 'required',
            'order_date'  => 'required'
        ], [
            'product_id.required' => 'Kode Barang tidak boleh kosong !',
            'supplier_id.required' => 'Supplier tidak boleh kosong !',
            'quantity.required' => 'Quantity tidak boleh kosong !',
            'order_date.required' => 'Tanggal tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        //Product::where('id', $product->id)->update(['stock' => $request->product_id]);

        Purchasing::updateOrCreate(['id' => $request->purchasing_id], [
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total_price' => $request->total_price,
            'order_date' => $request->order_date,
            'user_id' => Auth::user()->id,
        ]);

        $product = Product::find($request->product_id);
        $stock_now = $product->stock;
        $stock_update = $stock_now + $request->quantity;
        $product->stock = $stock_update;
        $product->save();
   
        //Cookie::queue('save_purchasing', 'Data berhasil disimpan.', 500);
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $purchasing = Purchasing::with('product')->with('supplier')->with('user')->find($id);
        //Cookie::queue('update_purchasing', 'Data berhasil diubah.', 500);
        return view('purchasing.edit', compact('purchasing'));
        //return response()->json($purchasing);
    }

    public function destroy($id)
    {
        Purchasing::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
    
}
