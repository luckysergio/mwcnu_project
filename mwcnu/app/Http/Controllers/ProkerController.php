<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use Illuminate\Http\Request;

class ProkerController extends Controller
{
    public function proker_request()
    {
        $prokers = Proker::where('status', 'pengajuan')->get();

        return view('pages.programkerja.request',[
            'prokers' => $prokers,
        ]);
    }

    public function index()
    {
        $query = Proker::query();

        if(request('status'))
        {
            $query->where('status', request('status'));
        }

        $prokers = $query->orderBy('created_at','desc')->paginate(10);

        return view('pages.programkerja.index', compact('prokers'));
    }
}
