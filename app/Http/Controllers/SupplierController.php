<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use DataTables;
use Validator;


class SupplierController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editSupplier">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSupplier">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('supplier.index');
    }

    public function store(Request $request)
    {

        $error = Validator::make($request->all(), [
            'supplier_name' =>  'required',
            'phone'         =>  'required|max:12',
            'address'       =>  'required'
        ], [
            'supplier_name.required' => 'Nama Supplier tidak boleh kosong !',
            'phone.required' => 'No. Telepon tidak boleh kosong !',
            'phone.max' => 'No. Telepon maksimal 12 karakter !',
            'address.required' => 'Alamat tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        Supplier::updateOrCreate(['id' => $request->supplier_id],
            [
                'supplier_name' => $request->supplier_name,
                'phone' => $request->phone,
                'address' => $request->address
            ]
        );
   
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return response()->json($supplier);
    }

    public function destroy($id)
    {
        Supplier::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    public function search(Request $request)
    {
        $supplier = Supplier::where('supplier_name', 'LIKE', '%'.$request->input('term', '').'%')->get(['id', 'supplier_name as text']);
        return ['results' => $supplier];
    }

}