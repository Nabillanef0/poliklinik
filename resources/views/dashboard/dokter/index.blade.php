<div class="row p-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h5><b>{{ $title }}</b></h5>
            <a href="/dokter/create" class="btn btn-primary mb-2"><i class="fas fa-plus"></i>Tambah</a>
            <table class="table">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th>Poli</th>
                    <th>Action</th>
                </tr>

                @foreach ($dokter as $item)
                <tr>
                    <td> {{ $loop->iteration }} </td>
                    <td> {{ $item->nama }} </td>
                    <td> {{ $item->alamat }} </td>
                    <td> {{ $item->no_hp }} </td>
                    <td> {{ $item->poli->nama_poli }} </td>
                    <td>
                        <div class="d-flex">
                            <a href="/dokter/{{ $item->id }}/edit" class="btn btn-success btn-sm"><i class="fas fa-edit"></i>Edit</a>
                            <form action="/dokter/{{ $item->id }}" method="POST">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm ml-1"><i class="fas fa-trash"></i>Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>    
                @endforeach
            </table>
            
            </div>
        </div>
    </div>
</div>