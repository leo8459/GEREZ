<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rezagos</h1>
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

                        <div class="float-left d-flex align-items-center">
                            <input type="text" wire:model.defer="searchTerm" placeholder="Buscar por código..."
                                class="form-control" style="margin-right: 10px;" wire:keydown.enter="selectBySearch">

                            <button type="button" class="btn btn-primary" wire:click="selectBySearch">
                                Buscar
                            </button>
                        </div>




                        <div class="ml-auto d-flex align-items-center">
                            <input type="file" wire:model="file" accept=".xlsx,.xls,.csv"
                                class="form-control-file mr-2">
                            <button type="button" class="btn btn-success" wire:click="importExcel"
                                @disabled(!$file)>
                                Importar Excel
                            </button>
                        </div>
                        <div class="ml-2">
                            <button type="button" class="btn btn-warning" wire:click="sendToRezago"
                                @disabled(!$selectedAdmisiones)>
                                Mandar a Rezago
                            </button>

                        </div>

                        @error('file')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror

                        <div wire:loading wire:target="file" class="mt-2">
                            Cargando archivo...
                        </div>
                        <div wire:loading wire:target="importExcel" class="mt-2">
                            Importando, por favor espera...
                        </div>

                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" wire:model="selectAll" /></th>
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
                                        @hasrole('ADMINISTRADOR')
                                            <th>Acciones</th>
                                        @endhasrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($admisiones as $r)
                                        <tr>
                                            <td>
                                                <input type="checkbox" wire:model="selectedAdmisiones"
                                                    value="{{ $r->id }}" />
                                            </td>
                                            <td>{{ ($admisiones->currentPage() - 1) * $admisiones->perPage() + $loop->iteration }}
                                            </td>
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
                                            @hasrole('ADMINISTRADOR')
                                                <td>
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        disabled>—</button>
                                                </td>
                                            @endhasrole
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
