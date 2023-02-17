<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaKomponenJasa extends Model
{
    use HasFactory;

    protected $fillable = [
        'jasa_id',
        'sub_pekerjaan_id',
        'koefisien',
        'harga_komponen_jasa',
        'profit',
        'created_at',
        'updated_at'
    ];

    public function jasa(){
        return $this->belongsTo(MasterJasa::class, 'jasa_id', 'id');
    }

    public function sub_pekerjaan(){
        return $this->belongsTo(MasterSubPekerjaan::class, 'sub_pekerjaan_id', 'id');
    }
}
