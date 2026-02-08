<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Modelo;
use App\Http\Services\MidiaService;
use App\Models\Midia;
use App\Models\HistoricoPlano;
use App\Models\Plano;
use Intervention\Image\ImageManager;


class FotoController extends Controller
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
        $user = auth()->user();
        $modelo = Modelo::where('user_id', $user->id)->first();

        $role = auth()->user()->role;

        $qtd_fotos_enviadas = Midia::where('modelo_id', $modelo->id)
        ->where('tipo', 'foto')
        ->where('principal', 0)    
        ->count();

        $fotos = Midia::where('modelo_id', $modelo->id)
        ->where('tipo', 'foto')
        ->where('principal', 0)    
        ->get();

        $plano_modelo = $modelo->historico_planos()->orderByDesc('created_at')->first();
        $plano = Plano::find($plano_modelo->plano_id);
        $qtd_fotos_plano = $plano->qtd_imagens;

        $mostra_botao = $qtd_fotos_enviadas <= $qtd_fotos_plano?1:0;


        return view('admin.fotos.index', compact('fotos','role', 'modelo','mostra_botao'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fotos.create');
    }

 

    public function store(Request $request)
    {
        
        $request->validate([
            'file' => 'required|image', // Apenas imagens válidas
        ]);

        $modelo_id = $request->modelo_id;
        $modelo = Modelo::find($modelo_id);
        $user = auth()->user();

        // Verifica se o campo 'file' foi enviado
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fotoPrincipal = $request->input('principal');
    
            try {
                // Cria uma instância do ImageManager com o driver GD
                $manager = new \Intervention\Image\ImageManager(
                    new \Intervention\Image\Drivers\Gd\Driver()
                );
    
                // Carrega a imagem
                $image = $manager->read($file->getRealPath());
    
                // Define as dimensões desejadas
                $targetWidth = 360;
                $targetHeight = 465;
    
                // Método "cover" - Redimensiona a imagem para que a dimensão menor preencha completamente
                // o espaço, mantendo a proporção, e depois corta o excesso da outra dimensão
    
                // Obtém as dimensões originais
                $originalWidth = $image->width();
                $originalHeight = $image->height();
                $originalRatio = $originalWidth / $originalHeight;
                $targetRatio = $targetWidth / $targetHeight;
    
                // Calcula novas dimensões mantendo a proporção original
                if ($originalRatio > $targetRatio) {
                    // Imagem original é mais larga proporcionalmente
                    // Redimensiona baseado na altura
                    $newHeight = $targetHeight;
                    $newWidth = $newHeight * $originalRatio;
                } else {
                    // Imagem original é mais alta proporcionalmente
                    // Redimensiona baseado na largura
                    $newWidth = $targetWidth;
                    $newHeight = $newWidth / $originalRatio;
                }
    
                // Redimensiona a imagem para as novas dimensões, mantendo a proporção
                $resized = $image->resize(width: (int)$newWidth, height: (int)$newHeight);
    
                // Calcula as coordenadas para o recorte centralizado
                $x = max(0, (int)(($newWidth - $targetWidth) / 2));
                $y = max(0, (int)(($newHeight - $targetHeight) / 2));
    
                // Recorta a imagem para as dimensões finais exatas
                $cropped = $resized->crop($targetWidth,$targetHeight, $x, $y);
    
                // Salva a imagem processada em um arquivo temporário
                $tempPath = tempnam(sys_get_temp_dir(), 'image');
                $cropped->save($tempPath);
    
                // Cria um UploadedFile fake para enviar ao MidiaService
                $uploadedFile = new \Illuminate\Http\UploadedFile(
                    $tempPath,
                    $file->getClientOriginalName(),
                    $file->getClientMimeType(),
                    null,
                    true
                );
    
                $foto = $this->midiaService->processarMidia($uploadedFile, $modelo->id, $fotoPrincipal, 'foto');
    
                // Limpa o arquivo temporário
                @unlink($tempPath);
    
                if ($fotoPrincipal == 1) {
                    return redirect()->route('fotos.principal')->with('success', 'Fotos enviadas com sucesso!');                    
                }
                if($user->role == 'user') {
                    return redirect()->route('fotos.index')->with('success', 'Fotos apagadas com sucesso!');
                } else {
                    return redirect()->route('fotos_admin.index', ['id' => $modelo_id])->with('success', 'Fotos apagadas com sucesso!');
                }
            } catch (\Exception $e) {
                return redirect()->route('fotos.index')->with('error', 'Houve um erro no envio da foto: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('fotos.index')->with('error', 'Nenhum arquivo foi enviado ou o arquivo é inválido.');
    }
    
    public function deleteSelected(Request $request)
    {
        $request->validate([
            'selected_fotos' => 'required|array',
            'selected_fotos.*' => 'exists:midias,id',
        ]);
    
        // Busca as fotos selecionadas
        $fotoIds = $request->input('selected_fotos');
        $fotos = Midia::whereIn('id', $fotoIds)->get();
        $modelo_id = $request->modelo_id;
    
        foreach ($fotos as $foto) {
            // Remove o arquivo da pasta public
            $fotoPath = public_path($foto->caminho);
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
    
            // Remove a foto do banco de dados
            $foto->delete();
        }

        $user = auth()->user();

        if($user->role == 'user') {
            return redirect()->route('fotos.index')->with('success', 'Fotos apagadas com sucesso!');
        } else {
            return redirect()->route('fotos_admin.index', ['id' => $modelo_id])->with('success', 'Fotos apagadas com sucesso!');
        }
    }

    public function principal(){
        $user_id = auth()->user()->id;
        $modelo = Modelo::where('user_id', $user_id)->first();

        $fotos = Midia::where('modelo_id', $modelo->id)
        ->where('tipo', 'foto')
        ->where('principal', 1)
        ->get();

        $mostra_botao = count($fotos) == 0?1:0;

        return view('admin.fotos.principal', compact('fotos','mostra_botao','modelo'));        
    }

    public function fotos_admin($id)
    {
        $modelo = Modelo::find($id);

        $role = auth()->user()->role;

        // Primeiro, vamos garantir que apenas uma foto esteja marcada como principal
        $fotoPrincipalAtual = Midia::where('modelo_id', $modelo->id)
            ->where('tipo', 'foto')
            ->where('principal', 1)
            ->first();

        // Se houver mais de uma foto marcada como principal, vamos corrigir
        if ($fotoPrincipalAtual) {
            Midia::where('modelo_id', $modelo->id)
                ->where('tipo', 'foto')
                ->where('principal', 1)
                ->where('id', '!=', $fotoPrincipalAtual->id)
                ->update(['principal' => 0]);
        }

        $itens = Midia::where('modelo_id', $modelo->id)
            ->where('tipo', 'foto')
            ->orderBy('principal', 'desc')   
            ->get();

        return view('admin.fotos.index_admin', compact('itens', 'role', 'modelo'));
    }    

    public function updatePrincipal(Request $request)
    {
        $request->validate([
            'foto_id' => 'required|exists:midias,id',
            'principal' => 'required|boolean',
            'modelo_id' => 'required|exists:modelos,id'
        ]);

        try {
            $foto = Midia::find($request->foto_id);
            
            // Se estiver marcando como principal, remove a foto principal anterior
            if ($request->principal == 1) {
                $fotoPrincipalAnterior = Midia::where('modelo_id', $request->modelo_id)
                    ->where('tipo', 'foto')
                    ->where('principal', 1)
                    ->where('id', '!=', $request->foto_id)
                    ->first();

                if ($fotoPrincipalAnterior) {
                    $fotoPrincipalAnterior->principal = 0;
                    $fotoPrincipalAnterior->save();
                }
            }

            $foto->principal = $request->principal;
            $foto->save();

            return response()->json(['success' => true, 'message' => 'Status da foto atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar status da foto: ' . $e->getMessage()], 500);
        }
    }
}
