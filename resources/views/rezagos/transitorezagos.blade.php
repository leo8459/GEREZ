@extends('adminlte::page')
@section('title', 'Usuarios')
@section('template_title')
Rezagos
@endsection

@section('content')
@livewire('transitorezagos')
@include('footer')
@endsection
