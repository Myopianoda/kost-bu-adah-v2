<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $unit->name }} - Kost Bu Adah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="font-bold text-xl text-gray-800 hover:text-indigo-600 transition">
                        Kost Bu Adah
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth('penyewa')
                        <a href="{{ route('penyewa.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Dashboard Saya
                        </a>
                        <span class="text-gray-300">|</span>
                        <div class="text-sm font-bold text-indigo-600">
                            {{ Auth::guard('penyewa')->user()->nama_lengkap }}
                        </div>
                    @else
                        <a href="{{ route('penyewa.login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Login Penyewa
                        </a>
                        <a href="{{ route('penyewa.register') }}" class="text-sm font-medium bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>


    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">

                <div class="h-80 bg-gray-200 w-full flex items-center justify-center overflow-hidden border-b relative">
                    @if($unit->gambar)
                        <img src="{{ asset('storage/' . $unit->gambar) }}" alt="{{ $unit->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center text-gray-500">
                            <svg class="w-16 h-16 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>[Tidak ada foto unit]</span>
                        </div>
                    @endif

                    <div class="absolute top-4 right-4">
                        @if($unit->status == 'tersedia')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg">TERSEDIA</span>
                        @elseif($unit->status == 'booking')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-white shadow-lg">BOOKED</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white shadow-lg">TERISI</span>
                        @endif
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-6 mb-6">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900">{{ $unit->name }}</h1>
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                            <p class="text-sm text-gray-500">Harga Sewa</p>
                            <p class="text-3xl text-indigo-600 font-bold">
                                Rp {{ number_format($unit->price, 0, ',', '.') }}
                                <span class="text-base text-gray-500 font-normal">/ bulan</span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Deskripsi & Fasilitas</h3>
                        <div class="prose max-w-none text-gray-600 whitespace-pre-line leading-relaxed">
                            {{ $unit->description ?? 'Tidak ada deskripsi khusus untuk unit ini.' }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        @if($unit->status == 'tersedia')
                            
                            @auth('penyewa')
                                <div class="text-center">
                                    <p class="text-gray-600 mb-4">Berminat dengan kamar ini? Segera ajukan booking sebelum diambil orang lain!</p>
                                    <a href="{{ route('penyewa.booking.create', $unit->id) }}" 
                                       class="inline-flex justify-center items-center w-full md:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-bold rounded-lg shadow-lg hover:shadow-xl transition duration-200 transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        AJUKAN BOOKING SEKARANG
                                    </a>
                                </div>
                            @else
                                <div class="flex items-start p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-md">
                                    <svg class="w-6 h-6 text-yellow-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-yellow-800 font-bold">Login Diperlukan</h4>
                                        <p class="text-yellow-700 mt-1">
                                            Anda harus login atau memiliki akun penyewa untuk melakukan booking unit ini.
                                        </p>
                                        <div class="mt-3 space-x-4">
                                            <a href="{{ route('penyewa.login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 underline">
                                                Masuk (Login)
                                            </a>
                                            <span class="text-gray-400">|</span>
                                            <a href="{{ route('penyewa.register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 underline">
                                                Daftar Akun Baru
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endauth

                        @else
                            <div class="text-center py-4">
                                <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-4 px-6 rounded-lg cursor-not-allowed flex justify-center items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    MAAF, UNIT INI TIDAK TERSEDIA
                                </button>
                                <p class="text-sm text-gray-500 mt-2">Silakan cek unit kami yang lain.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>