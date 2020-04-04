<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

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
                                <img src="{{ route('imagem.render', 'ajuste/m/' . $imagem) }}?h=200&w=200" alt="" class="cortar">
                            </td>
                            {{--  <td><img src="{{ route('img-load',['h=500&w=100&img='. $imagem]) }}" alt="" class="cortar"></td>  --}}
                            <td><a href="{{ route('imagem.delete', $imagem) }}">Excluir</a></td>
                        </tr>
                        @endforeach
                    </table>
                @endif
                {{-- <label for="">Única imagem</label> --}}
                <form action="/pot-upload-imagem-test" method="POST" enctype="multipart/form-data">
                    @csrf
                    @component('imagemupload::components.upload-view', ['name' => 'imagem', 'label' => 'Foto', 'multiple' => true])

                    @endcomponent
                    <button class="bt btn-primary" type="submit">Upload Imagem</button>
                </form>
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
