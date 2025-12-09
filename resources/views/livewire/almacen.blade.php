<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rezagos ENTREGADOS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Rezagos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">

                        {{-- BUSCADOR --}}
                        <div class="card-header">
                            <div class="d-flex align-items-center w-100">
                                <div class="d-flex align-items-center" style="max-width: 400px;">
                                    <input type="text" wire:model.defer="searchTerm"
                                        placeholder="Buscar por código, destinatario o teléfono..." class="form-control"
                                        style="margin-right: 10px;" wire:keydown.enter="selectBySearch">
                                    <button type="button" class="btn btn-primary" wire:click="selectBySearch">
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <button class="btn btn-warning" wire:click="devolverVentanilla">
                                Devolver a ventanilla
                                @if (count($selectedRezagos) > 0)
                                    ({{ count($selectedRezagos) }})
                                @endif
                            </button>
                        </div>

                        {{-- MENSAJES --}}
                        @if (session()->has('message'))
                            <div class="alert alert-success mb-0">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger mb-0">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- TABLA --}}
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            @php $pageIds = $rezagos->pluck('id')->toArray(); @endphp
                                            <input type="checkbox" @checked(count(array_intersect($selectedRezagos, $pageIds)) === count($pageIds) && count($pageIds) > 0)
                                                wire:click="toggleSelectPage({{ json_encode($pageIds) }}, $event.target.checked)">
                                        </th>

                                        <th>#</th>
                                        <th>Código</th>
                                        <th>Destinatario</th>
                                        <th>Teléfono</th>
                                        <th>Peso</th>
                                        <th>Aduana</th>
                                        <th>Zona</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Ciudad</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rezagos as $r)
                                        <tr>
                                            <td>
                                                <input type="checkbox" wire:model="selectedRezagos"
                                                    value="{{ $r->id }}" />
                                            </td>

                                            <td>{{ ($rezagos->currentPage() - 1) * $rezagos->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $r->codigo }}</td>
                                            <td>{{ $r->destinatario }}</td>
                                            <td>{{ $r->telefono }}</td>
                                            <td>{{ $r->peso }}</td>
                                            <td>{{ $r->aduana }}</td>
                                            <td>{{ $r->zona }}</td>
                                            <td>{{ $r->tipo }}</td>
                                            <td>
                                                <span class="badge badge-success">
                                                    {{ $r->estado }}
                                                </span>
                                            </td>
                                            <td>{{ $r->ciudad }}</td>
                                            <td>{{ $r->created_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">Sin resultados</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINACIÓN --}}
                        <div class="card-footer">
                            {{ $rezagos->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
