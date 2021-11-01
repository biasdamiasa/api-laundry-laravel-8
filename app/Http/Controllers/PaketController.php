<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Paket;
use JWTAuth;

class PaketController extends Controller
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
            'jenis' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

        $paket = new Paket();
        $paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
        $paket->save();

        $data = Paket::where('id', '=', $paket->id)->first();
        return $this->response->successResponseData('Data paket berhasil ditambahkan', $data);
    }

    public function getAll($limit = NULL, $offset = NULL)
    {
        $data['count'] = Paket::count();

        // if($limit == NULL && $offset == NULL) {
            $data['paket'] = Paket::get();
        // } else {
        //     $data['paket'] = Paket::take($limit)->skip($offset)->get();            
        // }

        return $this->response->successData($data);
    }

    public function getById($id)
    {
        $data['paket'] = Paket::where('id', '=', $id)->get();
        
        return $this->response->successData($data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()) {
            return $this->response->errorResponse($validator->errors());
        }

        $paket = Paket::where('id', '=', $id)->first();
        $paket->jenis = $request->jenis;
        $paket->harga = $request->harga;

        $outlet->save();

        return $this->response->successResponse('Data paket berhasil diubah');
    }

    public function delete($id)
    {
        $delete = Paket::where('id', '=', $id)->delete();

        if($delete) {
            return $this->response->successResponse('Data paket berhasil dihapus');
        } else {
            return $this->response->errorResponse('Data paket gagal dihapus');
        }
    }
}
