<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Http\Resources\ApiResource;

class DetailTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $data = DetailTransaksi::with(['transaksi.user','barang'])->get();

    return new ApiResource(true,'List detail transaksi',$data);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(),[
        'transaksi_id'=>'required|exists:transaksis,id',
        'barang_id'=>'required|exists:barangs,id',
        'jumlahBarang'=>'required|numeric|min:1'
    ]);

    if($validator->fails()){
        return new ApiResource(false,'Validasi gagal',$validator->errors());
    }

    $barang = Barang::findOrFail($request->barang_id);

    $subtotal = $barang->harga * $request->jumlahBarang;

    $detail = DetailTransaksi::create([
        'transaksi_id'=>$request->transaksi_id,
        'barang_id'=>$request->barang_id,
        'jumlahBarang'=>$request->jumlahBarang,
        'subtotal'=>$subtotal
    ]);

    return new ApiResource(true,'Detail transaksi berhasil ditambahkan',
        $detail->load(['transaksi.user','barang'])
    );
}

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $data = DetailTransaksi::with(['transaksi.user','barang'])
                ->findOrFail($id);

    return new ApiResource(true,'Detail transaksi item',$data);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
{
    $detail = DetailTransaksi::findOrFail($id);

    $validator = Validator::make($request->all(),[
        'jumlahBarang'=>'required|numeric|min:1'
    ]);

    if($validator->fails()){
        return new ApiResource(false,'Validasi gagal',$validator->errors());
    }

    $barang = Barang::findOrFail($detail->barang_id);

    $subtotal = $barang->harga * $request->jumlahBarang;

    $detail->update([
        'jumlahBarang'=>$request->jumlahBarang,
        'subtotal'=>$subtotal
    ]);

    return new ApiResource(true,'Detail transaksi berhasil diupdate',
        $detail->load(['transaksi.user','barang'])
    );
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    DetailTransaksi::destroy($id);

    return new ApiResource(true,'Detail transaksi berhasil dihapus',null);
}
}
