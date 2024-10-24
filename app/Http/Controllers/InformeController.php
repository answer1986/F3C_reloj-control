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
       // Establecer zona horaria
       Carbon::setLocale('es');
       date_default_timezone_set('America/Santiago');

       try {
           // Establecer fechas usando formato específico
           if ($request->has('fecha_inicio')) {
               $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->input('fecha_inicio'));
           } else {
               $fechaInicio = Carbon::now()->startOfMonth();
           }

           if ($request->has('fecha_fin')) {
               $fechaFin = Carbon::createFromFormat('Y-m-d', $request->input('fecha_fin'));
           } else {
               $fechaFin = Carbon::now()->endOfMonth();
           }

           $tipo = $request->input('tipo_negocio', 'ferreteria');

           // Obtener registros según el tipo
           $query = $tipo === 'central' ? Central::query() : Registro::query();

           // Consulta base
           $registros = $query->whereBetween('authDate', [
                   $fechaInicio->format('Y-m-d'), 
                   $fechaFin->format('Y-m-d')
               ]);

           if ($request->has('employeeID')) {
               $registros->where('employeeID', $request->employeeID);
           }

           $registros = $registros->whereNotNull('employeeID')
               ->whereNotNull('personName')
               ->orderBy('authDateTime')
               ->get();

           // Preparar los días del período
           $periodo = [];
           $currentDate = $fechaInicio->copy();
           
           while ($currentDate <= $fechaFin) {
               $diaKey = $currentDate->format('d');
               $periodo[$diaKey] = [
                   'fecha' => $diaKey,
                   'dia' => mb_strtoupper($currentDate->isoFormat('ddd')),
                   'registros' => []
               ];
               $currentDate->addDay();
           }

           // Procesar registros
           foreach ($registros as $registro) {
               try {
                   $diaRegistro = Carbon::parse($registro->authDate)->format('d');
                   if (isset($periodo[$diaRegistro])) {
                       $hora = Carbon::parse($registro->authTime)->format('H:i');
                       $periodo[$diaRegistro]['registros'][] = $hora;
                   }
               } catch (\Exception $e) {
                   \Log::error('Error procesando registro: ' . $e->getMessage());
                   continue;
               }
           }

           // Preparar información del empleado
           $empleado = null;
           if ($registros->isNotEmpty()) {
               $primerRegistro = $registros->first();
               $empleado = [
                   'cedula' => $primerRegistro->employeeID ?? 'Sin ID',
                   'nombre' => $primerRegistro->personName ?? 'Sin Nombre',
                   'departamento' => $tipo === 'central' ? 'Central/Bodega' : 'Ferretería',
                   'ac_id' => $primerRegistro->deviceSN ?? 'Sin dispositivo'
               ];
           }

           return view('reportes.mensual', [
               'periodo' => $periodo,
               'empleado' => $empleado,
               'fechaInicio' => $fechaInicio,
               'fechaFin' => $fechaFin,
               'tipo' => $tipo
           ]);

       } catch (\Exception $e) {
           \Log::error('Error en generarInforme: ' . $e->getMessage());
           return back()->with('error', 'Error al generar el informe: ' . $e->getMessage());
       }
   }
}