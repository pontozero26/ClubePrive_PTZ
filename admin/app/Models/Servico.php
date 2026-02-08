<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'slug',
        'ativo',
    ];

    public function modelos(){
        return $this->belongsToMany(Modelo::class);
    }
}
