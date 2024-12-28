<div class="row p-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex gap-3 align-items-center">
                <h4 class="card-title mr-3">Detail Informasi</h4>
                <div>
                    <span class="badge {{ $data->periksa ? 'badge-success' : 'badge-danger' }}">
                        {{ $data->periksa ? 'Selesai' : 'Menunggu Pemeriksaan' }}
                    </span>
                </div>
            </div>
            <!--end card-header-->
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Dokter</td>
                            <td>{{ $data->jadwalPeriksa->dokter->nama }}</td>
                        </tr>
                        <tr>
                            <td>Keluhan</td>
                            <td>{{ $data->keluhan }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Periksa</td>
                            <td>{{ $data && $data->periksa ? \Carbon\Carbon::parse($data->periksa->tgl_periksa)->format('Y-m-d') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Catatan</td>
                            <td>{{ $data->periksa->catatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Total Biaya Pemeriksaan</td>
                            <td>Rp. {{ number_format($data->periksa->biaya_periksa ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Obat</td>
                            <td>
                                @if ($data->periksa)
                                    @foreach ($data->periksa->detailPeriksas as $detail)
                                        <p>{{ $detail->obat->nama_obat }} - Rp. {{ number_format($detail->obat->harga, 0) }}</p>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5><b>Form Pemeriksaan</b></h5>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="/periksa/upsert/{{ $data->id }}">
                    @csrf
                    <input type="hidden" name="id_daftar_poli" value="{{ $data->id }}">
                    <input type="hidden" name="id_obat_selected" value="{{ json_encode($ids_obat) }}">
                    <input type="hidden" name="list_obats" value="{{ json_encode($obats) }}">
                    <input type="hidden" name="biaya_pemeriksaan">

                    <div class="form-group">
                        <label for="tgl_periksa">Tanggal Pemeriksaan</label>
                        <input type="date" class="form-control" id="tgl_periksa" name="tgl_periksa" value={{ $data && $data->periksa ? $data->periksa->tgl_periksa : '' }} required>
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan Dokter</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" required>{{ $data && $data->periksa ? $data->periksa->catatan : '' }}</textarea>
                    </div>
                    <div id="obat-container">
                        <div class="form-group">
                            <label for="id_obat">Obat</label>
                            <select class="form-control" name="id_obat" id="id_obat" required>
                                @foreach ($obats as $obat)
                                    <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                                @endforeach
                            </select><br>
                            <button type="button" id="buttonAddObat" class="btn btn-primary" style="width: 150px"><i class="mdi mdi-plus me-2"></i>Tambah</button>
                        </div>
                        <div id="info-obat" class="form-group mt-3"></div>
                        <div class="form-group">
                            <label for="biaya_pemeriksaan_mock">Jumlah</label>
                            <input type="text" class="form-control" name="biaya_pemeriksaan_mock" disabled required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Simpan Pemeriksaan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
    var list_obats = $('input[name="list_obats"]').val();
    var data_obat = list_obats ? JSON.parse(list_obats) : [];

    const formatRupiah = (angka) => {
        var number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    };

    const renderInfoObat = () => {
        let id_obat_selected = $('input[name="id_obat_selected"]').val();
        id_obat_selected = id_obat_selected ? JSON.parse(id_obat_selected) : [];
        const renderHtml = id_obat_selected.map(id => {
            const obat = data_obat.find(o => o.id == id);
            if (!obat) return '';
            return `
                <div class="obat-item">
                    <span>${obat.nama_obat} - ${formatRupiah(obat.harga)}</span>
                    <button type="button" class="btn btn-danger btn-sm ml-2" onclick="removeObat(${id})">Hapus</button>
                </div>
            `;
        });
        const biaya_periksa = 150000;
        const total = id_obat_selected.reduce((acc, curr) => {
            const obat = data_obat.find(o => o.id == curr);
            if (!obat) return acc;
            return acc + obat.harga;
        }, 0);

        $('input[name="biaya_pemeriksaan_mock"]').val(formatRupiah(total + biaya_periksa));
        $('input[name="biaya_pemeriksaan"]').val(total + biaya_periksa);
        $('#info-obat').html(renderHtml.join(''));
    };

    const addObat = () => {
        const id_obat = $('#id_obat').val();
        let id_obat_selected = $('input[name="id_obat_selected"]').val();
        id_obat_selected = id_obat_selected ? JSON.parse(id_obat_selected) : [];
        if (!id_obat_selected.includes(id_obat)) {
            id_obat_selected.push(id_obat);
            $('input[name="id_obat_selected"]').val(JSON.stringify(id_obat_selected));
        } else {
            alert('Obat sudah ditambahkan.');
        }
        renderInfoObat();
    };

    const removeObat = (id) => {
        let id_obat_selected = $('input[name="id_obat_selected"]').val();
        id_obat_selected = id_obat_selected ? JSON.parse(id_obat_selected) : [];
        id_obat_selected = id_obat_selected.filter(obatId => obatId != id);
        $('input[name="id_obat_selected"]').val(JSON.stringify(id_obat_selected));
        renderInfoObat();
    };

    $(document).ready(function() {
        renderInfoObat();
        $('#buttonAddObat').on('click', addObat);
    });
</script>

<style>
    .obat-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .obat-item span {
        flex-grow: 1;
    }
</style>
