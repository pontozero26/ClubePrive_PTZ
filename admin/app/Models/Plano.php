<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plano extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'dias_semana',
        'valor',
        'slug',
        'ativo',
        'hora_inicio',
        'hora_fim',
        'qtd_videos',
        'qtd_imagens',
        'mp_plan_id',
        'gratis',
        'qtd_dias',
    ];

    public function modelo(){
        return $this->hasMany(Modelo::class);
    }   

    public function historico_planos(){
        return $this->hasMany(PlanoAssinante::class);
    }


}
