@extends('adminlte::page')
@section('title', 'Usuarios')
@section('template_title')
Rezagos
@endsection


@section('content')
@livewire('almacen')
@include('footer')
@endsection

{{-- @section('content')
@hasrole ('ADMINISTRADOR')
@livewire('admisionesgeneradasadmin')
@endhasrole
@hasrole ('ADMISION')
@livewire('admisionesgeneradas')
@endhasrole
@include('footer')
@endsection --}}
