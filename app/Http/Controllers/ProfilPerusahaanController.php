<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;

class ProfilPerusahaanController extends Controller
{
    //
    public function index(){
       $profil = ProfilPerusahaan::all()->count();
       if($profil<=0){
            $create = ProfilPerusahaan::create(['nama_perusahaan' => '-', 'badan_hukum' => '-', 'alamat' => '-','no_telp' => '-','latitude' => '-', 'longitude' => '-','radius' => '5','status'=>'nonaktif']);
       }
       $profil = ProfilPerusahaan::find(1);
       return view('profilperusahaan.index', compact('profil'));
    }
    public function edit()
    {
        $profil = ProfilPerusahaan::find(1);
        return view('profilperusahaan.edit', compact('profil'));
    }
    public function update(Request $request)
    {
        $profil = ProfilPerusahaan::find(1);

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'badan_hukum' => 'required|string|max:255',
            'alamat' => 'required',
            'no_telp' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
            'status' => 'required',
        ]);

        $profil->nama_perusahaan = $request->nama_perusahaan;
        $profil->badan_hukum = $request->badan_hukum;
        $profil->alamat = $request->alamat;
        $profil->no_telp = $request->no_telp;
        $profil->latitude = $request->latitude;
        $profil->longitude = $request->longitude;
        $profil->radius = $request->radius;
        $profil->status = $request->status;
        $profil->save();

        return redirect()->route('profilperusahaan.index')->with('success', 'Profil updated successfully.');
    }
}
