<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $timestamps = false; // karena hanya created_at

    protected $fillable = [
        'user_id',
        'aksi',
        'tabel',
        'ip_address',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}