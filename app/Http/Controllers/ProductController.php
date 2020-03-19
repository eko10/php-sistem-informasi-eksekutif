<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use DataTables;
use Validator;
use File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
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
            'stock'       => 'required',
            'image_file'  => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'name.required' => 'Nama Barang tidak boleh kosong !',
            'category_id.required' => 'Kategori Barang tidak boleh kosong !',
            'price.required' => 'Harga tidak boleh kosong !',
            'stock.required' => 'Stok tidak boleh kosong !',
            //'image_file.required' => 'Gambar tidak boleh kosong !',
            'image_file.image' => 'File gambar harus berupa gambar !',
            'image_file.mimes' => 'Ekstensi gambar hanya boleh .jpeg, .png, .jpg, .gif, .svg !',
            'image_file.max' => 'Ukuran file gambar maksimal 2 mb !',
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($request->hasFile('image_file')){

            if(!empty($request->product_id)){
                $product = Product::where('id', $request->product_id)->first();
                if($product->image_file != ''){
                    File::delete('images/product/'.$product->image_file);
                }
            }

            $imageUpload = $request->file('image_file');
            $imageName = rand() . '.' . $imageUpload->getClientOriginalExtension();
            $imagePath = public_path('/images/product/');
            $imageUpload->move($imagePath, $imageName);

            $product = Product::updateOrCreate(
                ['id' => $request->product_id],
                [
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'image_file' => $imageName,
                ]
            );
        } else {

            $product = Product::updateOrCreate(
                ['id' => $request->product_id],
                [
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                    'price' => $request->price,
                    'stock' => $request->stock,
                ]
            );

        }
   
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $product = Product::with('category')->find($id);
        return response()->json($product);
    }

    public function destroy($id)
    {
        // hapus file
        $product = Product::where('id', $id)->first();
        if($product->image_file != ''){
            File::delete('images/product/'.$product->image_file);
        }
 
		// hapus data
        Product::where('id', $id)->delete();
        
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    public function search(Request $request)
    {
        $product = Product::where('name', 'LIKE', '%'.$request->input('term', '').'%')->get(['id', 'name as text']);
        return ['results' => $product];
    }

    public function searchByCode(Request $request){
        if($request->ajax()) {
            $data = Product::where('product_number', 'LIKE', $request->product.'%')
                            ->orWhere('name', 'LIKE', $request->product.'%')
                            ->get();
            $output = '';
            if (count($data)>0) {
                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';
                foreach ($data as $row){
                    $output .= '<li class="list-group-item" data-id="'.$row->id.'" data-code="'.$row->product_number.'" data-name="'.$row->name.'" data-price="'.$row->price.'" data-stock="'.$row->stock.'">'.$row->product_number.' - '.$row->name.'</li>';
                }
                $output .= '</ul>';
            }
            else {
                $output .= '<li class="list-group-item">'.'No results'.'</li>';
            }
            return $output;
        }
    }

}
