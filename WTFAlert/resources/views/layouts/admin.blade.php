<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WTFAlert - Administration</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition duration-200 ease-in-out">
            <div class="text-white flex items-center space-x-2 px-4">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="text-xl font-semibold">WTFAlert Admin</span>
            </div>
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    Tableau de bord
                </a>
                <a href="{{ route('admin.mairies.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    Mairies
                </a>
                <a href="{{ route('admin.foyers.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    Foyers
                </a>
                <a href="{{ route('admin.habitants.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    Habitants
                </a>
                <a href="{{ route('admin.alertes.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    Alertes
                </a>
                <a href="{{ route('admin.demandes.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    Demandes
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button class="text-gray-500 focus:outline-none md:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="ml-2 text-xl font-semibold text-gray-800">@yield('title', 'Tableau de bord')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="text-gray-600 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                        </div>
                        <div class="relative">
                            <button class="flex items-center focus:outline-none">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->prenom . ' ' . auth()->user()->nom) }}&background=random" alt="Profile">
                                <span class="ml-2 text-gray-700">{{ auth()->user()->prenom }}</span>
                                <svg class="ml-1 h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <!-- Dropdown -->
                            <div class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <div class="text-sm font-medium text-gray-800">
                                        {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate">
                                        {{ Auth::user()->email }}
                                    </div>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="h-4 w-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Mon profil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="h-4 w-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Paramètres
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <svg class="h-4 w-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle mobile menu
        document.querySelector('button[aria-label="Menu"]').addEventListener('click', function() {
            document.querySelector('.transform').classList.toggle('-translate-x-full');
        });

        // Toggle dropdown
        document.querySelector('button img[alt="Profile"]')?.parentElement.addEventListener('click', function(e) {
            e.stopPropagation();
            this.nextElementSibling.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            const dropdown = document.querySelector('.absolute[style*="display: none"]');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
