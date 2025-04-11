@extends('adminlte::page')

@section('title', 'Gestion des vendeurs')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Gestion des vendeurs</h1>
        <a href="{{ route('admin.vendeurs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un vendeur
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des vendeurs</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Rechercher">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Formules</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendeurs as $vendeur)
                        <tr>
                            <td>{{ $vendeur->prenom }} {{ $vendeur->nom }}</td>
                            <td>{{ $vendeur->email }}</td>
                            <td>{{ $vendeur->nom_entreprise }}</td>
                            <td>{{ $vendeur->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.vendeurs.show', $vendeur->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.vendeurs.edit', $vendeur->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($vendeur->is_active)
                                            <form action="{{ route('admin.vendeurs.deactivate', $vendeur->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-warning">
                                                    <i class="fas fa-ban mr-2"></i> Désactiver
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.vendeurs.activate', $vendeur->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="fas fa-check-circle mr-2"></i> Activer
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.vendeurs.delete', $vendeur->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce vendeur?')">
                                                <i class="fas fa-trash mr-2"></i> Supprimer
                                            </button>
                                        </form>
                                        <a class="dropdown-item" href="{{ route('admin.impersonate', $vendeur->id) }}">
                                            <i class="fas fa-user-secret mr-2"></i> Se connecter en tant que
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Aucun vendeur trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            @if(isset($vendeurs) && method_exists($vendeurs, 'links'))
                {{ $vendeurs->links() }}
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-header .input-group {
            margin-left: auto;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Gestion des vendeurs page loaded!');
    </script>
@stop 