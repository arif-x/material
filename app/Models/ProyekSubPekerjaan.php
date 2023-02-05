<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyekSubPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_pekerjaan_id',
        'sub_pekerjaan_id',
        'volume',
        'created_at',
        'updated_at'
    ];

    public function sub_pekerjaan(){
        return $this->belongsTo(MasterSubPekerjaan::class, 'sub_pekerjaan_id', 'id');
    }

    public function pekerjaan(){
        return $this->belongsTo(ProyekPekerjaan::class, 'proyek_pekerjaan_id', 'id');
    }

    public function harga_komponen_jasa(){
        return $this->hasMany(ProyekHargaKomponenJasa::class, 'proyek_sub_pekerjaan_id');
    }

    public function harga_komponen_material(){
        return $this->hasMany(ProyekHargaKomponenMaterial::class, 'proyek_sub_pekerjaan_id');
    }
}
