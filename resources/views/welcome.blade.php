<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REYTING 2024 - CHIRCHIQ DAVLAT PEDAGOGIKA UNIVERSITETI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="antialiased">
    <div class="font-sans bg-gray-200 bg-hero bg-no-repeat bg-cover bg-center bg-fixed min-h-screen flex flex-col"
        style="background-image: url(https://reyting.cspu.uz/assets/thumb__1_0_0_0_auto.jpg);">
        {{-- Texnik ishlar sahifasini ko'rsatish uchun --}}
        @include('texnik')
        <style>
            @keyframes slide-left {
                0% { transform: translateX(100%); }
                100% { transform: translateX(-100%); }
            }

            .warning-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 9999;
                background-color: rgba(37, 99, 235, 0.9);
                padding: 0.5rem 0;
                overflow: hidden;
            }

            .warning-content {
                display: flex;
                align-items: center;
                white-space: nowrap;
                animation: slide-left 20s linear infinite;
            }

            .warning-icon {
                width: 1.25rem;
                height: 1.25rem;
                margin-right: 0.5rem;
                flex-shrink: 0;
            }

            .warning-text {
                color: white;
                font-size: 0.875rem;
                font-weight: 500;
            }

            @media (min-width: 640px) {
                .warning-icon { width: 1.5rem; height: 1.5rem; }
                .warning-text { font-size: 1rem; }
            }

            @media (min-width: 768px) {
                .warning-icon { width: 2rem; height: 2rem; }
                .warning-text { font-size: 1.125rem; }
            }
        </style>

        <header class="warning-header">
            <div class="warning-content">
                <img src="https://cdn2.iconfinder.com/data/icons/medicare/512/warning_danger_alert_alarm_safety_exclamation_attention-1024.png" class="warning-icon" alt="Warning">
                <p class="warning-text">Saytda sozlash ishlari olib borilishi mumkin! &nbsp;&nbsp;&nbsp;&nbsp;</p>

            </div>
        </header>

        @if (Route::has('login'))
            <div id="login-div" class="absolute top-14 sm:top-16 md:top-20 right-4 z-10 transition-all duration-300">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block px-3 py-1 sm:px-4 sm:py-2 text-xs sm:text-sm md:text-base font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-300">Profilga kirish</a>
                @else
                    <a href="{{ route('login') }}" class="inline-block px-3 py-1 sm:px-4 sm:py-2 text-xs sm:text-sm md:text-base font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-300">Saytga kirish</a>
                @endauth
            </div>
        @endif

        <main class="flex-grow container mx-auto p-4 sm:p-6 md:p-8 flex flex-col items-center justify-center">
            <div class="mb-6 bg-white rounded-lg p-4 shadow-md">
                <img class="h-20 sm:h-24 md:h-32 lg:h-40 mx-auto" src="https://cspu.uz/storage/app/media/2024/sentyabr/CHDPU%20logo-2022.png" alt="Logo">
            </div>

            <div class="text-center text-white mb-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-2">REYTING 2024</h1>
                <h2 class="text-sm sm:text-base md:text-lg lg:text-4xl px-4">Chirchiq davlat pedagogika universiteti professor-oʻqituvchilarining asosiy faoliyat koʻrsatkichlari
                    KPI</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-7 mb-8 w-full">
                @foreach ($topEmployees as $user)
                    <div class="bg-white p-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300">
                        <img src="{{ '/storage/users/image' }}/{{ $user->image }}" alt="{{ ucwords(strtolower($user->FullName)) }}" class="w-20 h-20 sm:w-24 sm:h-24 md:w-32 md:h-32 mx-auto rounded-full mb-4 border-4 border-blue-500">
                        <h3 class="text-base sm:text-lg font-bold text-center">{{ ucwords(strtolower($user->FullName)) }}</h3>
                        <p class="text-lg sm:text-xl font-bold text-center text-blue-600">Ball: {{$user->total_points}}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold text-center mb-4 text-blue-600">TOP-5 KAFEDRA REYTINGI</h3>
                    <ol class="list-decimal pl-5 space-y-2">
                        @foreach ($topDepartments as $topDepartment)
                            <li class="text-sm sm:text-base md:text-lg font-bold">{{$topDepartment->name}} - <span class="text-blue-600">{{ $topDepartment->average_points}}</span></li>
                        @endforeach
                    </ol>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold text-center mb-4 text-blue-600">TOP-3 FAKULTETLAR REYTINGI</h3>
                    <ol class="list-decimal pl-5 space-y-2">
                        @foreach ($topFaculties as $topFaculty)
                            <li class="text-sm sm:text-base md:text-lg font-bold">{{$topFaculty->name}} - <span class="text-blue-600">{{$topFaculty->average_points}}</span></li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </main>

        <footer class="text-center text-white text-xs sm:text-sm p-4 bg-blue-600 bg-opacity-70 mt-8">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </footer>
    </div>

    <style>
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 20s linear infinite;
        }
    </style>

    <script>
        window.addEventListener('scroll', function() {
            var loginDiv = document.getElementById('login-div');
            if (window.scrollY > 100) {
                loginDiv.classList.add('opacity-0', 'pointer-events-none');
            } else {
                loginDiv.classList.remove('opacity-0', 'pointer-events-none');
            }
        });
    </script>
</body>
</html>
