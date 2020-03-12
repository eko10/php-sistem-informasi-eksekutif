<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use DataTables;
use Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->editColumn('category_id', function($data){
                        return $data->category->name;
                    })
                    ->editColumn('price', function($data){
                        return formatRupiah($data->price);
                    })
                    ->make(true);
        }
      
        return view('product.index');
    }

    public function store(Request $request)
    {
        
        $error = Validator::make($request->all(), [
            'name'        => 'required',
            'category_id' => 'required',
            'price'       => 'required',
            'stock'       => 'required'
        ], [
            'name.required' => 'Nama Barang tidak boleh kosong !',
            'category_id.required' => 'Kategori Barang tidak boleh kosong !',
            'price.required' => 'Harga tidak boleh kosong !',
            'stock.required' => 'Stok tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        Product::updateOrCreate(['id' => $request->product_id], ['name' => $request->name, 'category_id' => $request->category_id, 'price' => $request->price, 'stock' => $request->stock]);
   
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $product = Product::with('category')->find($id);
        return response()->json($product);
    }

    public function destroy($id)
    {
        Product::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    public function search(Request $request)
    {
        $product = Product::where('name', 'LIKE', '%'.$request->input('term', '').'%')->get(['id', 'name as text']);
        return ['results' => $product];
    }

}
