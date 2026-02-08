<?php

namespace App\Http\Controllers;

use App\Models\video;
use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use App\Models\Modelo;
use App\Models\Midia;
use App\Http\Services\MidiaService;

class VideoController extends Controller
{
    protected $midiaService;

    function __construct(MidiaService $midiaService)
    {
        $this->midiaService = $midiaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $modelo = Modelo::where('user_id', $user_id)->first();

        $role = auth()->user()->role;

        $videos = Midia::where('modelo_id', $modelo->id)
        ->where('tipo', 'video')
        ->where('principal', 0)    
        ->get();

        return view('admin.videos.index', compact('videos', 'role'));
    }


    public function enviar($principal)
    {
        return view('admin.videos.enviar',['principal' => $principal]);
    }    

    
    public function store(Request $request)
    {
        // Validar o arquivo
        if ($request->has('dzuuid')) {
            // Ignorar validação de MIME para chunks
            $request->validate([
                'file' => 'required|max:50000', // Apenas valida o tamanho
            ]);
        } else {
            // Validar o arquivo completo
            $request->validate([
                'file' => 'required|mimes:mp4,mov,ogg,qt,flv,avi|max:50000',
            ]);
        }
        // Dados do usuário e modelo
        $user_id = auth()->user()->id;
        $modelo = Modelo::where('user_id', $user_id)->first();

        // Verificar se o arquivo foi enviado
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $principal = $request->input('principal');

            // Verificar se é um chunk
            if ($request->has('dzuuid')) {
                return $this->handleChunkedUpload($request, $modelo->id, $principal);
            }

            // Processar upload único (caso não seja chunk)
            try {
                $video = $this->midiaService->processarMidia($file, $modelo->id, $principal, 'video');

                if ($principal == 1) {
                    return redirect()->route('videos.principal')->with('success', 'Vídeo enviado com sucesso!');
                }
                return redirect()->route('videos.index')->with('success', 'Vídeo enviado com sucesso!');
            } catch (\Exception $e) {
                return redirect()->route('videos.index')->with('error', 'Houve um erro no envio do vídeo: ' . $e->getMessage());
            }
        }

