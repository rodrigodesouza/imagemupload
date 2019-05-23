# Laravel Imagem Upload
> Faça upload de imagens ainda mais facilmente no Laravel

[ ! [NPM Version] [npm-imagem] ] [npm-url] [ ! [Status da Build][travis-image] ] [travis-url][ ! [Estatísticas de Downloads] [npm-downloads] ] [npm-url ]  

Este pacote facilita o **upload de imagens e renderização** delas sem você precisar criar pastas e link simbólico. Envie imagens e redimensione em diversos tamanhos para seu site.

O que ele fará por você:
- enviar imagens para uma pasta não publica
- criar automáticamente os diretórios de imagens
- redimensionar para quantos tamanhos desejar
- renderizar imagens escondendo a pasta de origem


! [] (header.png)

## Instalação

requer Laravel >= 5.5 e PHP 7.1:

``` composer require rd7/imagemupload ```  


requer Laravel >= 5.5 e PHP 7.1:

## Exemplo de uso

Não é bom carregar uma imagem de 980x980 pixels para exibir em uma miniatura de 100x100 pixels, por exemplo. A melhor opção seria ter a imagem em vários tamanhos diferentes. Exemplo:

![exemplo-imagem](https://user-images.githubusercontent.com/12474305/58225487-98df1080-7cf8-11e9-8a0a-7572f7191791.jpg)

_Você pode ter imagens em quantos tamanhos desejar._

   ```php
 public function __construct()
 {
 	$this->user = [
			'input_file' => 'user', //Nome do input
			'destino' => 'users/',
    		'resolucao' => ['p' => ['h' => 200, 'w' => 200], 'm' => ['h' => 400, 'w' => 400], ...]
      ];
}
	
	public function store(Request $request)
    {
            $input = $request->all();
            
            $imagens = ImagemUpload::salva($this->capa);
			// retorno image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg
            if ($imagens) {
                $input['imagem'] = $imagens;
            }
	}
```
O pacote vai criar as pastas **p** e **m** dentro de **users/**

`storage/app/public/users/p/image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg`

`storage/app/public/users/m/image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg`
