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
    <div class="font-sans min-h-screen flex flex-col relative">
        {{-- Texnik ishlar sahifasini ko'rsatish uchun --}}
        @include('texnik')

        <!-- Video background for desktop -->
        <div class="hidden sm:block absolute inset-0 w-full h-full overflow-hidden z-0">
            <video class="absolute min-w-full min-h-full object-cover" autoplay loop muted playsinline>
                <source src="https://new.cspu.uz/themes/univer/assets/video/project2-web.webm" type="video/mp4">
            </video>
            <!-- Gradient overlay with blue tint -->
            <div class="absolute inset-0 bg-gradient-to-b from-blue-900/80 via-blue-800/70 to-blue-900/80"></div>

            <!-- Additional subtle overlay for depth -->
            <div class="absolute inset-0 bg-blue-500 mix-blend-overlay opacity-20"></div>

            <!-- Optional: Add a subtle pattern overlay for texture -->
            <div class="absolute inset-0 opacity-10"
                style="background-image: url('data:image/png;base64,base64_encoded_noise_pattern');
                background-repeat: repeat;">
            </div>
        </div>

        <!-- Image background for mobile -->
        <div class="sm:hidden absolute inset-0 w-full h-full z-0">
            <img src="https://reyting.cspu.uz/assets/thumb__1_0_0_0_auto.jpg" class="w-full h-full object-cover"
                alt="Background">
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

        <style>
            @keyframes slide-left {
                0% {
                    transform: translateX(100%);
                }

                100% {
                    transform: translateX(-100%);
                }
            }

            .warning-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 9999;
                background: linear-gradient(to right, rgba(37, 99, 235, 0.9), rgba(67, 56, 202, 0.9));
                padding: 0.75rem 0;
                backdrop-filter: blur(8px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .warning-content {
                display: flex;
                align-items: center;
                white-space: nowrap;
                animation: slide-left 20s linear infinite;
                color: white;
                font-weight: 500;
            }

            .warning-icon {
                width: 1.5rem;
                height: 1.5rem;
                margin-right: 0.75rem;
                filter: drop-shadow(0 0 4px rgba(255, 255, 255, 0.3));
            }

            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .profile-image {
                transition: all 0.3s ease;
                border: 4px solid transparent;
                background: linear-gradient(white, white) padding-box,
                    linear-gradient(to right, #3B82F6, #6366F1) border-box;
            }

            .card-hover:hover .profile-image {
                transform: scale(1.1);
            }

            .stats-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: all 0.3s ease;
            }

            .stats-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                background: rgba(255, 255, 255, 0.98);
            }

            .logo-container {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: all 0.3s ease;
            }

            .logo-container:hover {
                transform: scale(1.02);
            }

            @media (min-width: 640px) {
                .warning-text {
                    font-size: 1rem;
                }
            }

            @media (min-width: 768px) {
                .warning-text {
                    font-size: 1.125rem;
                }
            }
        </style>

        <!-- Header -->
        <header class="warning-header">
            <div class="warning-content">
                <img src="https://cdn2.iconfinder.com/data/icons/medicare/512/warning_danger_alert_alarm_safety_exclamation_attention-1024.png"
                    class="warning-icon" alt="Warning">
                <p class="warning-text">Saytda sozlash ishlari olib borilishi mumkin! &nbsp;&nbsp;&nbsp;&nbsp;</p>
            </div>
        </header>

        <!-- Login Button -->
        @if (Route::has('login'))
            <div id="login-div" class="absolute top-14 sm:top-16 md:top-20 right-4 z-10 transition-all duration-300">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transform hover:scale-105 transition-all duration-300 shadow-lg">
                        Profilga kirish
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transform hover:scale-105 transition-all duration-300 shadow-lg">
                        Saytga kirish
                    </a>
                @endauth
            </div>
        @endif

        <!-- Main Content -->
        <main
            class="flex-grow container mx-auto p-4 sm:p-6 md:p-8 flex flex-col items-center justify-center relative z-1">
            <!-- Logo Section -->
            <div class="logo-container mt-8 mb-6 rounded-2xl p-6 shadow-xl w-full max-w-md">
                <img class="h-20 sm:h-24 md:h-32 lg:h-40 mx-auto transform hover:scale-105 transition-all duration-300"
                    src="https://cspu.uz/storage/app/media/2024/sentyabr/CHDPU%20logo-2022.png" alt="Logo">
            </div>

            <!-- Title Section -->
            <div class="text-center text-white mb-12">
                <h1
                    class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200">
                    REYTING 2024
                </h1>
                <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl px-4 font-medium text-white/90">
                    Chirchiq davlat pedagogika universiteti professor-oʻqituvchilarining asosiy faoliyat koʻrsatkichlari
                    KPI
                </h2>
            </div>

           <!-- Top Employees Grid -->
           <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-7 mb-12 w-full">
            @foreach ($topEmployees as $employee)
                <div class="card-hover bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl">
                    <img src="{{'/storage/users/image'}}/{{ $employee['image'] ?? '/path/to/default/image.jpg' }}"
                        alt="{{ $employee['first_name'] }}"
                        class="profile-image w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full mb-3">
                    <h3 class="text-sm sm:text-base font-bold text-center text-gray-800 mb-1 line-clamp-2">
                        {{ $employee['second_name'] }} {{ $employee['first_name'] }}
                    </h3>
                    <p class="text-base sm:text-lg font-bold text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-transparent bg-clip-text">
                        Ball: {{ number_format($employee['total_points'], 1) }}
                    </p>
                </div>
            @endforeach
        </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
                <!-- Top Departments -->
                <div class="stats-card rounded-2xl p-6 shadow-xl">
                    <h3 class="text-xl font-bold text-center mb-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-transparent bg-clip-text">
                        TOP-5 KAFEDRA REYTINGI
                    </h3>
                    <ol class="list-decimal pl-5 space-y-4">
                        @foreach ($topDepartments->take(5) as $department)
                            <li class="text-lg font-semibold">
                                {{ $department['name'] }} -
                                <span class="text-blue-600 font-bold">{{ number_format($department['total_points'], 2) }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- Top Faculties -->
                <div class="stats-card rounded-2xl p-6 shadow-xl">
                    <h3 class="text-xl font-bold text-center mb-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-transparent bg-clip-text">
                        TOP-3 FAKULTETLAR REYTINGI
                    </h3>
                    <ol class="list-decimal pl-5 space-y-4">
                        @foreach ($topFaculties->take(3) as $faculty)
                            <li class="text-lg font-semibold">
                                {{ $faculty['name'] }} -
                                <span class="text-blue-600 font-bold">{{ number_format($faculty['total_points'], 2) }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-1 text-center text-white p-6 bg-gradient-to-r from-blue-600 to-indigo-600 mt-12">
            <p class="text-sm sm:text-base opacity-90">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </p>
        </footer>
    </div>

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



