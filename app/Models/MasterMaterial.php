<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_material',
        'nama_material',
        'jenis_material_id',
        'satuan_material_id',
        'harga_beli',
        'created_at',
        'updated_at'
    ];

    public function jenis(){
        return $this->belongsTo(JenisMaterial::class, 'jenis_material_id', 'id');
    }

    public function satuan(){
        return $this->belongsTo(SatuanMaterial::class, 'satuan_material_id', 'id');
    }
}
