<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DataTables;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = User::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('user.index');
    }

    public function store(Request $request)
    {

        $error = Validator::make($request->all(), [
            'name'     =>  'required',
            'email'    =>  'required|email',
            'password' =>  'required|min:6',
            'role'     =>  'required'
        ], [
            'name.required' => 'Nama tidak boleh kosong !',
            'email.required' => 'Email tidak boleh kosong !',
            'email.email' => 'Format email anda salah !',
            'password.required' => 'Password tidak boleh kosong !',
            'password.min' => 'Password minimal 6 karakter !',
            'role.required' => 'Role tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $password = Hash::make($request->password);
        $remember_token = Str::random(60);

        User::create(['name' => $request->name, 'email' => $request->email, 'password' => $password, 'role' => $request->role, 'remember_token' => $remember_token]);
   
        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function editUser(Request $request)
    {

        $error = Validator::make($request->all(), [
            'edit_name'     =>  'required',
            'edit_email'    =>  'required|email',
            'edit_role'     =>  'required',
        ], [
            'edit_name.required' => 'Nama tidak boleh kosong !',
            'edit_email.required' => 'Email tidak boleh kosong !',
            'edit_email.email' => 'Format email anda salah !',
            'edit_role.required' => 'Role tidak boleh kosong !'
        ]);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if(!empty($request->edit_password)){
            $password = Hash::make($request->edit_password);
            User::find($request->user_id)->update(['name' => $request->edit_name, 'email' => $request->edit_email, 'password' => $password, 'role' => $request->edit_role]);
        }else{
            User::find($request->user_id)->update(['name' => $request->edit_name, 'email' => $request->edit_email, 'role' => $request->edit_role]);
        }
   
        return response()->json(['success' => 'Data berhasil diubah.']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}
