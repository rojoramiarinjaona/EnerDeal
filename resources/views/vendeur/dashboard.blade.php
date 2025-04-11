@extends('adminlte::page')

@section('title', 'Dashboard Vendeur')

@section('content_header')
    <h1>Dashboard Vendeur : {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $formuleCount }}</h3>
                    <p>Formules</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
                <a href="{{ route('vendeur.formules.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $energieCount }}</h3>
                    <p>Sources d'énergie</p>
                </div>
                <div class="icon">
                    <i class="fas fa-solar-panel"></i>
                </div>
                <a href="{{ route('vendeur.energies.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $contratCount }}</h3>
                    <p>Contrats actifs</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <a href="{{ route('vendeur.contrats.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $incidentCount }}</h3>
                    <p>Incidents ouverts</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('vendeur.incidents.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Derniers contrats</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
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
                                    <td>{{ $contrat->client->prenom }} {{ $contrat->client->nom }}</td>
                                    <td>{{ $contrat->formule->intitule }}</td>
                                    <td>{{ is_object($contrat->date_debut) && method_exists($contrat->date_debut, 'format') ? $contrat->date_debut->format('d/m/Y') : $contrat->date_debut }}</td>
                                    <td>
                                        @if($contrat->statut == 'actif')
                                            <span class="badge badge-success">Actif</span>
                                        @elseif($contrat->statut == 'suspendu')
                                            <span class="badge badge-warning">Suspendu</span>
                                        @else
                                            <span class="badge badge-danger">Terminé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('vendeur.contrats.show', $contrat->id) }}" class="btn btn-sm btn-info">Voir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun contrat trouvé</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('vendeur.contrats.index') }}" class="btn btn-sm btn-info float-right">Voir tous les contrats</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Incidents récents</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Client</th>
                                <th>Niveau</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incidents as $incident)
                                <tr>
                                    <td>{{ $incident->titre }}</td>
                                    <td>{{ $incident->client->prenom }} {{ $incident->client->nom }}</td>
                                    <td>
                                        @for($i = 0; $i < $incident->niveau; $i++)
                                            <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for($i = $incident->niveau; $i < 5; $i++)
                                            <i class="far fa-star text-warning"></i>
                                        @endfor
                                    </td>
                                    <td>
                                        @if($incident->statut == 'ouvert')
                                            <span class="badge badge-danger">Ouvert</span>
                                        @elseif($incident->statut == 'en_traitement')
                                            <span class="badge badge-warning">En traitement</span>
                                        @elseif($incident->statut == 'résolu')
                                            <span class="badge badge-success">Résolu</span>
                                        @else
                                            <span class="badge badge-secondary">Fermé</span>
                                        @endif
                                    </td>
                                    <td>{{ $incident->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('vendeur.incidents.show', $incident->id) }}" class="btn btn-sm btn-info">Voir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun incident trouvé</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('vendeur.incidents.index') }}" class="btn btn-sm btn-info float-right">Voir tous les incidents</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions rapides</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <a href="{{ route('vendeur.formules.create') }}" class="btn btn-lg btn-primary btn-block mb-3">
                                <i class="fas fa-plus-circle mr-2"></i> Nouvelle formule
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <a href="{{ route('vendeur.energies.create') }}" class="btn btn-lg btn-success btn-block mb-3">
                                <i class="fas fa-solar-panel mr-2"></i> Nouvelle énergie
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <a href="{{ route('vendeur.incidents.index') }}" class="btn btn-lg btn-warning btn-block mb-3">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Gérer les incidents
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box h3 {
            font-size: 2.2rem;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Dashboard vendeur chargé!');
    </script>
@stop

<!-- Modal de déconnexion -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirmation de déconnexion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir vous déconnecter ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Se déconnecter</button>
                </form>
            </div>
        </div>
    </div>
</div> 