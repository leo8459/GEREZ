<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rezagos (REZAGO)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Registros</li>
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
                            <div class="d-flex align-items-center w-100">
                                <div class="d-flex align-items-center">
                                    <input type="text"
                                           wire:model.defer="searchTerm"
                                           placeholder="Buscar por código..."
                                           class="form-control"
                                           style="margin-right: 10px;"
                                           wire:keydown.enter="selectBySearch">
                                    <button type="button" class="btn btn-primary" wire:click="selectBySearch">
                                        Buscar
                                    </button>
                                </div>

                                {{-- Botón SIEMPRE visible --}}
                                <div class="ml-auto">
                                    <button type="button" class="btn btn-success" wire:click="deliverSelected">
                                        Entregar
                                        @if (count($selectedAdmisiones) > 0)
                                            ({{ count($selectedAdmisiones) }})
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

                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        @php $pageIds = $admisiones->pluck('id')->toArray(); @endphp
                                        <input type="checkbox"
                                               @checked(count(array_intersect($selectedAdmisiones, $pageIds)) === count($pageIds) && count($pageIds) > 0)
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
                                @forelse ($admisiones as $r)
                                    <tr>
                                        <td>
                                            <input type="checkbox" wire:model="selectedAdmisiones" value="{{ $r->id }}" />
                                        </td>
                                        <td>{{ ($admisiones->currentPage() - 1) * $admisiones->perPage() + $loop->iteration }}</td>
                                        <td>{{ $r->codigo }}</td>
                                        <td>{{ $r->destinatario }}</td>
                                        <td>{{ $r->telefono }}</td>
                                        <td>{{ $r->peso }}</td>
                                        <td>{{ $r->aduana }}</td>
                                        <td>{{ $r->zona }}</td>
                                        <td>{{ $r->tipo }}</td>
                                        <td>{{ $r->estado }}</td>
                                        <td>{{ $r->ciudad }}</td>
                                        <td>{{ $r->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">Sin resultados</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            {{ $admisiones->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Descarga automática del PDF generado --}}
<script>
    window.addEventListener('trigger-download', event => {
        const url = event.detail.url;
        if (url) { window.location = url; } // fuerza descarga via ruta dedicada
    });
</script>
