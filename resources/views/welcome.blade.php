<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kost Bu Adah</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <nav x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 'bg-white shadow-md py-2': scrolled, 'bg-transparent py-4': !scrolled }"
         class="fixed w-full top-0 z-50 transition-all duration-300 ease-in-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="font-extrabold text-2xl tracking-tight flex items-center gap-2 group">
                    <span :class="{ 'text-indigo-600': scrolled, 'text-white': !scrolled }" class="transition-colors">KOST</span>
                    <span :class="{ 'text-gray-800': scrolled, 'text-white': !scrolled }" class="transition-colors">BU ADAH</span>
                </a>

                <div class="hidden md:flex items-center space-x-6">
                    @if (Auth::guard('web')->check())
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-white text-indigo-600 font-bold rounded-full shadow-lg hover:bg-gray-100 transition transform hover:-translate-y-0.5">
                            Dashboard Admin
                        </a>
                    @elseif (Auth::guard('penyewa')->check())
                        <a href="{{ route('penyewa.dashboard') }}" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-full shadow-lg hover:bg-indigo-700 transition transform hover:-translate-y-0.5">
                            Portal Saya
                        </a>
                    @else
                        <a href="{{ route('penyewa.login') }}" class="font-medium hover:text-indigo-400 transition" :class="{ 'text-gray-600': scrolled, 'text-white': !scrolled }">
                            Masuk
                        </a>
                        <a href="{{ route('penyewa.register') }}" class="px-5 py-2.5 bg-white text-indigo-900 font-bold rounded-full shadow hover:bg-gray-100 transition transform hover:-translate-y-0.5">
                            Daftar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative bg-gray-900 h-[600px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 opacity-95"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-10">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-500/20 text-indigo-200 text-sm font-semibold mb-6 border border-indigo-500/30 backdrop-blur-sm">
                ✨ Hunian Nyaman & Terjangkau
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6 leading-tight drop-shadow-lg">
                Temukan Kenyamanan <br>Seperti di Rumah Sendiri
            </h1>
            <p class="text-lg md:text-xl text-indigo-100 mb-10 font-light max-w-2xl mx-auto leading-relaxed">
                Kost & Kontrakan Bu Adah menyediakan fasilitas lengkap, lokasi strategis, dan lingkungan yang aman untuk mendukung produktivitas Anda.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#daftar-unit" class="px-8 py-4 bg-white text-indigo-900 font-bold rounded-full shadow-xl hover:bg-gray-100 transition transform hover:scale-105 flex items-center justify-center">
                    Lihat Kamar Kosong
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
            </div>
        </div>
        
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    <main id="daftar-unit" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Pilihan Unit Terbaik</h2>
                <p class="text-gray-600 max-w-xl mx-auto">Pilih unit yang sesuai dengan kebutuhan dan budget Anda. Semua unit dirawat dengan baik dan siap huni.</p>
                <div class="w-20 h-1.5 bg-indigo-600 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @forelse ($unitTersedia as $unit)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                        
                        <div class="h-56 bg-gray-200 w-full relative overflow-hidden">
                            @if($unit->gambar)
                                <img src="{{ asset('storage/' . $unit->gambar) }}" alt="{{ $unit->name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-medium">Tidak ada foto</span>
                                </div>
                            @endif

                            <div class="absolute top-4 right-4">
                                @if($unit->status == 'tersedia')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg tracking-wide">
                                        TERSEDIA
                                    </span>
                                @elseif($unit->status == 'booking')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-400 text-yellow-900 shadow-lg tracking-wide">
                                        BOOKED
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition mb-1">
                                    <a href="{{ route('public.unit.show', $unit->id) }}">
                                        {{ $unit->name }}
                                    </a>
                                </h3>
                                <p class="text-2xl font-bold text-indigo-600">
                                    Rp {{ number_format($unit->price, 0, ',', '.') }}
                                    <span class="text-sm text-gray-400 font-medium">/ bulan</span>
                                </p>
                            </div>

                            @if($unit->description)
                                <p class="text-gray-500 text-sm line-clamp-2 mb-6 flex-grow">
                                    {{ $unit->description }}
                                </p>
                            @else
                                <p class="text-gray-400 text-sm italic mb-6 flex-grow">Tidak ada deskripsi.</p>
                            @endif

                            <div class="pt-4 border-t border-gray-100 mt-auto">
                                @if($unit->status == 'tersedia')
                                    <a href="{{ route('public.unit.show', $unit->id) }}" class="block w-full text-center bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white font-bold py-2.5 rounded-lg transition duration-200">
                                        Lihat Detail & Booking
                                    </a>
                                @elseif($unit->status == 'booking')
                                    <button disabled class="block w-full text-center bg-gray-100 text-gray-400 font-bold py-2.5 rounded-lg cursor-not-allowed border border-gray-200">
                                        Sedang Dibooking
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="text-gray-500 text-lg font-medium">Belum ada unit yang tersedia saat ini.</p>
                        <p class="text-gray-400 text-sm">Silakan cek kembali nanti.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-8 mt-auto">
        <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Kost Bu Adah. All rights reserved.</p>
            
            <div class="mt-4 md:mt-0">
                <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-indigo-600 transition font-medium">
                    Login Pengelola (Admin)
                </a>
            </div>
        </div>
    </footer>

</body>
</html>