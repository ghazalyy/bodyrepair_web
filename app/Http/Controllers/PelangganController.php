<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search', '');
        $pelanggans = Pelanggan::when($keyword, fn($q) =>
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('no_telepon', 'like', "%$keyword%")
                  ->orWhere('alamat', 'like', "%$keyword%")
            )
            ->latest()->paginate(10)->withQueryString();

        return view('pelanggan.index', compact('pelanggans', 'keyword'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'        => ['required', 'string', 'max:100'],
            'alamat'      => ['nullable', 'string', 'max:500'],
            'no_telepon'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ], [
            'nama.required'         => 'Nama pelanggan wajib diisi.',
            'no_telepon.regex'      => 'Nomor telepon hanya boleh berisi angka, +, -, atau spasi.',
        ]);

        Pelanggan::create($data);
        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['transaksis' => fn($q) => $q->latest()->take(10)]);
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $data = $request->validate([
            'nama'        => ['required', 'string', 'max:100'],
            'alamat'      => ['nullable', 'string', 'max:500'],
            'no_telepon'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ]);

        $pelanggan->update($data);
        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }

    // API: Cari pelanggan untuk Select2 / autocomplete
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $results = Pelanggan::where('nama', 'like', "%$q%")
            ->orWhere('no_telepon', 'like', "%$q%")
            ->limit(10)->get(['id_pelanggan', 'nama', 'no_telepon']);

        return response()->json($results);
    }
}
