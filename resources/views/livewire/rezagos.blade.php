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

                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="float-left d-flex align-items-center">
                                    <input type="text" wire:model="searchTerm" placeholder="Buscar..."
                                        class="form-control" style="margin-right: 10px;" wire:keydown.enter="$refresh">
                                    <button type="button" class="btn btn-primary" wire:click="$refresh">Buscar</button>
                                </div>
                            </div>

                            <div class="form-inline mt-2">
                                @hasrole('ADMINISTRADOR')
                                    <label for="department">Departamento:</label>
                                @endhasrole
                                <select id="department" wire:model="department" class="form-control mx-2">
                                    <option value="">Todos</option>
                                    <option value="LA PAZ">LA PAZ</option>
                                    <option value="COCHABAMBA">COCHABAMBA</option>
                                    <option value="SANTA CRUZ">SANTA CRUZ</option>
                                    <option value="ORURO">ORURO</option>
                                    <option value="POTOSI">POTOSI</option>
                                    <option value="CHUQUISACA">CHUQUISACA</option>
                                    <option value="BENI">BENI</option>
                                    <option value="PANDO">PANDO</option>
                                    <option value="TARIJA">TARIJA</option>
                                </select>

                                <label for="startDate">Desde:</label>
                                <input type="date" id="startDate" wire:model="startDate" class="form-control mx-2">

                                <label for="endDate">Hasta:</label>
                                <input type="date" id="endDate" wire:model="endDate" class="form-control mx-2">
                            </div>
                        </div>

                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="ml-auto d-flex align-items-center">
                            <input type="file" wire:model="file" accept=".xlsx,.xls,.csv"
                                class="form-control-file mr-2">
                            <button type="button" class="btn btn-success" wire:click="importExcel"
                                @disabled(!$file)>
                                Importar Excel
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
