<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoAssinante extends Model
{
    use HasFactory;

    protected $table = 'planos_assinantes';

    protected $fillable = [
        'modelo_id',
        'plano_id',
        'data_contratacao',
        'ativo',
        'payment_id',
        'payment_status',
        'payment_method',
        'expira_em',        
    ];

    public function plano(){
        return $this->belongsTo(Plano::class);
    }

    public function modelo(){
        return $this->belongsTo(Modelo::class);
    }
}
