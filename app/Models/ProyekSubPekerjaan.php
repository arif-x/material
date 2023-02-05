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
}
