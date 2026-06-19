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
            ->orderByRaw("CASE status WHEN 'available' THEN 1 WHEN 'rented' THEN 2 WHEN 'maintenance' THEN 3 ELSE 4 END")
            ->orderBy('daily_price', 'asc')
            ->get();

        return view('welcome', compact('cars'));
    }
}
