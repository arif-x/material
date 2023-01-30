<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSubPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pekerjaan_id',
        'kode_sub_pekerjaan',
        'nama_sub_pekerjaan',
        'harga_sub_pekerjaan',
        'created_at',
        'updated_at'
    ];

    public function pekerjaan(){
        return $this->belongsTo(MasterPekerjaan::class, 'pekerjaan_id', 'id');
    }
}
