<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Outlet;
use JWTAuth;

class OutletController extends Controller
{
    public $response;
    public $user;
    public function __construct(){
        $this->response = new ResponseHelper();
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)     
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required'
        ]);

        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

        $outlet = new Outlet();
        $outlet->nama = $request->nama;
        $outlet->alamat = $request->alamat;
        $outlet->save();

        $data = Outlet::where('id', '=', $outlet->id)->first();
        return $this->response->successResponseData('Data outlet berhasil ditambahkan', $data);
    }

    public function getAll($limit = NULL, $offset = NULL)
    {
        $data['count'] = Outlet::count();

        // if($limit == NULL && $offset == NULL) {
            $data['outlet'] = Outlet::get();
        // } else {
        //     $data['outlet'] = Outlet::take($limit)->skip($offset)->get();            
        // }

        return $this->response->successData($data);
    }
    
    public function getById($id)
    {
        $data['outlet'] = Outlet::where('id', '=', $id)->get();
        
        return $this->response->successData($data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required'
        ]);

        if($validator->fails()) {
            return $this->response->errorResponse($validator->errors());
        }

        $outlet = Outlet::where('id', '=', $id)->first();
        $outlet->nama = $request->nama;
        $outlet->alamat = $request->alamat;

        $outlet->save();

        return $this->response->successResponse('Data outlet berhasil diubah');
    }

    public function delete($id)
    {
        $delete = Outlet::where('id', '=', $id)->delete();

        if($delete) {
            return $this->response->successResponse('Data outlet berhasil dihapus');
        } else {
            return $this->response->errorResponse('Data outlet gagal dihapus');
        }
    }
}
