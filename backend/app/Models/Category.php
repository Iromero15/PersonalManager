<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Esto es como darle permisos de 'write' al struct en memoria
    protected $fillable = [
        'name',
        'user_id'
    ];
}