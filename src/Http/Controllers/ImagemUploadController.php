<?php

namespace Rd7\ImagemUpload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rd7\ImagemUpload\ImagemUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;

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
        // dd(Input::all());
        $destino = 'ajuste';
        // $resolucao = ['original' => ['h' => 30, 'w' => 30], 'p' => ['h' => 150, 'w' => 150]];
        $resolucao = ['p' => ['h' => 150, 'w' => 150], 'm' => ['h' => 500, 'w' => 500], 'original'];
        $imagens = ImagemUpload::salva(['input_file' => 'imagem', 'destino' => $destino, 'resolucao' => $resolucao]); //Apenas move a imagem sem alterar sua resolução
        // $imagens = ImagemUpload::salva(['input_file' => 'imagem', 'destino' => $destino, 'resolucao' => $resolucao]); //cria novas imagens para as pastas com as resoulções.

        if ($imagens) {
            $input['imagem'] = $imagens;

            session(['imagens' => (is_array($imagens) ? $imagens : [$imagens])]);
            return redirect()->back();
        }

        return redirect()->back();

    }

    public function imagemRender(Request $request, $path = null, $tamanho = '/', $imagem = null)
    {
        // dd($request->all());
        $path = config('filesystems')['disks']['public']['root']. "/" . $path . '/' . $tamanho . (!empty($imagem) ? "/" . $imagem : "");

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        if($type == 'text/plain' || $type == "image/svg") {
            $type = 'image/svg+xml';
        }

        $file = Image::make($file);

        if ($request->get('h') || $request->get('w')) {
            $file = $file->resize($request->get('w'), $request->get('h'), function ($constraint) use($request) {
                if($request->get('w') == 'auto' || $request->get('h') == 'auto'){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            });
        }

        $response = Response::make($file->encode($file->mime), 200)
                ->header("Cache-Control", "public, max-age=31536000")
                 ->header("Content-Type", $type);

        return $response;

    }

    public function imagemDelete($imagem)
    {
        $destino = 'ajuste';
        $resolucao = ['p' => ['h' => 150, 'w' => 150], 'm' => ['h' => 500, 'w' => 500]];

        session()->forget('imagens.'.array_search($imagem, session('imagens')));

        ImagemUpload::deleta(['imagem' => $imagem, 'destino' => $destino, 'resolucao' => $resolucao]);

       return redirect()->back();

    }

}
