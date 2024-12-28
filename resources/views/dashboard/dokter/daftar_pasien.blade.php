<div class="row p-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5><b>{{ $title }}</b></h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftar_pasien as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->pasien->nama }}</td>
                                <td>{{ $item->keluhan }}</td>
                                <td>
                                    @if(!$item->periksa)
                                        <a href="{{ route('dokter.periksa', $item->id) }}" class="btn btn-primary"><i class="fas fa-stethoscope"></i> Periksa</a>
                                    @else
                                        <a href="{{ route('dokter.periksa', $item->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
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
