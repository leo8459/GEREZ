<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Rezago;

class Almacen extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $searchTerm = '';
    public $selectedRezagos = [];

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function selectBySearch()
    {
        $this->resetPage();
    }

    public function devolverVentanilla()
    {
        if (count($this->selectedRezagos) == 0) {
            session()->flash('error', 'Debe seleccionar al menos un rezago.');
            return;
        }

        Rezago::whereIn('id', $this->selectedRezagos)
            ->update(['estado' => 'REZAGO']);

        $this->selectedRezagos = [];

        session()->flash('message', 'Los rezagos fueron devueltos a ventanilla correctamente.');
    }

    public function toggleSelectPage($items, $checked)
    {
        if($checked){
            $this->selectedRezagos = array_unique(array_merge($this->selectedRezagos, $items));
        } else {
            // Quitarlos
            $this->selectedRezagos = array_diff($this->selectedRezagos, $items);
        }
    }

    public function render()
    {
        $rezagos = Rezago::query()
            ->estado('ENTREGADO')
            ->buscar($this->searchTerm)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.almacen', [
            'rezagos' => $rezagos,
        ]);
    }
}
