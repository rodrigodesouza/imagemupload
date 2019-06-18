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

Não é bom carregar uma imagem de 1200x1200 pixels para exibir em uma miniatura de 100x100 pixels, por exemplo. A melhor opção seria ter a imagem em vários tamanhos diferentes.
Exemplo:

![exemplo-imagem](https://user-images.githubusercontent.com/12474305/58225487-98df1080-7cf8-11e9-8a0a-7572f7191791.jpg)
_Você pode ter imagens em quantos tamanhos desejar._

## Enviando uma imagem
**Passo #1**
em seu formulário html:
```html
<!--Para enviar apenas uma imagem-->
<input type="file" name="user" />
```
**Passo #2**
em seu **Controller** coloque as configurações:
```php

use Rd7\ImagemUpload\ImagemUpload;

 public function __construct()
 {
 	$this->user = [
		'input_file' => 'user', //nome do input
		'destino' => 'users/', //Pasta que será criada automáticamente dentro de storage/app/public/
    		'resolucao' => ['p' => ['h' => 200, 'w' => 200], 'm' => ['h' => 400, 'w' => 400], ...] //Não há limites de quantos tamanhos podem ser configuradas.
      ];
}
	
public function store(Request $request)
{
    $input = $request->all();

    $imagens = ImagemUpload::salva($this->user);
		//retorno: image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg
    if ($imagens) {
	// a função retorna o novo nome da imagem. guarde em seu banco de dados.
	$input['imagem'] = $imagens;
    }
}
```
O pacote vai criar as pastas **p** e **m** dentro de **users/**

`storage/app/public/users/p/image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg` imagem 200X200

`storage/app/public/users/m/image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg` imagem 400x400

**Passo #3**
renderizando a imagem:
```php
<img src="{{ route('imagem.render', 'users/p/' . $user->imagem) }}" />
```
> **OBS:** use a rota **'imagem.render'** para renderizar as imagens, seguindo com o nome da pasta (users) e tamanho (pasta p, m, etc.).

## Deletando uma imagem:
em seu Controller:

```php
public function destroy($id)
{
	$user = User::find($id);
            
	$imagem = $user->imagem;

	$this->user['imagem'] = $imagem; // acrescente ao array o indice "imagem", e como valor, o nome da imagem.

	$user->delete();
	
	if (!empty($imagem)) {
		ImagemUpload::deleta($this->user); // $this->user é o array com todas as configurações de envio de imagens.
	}

	return redirect()->route('users.list')->with('msg', 'registro excluido com sucesso!');
}
```
> **OBS:** **$this->user**, é o seu array com as configurações de envio de suas imagens. A função **deleta()** irá apagar do disco todas as imagens em suas respectivas pastas.

## Enviando várias imagens
Caso queira enviar várias imagens, é muito simples:
**Passo #1**
em seu formulário html:
```html
<input type="file" name="galeria[]" multiple />
```
**Passo #2**
em seu **Controller** coloque as configurações:
```php
use Rd7\ImagemUpload\ImagemUpload;

 public function __construct()
 {
 	$this->galeria = [
		'input_file' => 'galeria', //nome do input
		'destino' => 'galeria/',
    		'resolucao' => ['p' => ['h' => 200, 'w' => 200], 'm' => ['h' => 400, 'w' => 400], ...]
      ];
}
	
public function store(Request $request)
{
	$input = $request->all();

	$imagens = ImagemUpload::salva($this->galeria);
	/*
	$imagens retorna: 
	array (
		image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg,
		image-example_3fc5ac232a6e60a10ca20a90350954a9.jpg
	)
	*/
	if ($imagens) {
		// guarde o nome das imagens em seu banco de dados
		$input['imagens'] = $imagens;
	}
}
```
> OBS: Ao enviar várias imagens, você recebe um array com o novo nome das imagens.

### Apenas mover uma imagem e manter o tamanho original
```php

 $this->user = [
 	'input_file' => 'user',
	'destino' => 'users/'
  ];
```


### Redimencionar imagens e manter também a original
```php

 $this->user = [
 	'input_file' => 'user',
	'destino' => 'users/',
	'resolucao' => ['p' => ['h' => 200, 'w' => 200], 'm' => ['h' => 400, 'w' => 400], 'pasta_original'] 
	// Apenas coloque o nome do indice que será o nome da pasta com as imagens originais.
  ];
```
Mais informações serão acrescentadas à este documento.
Qualquer dúvida, entre em contato.

------------
Rodrigo de Souza - rd7.rodrigo@gmail.com

https://github.com/rodrigodesouza/imagemupload/
