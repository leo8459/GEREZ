<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rezago;

class RezagoSearchController extends Controller
{
    /**
     * GET /buscar-rezagos?q=EN000001LP
     * Busca SOLO por 'codigo' con LIKE (parcial o completo).
     */
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if ($q === '') {
            return response()->json(['data' => []]);
        }

        $rezagos = Rezago::query()
            ->where('codigo', 'like', "%{$q}%")
            ->orderByDesc('created_at')
            ->limit(15)
            ->get([
                'id',
                'codigo',
                'destinatario',
                'estado',
                'ciudad',
                'peso',
                'telefono',
                'tipo',
                'aduana',
                'observacion',
                'created_at',
            ]);

        return response()->json(['data' => $rezagos]);
    }
}
