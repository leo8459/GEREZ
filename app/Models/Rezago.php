<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rezago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rezagos';

    protected $fillable = [
        'codigo',
        'destinatario',
        'telefono',
        'peso',
        'aduana',
        'zona',
        'tipo',
        'estado',
        'ciudad',
        'observacion',
    ];

    protected $casts = [
        'peso' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /* Scopes útiles */
    public function scopeEstado($query, $estado)
    {
        return $query->when($estado, fn($q) => $q->where('estado', $estado));
    }

    public function scopeCiudad($query, $ciudad)
    {
        return $query->when($ciudad, fn($q) => $q->where('ciudad', $ciudad));
    }

    public function scopeBuscar($query, $term)
    {
        return $query->when($term, function ($q) use ($term) {
            $q->where('codigo', 'like', "%{$term}%")
                ->orWhere('destinatario', 'like', "%{$term}%")
                ->orWhere('telefono', 'like', "%{$term}%");
        });
    }
}
