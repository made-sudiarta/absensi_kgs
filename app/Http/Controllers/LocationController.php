<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan;

class LocationController extends Controller
{
    public function checkLocation(Request $request)
    {
        \Log::info('Received coordinates:', $request->all()); // Log data yang diterima

        $latitudeFrom = $request->input('latitude');
        $longitudeFrom = $request->input('longitude');

        // Lokasi yang diizinkan (latitude dan longitude)
        // -8.652003183114994, 115.18017391627274 RUMAH
        // -8.652174327056608, 115.18012875611062 TES
        $profil = ProfilPerusahaan::find(1);
        $latitudeTo = $profil->latitude; // Ganti dengan latitude lokasi yang diizinkan
        $longitudeTo = $profil->longitude; // Ganti dengan longitude lokasi yang diizinkan

        // Jarak dalam meter
        $distance = $this->haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);

        // Radius yang diizinkan dalam meter
        $radius = $profil->radius;

        if ($distance <= $radius) {
            \Log::info('Access granted'); // Log akses yang diberikan
            return response()->json(['access' => true]);
        } else {
            \Log::info('Access denied'); // Log akses yang ditolak
            return response()->json(['access' => false]);
        }
    }

    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // Menghitung jarak menggunakan formula Haversine
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
