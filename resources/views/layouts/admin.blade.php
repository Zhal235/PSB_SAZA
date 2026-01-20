<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('components.sidebar-admin')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar - Premium -->
            <div class="bg-white shadow-xl p-6 flex justify-between items-center" style="border-bottom: 4px solid #00a0a0;">
                <div>
                    <h2 class="text-3xl font-bold" style="color: #00a0a0;">@yield('page-title')</h2>
                    <div class="text-gray-500 text-sm mt-1">
                        @yield('page-subtitle')
                    </div>
                </div>
                <div>
                    @yield('top-bar-action')
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-8">
                    @if(session('success'))
                        <div class="bg-gradient-to-r from-[#E8F5E9] to-[#C8E6C9] border-l-4 text-[#1B5E20] p-4 mb-6 rounded-lg shadow-md" style="border-left-color: #00a0a0;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
