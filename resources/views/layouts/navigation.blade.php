<nav x-data="{ open: false, openReports: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="/img/Grouplogof3c.png" alt="logo" id="logo" height="80%" width="80%">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Dropdown for Reportes -->
                    <div class="relative" @mouseleave="openReports = false">
                        <button style="margin-top:25px" @mouseenter="openReports = true" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none">
                            <span>{{ __('Reportes') }}</span>
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Content for Reportes -->
                        <div x-show="openReports" @mouseenter="openReports = true" class="absolute mt-2 w-48 bg-white border rounded-md shadow-lg z-50">
                            <x-nav-link :href="route('informe-formulario')" :active="request()->routeIs('informe-formulario')">
                                {{ __('Formulario de Reporte') }}
                            </x-nav-link>
                            <x-nav-link :href="route('informe-mensual')" :active="request()->routeIs('informe-mensual')">
                                {{ __('Reporte Mensual') }}
                            </x-nav-link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative">
                    <button @click="open = ! open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50">
                        <!-- Ejecutar Node Server -->
                        <button id="runNodeServer" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Ejecutar Node Server
                        </button>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger para móviles -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <div class="relative">
                <x-responsive-nav-link :href="route('informe-formulario')" :active="request()->routeIs('informe-formulario')">
                    {{ __('Formulario de Reporte') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('informe-mensual')" :active="request()->routeIs('informe-mensual')">
                    {{ __('Reporte Mensual') }}
                </x-responsive-nav-link>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Script para ejecutar el comando de Node.js -->
<script>
    document.getElementById('runNodeServer').addEventListener('click', function () {
        fetch('/run-node-server')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Comando ejecutado correctamente: ' + data.output);
                } else {
                    alert('Error al ejecutar el comando: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error en la solicitud: ' + error);
            });
    });
</script>
