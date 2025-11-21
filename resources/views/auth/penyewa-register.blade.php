<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Kost Bu Adah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">

    <div class="min-h-screen flex flex-row-reverse">
        
        <div class="hidden lg:block w-1/2 bg-indigo-900 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-bl from-purple-800 to-indigo-600 opacity-90"></div>
            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Background" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50">
            
            <div class="absolute inset-0 flex flex-col justify-center px-12 text-white text-right">
                <h2 class="text-4xl font-bold mb-4">Bergabunglah Bersama Kami</h2>
                <p class="text-lg text-indigo-100">Daftarkan diri Anda sekarang untuk memesan kamar impian dengan proses yang cepat dan transparan.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 overflow-y-auto">
            <div class="w-full max-w-lg bg-white p-8 rounded-2xl shadow-lg border border-gray-100 my-8">
                
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-extrabold text-gray-900">Pendaftaran Penghuni</h3>
                    <p class="text-sm text-gray-500 mt-2">Isi formulir di bawah ini dengan data yang valid.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <ul class="text-xs text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('penyewa.register.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WA / Telepon</label>
                            <input type="text" name="telepon" value="{{ old('telepon') }}" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KTP / NIK</label>
                            <input type="text" name="nomor_ktp" value="{{ old('nomor_ktp') }}" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto KTP (Wajib)</label>
                        
                        <div class="relative w-full h-48 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 bg-white overflow-hidden group">
                            
                            <input type="file" 
                                   name="foto_ktp" 
                                   id="foto_ktp" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" 
                                   accept="image/*" 
                                   onchange="previewImage(event)" 
                                   required />
                            
                            <div id="placeholder-area" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-10">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-gray-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="pt-1 text-sm text-gray-500 group-hover:text-gray-600">Klik area ini untuk upload foto</p>
                            </div>
            
                            <img id="image-preview" class="absolute inset-0 w-full h-full object-contain bg-gray-100 hidden z-10" alt="Preview KTP">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Asal</label>
                        <textarea name="alamat_asal" rows="2" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>{{ old('alamat_asal') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
                            <input type="password" name="password_confirmation" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition duration-150 transform hover:-translate-y-0.5">
                        Buat Akun Sekarang
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('penyewa.login') }}" class="font-bold text-indigo-600 hover:text-indigo-500">Login Disini</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('placeholder-area');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); // Munculkan gambar
                    placeholder.classList.add('hidden'); // Sembunyikan teks placeholder
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                // Kalau user batal milih, reset
                preview.src = "";
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>