<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/pokemons/{page?}', [App\Http\Controllers\PokemonController::class, 'index'])->name('pokemons')->middleware('auth');

Route::put('/update_user', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('update.user')->middleware('auth');

Route::get('/cambia_datos', [App\Http\Controllers\HomeController::class, 'cambiaDatos'])->name('cambia_datos')->middleware('auth');

Route::get('/mis_datos', [App\Http\Controllers\HomeController::class, 'misDatos'])->name('mis_datos')->middleware('auth');

Route::get('/pokemon/ver/{pokemonName}', [App\Http\Controllers\PokemonController::class, 'getPokemonInfo'])->middleware('auth');

Route::get('/agregar_favorito/{namePokemon?}', [App\Http\Controllers\PokemonController::class, 'agregarFavorito'])->name('agregar_favorito')->middleware('auth');

Route::get('/favoritos', [App\Http\Controllers\PokemonController::class, 'favoritos'])->name('favoritos')->middleware('auth');

Route::get('/validar_favorito/{namePokemon?}', [App\Http\Controllers\PokemonController::class, 'validarFavorito'])->name('validar_favorito')->middleware('auth');

Route::get('/quitar_favorito/{namePokemon?}', [App\Http\Controllers\PokemonController::class, 'quitarFavorito'])->name('quitar_favorito')->middleware('auth');
