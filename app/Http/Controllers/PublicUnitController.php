<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class PublicUnitController extends Controller
{
    /**
     * Menampilkan detail unit ke publik.
     */
    public function show(Unit $unit)
    {
        // Kirim data unit ke view
        return view('public.unit-show', compact('unit'));
    }
}