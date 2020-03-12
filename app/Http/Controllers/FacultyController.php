<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faculty;
use DataTables;
use Validator;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Faculty::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editFaculty">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteFaculty">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('faculty.index');
    }

    public function store(Request $request)
    {
        $error = Validator::make($request->all(), [
            'name' =>  'required'
        ], [
            'name.required' => 'Nama Fakultas tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        Faculty::updateOrCreate(['id' => $request->faculty_id], ['name' => $request->name]);
   
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $faculty = Faculty::find($id);
        return response()->json($faculty);
    }

    public function destroy($id)
    {
        Faculty::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    public function search(Request $request)
    {
        $faculty = Faculty::where('name', 'LIKE', '%'.$request->input('term', '').'%')->get(['id', 'name as text']);
        return ['results' => $faculty];
    }

}
