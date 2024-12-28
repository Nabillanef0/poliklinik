@extends('layouts.master')
@section('title')
    Dokter
@endsection
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/plugins/datatables/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row p-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5><b>Update Profile Dokter</b></h5>
                    <form id="formDokter" method="POST" action="/profile/dokter/{{ auth()->user()->dokter->id }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="id" name="id" value="{{ auth()->user()->dokter->id }}">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ auth()->user()->dokter->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ auth()->user()->dokter->alamat }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ auth()->user()->dokter->no_hp }}" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>

    <!-- Add success alert from session -->
    @if(session('success'))
        <script>
            Swal.fire('Success', "{{ session('success') }}", 'success');
        </script>
    @endif


    <script>
        "use strict";
        jQuery(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();
            let url = `${HOST_URL}/profile/dokter/{{ auth()->user()->dokter->id }}`;
            let method = 'PUT';
            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        Swal.fire('Success', response.message, 'success');
                        window.location.href = '/profile-dokter'; // Redirect after showing success message
                    }
                },
                error: function(response) {
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            });
        });
    });

    </script>
@endsection
