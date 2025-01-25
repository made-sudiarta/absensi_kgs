<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        // $jabatans = Jabatan::all();
        // return view('jabatan.index', compact('jabatans'));

        $search = $request->get('search');
        $jabatans = Jabatan::where('nama_jabatan', 'like', '%' . $search . '%')
            ->orWhere('keterangan', 'like', '%' . $search . '%')
            ->orderBy('nama_jabatan', 'ASC')
            ->paginate(25);

        return view('jabatan.index', compact('jabatans', 'search'));
    }

    public function create()
    {
        return view('jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required',
        ]);

        Jabatan::create($request->all());

        return redirect()->route('jabatan.index')->with('success', 'Jabatan created successfully.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'nama_jabatan' => 'required',
        ]);

        $jabatan->update($request->all());

        return redirect()->route('jabatan.index')->with('success', 'Jabatan updated successfully.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return redirect()->route('jabatan.index')->with('success', 'Jabatan deleted successfully.');
    }
}
