<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use  App\Models\Favorite;
class PokemonController extends Controller
{
    public function getPokemonInfo($pokemonName)
    {
        // Hacer una solicitud a la API de Pokémon
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$pokemonName}");

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodificar la respuesta JSON
            $data = $response->json();

            // Extraer la información relevante
            $pokemonInfo = [
                'Nombre' => $data['name'],
                'ID' => $data['id'],
                'Tipo' => $data['types'][0]['type']['name'],
                'Altura' => $data['height'] / 10 . ' m', // Convertir la altura de decímetros a metros
                'Peso' => $data['weight'] / 10 . ' kg', // Convertir el peso de decígramos a kilogramos
                'Habilidades' => [],
                'Estadisticas' => [],
            ];

            // Obtener las habilidades del Pokémon
            foreach ($data['abilities'] as $ability) {
                $pokemonInfo['Habilidades'][] = $ability['ability']['name'];
            }

            // Obtener las estadísticas del Pokémon
            foreach ($data['stats'] as $stat) {
                $statName = $stat['stat']['name'];
                $statValue = $stat['base_stat'];
                $pokemonInfo['Estadisticas'][$statName] = $statValue;
            }

            return $pokemonInfo;
        }

        // Si la solicitud no fue exitosa, puedes manejar el error aquí
        return ['Error' => 'No se pudo obtener la información del Pokémon.'];
    }
    public function index($page=0, $limit=12)
    {
        $client = new Client();
        $pokemons = [];

        try {
            $response = $client->get('https://pokeapi.co/api/v2/pokemon?limit='.$limit.'&offset='.($page*$limit)); // Consulta la lista completa de Pokémon
            $data = json_decode($response->getBody());
            $cantidad = $data->count;
            $pokemonList = $data->results;

            foreach ($pokemonList as $pokemon) {
                $pokemonResponse = $client->get($pokemon->url);
                $pokemonData = json_decode($pokemonResponse->getBody());

                $name = $pokemonData->name;
                $description = "N/A";
                $imageUrl = asset('img/incognito.jpg');
                try {
                    $descriptionResponse = $client->get("https://pokeapi.co/api/v2/pokemon-species/$name/");
                    $descriptionData = json_decode($descriptionResponse->getBody());

                    foreach ($descriptionData->flavor_text_entries as $entry) {
                        if ($entry->language->name == "es") {
                            $description = $entry->flavor_text;
                            break;
                        }
                    }

                    $imageUrl = $pokemonData->sprites->front_default;
                } catch (RequestException $e) {}

                array_push($pokemons, ["name" => $name, "description" => $description, "imagen" => $imageUrl]);

            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return view('pokemons.index', compact('pokemons', 'cantidad', 'page'));
    }

    public function agregarFavorito($namePokemon)
    {
        $favorite = new Favorite();

        $user = auth()->user();

        $data = [
            "id_usuario" => $user->id,
            "ref_api" => $namePokemon
        ];
        $favorite->fill($data);
        $favorite->save($data);

        return redirect()->route('pokemons', ['page' => 0])->with('success', 'Pokemon agregado a favoritos satisfactoriamente');
    }

    public function favoritos()
    {
        $user = auth()->user();
        $favorites = Favorite::where('id_usuario', $user->id)->get();

        $client = new Client();
        $pokemons = [];

        foreach ($favorites as $favorite) {
            $pokemonResponse = $client->get("https://pokeapi.co/api/v2/pokemon/$favorite->ref_api");
                $pokemonData = json_decode($pokemonResponse->getBody());

            $name = $pokemonData->name;
            $description = "N/A";
            $imageUrl = asset('img/incognito.jpg');
            try {
                $descriptionResponse = $client->get("https://pokeapi.co/api/v2/pokemon-species/$name/");
                $descriptionData = json_decode($descriptionResponse->getBody());
                $name = $descriptionData->name;
                foreach ($descriptionData->flavor_text_entries as $entry) {
                    if ($entry->language->name == "es") {
                        $description = $entry->flavor_text;
                        break;
                    }
                }
                $imageUrl = $pokemonData->sprites->front_default;
            } catch (RequestException $e) {}

            array_push($pokemons, ["name" => $name, "description" => $description, "imagen" => $imageUrl]);

        }
        return view('pokemons.index', compact('pokemons'));
    }

    public function validarFavorito($namePokemon)
    {
        $user = auth()->user();

        $favorites = Favorite::where('id_usuario', $user->id)->where('ref_api', $namePokemon)->first();
        if($favorites){
            return true;
        }
        return false;
    }

    public function quitarFavorito($namePokemon)
    {
        $user = auth()->user();

        $favorite = Favorite::where('id_usuario', $user->id)->where('ref_api', $namePokemon)->first();

        $favorite->delete();

        return redirect()->route('favoritos')->with('success', 'Pokemon quitado de favoritos satisfactoriamente');
    }
}
