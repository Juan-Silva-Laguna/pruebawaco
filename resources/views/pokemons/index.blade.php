@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="vol-md-6">
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </symbol>
                </svg>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                    <div>
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif
        <br>
        <h1 class="text-center">Lista de Pokémones</h1>
        <hr>
        <div class="row">
            @foreach ($pokemons as $pokemon)
                <div class="col-lg-3 col-md-4 col-sm-2 mt-3">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ $pokemon['imagen']; }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ strtoupper($pokemon['name']); }}</h5>
                            <p class="card-text">{{ substr($pokemon['description'], 0, 90); }}...</p>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-pokemon-name="{{ $pokemon['name']; }}" data-bs-target="#myModal">VER</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <hr>
        @if(isset($page) && isset($cantidad))
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    @if($page!=0)
                        <li class="page-item">
                            <a class="page-link" href="{{ url('pokemons/'.$page-1) }}" tabindex="-1">Anterior</a>
                        </li>
                    @endif

                    @if($page>3)
                        <li class="page-item"><a class="page-link" href="{{ url('pokemons/'.$page-3) }}">{{ $page-2; }}</a></li>
                        <li class="page-item"><a class="page-link" href="{{ url('pokemons/'.$page-2) }}">{{ $page-1; }}</a></li>
                        <li class="page-item"><a class="page-link" href="{{ url('pokemons/'.$page-1) }}">{{ $page; }}</a></li>
                    @endif

                    <li class="page-item"><a class="page-link active" href="{{ url('pokemons/'.$page) }}">{{ $page+1; }}</a></li>

                    @if($page <= 104)
                        <li class="page-item"><a class="page-link" href="{{ url('pokemons/'.$page+1) }}">{{ $page+2; }}</a></li>
                        <li class="page-item"><a class="page-link" href="{{ url('pokemons/'.$page+2) }}">{{ $page+3; }}</a></li>
                        <li class="page-item"><a class="page-link" href="{{ url('pokemons/'.$page+3) }}">{{ $page+4; }}</a></li>
                    @endif

                    @if(($page*12)+12 <= $cantidad)
                        <li class="page-item">
                            <a class="page-link" href="{{ url('pokemons/'.$page+1) }}">Proximo</a>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Información del Pokémon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Aquí puedes mostrar la información cargada desde la solicitud HTTP -->
                <p id="pokemonInfo">Cargando información...</p>
            </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#myModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que disparó el evento
            var modal = $(this);

            // Obtiene el nombre del Pokémon del atributo data
            var pokemonName = button.data('pokemon-name');

            // Realiza la solicitud HTTP aquí y actualiza el contenido del modal
            let x=false;
            $.get('/validar_favorito/'+pokemonName, function(data) {
                console.log(data);
                x = data;
            });
            console.log(x);
            $.get('/pokemon/ver/'+pokemonName, function(data) {
                modal.find('#pokemonInfo').html(
                    `<h3>Nombre: ${data.Nombre} </h3>
                    <h3>Tipo: ${data.Tipo} </h3>
                    <h3>Altura: ${data.Altura} </h3>
                    <h3>Peso: ${data.Peso} </h3>
                    <h3>Habilidades: ${data.Habilidades.toString()} </h3>
                    <h3>Estadisticas:</h3>
                    <ul>
                        <li>Puntos de Salud: ${data.Estadisticas.hp}</li>
                        <li>Ataque: ${data.Estadisticas.attack}</li>
                        <li>Defensa: ${data.Estadisticas.defense}</li>
                        <li>Velocidad: ${data.Estadisticas.speed}</li>

                        <li>Ataque especial: ${data.Estadisticas['special-attack']}</li>
                        <li>Defensa especial: ${data.Estadisticas['special-defense']}</li>
                    </ul>
                    <a class="btn ${x ? 'btn-danger': 'btn-warning'}" href="${x ? '{{ route("quitar_favorito") }}/'+data.Nombre : '{{ route("agregar_favorito") }}/'+data.Nombre}">${x ? 'QUITAR DE FAVORITOS': 'AGRAGAR A FAVORITOS'} </a>
                    `);
            });
        });
    </script>

@endsection
