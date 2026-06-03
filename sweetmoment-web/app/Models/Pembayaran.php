<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'nama_pemesan', 
        'nama_vendor', 
        'jenispemesanan', 
        'tanggal_acara',
        'catatan',
        'harga',
        'status',
    ];
}

