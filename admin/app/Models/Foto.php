<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;

    protected $fillable = [
        'caminho',
        'nome_arquivo',
        'modelo_id',
        'principal',
        'ativo'
    ];

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

}
