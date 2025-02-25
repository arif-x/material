<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSubPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pekerjaan_id',
        'satuan_sub_pekerjaan_id',
        'kode_sub_pekerjaan',
        'nama_sub_pekerjaan',
        'profit',
        'created_at',
        'updated_at'
    ];

    public function pekerjaan(){
        return $this->belongsTo(MasterPekerjaan::class, 'pekerjaan_id', 'id');
    }

    public function satuan_sub_pekerjaan(){
        return $this->belongsTo(SatuanSubPekerjaan::class, 'satuan_sub_pekerjaan_id', 'id');
    }

    public function harga_satuan_jasa(){
        return $this->hasMany(HargaKomponenJasa::class, 'sub_pekerjaan_id');
    }

    public function harga_satuan_material(){
        return $this->hasMany(HargaKomponenMaterial::class, 'sub_pekerjaan_id');
    }
}
