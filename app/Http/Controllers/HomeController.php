<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->city==null && $user->address == null && $user->birthdate==null) {
            return view('home');
        }

        return redirect()->route('pokemons', ['page' => 0]);
    }

    public function cambiaDatos()
    {
        return view('home');

    }

    public function misDatos()
    {
        return view('profile');

    }

    public function updateProfile(Request $request)
    {
        // Validar los datos recibidos del formulario
        $request->validate([
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'city' => 'required|string|max:255',
        ]);

        // Obtener el usuario autenticado (puedes cambiar esto según tus necesidades)
        $user = auth()->user();

        // Actualizar los campos del usuario
        $user->address = $request->input('address');
        $user->birthdate = $request->input('birthdate');
        $user->city = $request->input('city');

        // Guardar los cambios en la base de datos
        $user->save();

        // Redirigir al usuario a la página de home
        return redirect()->route('pokemons', ['page' => 0])->with('success', 'Perfil completado con éxito');
    }

}
