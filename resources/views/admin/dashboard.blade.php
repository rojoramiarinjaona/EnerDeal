@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard Admin : {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h1>
@stop


@section('css')
    <style>
        .small-box h3 {
            font-size: 2.2rem;
        }
    </style>
@stop

