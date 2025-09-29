<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Project Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-4 bg-indigo-600">
                <h1 class="text-sm md:text-base lg:text-lg font-bold text-white truncate">PT. Indonesia Gadai Oke</h1>
                <!-- Close button for mobile -->
                <button id="closeSidebar" class="lg:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-8 flex-1 overflow-y-auto pb-24">
                <div class="px-4 space-y-2">
                    
                    @if(Auth::user()->isAdmin())
                        <!-- ADMIN NAVIGATION -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-600' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7"></path>
                            </svg>
                            <span class="truncate">Dashboard</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-600' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="truncate">User Management</span>
                        </a>

                        <a href="{{ route('admin.projects.index') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-600' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="truncate">Projects</span>
                        </a>

                        <a href="#" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                            </svg>
                            <span class="truncate">Reports</span>
                        </a>

                    @elseif(Auth::user()->isProjectManager())
                        <!-- PROJECT MANAGER NAVIGATION -->
                        <a href="{{ route('pm.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('pm.dashboard') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-600' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7"></path>
                            </svg>
                            <span class="truncate">Dashboard</span>
                        </a>

                        <a href="{{ route('pm.projects.index') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('pm.projects.*') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-600' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="truncate">My Projects</span>
                        </a>

                        <a href="{{ route('pm.projects.create') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="truncate">Create Project</span>
                        </a>

                    @else
                        <!-- MEMBER NAVIGATION -->
                        <a href="{{ route('member.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('member.dashboard') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-600' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7"></path>
                            </svg>
                            <span class="truncate">Dashboard</span>
                        </a>

                        <a href="{{ route('member.projects.index') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('member.projects.*') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-600' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="truncate">My Projects</span>
                        </a>

                        <a href="#" onclick="showFeatureModal(); return false;" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="truncate">My Tasks</span>
                        </a>
                    @endif
                </div>

                <!-- Bottom Profile Menu -->
                <div class="fixed bottom-0 left-0 w-64 p-4 bg-white border-t border-gray-200 lg:absolute">
                    <div id="profileDropdown" class="relative">
                        <button id="profileButton" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-xs font-medium text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-1 text-left min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500 truncate">
                                    @if(Auth::user()->isAdmin()) Admin
                                    @elseif(Auth::user()->isProjectManager()) PM
                                    @else Member @endif
                                </div>
                            </div>
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div id="profileMenu" class="hidden absolute bottom-full left-4 right-4 mb-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full overflow-hidden">
            <!-- Mobile Header -->
            <header class="bg-white border-b border-gray-200 lg:hidden sticky top-0 z-40">
                <div class="flex items-center justify-between px-4 py-3">
                    <button id="openSidebar" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-800 truncate">
                        @if(Auth::user()->isAdmin()) Admin
                        @elseif(Auth::user()->isProjectManager()) PM
                        @else Member @endif
                    </h1>
                    <div class="w-6"></div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                @if (session('success'))
                    <div class="mx-4 mt-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm" role="alert">
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-4 mt-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <div class="py-4 md:py-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="overlay" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden hidden"></div>

    <!-- Feature Modal -->
    <div id="featureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Fitur Belum Tersedia</h3>
                <p class="text-sm text-gray-500 mb-4">Fitur ini sedang dalam tahap pengembangan.</p>
                <button onclick="closeFeatureModal()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }

    openBtn?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);

    // Profile Dropdown
    const profileBtn = document.getElementById('profileButton');
    const profileMenu = document.getElementById('profileMenu');

    profileBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        profileMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
            profileMenu.classList.add('hidden');
        }
    });

    // Feature Modal
    function showFeatureModal() {
        document.getElementById('featureModal').classList.remove('hidden');
    }
    function closeFeatureModal() {
        document.getElementById('featureModal').classList.add('hidden');
    }
    document.getElementById('featureModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeFeatureModal();
    });
    </script>
</body>
</html>