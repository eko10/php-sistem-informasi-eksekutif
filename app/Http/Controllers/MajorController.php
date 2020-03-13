<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Major;
use App\Faculty;
use DataTables;
use Validator;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Major::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editMajor">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteMajor">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->editColumn('faculty_id', function($data){
                        return $data->faculty->name;
                    })
                    ->make(true);
        }
      
        return view('major.index');
    }

    public function store(Request $request)
    {

        $error = Validator::make($request->all(), [
            'name' =>  'required',
            'faculty_id' =>  'required'
        ], [
            'name.required' => 'Nama Jurusan tidak boleh kosong !',
            'faculty_id.required' => 'Fakultas tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        Major::updateOrCreate(['id' => $request->major_id], ['name' => $request->name, 'faculty_id' => $request->faculty_id]);
   
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $major = Major::with('faculty')->find($id);
        return response()->json($major);
    }

    public function destroy($id)
    {
        Major::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    public function search($id)
    {
        // $majors = DB::table("majors")
        //             ->where("faculty_id",$id)
        //             ->pluck("name","id");
        $majors = Major::where('faculty_id', $id)->pluck('name', 'id');
        return json_encode($majors);
    }

}
