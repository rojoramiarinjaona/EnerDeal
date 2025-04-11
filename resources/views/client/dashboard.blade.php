@extends('adminlte::page')

@section('title', 'Dashboard Client')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Dashboard Client : {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h1>
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="fas fa-home mr-1"></i> Accueil
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info elevation-3 animate__animated animate__fadeIn">
                <div class="inner">
                    <h3>{{ $contratCount }}</h3>
                    <p>Mes contrats</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <a href="{{ route('client.contrats.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success elevation-3 animate__animated animate__fadeIn animate__delay-1s">
                <div class="inner">
                    <h3>{{ $factureCount }}</h3>
                    <p>Mes factures</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('client.factures.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning elevation-3 animate__animated animate__fadeIn animate__delay-2s">
                <div class="inner">
                    <h3>{{ number_format($facturesImpayees, 0) }}</h3>
                    <p>Factures impayées</p>
                </div>
                <div class="icon">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <a href="{{ route('client.factures.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger elevation-3 animate__animated animate__fadeIn animate__delay-3s">
                <div class="inner">
                    <h3>{{ $incidentCount }}</h3>
                    <p>Incidents déclarés</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <a href="{{ route('client.incidents.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card elevation-2 animate__animated animate__fadeInLeft">
                <div class="card-header">
                    <h3 class="card-title">Mes derniers contrats</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Formule</th>
                                <th>Date début</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contrats as $contrat)
                                <tr>
                                    <td>{{ $contrat->formule->intitule }}</td>
                                    <td>{{ $contrat->date_debut->format('d/m/Y') }}</td>
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
                                        <a href="{{ route('client.contrats.show', $contrat) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
                    <a href="{{ route('client.contrats.index') }}" class="btn btn-sm btn-info float-right">
                        <i class="fas fa-list mr-1"></i> Voir tous mes contrats
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card elevation-2 animate__animated animate__fadeInRight">
                <div class="card-header">
                    <h3 class="card-title">Mes dernières factures</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Contrat</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($factures as $facture)
                                <tr>
                                    <td>{{ $facture->created_at->format('d/m/Y') }}</td>
                                    <td>{{ number_format($facture->montant, 2) }} €</td>
                                    <td>{{ $facture->contrat->formule->intitule }}</td>
                                    <td>
                                        @if($facture->statut_paiement == 'payé' || $facture->statut_paiement == 'payée')
                                            <span class="badge badge-success">Payée</span>
                                        @elseif($facture->statut_paiement == 'en_attente' || $facture->statut_paiement == 'en attente')
                                            <span class="badge badge-warning">En attente</span>
                                        @else
                                            <span class="badge badge-danger">Annulée</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('client.factures.show', $facture) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune facture trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('client.factures.index') }}" class="btn btn-sm btn-info float-right">
                        <i class="fas fa-file-invoice mr-1"></i> Voir toutes mes factures
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card elevation-2 animate__animated animate__fadeInUp">
                <div class="card-header">
                    <h3 class="card-title">Actions rapides</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <a href="{{ route('client.formules.index') }}" class="btn btn-lg btn-primary btn-block mb-3 animate__animated animate__pulse animate__delay-4s">
                                <i class="fas fa-shopping-cart mr-2"></i> Parcourir les formules
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <a href="{{ route('client.panier') }}" class="btn btn-lg btn-success btn-block mb-3 animate__animated animate__pulse animate__delay-4s">
                                <i class="fas fa-shopping-basket mr-2"></i> Voir mon panier
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <a href="{{ route('client.incidents.create') }}" class="btn btn-lg btn-warning btn-block mb-3 animate__animated animate__pulse animate__delay-4s">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Signaler un incident
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .small-box h3 {
            font-size: 2.2rem;
        }
        .small-box:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.05);
            transition: all 0.2s ease;
        }
        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Animation des compteurs
            $('.small-box h3').each(function() {
                var $this = $(this);
                var num = parseInt($this.text());
                var countTo = 0;
                
                var countStep = Math.ceil(num / 30);
                
                var interval = setInterval(function() {
                    countTo += countStep;
                    if (countTo >= num) {
                        countTo = num;
                        clearInterval(interval);
                    }
                    $this.text(countTo);
                }, 50);
            });
        });
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