        return redirect()->route('videos.index')->with('error', 'Nenhum arquivo válido foi enviado.');
    }

    protected function handleChunkedUpload(Request $request, $modelo_id, $principal)
    {
        $chunkNumber = intval($request->input('dzchunkindex'));
        $totalChunks = intval($request->input('dztotalchunkcount'));
        $uuid = $request->input('dzuuid');
        $originalName = $request->file('file')->getClientOriginalName();
        
        $fileName = $uuid . '_' . $originalName;
        $chunksDir = storage_path('app/uploads/chunks');
        $uploadsDir = storage_path('app/uploads');
    
        // Garantir que os diretórios existam
        if (!is_dir($chunksDir)) mkdir($chunksDir, 0777, true);
        if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);
    
        // Salvar o chunk
        $chunkFileName = $fileName . '.part' . $chunkNumber;
        $chunkPath = $chunksDir . '/' . $chunkFileName;
        
        $request->file('file')->move($chunksDir, $chunkFileName);
    
        // Verificar se este é o último chunk
        if ($chunkNumber + 1 == $totalChunks) {
            $finalPath = $uploadsDir . '/' . $fileName;
            
            try {
                $this->recombineChunks($fileName, $totalChunks, $finalPath);
    
                // Verificar se o arquivo final existe e tem tamanho esperado
                if (!file_exists($finalPath)) {
                    throw new \Exception("Arquivo final não encontrado: " . $finalPath);
                }
    
                // Criar UploadedFile a partir do arquivo recombinado
                $file = new \Illuminate\Http\UploadedFile(
                    $finalPath, 
                    $originalName, 
                    mime_content_type($finalPath), 
                    filesize($finalPath)
                );
    
                // Processar mídia e obter o resultado
                $video = $this->midiaService->processarMidia($file, $modelo_id, $principal, 'video');
    
                // Limpar os chunks temporários
                $this->cleanChunks($fileName, $totalChunks);
    
                // Retornar a resposta esperada pelo frontend
                if ($principal == 1) {
                    return redirect()->route('videos.principal')->with('success', 'Vídeo enviado com sucesso!');
                }
                return redirect()->route('videos.index')->with('success', 'Vídeo enviado com sucesso!');
            } catch (\Exception $e) {
                Log::error('Erro no upload de vídeo', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
    
                return redirect()->route('videos.index')->with('error', 'Houve um erro no envio do vídeo: ' . $e->getMessage());
            }
        }
    
        // Para chunks intermediários, retornar sucesso para o Dropzone
        return response()->json(['success' => 'Chunk recebido com sucesso.'], 200);
    }

    protected function recombineChunks($fileName, $totalChunks, $finalPath)
    {
        $chunkDir = storage_path('app/uploads/chunks');
        
        // Log para verificar os chunks
        $existingChunks = glob($chunkDir . '/' . $fileName . '.part*');
        // Log::info('Chunks existentes antes da recombinação', [
        //     'chunks' => $existingChunks
        // ]);
    
        // Abrir o arquivo final para escrita
        $fileHandle = fopen($finalPath, 'wb');
        if ($fileHandle === false) {
            throw new \Exception("Falha ao abrir o arquivo final para escrita: " . $finalPath);
        }
    
        // Recombinar os chunks em ordem
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = $chunkDir . '/' . $fileName . '.part' . $i;
    
            // Log::info('Processando chunk', [
            //     'chunkPath' => $chunkPath,
            //     'chunkExists' => file_exists($chunkPath)
            // ]);
    
            // Verificar se o chunk existe
            if (!file_exists($chunkPath)) {
                fclose($fileHandle);
                throw new \Exception("Chunk não encontrado: " . $chunkPath);
            }
    
            // Ler o conteúdo do chunk
            $chunkContent = file_get_contents($chunkPath);
            if ($chunkContent === false) {
                fclose($fileHandle);
                throw new \Exception("Falha ao ler o conteúdo do chunk: " . $chunkPath);
            }
    
            // Escrever o conteúdo no arquivo final
            fwrite($fileHandle, $chunkContent);
        }
    
        // Fechar o arquivo final
        fclose($fileHandle);
    
        Log::info('Chunks recombinados com sucesso', [
            'finalPath' => $finalPath,
            'fileSize' => filesize($finalPath)
        ]);
    }

    protected function cleanChunks($fileName, $totalChunks)
    {
        $chunkDir = storage_path('app/uploads/chunks');
        
        // Remover todos os chunks correspondentes
        $chunkPattern = $chunkDir . '/' . $fileName . '.part*';
        $chunks = glob($chunkPattern);
        
        // Log::info('Limpando chunks', [
        //     'pattern' => $chunkPattern,
        //     'chunksToRemove' => $chunks
        // ]);
    
        foreach ($chunks as $chunkPath) {
            if (file_exists($chunkPath)) {
                unlink($chunkPath);
            }
        }
    }

    public function deleteSelected(Request $request)
    {
        // Valida se pelo menos uma video foi selecionada
        $request->validate([
            'selected_videos' => 'required|array',
            'selected_videos.*' => 'exists:midias,id',
        ]);

        // Busca as videos selecionadas
        $videoIds = $request->input('selected_videos');
        $videos = Midia::whereIn('id', $videoIds)->get();

        foreach ($videos as $video) {
            // Remove o arquivo da pasta public
            $videoPath = public_path($video->caminho);
            if (file_exists($videoPath)) {
                unlink($videoPath);
            }

            $thumbnailPath = public_path($video->thumbnail);
            if (file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }            
            // Remove a video do banco de dados
            $video->delete();
        }
    
        return redirect()->route('videos.index')->with('success', 'videos apagadas com sucesso!');
    }

    public function videos_admin($id)
    {
        $modelo = Modelo::find($id);

        $role = auth()->user()->role;

        $videos = Midia::where('modelo_id', $modelo->id)
        ->where('tipo', 'video')
        ->orderBy('principal', 'desc')   
        ->get();

        return view('admin.videos.index', compact('videos', 'role'));
    } 

    public function principal(){
        $user_id = auth()->user()->id;
        $modelo = Modelo::where('user_id', $user_id)->first();

        $videos = Midia::where('modelo_id', $modelo->id)->where('tipo', 'video')->where('principal', 1)->get();

        return view('admin.videos.principal', compact('videos'));        
    }    

}
