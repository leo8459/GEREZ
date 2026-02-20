<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rezagos EN TRÁNSITO</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">En Tránsito</li>
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
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center" style="max-width: 520px;">
                                    <input type="text" wire:model.defer="searchTerm"
                                           placeholder="Buscar por código, destinatario, teléfono o ciudad..."
                                           class="form-control" style="margin-right: 10px;"
                                           wire:keydown.enter="selectBySearch">
                                    <button type="button" class="btn btn-primary" wire:click="selectBySearch">
                                        Buscar
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-success" wire:click="receiveSelected">
                                        Recibir
                                        @if (count($selectedRezagos) > 0)
                                            ({{ count($selectedRezagos) }})
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>

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

                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            @php $pageIds = $rezagos->pluck('id')->toArray(); @endphp
                                            <input type="checkbox"
                                                   @checked(count(array_intersect($selectedRezagos, $pageIds)) === count($pageIds) && count($pageIds) > 0)
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
                                        <th>Ciudad destino</th>
                                        <th>Última actualización</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rezagos as $r)
                                        <tr>
                                            <td>
                                                <input type="checkbox" wire:model="selectedRezagos" value="{{ $r->id }}" />
                                            </td>
                                            <td>{{ ($rezagos->currentPage() - 1) * $rezagos->perPage() + $loop->iteration }}</td>
                                            <td>{{ $r->codigo }}</td>
                                            <td>{{ $r->destinatario }}</td>
                                            <td>{{ $r->telefono }}</td>
                                            <td>{{ $r->peso }}</td>
                                            <td>{{ $r->aduana }}</td>
                                            <td>{{ $r->zona }}</td>
                                            <td>{{ $r->tipo }}</td>
                                            <td><span class="badge badge-warning">{{ $r->estado }}</span></td>
                                            <td>{{ $r->ciudad }}</td>
                                            <td>{{ $r->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">Sin registros en tránsito</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            {{ $rezagos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
