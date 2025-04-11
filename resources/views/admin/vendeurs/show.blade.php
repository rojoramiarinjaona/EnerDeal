@extends('adminlte::page')

@section('title', 'Détails du vendeur')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Détails du vendeur</h1>
        <div>
            <a href="{{ route('admin.vendeurs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
            @if(!$user->hasRole('vendeur'))
                <form action="{{ route('admin.vendeurs.approve', $user) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approuver comme vendeur
                    </button>
                </form>
            @else
                <form action="{{ route('admin.vendeurs.reject', $user) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Retirer le statut de vendeur
                    </button>
                </form>
            @endif
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations personnelles</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Photo de profil" class="img-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('vendor/adminlte/dist/img/default-150x150.png') }}" alt="Photo de profil par défaut" class="img-circle" style="width: 150px; height: 150px;">
                        @endif
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Nom</th>
                            <td>{{ $user->nom }}</td>
                        </tr>
                        <tr>
                            <th>Prénom</th>
                            <td>{{ $user->prenom }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Lieu de résidence</th>
                            <td>{{ $user->lieu_de_residence }}</td>
                        </tr>
                        <tr>
                            <th>Date d'inscription</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($user->hasRole('vendeur'))
                                    <span class="badge badge-success">Vendeur approuvé</span>
                                @else
                                    <span class="badge badge-warning">En attente d'approbation</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sources d'énergie</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th>Stock (kWh)</th>
                                <th>Localisation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($energies as $energie)
                                <tr>
                                    <td>{{ $energie->id }}</td>
                                    <td>{{ $energie->titre }}</td>
                                    <td>{{ $energie->categorie->nom ?? 'Non catégorisé' }}</td>
                                    <td>{{ $energie->stock_kwh }}</td>
                                    <td>{{ $energie->localisation }}</td>
                                    <td>
                                        <a href="{{ route('admin.energies.show', $energie) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucune source d'énergie trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Formules proposées</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Référence</th>
                                <th>Intitulé</th>
                                <th>Quantité (kWh)</th>
                                <th>Prix/kWh</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($formules as $formule)
                                <tr>
                                    <td>{{ $formule->id }}</td>
                                    <td>{{ $formule->ref }}</td>
                                    <td>{{ $formule->intitule }}</td>
                                    <td>{{ $formule->quantite_kwh }}</td>
                                    <td>{{ $formule->prix_kwh }} €</td>
                                    <td>
                                        <a href="{{ route('admin.formules.show', $formule) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucune formule trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contrats associés</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Formule</th>
                                <th>Date de début</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contrats as $contrat)
                                <tr>
                                    <td>{{ $contrat->id }}</td>
                                    <td>{{ $contrat->client->prenom }} {{ $contrat->client->nom }}</td>
                                    <td>{{ $contrat->formule->intitule }}</td>
                                    <td>{{ $contrat->date_debut->format('d/m/Y') }}</td>
                                    <td>
                                        @if($contrat->statut == 'actif')
                                            <span class="badge badge-success">Actif</span>
                                        @elseif($contrat->statut == 'termine')
                                            <span class="badge badge-danger">Terminé</span>
                                        @else
                                            <span class="badge badge-warning">{{ $contrat->statut }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.contrats.show', $contrat) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucun contrat trouvé</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Page de détails du vendeur chargée!');
    </script>
@stop 