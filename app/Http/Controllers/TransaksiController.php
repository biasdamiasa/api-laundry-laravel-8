<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use Carbon\Carbon;
use JWTAuth;

class TransaksiController extends Controller
{
    public $response;
    public $user;

    public function __construct()
    {
        $this->response = new ResponseHelper();
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required'
        ]);

        if($validator->fails()) {
            return $this->response->errorResponse($validator->errors());
        }

        $transaksi = new Transaksi();
        $transaksi->id_member = $request->id_member;
        $transaksi->tgl_order = Carbon::now();
        $transaksi->batas_waktu = Carbon::now()->addDays(3);
        $transaksi->status = 'baru';
        $transaksi->dibayar = 'belum dibayar';
        $transaksi->id_user = $this->user->id;

        $transaksi->save();

        $data = Transaksi::where('id', '=', $transaksi->id)->first();

        return $this->response->successResponseData('Data transaksi berhasil ditambahkan', $data);
    }

    public function getAll()
    {
        $data['count'] = Transaksi::count();
        $data['transaksi'] = Transaksi::get();

        return $this->response->successData($data);
    }

    public function getById($id)
    {
        $data['transaksi'] = Transaksi::where('id', '=', $id)->get();

        return $this->response->successData($data);
    }

}
