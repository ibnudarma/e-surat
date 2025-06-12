<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\StatusSurat;
use App\Models\Surat;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Str;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $userBagianId = auth()->user()->bagian->id;

        $query = Surat::query()
            ->where(function ($q) use ($userBagianId) {
                $q->where('ditujukan', $userBagianId)
                    ->orWhereHas('disposisiSekda', function ($q) use ($userBagianId) {
                        $q->where('ditujukan', $userBagianId);
                    })
                    ->orWhereHas('disposisiAsda', function ($q) use ($userBagianId) {
                        $q->where('ditujukan', $userBagianId);
                    })
                    ->orWhereHas('kartuDisposisi', function ($q) use ($userBagianId) {
                        $q->where('ditujukan', $userBagianId);
                    });
        });

        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('tgl_surat', [$request->startDate, $request->endDate]);
        }

        if ($request->filled('bagian') && $request->bagian !== '') {
            $query->where('bagian_id', $request->bagian);
        }

        if ($request->filled('tipe') && $request->bagian !== '') {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('tipe') && $request->tipe !== '') {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('nomor')) {
        $query->where('nomor', 'like', '%' . $request->nomor . '%');
        }

        $surat_masuk = $query->with(['pengirim', 'statusTerakhir'])->orderBy('created_at', 'desc')->get();
        
        $data = [
            'title' => 'Surat Masuk',
            'bagian' => Bagian::where('id', '!=', $userBagianId)->get(),
            'request' => $request,
            'surat_masuk' => $surat_masuk
        ];

        // Kabag
        if(auth()->user()->bagian->id === 1){

            return view('pages.kabag.surat_masuk', $data);

        // Sekda
        }elseif (auth()->user()->bagian->id === 2){

            return view('pages.sekda.surat_masuk', $data);

        // Asda
        }elseif (auth()->user()->bagian->id === 3){

            return view('pages.asda.surat_masuk', $data);

        // BPKAD
        }elseif (auth()->user()->bagian->id === 4){

            return view('pages.bpkad.surat_masuk', $data);

        // BUMD
        }else{

            return view('pages.bumd.surat_masuk', $data);

        }

    }

    public function diterima($id)
    {
        $surat = Surat::where('id', $id)
        ->where('ditujukan', auth()->user()->bagian->id)
        ->firstOrFail();

        $statusSurat = new StatusSurat();
        $statusSurat->surat_id = $id;
        $statusSurat->bagian_id = auth()->user()->bagian->id;
        $statusSurat->status = 'diterima oleh ' . auth()->user()->bagian->nama_bagian;
        $statusSurat->color = 'success';

        DB::transaction(function() use ($surat, $statusSurat) {
            $surat->update([
                'tgl_diterima' => Carbon::now()
            ]);
            
            $statusSurat->save();
        });

        return back();
    }

    public function show($id)
    {
        // awalnya dibuat untuk validasi agar yang diliat hanya user yang punya akses saja
        $surat = Surat::where('id', $id)
            // ->where('ditujukan', auth()->user()->bagian->id)
            // ->orWhereHas('disposisiSekda', function ($query){ $query->where('ditujukan', auth()->user()->bagian->id);} )
            // ->orWhereHas('disposisiAsda', function ($query){ $query->where('ditujukan', auth()->user()->bagian->id);} )
            // ->orWhereHas('kartuDisposisi', function ($query){ $query->where('ditujukan', auth()->user()->bagian->id);} )
            ->firstOrFail();
        $data = [
            'title' => 'Detail Surat Masuk',
            'status_surat' => StatusSurat::where('surat_id', '=', $id)->get(),
            'surat' => $surat
        ];

        return view('pages.surat_masuk.show', $data);
    }

    public function reply($id)
    {
        $surat = Surat::where('id', $id)
            ->where('ditujukan', auth()->user()->bagian->id)
            ->firstOrFail();
        $data = [
            'title' => 'Balas Surat',
            'surat' => $surat
        ];

        return view('pages.surat_masuk.reply', $data);
    }

    public function replyStore(Request $request)
    {
        $validated = $request->validate([
            'noref' => ['required'],
            'ditujukan' => ['required'],
            'sifat' => ['required'],
            'lampiran' => ['nullable'],
            'perihal' => ['required'],
            'tgl_surat' => ['required'],
            'file' => ['required'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat', $filename, 'public');
        }

        $surat = new Surat();
        $surat->noref = $validated['noref'];
        $surat->bagian_id = auth()->user()->bagian->id;
        $surat->ditujukan = $validated['ditujukan'];
        $surat->sifat = $validated['sifat'];
        $surat->lampiran = $validated['lampiran'];
        $surat->perihal = $validated['perihal'];
        $surat->tgl_surat = $validated['tgl_surat'];
        $surat->file = $filePath;

        $penerima = Bagian::where('id', '=',$surat->ditujukan)->value('nama_bagian');
        $statusSurat = new StatusSurat();
        $statusSurat->bagian_id = auth()->user()->bagian->id;
        $statusSurat->status = 'menunggu diterima oleh ' . $penerima;
        $statusSurat->color = 'warning';

        $statusSurat2 = new StatusSurat();
        $statusSurat2->surat_id = $validated['noref'];
        $statusSurat2->bagian_id = auth()->user()->bagian->id;
        $statusSurat2->status = 'dibalas oleh ' . auth()->user()->bagian->nama_bagian;
        $statusSurat2->color = 'secondary';

        DB::transaction(function () use ($surat, $statusSurat, $statusSurat2) {
            $surat->save();
            $statusSurat->surat_id = $surat->id;
            $statusSurat->save();
            $statusSurat2->save();
        });

        return redirect('surat_keluar')->with('success', 'Berhasil membuat balasan surat');
    }
}
