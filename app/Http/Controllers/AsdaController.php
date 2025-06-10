<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\LembarDisposisiAsda;
use App\Models\LembarDisposisiSekda;
use App\Models\StatusSurat;
use App\Models\Surat;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AsdaController extends Controller
{
    public function __construct()
    {
        if(auth()->user()->bagian->id !== 3){
            abort(404);
        }
    }
    public function disposisiSekda()
    {
        $data = [
            'title' => 'Disposisi Sekda',
            'disposisi' => LembarDisposisiSekda::where('ditujukan', '=', auth()->user()->bagian->id)->get()
        ];

        return view('pages.asda.disposisi_sekda', $data); 
    }

    public function terimaDisposisiSekda($id)
    {
        $disposisiSekda = LembarDisposisiSekda::findOrFail($id);
        $disposisiSekda->tgl_diterima = Carbon::now();
        $statusSurat = new StatusSurat();
        $statusSurat->surat_id = $disposisiSekda->surat_id;
        $statusSurat->bagian_id = auth()->user()->bagian->id;
        $statusSurat->status = 'Diterima oleh Asda';
        $statusSurat->color = 'success';

        DB::transaction(function () use ($disposisiSekda, $statusSurat) {
            $disposisiSekda->save();
            $statusSurat->save();
        });
        return back();
    }

    public function disposisiCreate($id)
    {
        $sudahDiDisposisi = LembarDisposisiAsda::where('surat_id', '=', $id)->exists();
        if($sudahDiDisposisi){
            return back()->with('error', 'Sudah Disiposisikan');
        }
        $data = [
            'title' => 'Disposisi Sekda',
            'surat' => Surat::findOrFail($id)
        ];

        return view('pages.asda.disposisi_create', $data);
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

        $disposisiAsda = new LembarDisposisiAsda();
        $disposisiAsda->surat_id = $validated['surat_id'];
        $disposisiAsda->ditujukan = (int) $validated['ditujukan'];
        $disposisiAsda->nomor_agenda = $validated['nomor_agenda'];
        $disposisiAsda->sifat = $validated['sifat'];
        $disposisiAsda->intruksi = $validated['intruksi'];
        $disposisiAsda->catatan = $validated['catatan'];

        $penerima = Bagian::where('id', '=',(int)$validated['ditujukan'])->value('nama_bagian');
        $statusSurat = new StatusSurat();
        $statusSurat->surat_id = $validated['surat_id'];
        $statusSurat->bagian_id = auth()->user()->bagian->id;
        $statusSurat->status = 'Didisposisikan ke ' . $penerima;
        $statusSurat->color = 'warning';

        DB::transaction(function () use ($disposisiAsda, $statusSurat) {
            $disposisiAsda->save();
            $statusSurat->save();
        });

        return redirect('surat_masuk')->with('success', 'Berhasil Didisposisikan');
    }
}
