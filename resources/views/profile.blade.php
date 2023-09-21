@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">MIS DATOS</div>

                <div class="card-body">
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Nombre</label>

                            <div class="col-md-6">
                                <input  type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Correo</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">Dirección</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ Auth::user()->address }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-end">Fecha de Nacimiento:</label>
                            <div class="col-md-6">
                                <input type="date" class="form-control" value="{{ Auth::user()->birthdate }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="city" class="col-md-4 col-form-label text-md-end">Ciudad</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ Auth::user()->city }}" disabled>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
