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
    public $selectedAdmisiones = [];

    // Archivo para importar
    public $file;

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB
    ];

    public function updatingSearchTerm(){ $this->resetPage(); }
    public function updatingDepartment(){ $this->resetPage(); }
    public function updatingStartDate(){ $this->resetPage(); }
    public function updatingEndDate(){ $this->resetPage(); }

    public function importExcel()
    {
        $this->validate();

        // Importa directamente desde el archivo temporal de Livewire
        Excel::import(new RezagosImport, $this->file->getRealPath());

        $this->reset('file');
        session()->flash('message', 'Importación completada correctamente.');
        $this->resetPage(); // refresca la lista
    }

    public function render()
    {
        $rezagos = Rezago::query()
            ->when($this->searchTerm, function ($q) {
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
