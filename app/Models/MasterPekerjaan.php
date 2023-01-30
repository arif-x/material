<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pekerjaan',
        'nama_pekerjaan',
        'created_at',
        'updated_at'
    ];
}
