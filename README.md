# Laravel Imagem Upload
> Faça upload de imagens ainda mais facilmente no Laravel

Este pacote facilita o **upload de imagens e renderização** delas sem você precisar criar pastas e link simbólico. Envie imagens e redimensione em diversos tamanhos para seu site.

O que ele fará por você:
- enviar imagens para uma pasta não publica
- criar automáticamente os diretórios de imagens
- redimensionar para quantos tamanhos desejar
- renderizar imagens escondendo a pasta de origem


## Instalação

requer Laravel >= 5.5 e PHP 7.1:

``` composer require rd7/imagemupload ```  


## Exemplo de uso

Não é bom carregar uma imagem de 980x980 pixels para exibir em uma miniatura de 100x100 pixels, por exemplo. A melhor opção seria ter a imagem em vários tamanhos diferentes. 
Exemplo:

![exemplo-imagem](https://user-images.githubusercontent.com/12474305/58225487-98df1080-7cf8-11e9-8a0a-7572f7191791.jpg)
_Você pode ter imagens em quantos tamanhos desejar._



```html
<!--Para enviar apenas uma imagem-->
<input type="file" name="user" />
```

```php
 public function __construct()
 {
 	$this->user = [
			'input_file' => 'user', //nome do input
			'destino' => 'users/',
    		'resolucao' => ['p' => ['h' => 200, 'w' => 200], 'm' => ['h' => 400, 'w' => 400], ...]
      ];
}
	
	public function store(Request $request)
    {
            $input = $request->all();
            
            $imagens = ImagemUpload::salva($this->user);
			//retorno: image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg
            if ($imagens) {
                $input['imagem'] = $imagens;
            }
	}
```
O pacote vai criar as pastas **p** e **m** dentro de **users/**

`storage/app/public/users/p/image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg`

`storage/app/public/users/m/image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg`

## Enviando várias imagens

Caso queira enviar várias imagens, é muito simples:

```html
<input type="file" name="galeria[]" multiple />
```

```php
 public function __construct()
 {
 	$this->user = [
			'input_file' => 'galeria', //Nome do input
			'destino' => 'galeria/',
    		'resolucao' => ['p' => ['h' => 200, 'w' => 200], 'm' => ['h' => 400, 'w' => 400], ...]
      ];
}
	
	public function store(Request $request)
    {
            $input = $request->all();
            
            $imagens = ImagemUpload::salva($this->user);
			//retorno: [
				// image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg,
				// image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg
			// ]
            if ($imagens) {
                $input['imagens'] = $imagens;
            }
	}
```
