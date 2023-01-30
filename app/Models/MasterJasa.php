<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJasa extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_jasa_id',
        'satuan_jasa_id',
        'kode_jasa',
        'nama_jasa',
        'harga_jasa',
        'created_at',
        'updated_at'
    ];

    public function jenis(){
        return $this->belongsTo(JenisJasa::class, 'jenis_jasa_id', 'id');
    }

    public function satuan(){
        return $this->belongsTo(SatuanJasa::class, 'satuan_jasa_id', 'id');
    }
}
