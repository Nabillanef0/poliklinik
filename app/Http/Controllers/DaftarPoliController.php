<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DaftarPoliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $id_pasien = Auth::user()->id_pasien;
        $data = [
            'title' => 'Riwayat Periksa',
            'daftar_poli' => DaftarPoli::where('id_pasien', $id_pasien)->get(),
            'content' => 'dashboard.daftar_poli.index'
        ];
        return view('layouts.wrapper', $data);
    }

    public function dataJadwalPeriksa(Request $request)
    {
        $query = JadwalPeriksa::with('dokter')->orderBy('created_at', 'desc');
        $query->where('status', 1);

        if ($request->has('hari')) {
            $query->where('hari', $request->input('hari'));
        }

        if ($request->has('id_dokter')) {
            $query->where('id_dokter', $request->input('id_dokter'));
        }

        if ($request->has('id_poli')) {
            $query->whereHas('dokter', function ($query) use ($request) {
                $query->where('id_poli', $request->id_poli);
            });
        }


        $jadwalPeriksas = $query->get();

        $dataJson = $jadwalPeriksas;

        return response()->json($dataJson);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data = [
            'title' => 'Daftar Poli',
            'polis' => Poli::all(),
            'content' => 'dashboard.daftar_poli.create'
        ];
        return view('layouts.wrapper', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $request->validate([
                'id_jadwal' => 'required',
                'keluhan' => 'required',
            ]);

            $no_antrian = DaftarPoli::count() + 1;
            $id_pasien = Auth::user()->id_pasien;

            DaftarPoli::create([
                'id_pasien' => $id_pasien,
                'id_jadwal' => $request->input('id_jadwal'),
                'keluhan' => $request->input('keluhan'),
                'no_antrian' => $no_antrian,
            ]);

            Alert::success('Success', 'Daftar Poli telah ditambahkan!!');
            return redirect('/daftar_poli');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = DaftarPoli::with('jadwalPeriksa.dokter.poli', 'periksa.detailPeriksas.obat', 'pasien')->findOrFail($id);

        $ids_obat = $data && $data->periksa && $data->periksa->detailPeriksas ? $data->periksa->detailPeriksas->pluck('id_obat')->map(function ($id) {
            return (string) $id;
        })->toArray() : [];

        $data = [
            'title' => 'Detail Periksa',
            'data' => $data,
            'ids_obat' => $ids_obat,
            'content' => 'dashboard.daftar_poli.show'
        ];
        
        return view('layouts.wrapper', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
