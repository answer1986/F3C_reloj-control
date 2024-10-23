<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Mostrar la página de reportes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('reportes.index');
    }
}
