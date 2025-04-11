@extends('adminlte::page')

@section('title', 'Gestion des vendeurs')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Gestion des vendeurs</h1>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des vendeurs</h3>
            <div class="card-tools">
                <form action="{{ route('admin.vendeurs.index') }}" method="GET" class="form-inline">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Rechercher..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Lieu de résidence</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendeurs as $vendeur)
                        <tr>
                            <td>{{ $vendeur->id }}</td>
                            <td>{{ $vendeur->prenom }} {{ $vendeur->nom }}</td>
                            <td>{{ $vendeur->email }}</td>
                            <td>{{ $vendeur->lieu_de_residence }}</td>
                            <td>{{ $vendeur->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.vendeurs.show', $vendeur) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                @if(!$vendeur->hasRole('vendeur'))
                                    <form action="{{ route('admin.vendeurs.approve', $vendeur) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Approuver
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.vendeurs.reject', $vendeur) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i> Retirer
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun vendeur trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $vendeurs->links() }}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Page de gestion des vendeurs chargée!');
    </script>
@stop 