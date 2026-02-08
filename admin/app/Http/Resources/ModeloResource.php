<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class ModeloResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome_fantasia,
            'slug' => $this->slug,
            'telefone' => $this->telefone,
            'possui_local' => $this->possui_local,
            'logradouro' => $this->logradouro,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'uf' => $this->uf,
            'cep' => $this->cep,
            'frase_impacto' => $this->frase_impacto,
            'descricao' => $this->descricao,
            'valor_hora' => $this->valor_hora,
            'altura' => $this->altura,
            'peso' => $this->peso,
            'genitalia' => $this->get_genitalia($this->genitalia),
            'piercing' => $this->get_piercing($this->piercing),
            'tatuagem' => $this->get_tatuagem($this->tatuagem), 
            'quem_atende' => $this->quem_atende($this->quem_atende),
            'silicone' => $this->silicone,
            'atende_grupo' => $this->atende_grupo,
            'cor_pele' => $this->cor_pele?->descricao ?? 'Não informado',
            'cor_cabelo' => $this->cor_cabelo?->descricao ?? 'Não informado',
            'cor_olho' => $this->cor_olho?->descricao ?? 'Não informado',
            'midias' => $this->midias->map(function($midia){
                return [
                    'id' => $midia->id,
                    'caminho' => url($midia->caminho),
                    'nome_arquivo' => $midia->nome_arquivo,
                    'principal' => $midia->principal,
                    'tipo' => $midia->tipo,
                    'data_envio' => $midia->created_at->format('d/m/Y')
                ];
            }),
            'idade' => $this->get_idade(),
            'genero' => $this->genero,
            'cidade_atendimento' => $this->cidade_atendimento . ' - ' . strtoupper($this->uf_atendimento),
            'cidade_atendimento_slug' => $this->cidade_atendimento_slug,
            'servicos' => $this->servicos->pluck('nome'),
            'mostrar_idade' => $this->mostrar_idade,
            'mostrar_telefone' => $this->mostrar_telefone,

        ];
    }

    public function get_genitalia($valor){
        $de_para = [
            'v' => 'Vagina',
            'p' => 'Pênis',
        ];

        return $de_para[$valor]??'Não informado';
    }

    public function get_piercing($valor){
        $de_para = [
            'sim_poucos' => 'Sim, poucos',
            'sim_muitos' => 'Sim, muitos',
            'nao' => 'Não',
        ];

        return $de_para[$valor]??'Não informado';
    }

    public function get_tatuagem($valor){
        $de_para = [
            'sim_poucas' => 'Sim, poucas',
            'sim_muitas' => 'Sim, muitas',
            'nao' => ' Não',
        ];
        return $de_para[$valor]??'Não informado';
    }

    public function quem_atende($valor){
        $de_para = [
            'apenas_homens' => 'Apenas homens',
            'apenas_mulheres' => 'Apenas mulheres',
            'homens_mulheres' => 'Homens e mulheres',
            'trans_afins' => 'Trans e afins',
        ];
        return $de_para[$valor]??'Nao informado';
    }    
}
