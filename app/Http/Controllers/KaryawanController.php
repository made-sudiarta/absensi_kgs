<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        // $karyawans = Karyawan::all();
        // return view('karyawan.index', compact('karyawans'));

        $search = $request->get('search');
        $karyawans = Karyawan::where('nama', 'like', '%' . $search . '%')
            ->orWhere('nomor_anggota', 'like', '%' . $search . '%')
            ->orderBy('nama', 'ASC')
            ->paginate(25);

        return view('karyawan.index', compact('karyawans', 'search'));
    }

    public function create()
    {
        $jabatans = Jabatan::all();
        return view('karyawan.create', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_anggota' => 'required|unique:karyawans',
            'nama' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Karyawan created successfully.');
    }

    public function edit(Karyawan $karyawan)
    {
        $jabatans = Jabatan::all();
        return view('karyawan.edit', compact('karyawan', 'jabatans'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nomor_anggota' => 'required|unique:karyawans,nomor_anggota,'.$karyawan->id,
            'nama' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        $karyawan->update($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan deleted successfully.');
    }
    public function form($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            // $absensi = Karyawan::where('nomor_anggota',$id)->get;
            // return view('absensi.form', compact('absensi'));
            return redirect()->route('absen.form.nomor_anggota')->with('nomor_anggota', $id);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Invalid ID');
        }
    }
}
