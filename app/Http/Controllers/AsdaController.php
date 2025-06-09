<?php

namespace App\Http\Controllers;

use App\Models\LembarDisposisiSekda;
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
        
    }
}
