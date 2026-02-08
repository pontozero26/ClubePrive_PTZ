<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorOlho extends Model
{
    use HasFactory;

    protected $table = 'cores_olhos';

    protected $fillable = [
        'descricao',
    ];
}
