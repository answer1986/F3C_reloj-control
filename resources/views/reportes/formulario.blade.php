
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Informe</title>
</head>
<body>
    <form action="{{ route('informe-mensual') }}" method="GET">
        <label for="tipo">Selecciona el tipo de negocio:</label>
        <select name="tipo" id="tipo">
            <option value="registro">Ferretería</option>
            <option value="central">Centro de Distribución</option>
        </select>
        
        <label for="fecha_inicio">Fecha inicio:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="2024-07-01">

        <label for="fecha_fin">Fecha fin:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="2024-07-08">

        <button type="submit">Generar Informe</button>
    </form>
</body>
</html>
