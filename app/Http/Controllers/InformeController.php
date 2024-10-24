<?php

namespace App\Http\Controllers;

use App\Models\Central;
use App\Models\Registro;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InformeController extends Controller
{
    public function generarInforme(Request $request)
    {
        $tipo = $request->input('tipo', 'registro');
        $fechaInicio = Carbon::parse($request->input('fecha_inicio'));
        $fechaFin = Carbon::parse($request->input('fecha_fin'));
        
        // Obtener registros según el tipo
        $query = $tipo === 'central' ? Central::query() : Registro::query();
        
        // Obtener registros válidos (con employeeID y personName)
        $registros = $query->whereBetween('authDate', [$fechaInicio->format('Y-m-d'), $fechaFin->format('Y-m-d')])
            ->whereNotNull('employeeID')
            ->whereNotNull('personName')
            ->orderBy('authDateTime')
            ->get()
            ->groupBy(['employeeID', function($item) {
                return Carbon::parse($item->authDate)->format('Y-m-d');
            }]);

        // Procesar los registros
        $procesados = [];
        foreach ($registros as $employeeID => $diasRegistro) {
            foreach ($diasRegistro as $fecha => $registrosDia) {
                $registrosDiarios = collect($registrosDia)->sortBy('authTime');
                
                $procesados[$employeeID][$fecha] = [
                    'personName' => $registrosDia->first()->personName,
                    'fecha' => Carbon::parse($fecha)->format('d/m/Y'),
                    'registros' => $registrosDiarios->map(function($registro) {
                        return [
                            'hora' => Carbon::parse($registro->authTime)->format('H:i:s'),
                            'dispositivo' => $registro->deviceName,
                            'datetime' => $registro->authDateTime
                        ];
                    })->values()->toArray()
                ];
            }
        }

        return view('reportes.mensual', [
            'registros' => $procesados,
            'tipo' => $tipo,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'ubicacion' => $tipo === 'central' ? 'Centro de Distribución' : 'Ferretería'
        ]);
    }
}