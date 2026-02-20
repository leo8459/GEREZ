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

                    {{-- ALERTAS Bootstrap para mensajes/errores --}}
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card">

                        <div class="card-header d-flex align-items-center justify-content-between">
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

                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-outline-primary mr-2" wire:click="downloadTemplate">
                                    Descargar formato
                                </button>
                                <input type="file" wire:model="file" accept=".xlsx,.xls,.csv"
                                       class="form-control-file mr-2">
                                <button type="button" class="btn btn-success" wire:click="importExcel">
                                    Importar Excel
                                </button>
                                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#modalManualRezago" wire:click="resetManualForm">
                                    Añadir manualmente
                                </button>

                                {{-- Botón SIEMPRE activo --}}
                                <button type="button" class="btn btn-warning ml-2" wire:click="sendToRezago">
                                    Mandar a Rezago
                                </button>
                                <button type="button" class="btn btn-primary ml-2" wire:click="sendToTransito">
                                    Mandar a Tránsito
                                </button>
                            </div>
                        </div>

                        @error('file')
                            <div class="text-danger mt-2 px-3">{{ $message }}</div>
                        @enderror

                        <div wire:loading wire:target="file" class="mt-2 px-3">
                            Cargando archivo...
                        </div>
                        <div wire:loading wire:target="importExcel" class="mt-2 px-3">
                            Importando, por favor espera...
                        </div>

                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" wire:model="selectAll" /></th>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>Origen</th>
                                        <th>Teléfono</th>
                                        <th>Peso</th>
                                        <th>Aduana</th>
                                        <th>Zona</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Destino</th>
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
                                            @hasrole('ADMINISTRADOR')
                                                <td>
                                                    <button type="button" class="btn btn-secondary btn-sm" disabled>—</button>
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

    <div wire:ignore.self class="modal fade" id="modalManualRezago" tabindex="-1" role="dialog" aria-labelledby="modalManualRezagoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalManualRezagoLabel">Añadir rezago manualmente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" wire:click="resetManualForm">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="manualCodigo">Código *</label>
                            <input id="manualCodigo" type="text" class="form-control" wire:model.defer="manualCodigo">
                            @error('manualCodigo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="manualDestinatario">Origen</label>
                            <select id="manualDestinatario" class="form-control" wire:model.defer="manualDestinatario">
                                <option value="">Seleccione una opción</option>
                                <option value="LA PAZ">LA PAZ</option>
                                <option value="SANTA CRUZ">SANTA CRUZ</option>
                                <option value="PANDO">PANDO</option>
                                <option value="BENI">BENI</option>
                                <option value="TARIJA">TARIJA</option>
                                <option value="SUCRE">SUCRE</option>
                                <option value="ORURO">ORURO</option>
                                <option value="COCHABAMBA">COCHABAMBA</option>
                                <option value="POTOSI">POTOSI</option>
                            </select>
                            @error('manualDestinatario') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="manualTelefono">Teléfono</label>
                            <input id="manualTelefono" type="text" class="form-control" wire:model.defer="manualTelefono">
                            @error('manualTelefono') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="manualPeso">Peso</label>
                            <input id="manualPeso" type="number" step="0.001" min="0" class="form-control" wire:model.defer="manualPeso">
                            @error('manualPeso') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="manualAduana">Aduana</label>
                            <input id="manualAduana" type="text" class="form-control" wire:model.defer="manualAduana">
                            @error('manualAduana') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="manualZona">Zona</label>
                            <input id="manualZona" type="text" class="form-control" wire:model.defer="manualZona">
                            @error('manualZona') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="manualTipo">Tipo</label>
                            <input id="manualTipo" type="text" class="form-control" wire:model.defer="manualTipo">
                            @error('manualTipo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="manualCiudad">Destino</label>
                            <select id="manualCiudad" class="form-control" wire:model.defer="manualCiudad">
                                <option value="">Seleccione una opción</option>
                                <option value="LA PAZ">LA PAZ</option>
                                <option value="SANTA CRUZ">SANTA CRUZ</option>
                                <option value="PANDO">PANDO</option>
                                <option value="BENI">BENI</option>
                                <option value="TARIJA">TARIJA</option>
                                <option value="SUCRE">SUCRE</option>
                                <option value="ORURO">ORURO</option>
                                <option value="COCHABAMBA">COCHABAMBA</option>
                                <option value="POTOSI">POTOSI</option>
                            </select>
                            @error('manualCiudad') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="manualObservacion">Observación</label>
                            <input id="manualObservacion" type="text" class="form-control" wire:model.defer="manualObservacion">
                            @error('manualObservacion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetManualForm">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="saveManual">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('manual-rezago-created', function () {
            $('#modalManualRezago').modal('hide');
        });
    </script>
</div>
