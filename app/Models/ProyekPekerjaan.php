<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyekPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_id',
        'pekerjaan_id',
        'created_at',
        'updated_at'
    ];

    public function pekerjaan(){
        return $this->belongsTo(MasterPekerjaan::class, 'pekerjaan_id', 'id');
    }

    public function sub_pekerjaan(){
        return $this->hasMany(ProyekSubPekerjaan::class, 'proyek_pekerjaan_id');
    }

    public function proyek(){
        return $this->belongsTo(Proyek::class);
    }
}
