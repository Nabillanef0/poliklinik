<div class="row p-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Edit Riwayat Pasien</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('dokter.updateRiwayat', $riwayat->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama Pasien</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $riwayat->pasien->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat Pasien</label>
                        <textarea name="alamat" id="alamat" class="form-control" required>{{ old('alamat', $riwayat->pasien->alamat) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="no_ktp">No KTP Pasien</label>
                        <input type="text" name="no_ktp" id="no_ktp" class="form-control" value="{{ old('no_ktp', $riwayat->pasien->no_ktp) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No HP Pasien</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $riwayat->pasien->no_hp) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="no_rm">No RM Pasien</label>
                        <input type="text" name="no_rm" id="no_rm" class="form-control" value="{{ old('no_rm', $riwayat->pasien->no_rm) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('dokter.periksa', $riwayat->id) }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
