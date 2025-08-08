<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-grey-400 font-sans">
    <div class="flex h-screen">

        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Navigation -->
            <div class="flex items-center justify-between bg-white px-6 py-4 shadow-md">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('image/hype.png') }}" alt="Logo" class="h-10">
                    <span class="text-xl font-semibold text-gray-800">
                        Welcome, {{ Auth::user()->name ?? 'Guest' }}
                    </span>
                </a>

                <!-- Bisa tambahkan isi topnav lain, misal user info / tombol logout -->
                <div>
                    @include('components.topnav')
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>

        </div>

    </div>

    <script>
  document.getElementById('notifToggle').addEventListener('click', function () {
    document.getElementById('notifDropdown').classList.toggle('hidden');
  });
</script>

</body>

</html>