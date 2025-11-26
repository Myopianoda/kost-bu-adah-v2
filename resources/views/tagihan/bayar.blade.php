<?php
    if (Auth::guard('web')->check()) { $layout = 'app-layout'; } else { $layout = 'penyewa-layout'; }
?>

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
                
                <div class="flex flex-col space-y-6">
                    
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Total Tagihan</p>
                                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">Rp {{ number_format($tagihan->jumlah) }}</h3>
                            </div>
                            <div class="text-right">
                                <span class="block text-sm font-bold text-gray-800">{{ $tagihan->sewa->unit->name }}</span>
                                <span class="block text-xs text-gray-500">{{ $tagihan->bulan }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex-1">
                        <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide">Pilih Cara Bayar</h3>
                        
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-xl p-4 flex items-center gap-4 hover:border-gray-400 transition cursor-pointer">
                                <div class="bg-gray-50 p-2 rounded-lg border border-gray-100">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BayarKostBuAdah" 
                                         class="w-16 h-16 object-contain mix-blend-multiply">
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">Scan QRIS (Digital)</h4>
                                    <p class="text-xs text-gray-500 mt-1">OVO, GoPay, Dana, ShopeePay.</p>
                                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded mt-2 inline-block">Otomatis Upload Bukti -></span>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-xl p-4 flex items-center gap-4 hover:border-gray-400 transition cursor-pointer">
                                <div class="w-20 h-20 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">Bayar Tunai (Cash)</h4>
                                    <p class="text-xs text-gray-500 mt-1">Bayar langsung ke Ibu Adah.</p>
                                    <p class="text-[10px] text-red-500 mt-1 italic">*Tidak perlu upload bukti di sini.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col h-full">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-full flex flex-col">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Konfirmasi Transfer</h3>
                            <p class="text-sm text-gray-500">Khusus pembayaran QRIS/Transfer, upload buktinya di sini.</p>
                        </div>

                        <form action="{{ route('tagihan.upload', $tagihan->id) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col">
                            @csrf
                            @method('PATCH')

                            <div class="flex-1 mb-4 min-h-[200px]">
                                <label class="relative w-full h-full border-2 border-dashed border-gray-300 rounded-xl hover:border-gray-500 hover:bg-gray-50 transition flex flex-col justify-center items-center cursor-pointer overflow-hidden group bg-white">
                                    
                                    <input type="file" 
                                           name="bukti_bayar" 
                                           class="absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer"
                                           accept="image/*"
                                           onchange="previewBukti(event)"
                                           required>
                                    
                                    <div id="placeholder-area" class="text-center p-4">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-gray-200 transition">
                                            <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Klik untuk Upload Bukti</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (Max 2MB)</p>
                                    </div>

                                    <img id="image-preview" class="absolute inset-0 w-full h-full object-contain bg-white hidden z-10 p-4" />
                                </label>
                            </div>

                            <button type="submit" class="w-full py-3 px-4 bg-gray-900 hover:bg-black text-white font-bold rounded-lg transition shadow-md">
                                Kirim Bukti
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function previewBukti(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('placeholder-area').classList.add('opacity-0');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-dynamic-component>