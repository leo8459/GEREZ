<?php

namespace App\Livewire;

use App\Models\Rezago;
use Livewire\Component;
use Livewire\WithPagination;

class Transitorezagos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $searchTerm = '';
    public $selectedRezagos = [];

    public function updatingSearchTerm(): void
    {
        $this->resetPage();
    }

    public function selectBySearch(): void
    {
        $code = trim((string) $this->searchTerm);
        if ($code === '') {
            return;
        }

        $row = Rezago::where('codigo', $code)
            ->where('estado', 'TRANSITO')
            ->first();

        if (!$row) {
            session()->flash('error', "No se encontró en TRANSITO el código: {$code}");
            return;
        }

        if (!in_array($row->id, $this->selectedRezagos, true)) {
            $this->selectedRezagos[] = $row->id;
        }

        $this->searchTerm = '';
        session()->flash('message', "Código {$row->codigo} seleccionado.");
        $this->dispatch('$refresh');
    }

    public function toggleSelectPage(array $items, bool $checked): void
    {
        if ($checked) {
            $this->selectedRezagos = array_values(array_unique(array_merge($this->selectedRezagos, $items)));
            return;
        }

        $this->selectedRezagos = array_values(array_diff($this->selectedRezagos, $items));
    }

    public function receiveSelected(): void
    {
        if (empty($this->selectedRezagos)) {
            session()->flash('error', 'Debe seleccionar al menos un rezago.');
            return;
        }

        $updated = Rezago::whereIn('id', $this->selectedRezagos)
            ->where('estado', 'TRANSITO')
            ->update(['estado' => 'REZAGO']);

        $this->selectedRezagos = [];

        session()->flash('message', "Se recibieron {$updated} registro(s) y fueron enviados a REZAGO.");
    }

    public function render()
    {
        $rezagos = Rezago::query()
            ->where('estado', 'TRANSITO')
            ->when(trim((string) $this->searchTerm) !== '', function ($q) {
                $t = trim((string) $this->searchTerm);
                $q->where(function ($qq) use ($t) {
                    $qq->where('codigo', 'like', "%{$t}%")
                        ->orWhere('destinatario', 'like', "%{$t}%")
                        ->orWhere('telefono', 'like', "%{$t}%")
                        ->orWhere('ciudad', 'like', "%{$t}%");
                });
            })
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('livewire.transitorezagos', [
            'rezagos' => $rezagos,
        ]);
    }
}
