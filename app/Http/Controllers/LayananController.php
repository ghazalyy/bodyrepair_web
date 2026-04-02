<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $keyword  = $request->get('search', '');
        $layanans = Layanan::when($keyword, fn($q) =>
                $q->where('nama_layanan', 'like', "%$keyword%")
                  ->orWhere('deskripsi', 'like', "%$keyword%")
            )
            ->latest()->paginate(10)->withQueryString();

        return view('layanan.index', compact('layanans', 'keyword'));
    }

    public function create()
    {
        return view('layanan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:150'],
            'harga'        => ['required', 'numeric', 'min:0'],
            'deskripsi'    => ['nullable', 'string', 'max:500'],
            'aktif'        => ['nullable', 'boolean'],
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'harga.required'        => 'Harga wajib diisi.',
            'harga.numeric'         => 'Harga harus berupa angka.',
            'harga.min'             => 'Harga tidak boleh negatif.',
        ]);

        $data['aktif'] = $request->boolean('aktif');
        Layanan::create($data);

        return redirect()->route('layanan.index')
            ->with('success', 'Data layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan)
    {
        return view('layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $data = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:150'],
            'harga'        => ['required', 'numeric', 'min:0'],
            'deskripsi'    => ['nullable', 'string', 'max:500'],
        ]);

        $data['aktif'] = $request->boolean('aktif');
        $layanan->update($data);

        return redirect()->route('layanan.index')
            ->with('success', 'Data layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        $layanan->delete();
        return redirect()->route('layanan.index')
            ->with('success', 'Data layanan berhasil dihapus.');
    }

    // API: untuk dropdown di form transaksi (AJAX)
    public function apiList()
    {
        $layanans = Layanan::aktif()->get(['id_layanan', 'nama_layanan', 'harga']);
        return response()->json($layanans);
    }
}
