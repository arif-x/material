<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanSubPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'satuan_sub_pekerjaan',
        'created_at',
        'updated_at'
    ];
}
