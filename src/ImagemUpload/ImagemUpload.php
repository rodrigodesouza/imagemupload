<?php
namespace Rd7\ImagemUpload;

use File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

class ImagemUpload {

    public static function salva($array = array()) {
  
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

        list($w, $h) = getimagesize($source);
        
        $nome = str_slug(pathinfo($imagem->getClientOriginalName(), PATHINFO_FILENAME));

        $extensao = $imagem->getClientOriginalExtension();

        $array['novoNome'] = $nome . '_' .md5(uniqid(rand(), true)) . '.' . $extensao;
        $array['source'] = $source;

        if ($array['resolucao']) {

            if (count($array['resolucao']) == count($array['resolucao'], COUNT_RECURSIVE)) {

                $array['resolucao']['dimensoes'] = $array['resolucao'];
                ImagemUpload::moveImagem($array);
            } else {

                foreach ($array['resolucao'] as $pasta => $dimensoes) {
                    $array['resolucao'] = ['pasta' => $pasta, 'dimensoes' => $dimensoes];   
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
                $img->resize(isset($array['resolucao']['dimensoes']['w']) ? $array['resolucao']['dimensoes']['w'] : $w, isset($array['resolucao']['dimensoes']['h']) ? $array['resolucao']['dimensoes']['h'] : $h, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            if (count($array['preencher']) > 0 && in_array($array['resolucao']['pasta'], $array['preencher'])) {
                $img->resizeCanvas($array['resolucao']['dimensoes']['w'], $array['resolucao']['dimensoes']['h'], 'center', false, explode(', ', $array['background']));
            }

            $array['destino'] = config("imagemupload.destino.root")."/".$array['destino'];

            $caminho = str_replace('//', '/', $array['destino'].(isset($array['resolucao']['pasta']) ? "/" . $array['resolucao']['pasta']."/" : '').'/');

            if (!File::exists($caminho)) {
                $result = File::makeDirectory($caminho, 0777, true);
                File::put($caminho . '.gitignore', '* !.gitignore');
            }            

            $img->save($caminho . $array['novoNome']);

            return true;

        } catch (\Exception $e) {
            dd($e);
            return false;
        }

    }

    public static function deleta($array = array()) 
    {
        $imagem     = $array['imagem'];
        $resolucao  = $array['resolucao'];
        $destino    = $array['destino'];
        $status = [];
        if(is_array($resolucao)) {
            foreach ($resolucao as $pasta => $tamanho) {
                if(is_array($resolucao[$pasta])){
                    $status[] = @unlink($destino . $pasta . '/' . $imagem);
                } else {
                    $status[] = @unlink($destino . $imagem);
                }
            }
        } else {
            $status = @unlink($destino . $imagem);
        }

        return $status;
    }
}
