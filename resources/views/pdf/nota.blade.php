<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E-Receipt #{{ $tagihan->id }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace; 
            font-family: sans-serif;
            color: #1f2937;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        
        /* HEADER KEREN DENGAN BACKGROUND */
        .header-bg {
            background-color: #4338ca; 
            height: 120px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .container {
            padding: 40px;
            position: relative;
        }
        .brand {
            color: white;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .brand-sub {
            color: #c7d2fe;
            font-size: 12px;
        }

        /* BOX UTAMA (KARTU) */
        .invoice-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* Bayangan halus (dompdf support terbatas) */
            border: 1px solid #e5e7eb;
            margin-top: 20px;
            overflow: hidden;
        }

        /* STATUS BADGE */
        .status-bar {
            text-align: right;
            padding: 20px;
            border-bottom: 1px solid #f3f4f6;
        }
        .badge-lunas {
            background-color: #d1fae5;
            color: #065f46;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            border: 1px solid #10b981;
        }

        /* ISI KONTEN */
        .content {
            padding: 30px;
        }
        
        /* INFO UTAMA (GRID 2 KOLOM) */
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-label { font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }
        .info-value { font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 15px; }

        /* TABEL RINCIAN */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th {
            background-color: #f9fafb;
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
            padding: 12px;
            text-align: left;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-table td {
            padding: 15px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        .details-table tr:last-child td { border-bottom: none; }

        /* TOTAL HIGHLIGHT */
        .total-row td {
            background-color: #4338ca;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        /* BARCODE AREA (Palsu utk visual, bisa diganti QR Asli) */
        .footer-section {
            background-color: #f9fafb;
            padding: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        .barcode {
            font-family: 'Courier New', Courier, monospace; 
            font-size: 24px;
            letter-spacing: 3px;
            color: #333;
            margin: 10px 0;
            opacity: 0.7;
        }
        .hash-code {
            font-size: 10px;
            color: #9ca3af;
            font-family: monospace;
        }
        .legal-text {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header-bg"></div>

    <div class="container">
        
        <table width="100%">
            <tr>
                <td>
                    <div class="brand">KOST BU ADAH</div>
                    <div class="brand-sub">Official Payment Receipt</div>
                </td>
                <td align="right" style="color:white;">
                    <div style="font-size: 10px; opacity: 0.8;">DATE</div>
                    <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($tagihan->updated_at)->format('d M Y') }}</div>
                </td>
            </tr>
        </table>

        <div class="invoice-card">
            
            <div class="status-bar">
                <span class="badge-lunas"> PAID / LUNAS</span>
            </div>

            <div class="content">
                <table class="info-table">
                    <tr>
                        <td width="50%" valign="top">
                            <div class="info-label">BILLED TO (PENYEWA)</div>
                            <div class="info-value">{{ $tagihan->sewa->penyewa->nama_lengkap }}</div>
                            
                            <div class="info-label">ROOM / UNIT</div>
                            <div class="info-value">{{ $tagihan->sewa->unit->name }}</div>
                        </td>
                        <td width="50%" valign="top" align="right">
                            <div class="info-label">RECEIPT NUMBER</div>
                            <div class="info-value">#INV-{{ \Carbon\Carbon::parse($tagihan->created_at)->format('Ym') }}-{{ str_pad($tagihan->id, 4, '0', STR_PAD_LEFT) }}</div>
                            
                            <div class="info-label">PAYMENT METHOD</div>
                            <div class="info-value">Bank Transfer / Cash</div>
                        </td>
                    </tr>
                </table>

                <table class="details-table">
                    <thead>
                        <tr>
                            <th width="60%">DESCRIPTION</th>
                            <th width="20%">PERIOD</th>
                            <th width="20%" align="right">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Sewa Kost Bulanan</strong><br>
                                <span style="color:#6b7280; font-size: 11px;">Termasuk biaya kebersihan & keamanan</span>
                            </td>
                            <td>{{ $tagihan->bulan }}</td>
                            <td align="right">Rp {{ number_format($tagihan->jumlah ?? $tagihan->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                        
                        <tr class="total-row">
                            <td colspan="2" align="right" style="padding-right: 20px;">TOTAL PAID</td>
                            <td align="right">Rp {{ number_format($tagihan->jumlah ?? $tagihan->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="footer-section">
                <div class="barcode">||| || ||||| ||| || |||| |||</div>
                
                <div class="hash-code">
                    VERIFICATION ID: {{ md5($tagihan->id . $tagihan->created_at . 'SECRET') }}
                </div>

                <p class="legal-text">
                    Dokumen ini adalah bukti pembayaran yang sah yang diterbitkan oleh Sistem Kost Bu Adah.<br>
                    Simpan dokumen ini sebagai referensi transaksi Anda.
                </p>
            </div>
        </div>

        <div style="text-align: center; color: #6b7280; font-size: 11px; margin-top: 20px;">
            Jl. Alternatif Cikopak RT 29 / 01, Desa Mulyamekar, Kec. Babakancikao, Purwakarta, Jawa Barat | 0819-1262-2728
        </div>
    </div>
</body>
</html>