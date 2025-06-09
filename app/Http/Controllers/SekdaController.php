<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\LembarDisposisiSekda;
use App\Models\StatusSurat;
use App\Models\Surat;
use DB;
use Illuminate\Http\Request;

class SekdaController extends Controller
{
    public function __construct()
    {
        if(auth()->user()->bagian->id !== 2){
            abort(404);
        }
    }

    public function disposisiCreate($id)
    {
        $sudahDiDisposisi = LembarDisposisiSekda::where('surat_id', '=', $id)->exists();
        if($sudahDiDisposisi){
            return back()->with('error', 'Sudah Disiposisikan');
        }
        $data = [
            'title' => 'Disposisi Sekda',
            'surat' => Surat::findOrFail($id)
        ];

        return view('pages.sekda.disposisi_create', $data);
    }

    public function disposisiStore(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'surat_id' => ['required'],
            'ditujukan' => ['required'],
            'nomor_agenda' => ['required'],
            'sifat' => ['required'],
            'intruksi' => ['required'],
            'catatan' => ['required'],
        ]);

        $disposisiSekda = new LembarDisposisiSekda();
        $disposisiSekda->surat_id = $validated['surat_id'];
        $disposisiSekda->ditujukan = (int) $validated['ditujukan'];
        $disposisiSekda->nomor_agenda = $validated['nomor_agenda'];
        $disposisiSekda->sifat = $validated['sifat'];
        $disposisiSekda->intruksi = $validated['intruksi'];
        $disposisiSekda->catatan = $validated['catatan'];

        $penerima = Bagian::where('id', '=',(int)$validated['ditujukan'])->value('nama_bagian');
        $statusSurat = new StatusSurat();
        $statusSurat->surat_id = $validated['surat_id'];
        $statusSurat->bagian_id = auth()->user()->bagian->id;
        $statusSurat->status = 'Didisposisikan ke ' . $penerima;
        $statusSurat->color = 'warning';

        DB::transaction(function () use ($disposisiSekda, $statusSurat) {
            $disposisiSekda->save();
            $statusSurat->save();
        });

        return redirect('surat_masuk')->with('success', 'Berhasil Didisposisikan');
    }
}
