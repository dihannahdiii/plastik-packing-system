<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Sumber Plastik Packing')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-xl font-bold">Daerah Istimewa Yogyakarta</div>
            <div class="flex items-center gap-6">
                <a href="tel:0889-5243-366" class="flex items-center gap-2">
                    <span class="text-2xl">📞</span>
                    <span class="text-xl font-semibold">0889-5243-366</span>
                </a>
                @auth
                    <div class="flex items-center gap-4 border-l pl-6">
                        <div class="text-sm">
                            <div class="font-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-gray-500">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="container mx-auto px-6 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-6 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <main class="container mx-auto px-6 py-8">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
