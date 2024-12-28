<div class="row p-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5><b>{{ $title }}</b></h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No KTP</th>
                            <th>No HP</th>
                            <th>No RM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat_pasien as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->pasien->nama }}</td>
                            <td>{{ $item->pasien->alamat }}</td>
                            <td>{{ $item->pasien->no_ktp }}</td>
                            <td>{{ $item->pasien->no_hp }}</td>
                            <td>{{ $item->pasien->no_rm }}</td>
                            <td>
                                @if($item->periksa)
                                <a href="{{ route('dokter.riwayat_pasien_detail', $item->id) }}" class="btn btn-info"><i class="fas fa-eye"></i> Lihat Detail</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
