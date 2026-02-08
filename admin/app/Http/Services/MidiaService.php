<?php

namespace App\Http\Services;

use App\Models\Midia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Modelo;
use FFMpeg\FFMpeg;

class MidiaService {
    public function processarMidia($arquivo, $modeloId, $principal, $tipo) {
        $modelo = Modelo::find($modeloId);
        $assinante = $modelo->nome_fantasia;

        if (!$modelo) {
            Log::error('Modelo não encontrado.', ['modeloId' => $modeloId]);
            return redirect()->back()->with('error', 'Modelo não encontrado.');
        }
    
        // Se for FOTO e for principal, remove a anterior
        if ($tipo === 'foto' && $principal == 1) {
            $principal_existe = Midia::where('modelo_id', $modeloId)
                ->where('tipo', 'foto')
                ->where('principal', 1)
                ->first();
    
            if ($principal_existe) {
                $caminhoArquivo = public_path($principal_existe->caminho);
                if (file_exists($caminhoArquivo)) {
                    unlink($caminhoArquivo);
                }
                $principal_existe->delete();
            }
        }
    
        if ($tipo === 'video' && $principal == 1) {
            $principal_existe = Midia::where('modelo_id', $modeloId)
                ->where('tipo', 'video')
                ->where('principal', 1)
                ->first();
    
            if ($principal_existe) {
                $caminhoArquivo = public_path($principal_existe->caminho);
                if (file_exists($caminhoArquivo)) {
                    unlink($caminhoArquivo);
                }
                $principal_existe->delete();
            }
        }
    
        // Define o caminho para salvar a mídia
        $caminho = 'modelos/' . $modelo->slug . '/midias';
    
        // Gera um nome único
        $nomeBase = $modelo->slug . '-cp' . $modeloId;
        $timestamp = now()->format('Ymd_His');
        $extensao = pathinfo($arquivo->getClientOriginalName(), PATHINFO_EXTENSION); // Extrai a extensão do nome original
        $nome = $nomeBase . $timestamp . '.' . $extensao;

        // Caminho completo para salvar o arquivo
        $caminhoCompleto = $caminho . '/' . $nome;
        $caminhoAbsoluto = public_path($caminhoCompleto);
        $diretorio = dirname($caminhoAbsoluto);

        if (!file_exists($diretorio)) {
            mkdir($diretorio, 0775, true);
        }
    
        if (is_file($arquivo)) {
            // Se for um caminho absoluto, copia o arquivo para o diretório público
            copy($arquivo, public_path($caminhoCompleto));
    
            // Remove o arquivo temporário após a cópia
            unlink($arquivo);
            Log::info('Arquivo temporário removido.', ['caminho' => $arquivo]);
        } else {
            // Se for um UploadedFile, move o arquivo para o diretório público
            $arquivo->move(public_path($caminho), $nome);
        }
    
        // Inicializa a variável do thumbnail
        $thumbnailCaminho = null;
    
        // Se for vídeo, gera o thumbnail
        if ($tipo === 'video') {
            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open(public_path($caminhoCompleto));
    
            // Cria o thumbnail
            $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
    
            // Define o nome do arquivo do thumbnail com a mesma estrutura do arquivo original
            $thumbnailNome = $nomeBase . $timestamp . '.jpg';
            $thumbnailCaminho = $caminho . '/' . $thumbnailNome;
    
            // Caminho absoluto para salvar o thumbnail
            $thumbnailPath = public_path($thumbnailCaminho);
    
            // Gera o thumbnail
            $frame->save($thumbnailPath);
        } else {
            // Se for imagem, o thumbnail pode ser apenas o próprio arquivo ou um redimensionamento
            $thumbnailCaminho = $caminhoCompleto;
        }
        
        try {
            // Cria uma nova instância de Midia
            $item = new Midia([
                'caminho' => $caminhoCompleto,
                'nome_arquivo' => $nome,
                'modelo_id' => $modeloId,
                'principal' => $principal ?? 0, // Define como 0 se $principal for null
                'tipo' => $tipo,
                'thumbnail' => $thumbnailCaminho, // Pode ser null para vídeos sem thumbnail
            ]);
    
            // Salva no banco de dados
            $item->save();
    
            Log::info('Mídia enviada.', [
                'assinante' => $assinante,
                'arquivo' => $item->nome_arquivo
            ]);
    
            if ($tipo == 'imagem') {
                return redirect()->route('fotos.index')->with('success', 'Foto salva com sucesso!');
            } else {
                return redirect()->route('videos.index')->with('success', 'Vídeo salvo com sucesso!');
            }
        } catch (\Throwable $th) {
            Log::error('Erro ao salvar mídia no banco de dados.', [
                'erro' => $th->getMessage(),
                'arquivo' => $nome,
                'caminho' => $caminhoCompleto,
            ]);
            return redirect()->back()->with('error', 'Erro ao enviar a mídia. Tente novamente. Código erro: ' . $th->getMessage());
        }
    }
}