<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\StatusSurat;
use App\Models\Surat;
use Auth;
use DB;
use Illuminate\Http\Request;
use Str;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Surat Keluar',
            'surat_keluar' => Surat::where('bagian_id', '=', Auth::user()->bagian->id)->with('penerima','statusTerakhir')->get()
        ];

        return view('pages.surat_keluar.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Surat Keluar',
            'bagian' => Bagian::select('id', 'nama_bagian')->where('id','!=', Auth::user()->bagian->id)->get()
        ];

        return view('pages.surat_keluar.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe' => ['nullable', 'in:umum,permohonan'],
            'ditujukan' => ['required', 'exists:bagian,id'],
            'sifat' => ['required', 'in:biasa,penting,segera,amat segera'],
            'lampiran' => ['nullable', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'tgl_surat' => ['required', 'date'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:3072'], // max:3072 = 3MB
        ], [
            'tipe.in' => 'Tipe surat harus salah satu dari: umum atau permohonan.',
            'ditujukan.required' => 'Tujuan surat harus dipilih.',
            'ditujukan.exists' => 'Tujuan tidak ditemukan dalam data bagian.',
            'sifat.required' => 'Sifat surat harus dipilih.',
            'sifat.in' => 'Sifat surat tidak valid.',
            'lampiran.max' => 'Lampiran maksimal 255 karakter.',
            'perihal.required' => 'Perihal surat harus diisi.',
            'perihal.max' => 'Perihal maksimal 255 karakter.',
            'tgl_surat.required' => 'Tanggal surat wajib diisi.',
            'tgl_surat.date' => 'Format tanggal tidak valid.',
            'file.required' => 'File surat harus diunggah.',
            'file.mimes' => 'File harus berupa PDF.',
            'file.max' => 'Ukuran file maksimal 3 MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat', $filename, 'public');
        }

        $surat = new Surat();
        $surat->tipe = $validated['tipe'];
        $surat->ditujukan = $validated['ditujukan'];
        $surat->sifat = $validated['sifat'];
        $surat->lampiran = $validated['lampiran'] ?? null;
        $surat->perihal = $validated['perihal'];
        $surat->tgl_surat = $validated['tgl_surat'];
        $surat->file = $filePath;
        $surat->bagian_id = auth()->user()->bagian->id;

        $bagian_id = auth()->user()->bagian->id;
        $penerima = Bagian::where('id', '=',$surat->ditujukan)->value('nama_bagian');
        $statusSurat = new StatusSurat();
        $statusSurat->bagian_id = $bagian_id;
        $statusSurat->status = 'menunggu diterima oleh ' . $penerima;

        DB::transaction(function () use ($surat, $statusSurat) {
        $surat->save();
        $statusSurat->surat_id = $surat->id; // diatur setelah save
        $statusSurat->save();
        });

        return redirect('surat_keluar')->with('success', 'Surat berhasil dikirim');
    }
    
}
