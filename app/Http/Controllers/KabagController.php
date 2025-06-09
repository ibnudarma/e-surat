<?php

namespace App\Http\Controllers;

use App\Models\KartuDisposisi;
use App\Models\LembarDisposisiSekda;
use App\Models\StatusSurat;
use App\Models\Surat;
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

        $disposisiSekda = LembarDisposisiSekda::where('surat_id', '=', $validated['surat_id'])->exists();
        if($disposisiSekda){
            dd($disposisiSekda);
        }

        $kartuDisposisi = new KartuDisposisi();
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
}
