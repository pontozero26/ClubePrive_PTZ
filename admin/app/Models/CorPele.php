<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorPele extends Model
{
    use HasFactory;

    protected $table = 'cores_pele';

    protected $fillable = [
        'descricao',
    ];
}
