<?php

namespace App\Http\Controllers;

use App\Models\JadwalProker;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jadwals = JadwalProker::with('proker')
            ->whereNotNull('estimasi_mulai')
            ->whereNotNull('estimasi_selesai')
            ->get();

        $events = [];

        foreach ($jadwals as $jadwal) {
            $events[] = [
                'title' => $jadwal->proker->judul,
                'start' => $jadwal->estimasi_mulai,
                'end'   => $jadwal->estimasi_selesai,
                'status'=> $jadwal->status,
            ];
        }

        return view('pages.dashboard.index', compact('events'));
    }
}
