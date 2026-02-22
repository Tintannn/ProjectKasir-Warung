<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApiResource;


class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $barang = Barang::all();
    return new ApiResource(true, 'List data barang', $barang);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nama_menu'=>'required|string',
        'harga'=>'required|numeric',
        'satuan'=>'required|string'
    ]);

    if($validator->fails()){
        return new ApiResource(false,'Validasi gagal',$validator->errors());
    }

    $barang = Barang::create($request->all());

    return new ApiResource(true,'Barang berhasil ditambahkan',$barang);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $barang = Barang::findOrFail($id);
    return new ApiResource(true,'Detail barang',$barang);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
{
    $barang = Barang::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'nama_menu'=>'required|string',
        'harga'=>'required|numeric',
        'satuan'=>'required|string'
    ]);

    if($validator->fails()){
        return new ApiResource(false,'Validasi gagal',$validator->errors());
    }

    $barang->update($request->all());

    return new ApiResource(true,'Barang berhasil diupdate',$barang);
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    Barang::destroy($id);
    return new ApiResource(true,'Barang berhasil dihapus',null);
}
}
