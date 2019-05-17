<?php

namespace Rd7\ImagemUpload\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;
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
        $destino = 'user/';
        $resolucao = ['p' => ['h' => 150, 'w' => 150], 'm' => ['h' => 500, 'w' => 500]];

        $imagens = ImagemUpload::salva(['input_file' => 'imagem', 'destino' => $destino, 'preencher' =>['p'], 'resolucao' => $resolucao]);

        if ($imagens) {
            $input['imagem'] = $imagens;

            session(['imagens' => (is_array($imagens) ? $imagens : [$imagens])]);
            return redirect()->back();
        }

        return redirect()->back();

    }

    public function imagemRender($path = null, $tamanho = 'p', $imagem = null)
    {

        $path = config('filesystems')['disks']['public']['root'] . $path . '/' . $tamanho . '/' . $imagem;
        // $path = storage_path() . '/app/public/' . $path . '/' . $tamanho . '/' . $imagem;

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;

    }

    public function imagemDelete($imagem)
    {
        $destino = storage_path() . '/app/public/user/';
        $resolucao = ['p' => ['h' => 150, 'w' => 150], 'm' => ['h' => 500, 'w' => 500]];

        session()->forget('imagens.'.array_search($imagem, session('imagens')));

        ImagemUpload::deleta(['imagem' => $imagem, 'destino' => $destino, 'resolucao' => $resolucao]);

       return redirect()->back();

    }
    

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
