<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'F3C') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS Personalizado -->
    <style>
        body {
            /* Imagen de fondo */
            background-image: url('{{ asset('/img/oficina.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-custom {
            background: rgba(255, 255, 255, 0.85); /* Fondo blanco semi-transparente */
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }

        .btn-custom-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .btn-custom-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
            color: white;
        }

        .btn-custom-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-custom-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: white;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="card-custom text-center">
        <!-- Logo -->
        <img src="{{ asset('/img/Grouplogof3c.png') }}" alt="Logo" class="logo">
        
        <h1 class="mb-4">Bienvenido a F3C</h1>
        
        <div class="d-flex justify-content-center">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-custom-primary me-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-custom-primary me-2">Login</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-custom-secondary">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
