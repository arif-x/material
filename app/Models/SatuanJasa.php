<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanJasa extends Model
{
    use HasFactory;

    protected $fillable = [
        'satuan_jasa',
        'created_at',
        'updated_at'
    ];
}
