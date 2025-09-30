<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RezagoController extends Controller
{
      public function getrezagos ()
    {
        return view('rezagos.rezago');
    }
}
