<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApiResource;


class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $data = Transaksi::with('detail.barang','user')->get();
    return new ApiResource(true,'List transaksi',$data);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'tanggal'=>'required|date',
        'bayar'=>'required|numeric',
        'items'=>'required|array'
    ]);

    if($validator->fails()){
        return new ApiResource(false,'Validasi gagal',$validator->errors());
    }

    $transaksi = Transaksi::create([
        'user_id'=>auth()->id(),
        'tanggal'=>$request->tanggal,
        'total_harga'=>0,
        'bayar'=>$request->bayar,
        'kembali'=>0
    ]);

    $total = 0;

    foreach($request->items as $item){
        $barang = Barang::findOrFail($item['barang_id']);
        $subtotal = $barang->harga * $item['jumlahBarang'];

        DetailTransaksi::create([
            'transaksi_id'=>$transaksi->id,
            'barang_id'=>$barang->id,
            'jumlahBarang'=>$item['jumlahBarang'],
            'subtotal'=>$subtotal
        ]);

        $total += $subtotal;
    }

    $transaksi->update([
        'total_harga'=>$total,
        'kembali'=>$request->bayar - $total
    ]);

    return new ApiResource(true,'Transaksi berhasil',$transaksi->load('detail.barang','user'));
}

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $data = Transaksi::with('detail.barang','user')->findOrFail($id);
    return new ApiResource(true,'Detail transaksi',$data);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $transaksi = Transaksi::findOrFail($id);

    $validator = Validator::make($request->all(),[
        'bayar'=>'required|numeric|min:0'
    ]);

    if($validator->fails()){
        return new ApiResource(false,'Validasi gagal',$validator->errors());
    }

    $kembali = $request->bayar - $transaksi->total_harga;

    $transaksi->update([
        'bayar'=>$request->bayar,
        'kembali'=>$kembali
    ]);

    return new ApiResource(true,'Transaksi berhasil diupdate',
        $transaksi->load('detail.barang','user')
    );
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $transaksi = Transaksi::findOrFail($id);
    $transaksi->delete();

    return new ApiResource(true,'Transaksi berhasil dihapus',null);
}
}
