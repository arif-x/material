<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaKomponenMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'sub_pekerjaan_id',
        'koefisien',
        'harga_komponen_material',
        'profit',
        'created_at',
        'updated_at'
    ];

    public function material(){
        return $this->belongsTo(MasterMaterial::class, 'material_id', 'id');
    }

    public function sub_pekerjaan(){
        return $this->belongsTo(MasterSubPekerjaan::class, 'sub_pekerjaan_id', 'id');
    }
}
