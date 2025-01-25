<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hitung jumlah total karyawan
        $totalKaryawan = Karyawan::count();

        // Hitung jumlah karyawan yang sudah absen hari ini
        $tanggalHariIni = Carbon::now()->toDateString();
        $karyawanAbsenHariIni = Absensi::whereDate('tanggal', $tanggalHariIni)->distinct('karyawan_id')->count('karyawan_id');

        $absensis = Absensi::with('karyawan')
            ->whereDate('tanggal', $tanggalHariIni)
            ->get();

        // Hitung total jam kerja per karyawan
        $totalJamKerjaPerKaryawan = $absensis->groupBy('karyawan_id')->map(function ($absensi) {
            return $absensi->sum(function ($record) {
                if ($record->jam_masuk && $record->jam_keluar) {
                    return Carbon::parse($record->jam_keluar)->diffInHours(Carbon::parse($record->jam_masuk));
                }
                return 0;
            });
        });

        $data = Absensi::with('karyawan')->whereDate('tanggal', $tanggalHariIni)->get();

        return view('dashboard', compact('totalKaryawan', 'karyawanAbsenHariIni','totalJamKerjaPerKaryawan','data'));
    }
}
