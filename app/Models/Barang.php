<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
    'nama_menu',
    'harga',
    'satuan'
];

public function detail()
{
    return $this->hasMany(DetailTransaksi::class);
}
}

