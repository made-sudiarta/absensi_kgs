<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use DateTime;
use Auth;


class AbsensiController extends Controller
{

    public function index(Request $request)
    {
        // $tgl = Carbon::now()->toDateString();
        // $absensis = Absensi::with('karyawan')->OrderBy('tanggal','DESC')->get();
        // return view('absensi.index', compact('absensis'));

        $search = $request->input('search');
        $query = Absensi::with('karyawan')->orderBy('tanggal', 'DESC');

        if ($search) {
            $query->whereHas('karyawan', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        $absensis = $query->paginate(20);

        return view('absensi.index', compact('absensis'));
    }

    public function create()
    {
        $today = Carbon::now()->toDateString();
        $karyawan = Karyawan::all();
        return view('absensi.create', compact('karyawan','today'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable',
            'jam_keluar' => 'nullable',
        ]);

        $data = $request->all();
        if ($request->jam_masuk && $request->jam_keluar) {
            $jamMasuk = Carbon::parse($request->jam_masuk);
            $jamKeluar = Carbon::parse($request->jam_keluar);

            $selisih = $jamKeluar->diffInSeconds($jamMasuk);
            $selisih = gmdate('H:i:s',$selisih);
            function decimalHours($time)
            {
                $hms = explode(":", $time);
                return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
            }
            $data['jumlah_jam_kerja'] = decimalHours($selisih);
        }

        Absensi::create($data);

        return redirect()->route('absensi.index')->with('success', 'Absensi created successfully.');
    }

    public function edit(Absensi $absensi)
    {
        $karyawans = Karyawan::all();
        return view('absensi.edit', compact('absensi', 'karyawans'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable',
            'jam_keluar' => 'nullable',
        ]);

        $data = $request->all();
        if ($request->jam_masuk && $request->jam_keluar) {
            $jamMasuk = Carbon::parse($request->jam_masuk);
            $jamKeluar = Carbon::parse($request->jam_keluar);

            $selisih = $jamKeluar->diffInSeconds($jamMasuk);
            $selisih = gmdate('H:i:s',$selisih);
            function decimalHours($time)
            {
                $hms = explode(":", $time);
                return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
            }
            $data['jumlah_jam_kerja'] = decimalHours($selisih);
        }

        $absensi->update($data);

        return redirect()->route('absensi.index')->with('success', 'Absensi updated successfully.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('absensi.index')->with('success', 'Absensi deleted successfully.');
    }
    // Metode untuk menampilkan form laporan
    public function laporan()
    {
        return view('absensi.laporan');
    }

    // Metode untuk menghasilkan laporan
    public function generateLaporan(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // $data = Absensi::with('karyawan')
        //         ->whereBetween('tanggal', [$startDate, $endDate])
        //         ->groupBy('karyawan_id')
        //         ->get();
        
        // return view('absensi.laporan_result', compact('data', 'startDate','endDate'));

        $absensis = Absensi::with('karyawan')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        // Hitung total jam kerja per karyawan
        $totalJamKerjaPerKaryawan = $absensis->groupBy('karyawan_id')->map(function ($absensi) {
            return $absensi->sum(function ($record) {
                if ($record->jam_masuk && $record->jam_keluar) {
                    return $record->jumlah_jam_kerja;
                }
                return 0;
            });
        });

        return view('absensi.laporan_result', compact('absensis', 'startDate', 'endDate', 'totalJamKerjaPerKaryawan'));
    }

    public function showAbsenFormByNomorAnggota($nomor_anggota)
{
    try {
        $nomor_anggota = Crypt::decryptString($nomor_anggota);
        // return $nomor_anggota;
    } catch (\Exception $e) {
        return redirect()->route('not-found');
    }
    // $nomor_anggota = Crypt::decryptString($nomor_anggota);
    $karyawan = Karyawan::where('nomor_anggota', $nomor_anggota)->first();

    if (!$karyawan) {
        // return redirect()->route('home')->with('error', 'Nomor anggota tidak ditemukan.');
        return redirect()->route('not-found');
    }

    $today = Carbon::now()->toDateString();

    $absensi = Absensi::where('karyawan_id', $karyawan->id)
                      ->whereDate('tanggal', $today)
                      ->first();

    $profil = ProfilPerusahaan::find(1);

    return view('absensi.form', compact('karyawan', 'absensi','profil'));
}

public function handleAbsenByNomorAnggota(Request $request, $nomor_anggota)
{
    
    
    $karyawan = Karyawan::where('nomor_anggota', $nomor_anggota)->first();

    if (!$karyawan) {
        // return redirect()->route('home')->with('error', 'Nomor anggota tidak ditemukan.');
        return redirect()->route('not-found');
    }

    $today = Carbon::now()->toDateString();
    $timeNow = Carbon::now()->toTimeString();
    

    $absensi = Absensi::where('karyawan_id', $karyawan->id)
                      ->whereDate('tanggal', $today)
                      ->first();

    if (!$absensi) {
        // Absen masuk
        if(Carbon::now()->toTimeString() <= "08:00:00"){
            $jam = "08:00:00";
        }elseif(Carbon::now()->toTimeString() >= "16:00:00"){
            $jam = "16:00:00";
        }else{
            $jam = $timeNow;
        }
        Absensi::create([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $today,
            'jam_masuk' => $jam,
        ]);
    } elseif ($absensi && !$absensi->jam_keluar) {
        // Absen keluar
        if(Carbon::now()->toTimeString() <= "08:00:00"){
            $jam = "08:00:00";
        }elseif(Carbon::now()->toTimeString() >= "16:00:00"){
            $jam = "16:00:00";
        }else{
            $jam = $timeNow;
        }
        $absensi->update([
            'jam_keluar' => $jam,
        ]);
        
        if($timeNow < $absensi->jam_masuk){
            $absensi->update([
                'jumlah_jam_kerja' => 0,
            ]);
        }else{

            //JUMLAH JAM KERJA
            $jamMasuk = Carbon::parse($absensi->jam_masuk);
            $jamKeluar = Carbon::parse($jam);

            $selisih = $jamKeluar->diffInSeconds($jamMasuk);
            $selisih = gmdate('H:i:s',$selisih);
            function decimalHours($time)
            {
                $hms = explode(":", $time);
                return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
            }
            $data['jumlah_jam_kerja'] = decimalHours($selisih);

            $absensi->update([
                'jumlah_jam_kerja' => decimalHours($selisih),
            ]);
        }
    }
    $nomor_anggota = Crypt::encryptString($nomor_anggota);

    return redirect()->route('absen.form.nomor_anggota', ['nomor_anggota' => $nomor_anggota])->with('success', 'Absensi berhasil.');
}

    public function masuk(Request $request){
        $karyawan = Karyawan::find($request->id);
        $today = Carbon::now()->toDateString();
        if(Carbon::now()->toTimeString() <= "08:00:00"){
            $timeNow = "08:00:00";
        }elseif(Carbon::now()->toTimeString() >= "16:00:00"){
            $timeNow = "16:00:00";
        }else{
            $timeNow = Carbon::now()->toTimeString();
        }
        
        Absensi::create([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $today,
            'jam_masuk' => $timeNow,
        ]);
        return redirect()->route('absensi.index');

    }

    public function keluar(Request $request){
        $karyawan = Karyawan::find($request->id);
        $today = Carbon::now()->toDateString();
        if(Carbon::now()->toTimeString() <= "08:00:00"){
            $timeNow = "08:00:00";
        }elseif(Carbon::now()->toTimeString() >= "16:00:00"){
            $timeNow = "16:00:00";
        }else{
            $timeNow = Carbon::now()->toTimeString();
        }

        $absensi = Absensi::where('karyawan_id', $request->id)
                      ->whereDate('tanggal', $today)
                      ->first();

        $absensi->update([
            'jam_keluar' => $timeNow,
        ]);

        //JUMLAH JAM KERJA
        $jamMasuk = Carbon::parse($absensi->jam_masuk);
        $jamKeluar = Carbon::parse($timeNow);

        $selisih = $jamKeluar->diffInSeconds($jamMasuk);
        $selisih = gmdate('H:i:s',$selisih);
        function decimalHours($time)
        {
            $hms = explode(":", $time);
            return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
        }
        $data['jumlah_jam_kerja'] = decimalHours($selisih);

        $absensi->update([
            'jumlah_jam_kerja' => decimalHours($selisih),
        ]);
        return redirect()->route('absensi.index');

    }

    public function getServerTime()
    {
        return response()->json(['serverTime' => now()->timestamp]);
    }
}