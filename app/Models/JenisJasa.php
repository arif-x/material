<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisJasa extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_jasa',
        'created_at',
        'updated_at'
    ];
}
