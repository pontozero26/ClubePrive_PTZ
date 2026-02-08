<?php

namespace App\Http\Services;

use App\Models\Foto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Modelo;

class FotoService {
    public function processarFoto($foto, $modeloId, $fotoPrincipal) {

        $modelo = Modelo::find($modeloId);

        if ($fotoPrincipal == 1){
            $fotoPrincipalExistente = Foto::where('modelo_id', $modelo->id)->where('principal', 1)->first();

            if ($fotoPrincipalExistente){
                $caminhoArquivo = public_path($fotoPrincipalExistente->caminho);
                if (file_exists($caminhoArquivo)) {
                    unlink($caminhoArquivo);
                }

                $fotoPrincipalExistente->delete();
            }
        }

        $dimensoes = getimagesize($foto);
        $largura = $dimensoes[0];
        $altura = $dimensoes[1];

        // Calcule a proporção
        $proportion = $largura / $altura;

        // Verifique se a proporção é aproximadamente 9:16 (tolerância para arredondamento)
        if (abs($proportion - 9 / 16) > 0.01) {
            return back()->withErrors(['foto' => 'A imagem deve estar na proporção 9:16.']);
        }

        // Define o caminho para salvar a foto
        $caminho = 'modelos/'.$modelo->slug.'/fotos';

        // Gera um nome único para a imagem
        // $nomeFoto = $foto->getClientOriginalName();

        $nomeBase = $modelo->slug . '-';

        $timestamp = now()->format('Ymd_His');
        $extensao = $foto->getClientOriginalExtension();
        $nomeFoto = $nomeBase .  $timestamp . '.' . $extensao;
        
        // Move a foto para o diretório desejado
        $foto->move(public_path($caminho), $nomeFoto);

        // Retorna o caminho completo da foto salva
        $caminhoCompleto = $caminho . '/' . $nomeFoto;

        // Salva o caminho completo da foto no banco de dados
        try {
            $item = new Foto(
                [
                    'caminho' => $caminhoCompleto,
                    'nome_arquivo' => $nomeFoto,
                    'modelo_id' => $modeloId,
                    'principal' => $fotoPrincipal
                ]
            );

            $item->save();
            Log::info('Foto enviada.',[
                'usuario' => auth()->user()->name,
                'foto' => $item->nome_arquivo,
            ]);
            return redirect()->route('fotos.index')->with('success', 'Foto salva com sucesso!');
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Erro ao enviar a foto. Tente novamente.Código erro:'.$th->getMessage());
        }
    }
}