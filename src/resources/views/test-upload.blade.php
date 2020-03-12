<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            table {
                width: 80%;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                @if(session()->has('imagens'))
                    @php
                        $imagens = session('imagens');
                    @endphp

                    <table class="table">
                        @foreach($imagens as $imagem)
                        <tr>
                            <td>
                                <img src="{{ route('imagem.render', 'ajuste/p/' . $imagem) }}?w=auto&h=90" alt="" class="cortar">
                            </td>
                            {{--  <td><img src="{{ route('img-load',['h=500&w=100&img='. $imagem]) }}" alt="" class="cortar"></td>  --}}
                            <td><a href="{{ route('imagem.delete', $imagem) }}">Excluir</a></td>
                        </tr>
                        @endforeach
                    </table>
                @endif
                {{-- <label for="">Única imagem</label> --}}
                <div class="container">
                <form action="/pot-upload-imagem-test" method="POST" enctype="multipart/form-data">
                    @csrf

                    @preview
                    {{-- @component('imagemupload::components.upload-view', ['name' => 'imagem', 'label' => 'Foto', 'multiple' => true])
                    @endcomponent --}}

                    <input type="file" class="preview" name="imagens" multiple>
                    <button class="bt btn-primary" type="submit">Upload Imagem</button>
                </form>
                </div>
               {{-- <form action="/pot-upload-imagem-test" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="">Imagem</label>
                    <input type="file" name="imagem" id="" required>
                    <button class="bt btn-primary" type="submit">Upload Imagem</button>
                </form>

                <label for="">Varias imagens</label>
                <form action="/pot-upload-imagem-test" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="">Imagem</label>
                    <input type="file" name="imagem[]" id="" multiple required>
                    <button class="bt btn-primary" type="submit">Upload Imagem</button>
                </form> --}}
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

        {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> --}}

        @yield('scripts')
    </body>
</html>


{{-- <div class="col-md-4">
    <div class="card mb-4 shadow-sm">
      <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
      <div class="card-body">
        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        <div class="d-flex justify-content-between align-items-center">
          <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
          </div>
          <small class="text-muted">9 mins</small>
        </div>
      </div>
    </div>
  </div> --}}
