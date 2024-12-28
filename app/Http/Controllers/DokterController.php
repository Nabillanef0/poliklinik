<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileDokterRequest;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = [
            'title' => 'Manajemen Dokter',
            'dokter' => Dokter::get(),
            'content' => 'dashboard.dokter.index'
        ];
        return view('layouts.wrapper', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Dokter',
            'content' => 'dashboard.dokter.create',
            'polis' => Poli::all(),
        ];
        return view('layouts.wrapper', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'nama' => 'required|min:1|max:255|unique:users,name',
            'alamat' => 'required|min:1|max:255',
            'no_hp' => 'required|min:1|max:15',
            'id_poli' => 'required|exists:polis,id',
        ]);

        $d = Dokter::create($data);
        User::create([
            'name' => $data['nama'],
            'email' => $data['nama'],
            'password' => bcrypt($data['alamat']),
            'id_dokter' => $d->id,
            'role' => 'dokter'
        ]);

        Alert::success('Success', 'Data Poli telah ditambahkan!!');
        return redirect('/dokter')->with('success', 'Data Dokter telah ditambahkan!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = [
            'dokter' => Dokter::findOrFail($id),
            'content' => 'dashboard.dokter.show'
        ];
        return view('layouts.wrapper', $data);
    }

    // fungsi daftar pasien
    public function daftarPasien()
    {
        // dd('cek');
        $id_pasien = Auth::user()->id_pasien;

        $daftarPolis = DaftarPoli::with('jadwalPeriksa.dokter.poli', 'periksa', 'pasien');

        if (Auth::user()->role == 'dokter') {
            $daftarPolis->whereHas('jadwalPeriksa.dokter', function ($query) {
                $query->where('id', Auth::user()->id_dokter);
            });
        } elseif (Auth::user()->role == 'pasien') {
            $daftarPolis->where('id_pasien', $id_pasien);
        }

        $da = $daftarPolis->orderBy('no_antrian', 'asc')->get();

        $data = [
            'title' => 'Daftar Pasien',
            'daftar_pasien' => $da,
            'content' => 'dashboard.dokter.daftar_pasien',
        ];

        return view('layouts.wrapper', $data);
    }

    public function formPeriksa(Request $request, $id_daftar_poli)
    {
        try {
            // Validasi input
            $request->validate([
                'biaya_pemeriksaan' => 'required|numeric',
                'tgl_periksa' => 'required|date',
                'catatan' => 'nullable|string',
                'id_obat_selected' => 'required|array',
                'dokter' => 'required|exists:dokters,id',
            ]);

            // Ambil data dari request
            $id_obat_selected = $request->id_obat_selected;
            $biaya_pemeriksaan = $request->biaya_pemeriksaan;
            $tgl_periksa = $request->tgl_periksa;
            $catatan = $request->catatan;
            $dokter = $request->dokter;

            // Simpan atau perbarui data pemeriksaan
            $periksa = Periksa::updateOrCreate(
                ['id_daftar_poli' => $id_daftar_poli],
                [
                    'biaya_periksa' => $biaya_pemeriksaan,
                    'tgl_periksa' => $tgl_periksa,
                    'catatan' => $catatan,
                    'dokter' => $dokter,
                ]
            );

            // Hapus detail pemeriksaan lama dan tambahkan yang baru
            DetailPeriksa::where('id_periksa', $periksa->id)->delete();
            foreach ($id_obat_selected as $id_obat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $id_obat,
                ]);
            }

            // Redirect ke halaman detail pendaftaran poli
            return redirect()->route('dokter.daftar_pasien')->with('success', 'Pemeriksaan berhasil disimpan.');
        } catch (\Throwable $th) {
            // Tangani error
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $th->getMessage()]);
        }
    }

    public function periksa($id) {
        $data = DaftarPoli::with('jadwalPeriksa.dokter.poli', 'periksa.detailPeriksas.obat', 'pasien')->find($id);
        $ids_obat = $data && $data->periksa && $data->periksa->detailPeriksas ? $data->periksa->detailPeriksas->pluck('id_obat')->map(function ($id) {
            return (string) $id;
        })->toArray() : [];
        $obats = Obat::get();

        $data = [
            'title' => 'Periksa Pasien',
            'data' => $data,
            'ids_obat' => $ids_obat,
            'obats' => $obats,
            'content' => 'dashboard.dokter.form_periksa',
        ];

        // dd($data['data']->periksa->tgl_periksa);

        return view('layouts.wrapper', $data);
    }

    public function riwayat_periksa_detail($id) {
        $data = DaftarPoli::with('jadwalPeriksa.dokter.poli', 'periksa.detailPeriksas.obat', 'pasien')->find($id);
        $ids_obat = $data && $data->periksa && $data->periksa->detailPeriksas ? $data->periksa->detailPeriksas->pluck('id_obat')->map(function ($id) {
            return (string) $id;
        })->toArray() : [];
        $obats = Obat::get();

        $data = [
            'title' => 'Periksa Pasien',
            'data' => $data,
            'ids_obat' => $ids_obat,
            'obats' => $obats,
            'content' => 'dashboard.dokter.riwayat_pasien_detail',
        ];

        // dd($data['data']->periksa->tgl_periksa);

        return view('layouts.wrapper', $data);
    }

    // fungsi periksa pasien
    public function periksaPasien(Request $request, $id)
    {
        try {
            $allRequest = $request->all();
            $id_daftar_poli = $allRequest['id_daftar_poli'];
            $id_obat_selected = json_decode($allRequest['id_obat_selected']);
            $biaya_pemeriksaan = $allRequest['biaya_pemeriksaan'];
            $tgl_periksa = $allRequest['tgl_periksa'];
            $catatan = $allRequest['catatan'];

            $periksa = Periksa::updateOrCreate(
                ['id_daftar_poli' => $id_daftar_poli],
                [
                    'biaya_periksa' => $biaya_pemeriksaan,
                    'tgl_periksa' => $tgl_periksa,
                    'catatan' => $catatan,
                ]
            );

            DetailPeriksa::where('id_periksa', $periksa->id)->delete();
            foreach ($id_obat_selected as $id_obat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $id_obat,
                ]);
            }

            return redirect("/periksa/$id")->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function riwayatPasien()
    {
        $riwayatPasien = DaftarPoli::with('pasien', 'jadwalPeriksa.dokter', 'periksa.detailPeriksas.obat')
            ->whereHas('periksa', function ($query) {
                $query->whereNotNull('tgl_periksa');
            })
            ->orderBy('updated_at', 'desc')
            ->get();

            // dd($riwayatPasien);

        $data = [
            'title' => 'Riwayat Pasien',
            'riwayat_pasien' => $riwayatPasien,
            'content' => 'dashboard.dokter.riwayat_pasien',
        ];

        return view('layouts.wrapper', $data);
    }

    public function detailRiwayatPasien($id_daftar_poli)
    {
        // Ambil detail riwayat pemeriksaan berdasarkan id_daftar_poli
        $riwayat = DaftarPoli::with('pasien', 'jadwalPeriksa.dokter.poli', 'periksa.detailPeriksas.obat')
            ->findOrFail($id_daftar_poli);

        $data = [
            'title' => 'Detail Riwayat Pemeriksaan',
            'riwayat' => $riwayat,
            'content' => 'dashboard.dokter.detail_riwayat_pasien',
        ];

        return view('layouts.wrapper', $data);
    }

    public function updateProfile(ProfileDokterRequest $request, $id)
    {
        $dokter = Dokter::findOrFail($id);

        // Update dokter
        $dokter->update($request->validated());

        // Update user
        $user = User::where('id_dokter', $id)->first();
        $user->update([
            'name' => $request->nama,
            'email' => $request->nama,
        ]);

        Alert::success('Success', 'Data Dokter telah diperbarui!!');
        return redirect('/profile-dokter')->with('success', 'Data Dokter telah diperbarui!!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data = [
            'dokter' => Dokter::find($id),
            'content' => 'dashboard.dokter.edit',
            'polis' => Poli::all(),
        ];
        return view('layouts.wrapper', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $dokter = Dokter::find($id);
        $data = $request->validate([
            'nama' => 'required|min:1|max:255',
            'alamat' => 'required|min:1|max:255',
            'no_hp' => 'required|min:1|max:15',
            'id_poli' => 'required|exists:polis,id',
        ]);

        $dokter->update($data);

        $user = User::where('id_dokter', $id)->first();
        $user->update([
            'name' => $request->nama,
            'email' => $request->nama,
            'password' => bcrypt($request->alamat)
        ]);
        
        Alert::success('Success', 'Data Dokter telah diperbarui!!');
        return redirect('/dokter')->with('success', 'Data Dokter telah diperbarui!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $dokter = Dokter::find($id);
        $dokter->delete();
        Alert::success('Success', 'Data Dokter telah dihapus!!');
        return redirect('/dokter')->with('success', 'Data Dokter telah dihapus!!');
    }
}
