<div class="row p-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5><b>{{ $title }}</b></h5>
                <table class="table">
                    <tr>
                        <th>Poli</th>
                        <td>{{ $data->jadwalPeriksa->dokter->poli->nama_poli }}</td>
                    </tr>
                    <tr>
                        <th>Dokter</th>
                        <td>{{ $data->jadwalPeriksa->dokter->nama }}</td>
                    </tr>
                    <tr>
                        <th>No Antrian</th>
                        <td>{{ $data->no_antrian }}</td>
                    </tr>
                    <tr>
                        <th>Hari</th>
                        <td>{{ $data->jadwalPeriksa->hari }}</td>
                    </tr>
                    <tr>
                        <th>Jam Mulai</th>
                        <td>{{ $data->jadwalPeriksa->jam_mulai }}</td>
                    </tr>
                    <tr>
                        <th>Jam Selesai</th>
                        <td>{{ $data->jadwalPeriksa->jam_selesai }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($data->periksa)
                                <span class="badge badge-success">Sudah Diperiksa</span>
                            @else
                                <span class="badge badge-danger">Belum Diperiksa</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Keluhan</th>
                        <td>{{ $data->keluhan }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row p-2">
    <div class="col-md-12">
        <div class="card">
        @if($data->periksa)
            <div class="card-body">
            <h5><b>Riwayat Periksa</b></h5>
                <table class="table">
                    <tr>
                        <th>Tanggal Periksa</th>
                        <td>{{ \Carbon\Carbon::parse($data->periksa->tgl_periksa)->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>Catatan Dokter</th>
                        <td>{{ $data->periksa->catatan }}</td>
                    </tr>
                    <tr>
                        <th>Resep Obat</th>
                        <td>
                            @foreach ($data->periksa->detailPeriksas as $detail)
                                <p>Obat: {{ $detail->obat->nama_obat }} - Qty: {{ $detail->jumlah }} - Harga: {{ $detail->obat->harga }}</p>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Total Biaya Priksa</th>
                        <td>
                            <p>Rp. {{ number_format($data->periksa->biaya_periksa, 2) }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>
    <a href="/daftar_poli" class="btn btn-primary mt-3">Kembali</a>
</div>