@extends('adminlte::page')

@section('title', 'Liste des Clients')

@section('content_header')
    <h1>Liste des Clients</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tous les clients</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="search" class="form-control float-right" placeholder="Rechercher">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Lieu de résidence</th>
                        <th>Date d'inscription</th>
                        <th>Contrats</th>
                        <th>Factures</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->nom }}</td>
                        <td>{{ $client->prenom }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->lieu_de_residence }}</td>
                        <td>{{ $client->created_at->format('d/m/Y') }}</td>
                        <td>{{ $client->contrats_count ?? 0 }}</td>
                        <td>{{ $client->factures_count ?? 0 }}</td>
                        <td>
                            <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Aucun client trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($clients->hasPages())
        <div class="card-footer">
            {{ $clients->links() }}
        </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .card-tools {
            margin-top: -5px;
        }
        .pagination {
            margin-bottom: 0;
        }
    </style>
@stop 