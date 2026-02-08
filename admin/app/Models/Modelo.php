<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_fantasia',
        'telefone',
        'possui_local',
        'logradouro',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'complemento',
        'frase_impacto',
        'descricao',
        'valor_hora',
        'slug',
        'altura',
        'peso',
        'genero',
        'genitalia', 
        'quem_atende',
        'tatuagem',
        'piercing',
        'silicone',
        'atende_grupo',
        'cor_pele_id',
        'cor_cabelo_id',
        'cor_olho_id',
        'user_id',
        'numero',
        'cidade_atendimento',
        'uf_atendimento',
        'data_nascimento',
        'cidade_atendimento_slug',
        'mostrar_telefone',
        'mostrar_idade',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function plano(){
        return $this->belongsTo(Plano::class);
    }

    public function historico_planos(){
        return $this->hasMany(PlanoAssinante::class);
    }

    public function midias(){
        return $this->hasMany(Midia::class);
    }

    public function cor_pele(){
        return $this->belongsTo(CorPele::class);
    }

    public function cor_cabelo(){
        return $this->belongsTo(CorCabelo::class);
    }

    public function cor_olho(){
        return $this->belongsTo(CorOlho::class);
    }

    public function servicos(){
        return $this->belongsToMany(Servico::class);
    }

    public function get_idade(): ?int
    {
        return $this->data_nascimento
            ? now()->diffInYears($this->data_nascimento)
            : null; // Retorna null se a data de nascimento não estiver preenchida
    }

    public function estaCompleto(): bool
    {

        $atributosObrigatorios = [
            'nome_fantasia', 
            'telefone', 
            'possui_local', 
            'frase_impacto', 
            'descricao',
            'valor_hora', 
            'altura', 
            'peso', 
            'genero', 
            'genitalia', 
            'quem_atende',
            'tatuagem', 
            'piercing', 
            'silicone', 
            'atende_grupo', 
            'cor_pele_id', 
            'cor_cabelo_id',
            'cor_olho_id', 
            'cidade_atendimento', 
            'uf_atendimento',
            'data_nascimento'
        ];

        foreach ($atributosObrigatorios as $atributo) {
            if (is_null($this->$atributo) || (is_string($this->$atributo) && trim($this->$atributo) === '')) {
                return false; // Retorna falso se o atributo for null ou string vazia
            }
        }

        return true; // Se todos os campos estiverem preenchidos, retorna verdadeiro
    }    

    public function temFotoPrincipal()
    {
        return $this->midias()->where('tipo', 'foto')->where('principal', 1)->exists();
    }

    public function temPeloMenosUmaFotoNaoPrincipal()
    {
        return $this->midias()->where('tipo', 'foto')->where('principal', 0)->exists();
    }

    public function temVideoPrincipal()
    {
        return $this->midias()->where('tipo', 'video')->where('principal', 1)->exists();
    }

    public function temPeloMenosUmVideo()
    {
        return $this->midias()->where('tipo', 'video')->where('principal', 0)->exists();
    }

    public function atendeRequisitos()
    {
        return $this->estaCompleto() &&
               $this->temFotoPrincipal() &&
               $this->temPeloMenosUmaFotoNaoPrincipal() &&
               $this->temPeloMenosUmVideo();
               //$this->temVideoPrincipal() &&
               
    }    
}
