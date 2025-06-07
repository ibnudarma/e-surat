<?php

namespace App\Http\Controllers;

use App\Models\StatusSurat;
use App\Models\Surat;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Surat Masuk',
            'surat_masuk' => Surat::where('ditujukan', '=', auth()->user()->bagian->id)->with('pengirim','statusTerakhir')->get()
        ];

        return view('pages.surat_masuk.index', $data);
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
}
