<?php
namespace Rd7\ImagemUpload;

use File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

class ImagemUpload {

    public static function salva($array = array()) {

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        ini_set('max_input_time', -1);
        ini_set('max_input_vars', -1);
        ini_set('post_max_size', -1);
        ini_set('upload_max_filesize', -1);
        ini_set('max_file_uploads', -1);
        set_time_limit(0);

        $array['imagens'] = (isset($array['input_file'])) ? $array['input_file'] : false;

        $array['destino'] = (isset($array['destino'])) ? $array['destino'] : false;

        $array['preencher'] = (isset($array['preencher'])) ? $array['preencher'] : [];//@array Tamanhos que terão um fundo branco ex: ['p', 'g']

        $array['resolucao'] = (isset($array['resolucao'])) ? $array['resolucao'] : false;

        $array['background'] = (isset($array['background'])) ? $array['background'] : '255, 255, 255, 0';

        $array['crop'] = (isset($array['crop'])) ? $array['crop'] : false;


        if (!$array['imagens'] || !$array['destino']) {
            exit("indices \"input_file\" e \"destino\" são obrigatórios");
        }

        $imagens = Input::file(str_replace('[]', '', $array['imagens']));

        if ($array['imagens'] and $array['destino'] and isset($imagens) > 0) {

            $returnImages = is_array($imagens) ? [] : null;

            if (is_array($imagens)) {
                foreach ($imagens as $imagem) {
                    array_push($returnImages, ImagemUpload::enviaImagem($imagem, $array));
                }
            } else {
                $returnImages = ImagemUpload::enviaImagem($imagens, $array);
            }

            return $returnImages;

        }
        return null;
    }

    #se white true, insere fundo branco
    public static function enviaImagem($imagem, $array) {

        $source = $imagem->getRealPath();

        $nome = str_slug(pathinfo($imagem->getClientOriginalName(), PATHINFO_FILENAME));

        $array['extensao'] = $extensao = $imagem->getClientOriginalExtension();

        $array['novoNome'] = $nome . '_' .md5(uniqid(rand(), true)) . '.' . $extensao;
        $array['source'] = $source;

        if ($array['resolucao']) {

            if (count($array['resolucao']) == count($array['resolucao'], COUNT_RECURSIVE)) {

                $array['resolucao']['dimensoes'] = $array['resolucao'];
                ImagemUpload::moveImagem($array);
            } else {

                foreach ($array['resolucao'] as $pasta => $dimensoes) {
                    // dd($dimensoes, is_array($pasta), $pasta);
                    $array['resolucao'] = ['pasta' => ((is_array($dimensoes)) ? $pasta : $dimensoes), 'dimensoes' => $dimensoes];
                    // dd($array['resolucao']);
                    // $array['resolucao'] = ['pasta' => $pasta, 'dimensoes' => $dimensoes];
                    // dd($pasta, $dimensoes, $array['resolucao']);
                    ImagemUpload::moveImagem($array);
                }
            }

            return $array['novoNome'];
        } else {

            ImagemUpload::moveImagem($array);

            return $array['novoNome'];
        }
    }

    public static function moveImagem($array){

        try {
            if ($array['extensao'] != 'svg') {
                $img = Image::make($array['source']);

                if (isset($array['crop'])) {

                }

                if (!empty($array['crop']['x']) || !empty($array['crop']['y'])) {

                    $x = (integer) $array['crop']['x'];
                    $y = (integer) $array['crop']['y'];
                    $h = (integer) $array['crop']['h'];
                    $w = (integer) $array['crop']['w'];
                    $img = $img->crop($w, $h, $x, $y);
                }

                if($array['resolucao']){
                    if (isset($array['resolucao']['dimensoes']['w']) and isset($array['resolucao']['dimensoes']['h'])) {
                        $img->resize(isset($array['resolucao']['dimensoes']['w']) ? $array['resolucao']['dimensoes']['w'] : $w, isset($array['resolucao']['dimensoes']['h']) ? $array['resolucao']['dimensoes']['h'] : $h, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                }

                if (count($array['preencher']) > 0 && in_array($array['resolucao']['pasta'], $array['preencher'])) {
                    $img->resizeCanvas($array['resolucao']['dimensoes']['w'], $array['resolucao']['dimensoes']['h'], 'center', false, explode(', ', $array['background']));
                }
            }

            $array['destino'] = config("imagemupload.destino.root")."/".$array['destino'];

            $caminho = str_replace('//', '/', $array['destino'].(isset($array['resolucao']['pasta']) ? "/" . $array['resolucao']['pasta']."/" : '').'/');
            // $caminho = str_replace('//', '/', $caminho);

            if (!File::exists($caminho)) {
                $result = File::makeDirectory($caminho, 0777, true);
                if (config('imagemupload.generate_gitignore')) {
                    File::put($caminho . '.gitignore', '* !.gitignore');
                }
            }


            if ($array['extensao'] != 'svg') {
                $img->save($caminho . $array['novoNome'], config('imagemupload.qualidade'));
            } else {
                File::copy($array['source'], $caminho.$array['novoNome']);
            }

            return true;

        } catch (\Exception $e) {
            dd($e);
            return false;

        }

    }

    public static function deleta($array = array())
    {
        $imagem             = $array['imagem'];
        $resolucao          = (isset($array['resolucao'])) ? $array['resolucao'] : false;
        $array['destino']   = config("imagemupload.destino.root")."/".$array['destino'];
        $status             = [];
        // $destino = str_replace('//', '/', $array['destino'].(isset($array['resolucao']['pasta']) ? "/" . $array['resolucao']['pasta']."/" : '').'/');
        if(is_array($resolucao)) {
            foreach ($resolucao as $pasta => $tamanho) {
                if(is_array($resolucao[$pasta])){
                    $status[] = @unlink($array['destino'] . "/" . $pasta . '/' . $imagem);
                } else {
                    $status[] = @unlink($array['destino'] . "/" . $imagem);
                }
            }
        } else {
            $status = @unlink($array['destino'] . "/" . $imagem);
        }

        return $status;
    }
}
