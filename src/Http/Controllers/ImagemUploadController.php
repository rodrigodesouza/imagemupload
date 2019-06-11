<?php

namespace Rd7\ImagemUpload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rd7\ImagemUpload\ImagemUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


class ImagemUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function testUploadImagem()
    {
        return view("imagemupload::test-upload");
    }
    public function uploadImagem()
    {   
        // testes
        $destino = 'teste';
        $resolucao = ['p' => ['h' => 150, 'w' => 150], 'm' => ['h' => 500, 'w' => 500]];
        // $imagens = ImagemUpload::salva(['input_file' => 'imagem', 'destino' => $destino]); //Apenas move a imagem sem alterar sua resolução
        $imagens = ImagemUpload::salva(['input_file' => 'imagem', 'destino' => $destino, 'preencher' =>['p'], 'resolucao' => $resolucao]); //cria novas imagens para as pastas com as resoulções.

        if ($imagens) {
            $input['imagem'] = $imagens;

            session(['imagens' => (is_array($imagens) ? $imagens : [$imagens])]);
            return redirect()->back();
        }

        return redirect()->back();

    }

    public function imagemRender($path = null, $tamanho = '/', $imagem = null)
    {
        $path = config('filesystems')['disks']['public']['root']. "/" . $path . '/' . $tamanho . (!empty($imagem) ? "/" . $imagem : "");

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);
        if($type == 'text/plain') {
            $type = 'image/svg+xml';
        }
        
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;

    }

    public function imagemDelete($imagem)
    {
        $destino = 'teste';
        $resolucao = ['p' => ['h' => 150, 'w' => 150], 'm' => ['h' => 500, 'w' => 500]];

        session()->forget('imagens.'.array_search($imagem, session('imagens')));

        ImagemUpload::deleta(['imagem' => $imagem, 'destino' => $destino, 'resolucao' => $resolucao]);

       return redirect()->back();

    }

}
