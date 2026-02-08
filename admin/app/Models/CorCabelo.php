<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorCabelo extends Model
{
    use HasFactory;

    protected $table = 'cores_cabelo';

    protected $fillable = [
        'descricao',
    ];

    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}
