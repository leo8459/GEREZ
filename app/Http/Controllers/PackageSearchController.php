<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PackageSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if ($q === '') {
            return response()->json(['data' => []]);
        }

        // Ajusta el nombre de la tabla/columnas a tu esquema real
        // Ejemplo basado en tus memorias: tabla "paquetes" con "codigo" y "destinatario"
        $results = DB::table('paquetes')
            ->select('id', 'codigo', 'destinatario', 'estado', 'cuidad as ciudad', 'peso', 'created_at')
            ->where(function ($w) use ($q) {
                $w->where('codigo', 'like', "%{$q}%")
                  ->orWhere('destinatario', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return response()->json(['data' => $results]);
    }
}
