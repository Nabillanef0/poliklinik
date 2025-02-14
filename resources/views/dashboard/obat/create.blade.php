<div class="row p-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h5><b>{{ $title }}</b></h5>
            @isset($obat)
                <form action="/obat/{{ $obat->id }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
            @else
                <form action="/obat" method="POST" enctype="multipart/form-data">
            @endisset

                @csrf

                <label for="nama_obat">Nama Obat</label>
                <input type="text" id="nama_obat" name="nama_obat" class="form-control @error('nama_obat') is-invalid @enderror" placeholder="Nama Obat" value="{{ isset($obat) ? $obat->nama_obat : old('nama_obat') }}">
                @error('nama_obat')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <label for="kemasan">Kemasan</label>
                <textarea name="kemasan" id="kemasan" class="form-control @error('kemasan') is-invalid @enderror" placeholder="Kemasan">{{ isset($obat) ? $obat->kemasan : old('kemasan') }}</textarea>
                @error('kemasan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" class="form-control @error('harga') is-invalid @enderror" placeholder="Harga Obat" value="{{ isset($obat) ? $obat->harga : old('harga') }}">
                @error('harga')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <br>
                <!-- Button Back and Save -->
                <a href="/obat" class="btn btn-info mt-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save"></i> Simpan</button>

            </form>
            </div>
        </div>
    </div>
</div>
