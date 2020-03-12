<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use DataTables;
use Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editCategory">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteCategory">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('category.index');
    }

    public function store(Request $request)
    {

        $error = Validator::make($request->all(), [
            'name' =>  'required'
        ], [
            'name.required' => 'Nama Kategori tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        Category::updateOrCreate(['id' => $request->category_id], ['name' => $request->name]);
   
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }

    public function destroy($id)
    {
        Category::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    public function search(Request $request)
    {
        $category = Category::where('name', 'LIKE', '%'.$request->input('term', '').'%')->get(['id', 'name as text']);
        return ['results' => $category];
    }
}
