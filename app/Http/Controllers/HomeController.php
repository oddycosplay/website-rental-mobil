<?php

namespace App\Http\Controllers;

use App\Models\Car;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman beranda dengan seluruh daftar mobil yang tersedia.
     */
    public function index()
    {
        $cars = Car::query()
            ->orderByRaw("FIELD(status, 'available', 'rented', 'maintenance')")
            ->orderBy('daily_price', 'asc')
            ->get();

        return view('welcome', compact('cars'));
    }
}
