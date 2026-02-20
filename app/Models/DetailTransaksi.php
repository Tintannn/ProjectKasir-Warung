<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $fillable = [
    'transaksi_id',
    'barang_id',
    'jumlahBarang',
    'subtotal'
];

public function transaksi()
{
    return $this->belongsTo(Transaksi::class);
}

public function barang()
{
    return $this->belongsTo(Barang::class);
}
}
