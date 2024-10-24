<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas y Salidas Mensual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .filter-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-group {
            display: inline-block;
            margin-right: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="date"],
        input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .calendar {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .calendar th,
        .calendar td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .calendar th {
            background: #f8f9fa;
            color: #333;
        }
        .employee-info {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .employee-info table {
            width: 100%;
        }
        .employee-info td {
            padding: 5px;
        }
        .navigation {
            margin-bottom: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                background: white;
            }
            .container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="navigation no-print">
        <a href="{{ route('reportes') }}" class="btn btn-secondary">← Volver</a>

        </div>

        <div class="header">
            <h1 class="title">Entradas y Salidas Mensual</h1>
            <div>Período: {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}</div>
        </div>

        <form action="{{ route('reportes.mensual') }}" method="GET" class="filter-form no-print">
            <div class="form-group">
                <label>Tipo de Negocio:</label>
                <select name="tipo_negocio" required>
                    <option value="ferreteria" {{ ($tipo ?? '') === 'ferreteria' ? 'selected' : '' }}>Ferretería</option>
                    <option value="central" {{ ($tipo ?? '') === 'central' ? 'selected' : '' }}>Central/Bodega</option>
                </select>
            </div>

            <div class="form-group">
                <label>Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio->format('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label>Fecha Fin:</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin->format('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label>ID Empleado:</label>
                <input type="text" name="employeeID" value="{{ request('employeeID') }}" placeholder="ID Empleado">
            </div>

            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        @if(isset($empleado) && $empleado)
            <div class="employee-info">
                <table>
                    <tr>
                        <td width="20%"><strong>Cedula Nº:</strong></td>
                        <td width="30%">{{ $empleado['cedula'] }}</td>
                        <td width="20%"><strong>Departamento:</strong></td>
                        <td width="30%">{{ $empleado['departamento'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nombre:</strong></td>
                        <td>{{ $empleado['nombre'] }}</td>
                        <td><strong>AC Nº / ID:</strong></td>
                        <td>{{ $empleado['ac_id'] }}</td>
                    </tr>
                </table>
            </div>
        @endif
            <table class="calendar">
                <thead>
                    <tr>
                        @foreach($periodo as $dia)
                            <th>{{ $dia['dia'] }}<br>{{ $dia['fecha'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($periodo as $dia)
                            <td>
                                @foreach($dia['registros'] as $registro)
                                    {{ $registro }}<br>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>

            <div class="signature">
                <p>_______________________</p>
                <p>Firma Empleado</p>
            </div>
            </table>

    
      
    </div>
</body>
</html>