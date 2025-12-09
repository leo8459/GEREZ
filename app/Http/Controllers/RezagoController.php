<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RezagoController extends Controller
{
      public function getrezagos ()
    {
        return view('rezagos.rezago');
    }
    public function getventanillarezagos ()
    {
        return view('rezagos.ventanillarezagos');
    }
    public function getalmacenrezagos ()
    {
        return view('rezagos.almacenrezagos');
    }
}
