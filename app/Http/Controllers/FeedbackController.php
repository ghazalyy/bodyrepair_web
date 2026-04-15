<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('feedback.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'tanggal'         => 'required|date|before_or_equal:today',
            'kesan_umum'      => 'required|string|max:2000',
            'fitur_baik'      => 'required|string|max:2000',
            'masalah'         => 'required|string|max:2000',
            'saran'           => 'required|string|max:2000',
        ]);

        return redirect()->route('feedback.index')
            ->with('success', 'Terima kasih! Lembar saran dan masukan Anda telah berhasil dikirim.');
    }
}
