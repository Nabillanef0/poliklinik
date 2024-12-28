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
