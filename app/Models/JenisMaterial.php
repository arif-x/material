<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_material',
        'created_at',
        'updated_at'
    ];
}
