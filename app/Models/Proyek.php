<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_proyek',
        'created_at',
        'updated_at'
    ];

    public function proyek_pekerjaan(){
        return $this->hasMany(ProyekPekerjaan::class, 'proyek_id');
    }
}
