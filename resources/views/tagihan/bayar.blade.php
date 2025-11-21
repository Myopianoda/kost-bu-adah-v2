<?php
    // Logika Layout (Pilih baju Admin atau Penyewa)
    if (Auth::guard('web')->check()) {
        $layout = 'app-layout';
    } else {
        $layout = 'penyewa-layout';
    }
?>

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div>
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Detail Tagihan</h3>
                            <div class="text-gray-600">
                                <p>Unit: <strong>{{ $tagihan->sewa->unit->name }}</strong></p>
                                <p>Bulan: {{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->format('F Y') }}</p>
                                <p class="text-2xl font-bold text-indigo-600 mt-2">Rp {{ number_format($tagihan->jumlah) }}</p>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-4">Metode Pembayaran</h3>
                        
                        <div class="mb-6">
                            <p class="text-sm font-semibold mb-2 text-gray-700">Opsi 1: Scan QRIS</p>
                            <div class="bg-gray-100 w-48 h-48 flex items-center justify-center text-gray-400 text-xs border border-gray-300 rounded-lg">
                                {{-- GANTI DENGAN FILE GAMBAR QRIS ANDA NANTI --}}
                                {{-- <img src="{{ asset('img/qris.jpg') }}" class="w-full h-full object-contain"> --}}
                                [Gambar QRIS Anda]
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-sm font-semibold mb-2 text-gray-700">Opsi 2: Transfer Bank</p>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-bold text-blue-800">BCA (Bank Central Asia)</span>
                                    {{-- Ikon copy bisa ditambahkan di sini nanti --}}
                                </div>
                                <p class="text-xl font-mono text-gray-800 tracking-wider">123-456-7890</p>
                                <p class="text-sm text-gray-500 mt-1">a.n Bu Adah</p>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <p class="text-sm font-bold text-yellow-800 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Opsi 3: Pembayaran Tunai
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Silakan temui Admin/Pengelola di lokasi untuk bayar tunai. Anda tidak perlu upload bukti di sini.
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 h-fit">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Sudah Transfer?</h3>
                        <p class="text-sm text-gray-500 mb-6">Silakan upload bukti pembayaran agar kami bisa memverifikasi.</p>
                        
                        <form action="{{ route('tagihan.upload', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="mb-6">
                                <label class="block font-medium text-sm text-gray-700 mb-2">Foto Bukti Transfer</label>
                                
                                <div class="flex items-center justify-center w-full">
                                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 relative overflow-hidden">
                                        
                                        {{-- Tampilan Awal (Icon Upload) --}}
                                        <div id="upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span></p>
                                            <p class="text-xs text-gray-500">JPG, PNG (MAX. 2MB)</p>
                                        </div>

                                        {{-- Tampilan Preview (Awalnya Sembunyi) --}}
                                        <img id="preview-image" src="#" alt="Preview" class="hidden absolute inset-0 w-full h-full object-contain p-2 bg-white" />

                                        <input id="dropzone-file" type="file" name="bukti_bayar" class="hidden" required accept="image/*" onchange="previewFile(this)" />
                                    </label>
                                </div>
                            </div>

                            {{-- SCRIPT SEDERHANA UNTUK PREVIEW --}}
                            <script>
                                function previewFile(input) {
                                    var file = input.files[0];
                                    if (file) {
                                        var reader = new FileReader();
                                        reader.onload = function(e) {
                                            document.getElementById('preview-image').src = e.target.result;
                                            document.getElementById('preview-image').classList.remove('hidden');
                                            document.getElementById('upload-placeholder').classList.add('hidden');
                                        }
                                        reader.readAsDataURL(file);
                                    }
                                }
                            </script>

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-150 flex justify-center items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                KIRIM BUKTI PEMBAYARAN
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-dynamic-component>