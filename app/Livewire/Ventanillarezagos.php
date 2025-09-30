<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Rezago;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // Usa la fachada

class Ventanillarezagos extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $department = '';
    public $startDate;
    public $endDate;
    public $selectAll = false;
    public $selectedAdmisiones = [];

    public function updatingDepartment(){ $this->resetPage(); }
    public function updatingStartDate(){ $this->resetPage(); }
    public function updatingEndDate(){ $this->resetPage(); }

    /** Enter o botón Buscar: seleccionar por código (solo REZAGO) */
    public function selectBySearch(): void
    {
        $code = trim((string)$this->searchTerm);
        if ($code === '') return;

        $row = Rezago::where('codigo', $code)
            ->where('estado', 'REZAGO')
            ->first();

        if (!$row) {
            session()->flash('error', "No se encontró en REZAGO el código: {$code}");
            return;
        }

        if (!in_array($row->id, $this->selectedAdmisiones, true)) {
            $this->selectedAdmisiones[] = $row->id;
        }

        $this->searchTerm = '';
        session()->flash('message', "Código {$row->codigo} seleccionado.");
        $this->dispatch('$refresh');
    }

    /** Seleccionar/deseleccionar todos los visibles de la página actual */
    public function toggleSelectPage(array $ids, bool $checked): void
    {
        if ($checked) {
            $this->selectedAdmisiones = array_values(array_unique(array_merge($this->selectedAdmisiones, $ids)));
        } else {
            $this->selectedAdmisiones = array_values(array_diff($this->selectedAdmisiones, $ids));
        }
    }

    /** Entregar seleccionados: cambia estado y descarga PDF */
    public function deliverSelected(): void
    {
        $items = Rezago::whereIn('id', $this->selectedAdmisiones)
            ->where('estado', 'REZAGO')
            ->orderBy('codigo')
            ->get();

        if ($items->isEmpty()) {
            session()->flash('error', 'No hay registros seleccionados en estado REZAGO.');
            return;
        }

        // Cambiar estado
        Rezago::whereIn('id', $items->pluck('id'))->update(['estado' => 'ENTREGADO']);

        // Verificar DomPDF
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            session()->flash('error', 'DomPDF no está disponible. Ejecuta: composer require barryvdh/laravel-dompdf');
            return;
        }

        // Generar PDF
        $viewData = [
            'items'   => $items,
            'fecha'   => now()->format('Y-m-d H:i:s'),
            'usuario' => auth()->user()->name ?? 'Sistema',
        ];

        $pdf = Pdf::loadView('pdf.rezagos_entregados', $viewData)->setPaper('a4', 'portrait');
        $output = $pdf->output();

        // Guardar en storage/app/public/reportes
        $now = now()->format('Y-m-d_H-i-s');
        $filename = "rezagos-entregados-{$now}.pdf";
        $path = "reportes/{$filename}";
        Storage::disk('public')->put($path, $output);

        // Limpieza y feedback
        $count = $items->count();
        $this->selectedAdmisiones = [];
        $this->selectAll = false;
        session()->flash('message', "Se entregaron {$count} rezago(s).");

        // Forzar descarga vía ruta dedicada
        $downloadUrl = route('reportes.download', ['filename' => $filename]);
        $this->dispatch('trigger-download', url: $downloadUrl);

        $this->resetPage();
    }

    public function render()
    {
        $rezagos = Rezago::query()
            ->where('estado', 'REZAGO')
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

        return view('livewire.ventanillarezagos', [
            'admisiones' => $rezagos,
        ]);
    }
}
