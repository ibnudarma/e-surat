<?php

namespace App\Http\Controllers;

use App\Models\KartuDisposisi;
use App\Models\LembarDisposisiAsda;
use App\Models\LembarDisposisiSekda;
use App\Models\StatusSurat;
use App\Models\Surat;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class KabagController extends Controller
{
    public function __construct()
    {
        if(auth()->user()->bagian->id !== 1){
            abort(404);
        }
    }

    public function kartuDisposisiCreate($id)
    {
        $data = [
            'title' => 'Kartu Disposisi Create',
            'surat' => Surat::findOrFail($id)
        ];

        return view('pages.kabag.kartu_disposisi_create', $data);
    }

    public function kartuDisposisiStore(Request $request)
    {
        $validated = $request->validate([
            'surat_id' => ['required'],
            'index' => ['required'],
            'tgl_penyelesaian' => ['required'],
            'diteruskan' => ['required'],
            'keputusan' => ['required'],
            'catatan' => ['required'],
        ]);

        $kartuDisposisi = new KartuDisposisi();

        $surat = Surat::findOrFail($validated['surat_id']);

        if($surat->disposisiSekda){
            $kartuDisposisi->lembar_disposisi_sekda_id = $surat->disposisiSekda->id;
        }
        if($surat->disposisiAsda){
            $kartuDisposisi->lembar_disposisi_asda_id = $surat->disposisiAsda->id;
        }

        $kartuDisposisi->surat_id = (int)$validated['surat_id'];
        $kartuDisposisi->index = $validated['index'];
        $kartuDisposisi->tgl_penyelesaian = $validated['tgl_penyelesaian'];
        $kartuDisposisi->diteruskan = $validated['diteruskan'];
        $kartuDisposisi->keputusan = $validated['keputusan'];
        $kartuDisposisi->catatan = $validated['catatan'];

        $statusSurat = new StatusSurat();
        $statusSurat->surat_id = $validated['surat_id'];
        $statusSurat->bagian_id = auth()->user()->bagian->id;
        $statusSurat->status = 'Dibuatkan Kartu Disposisi';
        $statusSurat->color = 'success';

        DB::transaction(function () use ($kartuDisposisi, $statusSurat) {
            $kartuDisposisi->save();
            $statusSurat->save();
        });

        return redirect('surat_masuk')->with('success', 'Berhasil membuat Kartu Disposisi');
    }

    public function terimaDisposisiSekda($id)
    {
        $disposisiSekda = LembarDisposisiSekda::findOrFail($id);
        $disposisiSekda->tgl_diterima = Carbon::now();
        $statusSurat = new StatusSurat();
        $statusSurat->surat_id = $disposisiSekda->surat_id;
        $statusSurat->bagian_id = auth()->user()->bagian->id;
        $statusSurat->status = 'Diterima oleh Kabag';
        $statusSurat->color = 'success';

        DB::transaction(function () use ($disposisiSekda, $statusSurat) {
            $disposisiSekda->save();
            $statusSurat->save();
        });
        return back();
    }

    public function terimaDisposisiAsda($id)
    {
        $disposisiAsda = LembarDisposisiAsda::findOrFail($id);
        $disposisiAsda->tgl_diterima = Carbon::now();
        $statusSurat = new StatusSurat();
        $statusSurat->surat_id = $disposisiAsda->surat_id;
        $statusSurat->bagian_id = auth()->user()->bagian->id;
        $statusSurat->status = 'Diterima oleh Kabag';
        $statusSurat->color = 'success';

        DB::transaction(function () use ($disposisiAsda, $statusSurat) {
            $disposisiAsda->save();
            $statusSurat->save();
        });
        return back();
    }

    public function kartuDisposisiView($id)
    {
        
        $data = [
            'title' => 'Kartu Disposisi View',
            'ks' => KartuDisposisi::findOrFail($id)
        ];

        return view('pages.kabag.kartu_disposisi_view', $data);
    }
}
