<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <!-- Add inside <head> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Add before </body> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- In <head> -->
    @livewireStyles

    <!-- Just before </body> -->
    @livewireScripts

</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

<div class="min-h-screen flex flex-col">
    <!-- Top Nav -->
    @include('layouts.navigation')

    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white p-4">
            <h2 class="text-lg font-bold mb-4">Sidebar</h2>
            <ul class="space-y-2">
                <li><a href="/dashboard" class="hover:underline">Dashboard</a></li>
                <li><a href="{{ route('contacts.index') }}" class="hover:underline">Contacts</a></li>

            </ul>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow mb-4">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                <div>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 text-center text-sm py-4 shadow">
        © {{ date('Y') }} Livewire App
    </footer>
</div>
@livewireScripts(['csrf' => true])
@livewireStyles
@stack('scripts')

</body>
</html>
