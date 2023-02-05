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

    public function sub_pekerjaan(){
        return $this->belongsTo(ProyekSubPekerjaan::class, 'proyek_sub_pekerjaan_id', 'id');
    }

    public function jasa(){
        return $this->belongsTo(MasterJasa::class, 'jasa_id', 'id');
    }
}
