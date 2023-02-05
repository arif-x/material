<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyekHargaKomponenJasa extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_sub_pekerjaan_id',
        'jasa_id',
        'harga_asli',
        'koefisien',
        'harga_fix',
        'created_at',
        'updated_at'
    ];
}
