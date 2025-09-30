<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Rezago;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RezagosImport;

class Rezagos extends Component
{
    use WithPagination, WithFileUploads;

    public $searchTerm = '';
    public $department = '';
    public $startDate;
    public $endDate;
    public $selectAll = false;

    /** IDs seleccionados en la tabla */
    public $selectedAdmisiones = [];

    /** Upload Excel */
    public $file;

    protected $rules = [
        'file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
    ];

    // --- Hooks para resetear página al cambiar filtros ---
    public function updatingSearchTerm()
    { /* no reset aquí para permitir Enter */
    }
    public function updatingDepartment()
    {
        $this->resetPage();
    }
    public function updatingStartDate()
    {
        $this->resetPage();
    }
    public function updatingEndDate()
    {
        $this->resetPage();
    }

    /** Buscar por código al presionar Enter o al pulsar el botón Buscar */
    public function selectBySearch(): void
    {
        $code = trim((string)$this->searchTerm);
        if ($code === '') return;

        $row = Rezago::where('codigo', $code)->first();

        if (!$row) {
            session()->flash('error', "No se encontró el código: {$code}");
            return;
        }

        // Asegurar que esté en PRE REZAGO (admite con o sin guion bajo)
        $isPre = in_array($row->estado, ['PRE REZAGO', 'PRE_REZAGO'], true);
        if (!$isPre) {
            session()->flash('error', "El código {$row->codigo} no está en PRE REZAGO.");
            return;
        }

        if (!in_array($row->id, $this->selectedAdmisiones, true)) {
            $this->selectedAdmisiones[] = $row->id;
        }

        $this->searchTerm = '';
        session()->flash('message', "Código {$row->codigo} seleccionado (PRE REZAGO).");
        $this->dispatch('$refresh');
    }
    /** Botón: Mandar a Rezago (cambia estado = REZAGO de los seleccionados) */
    public function sendToRezago(): void
    {
        if (empty($this->selectedAdmisiones)) {
            session()->flash('error', 'No hay registros seleccionados.');
            return;
        }

        Rezago::whereIn('id', $this->selectedAdmisiones)->update(['estado' => 'REZAGO']);

        $count = count($this->selectedAdmisiones);
        $this->selectedAdmisiones = [];
        $this->selectAll = false;

        session()->flash('message', "Se cambiaron {$count} registro(s) a estado REZAGO.");
        $this->dispatch('$refresh');
    }

    /** Importar Excel (opcional, queda aquí por si ya lo usas) */
    public function importExcel(): void
    {
        $this->validate();
        if ($this->file) {
            Excel::import(new \App\Imports\RezagosImport, $this->file->getRealPath());
            $this->reset('file');
            session()->flash('message', 'Importación completada correctamente.');
            $this->resetPage();
        }
    }

    public function render()
    {
        $rezagos = Rezago::query()
            // 👇 Solo PRE REZAGO
            ->whereIn('estado', ['PRE REZAGO', 'PRE_REZAGO'])
            ->when($this->searchTerm === '' ? null : $this->searchTerm, function ($q) {
                $t = trim($this->searchTerm);
                $q->where(function ($qq) use ($t) {
                    $qq->where('codigo', 'like', "%{$t}%")
                        ->orWhere('destinatario', 'like', "%{$t}%")
                        ->orWhere('telefono', 'like', "%{$t}%");
                });
            })
            ->when($this->department, fn($q) => $q->where('ciudad', $this->department))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate,   fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.rezagos', [
            'admisiones' => $rezagos,
        ]);
    }
}
