@extends('auth.layouts')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<style>
    .swal-text {
        text-align: center !important;
    }
</style>
@endsection
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">Daftar Assesment Tatanan</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">-</span>
                </h3>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-success" onclick="showTambahModal()"><i class="ki-duotone ki-plus fs-2"></i>Tambah Periode</a>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                <div class="card card-flush mt-6 mt-xl-9">
                    <table id="kt_datatable_fixed_header" class="table table-striped table-row-bordered gy-5 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800">
                                <th>No</th>
                                <th>Periode</th>
                                <th class="text-center">Jumlah Tatanan</th>
                                <th>Status</th>
                                <th>Assesment Kelembagaan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->periode }}</td>
                                <td class="text-center">9</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input switchButton" type="checkbox" id="switchButton{{ $loop->index }}" data-id="{{ $item->id }}" @if($item->status == 1) checked @endif>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input switchButtonLembaga" type="checkbox" id="switchButtonLembaga{{ $loop->index }}" data-id="{{ $item->id }}" @if($item->status_lembaga == 1) checked @endif>
                                    </div>
                                </td>
                                <td>
                                  <div class="text-center">
                                    @if($item->status == 0)
                                        <a href="#" class="menu-link px-3" onclick="deletePeriode({{ $item->id }})">
                                            <i class="bi bi-trash"></i> <!-- Ikon Delete -->
                                        </a>
                                    @endif
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">Data tidak ditemukan</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <!--end::Post-->
    <!-- Modal Tambah Periode -->
    <div class="modal fade" id="modalTambahPeriode" tabindex="-1" aria-labelledby="modalTambahPeriodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPeriodeLabel">Tambah Periode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="inputPeriode" class="form-label">Periode</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="form-control" id="inputPeriodeStart" name="start" placeholder="Mulai">
                                <span class="input-group-text">to</span>
                                <input type="text" class="form-control" id="inputPeriodeEnd" name="end" placeholder="Selesai">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success"  onclick="simpanPeriode()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('.input-daterange').datepicker({
            format: "yyyy",
            startView: "years",
            minViewMode: "years",
            autoclose: true
        });
    });
</script>
<script>

    function deletePeriode(periodeId) {
        swal({
            title: "Konfirmasi",
            text: "Apakah Anda yakin akan menghapus periode ini?",
            icon: "warning",
            buttons: {
                cancel: "Batal",
                confirm: "Ya, Hapus"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                // Kirim permintaan Ajax untuk menghapus periode
                $.ajax({
                    url: '/periode/tatanan/' + periodeId + '/delete',
                    method: 'get',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        swal("Sukses!", "Periode berhasil dihapus", "success");
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        swal("Oops!", "Terjadi kesalahan saat menghapus periode", "error");
                    }
                });
            }
        });
    }
    // Fungsi untuk menampilkan modal Tambah Periode
    function showTambahModal() {
        $('#modalTambahPeriode').modal('show');
    }

    // Fungsi untuk menyimpan periode baru
    function simpanPeriode() {
        var start = $('#inputPeriodeStart').val();
        var end = $('#inputPeriodeEnd').val();

        if (start == end) {
            swal("Oops!", "Periode tidak boleh sama dengan periode sebelumnya", "error");
            return; // Berhenti eksekusi fungsi jika ada kesalahan
        }

        $.ajax({
            url: '{{ route("periode.tatanan.store") }}', // Ganti URL dengan route yang sesuai
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                start: start,
                end: end,
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.error){
                    swal("Oops!", data.message, "error");
                }else{
                    swal("Sukses!", "Periode berhasil ditambahkan", "success");
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
            }
        });
    }

    function updateStatus(periodeId, isChecked) {
        // Tampilkan konfirmasi dengan SweetAlert
        swal({
            title: "Konfirmasi",
            text: "Apakah Anda yakin akan merubah periode ini?",
            icon: "warning",
            buttons: {
                cancel: "Batal",
                confirm: "Ya"
            }
        })
        .then((willActivate) => {
            // Jika pengguna mengonfirmasi
            if (willActivate) {
                // loader aktif

                // Kirim permintaan AJAX untuk memperbarui status
                $.ajax({
                    url: '{{ route("periode.updateStatus") }}', // Ganti URL dengan route yang sesuai
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        periode_id: periodeId,
                        is_checked: isChecked ? 1 : 0 // Ubah nilai boolean ke integer
                    },
                    success: function(response) {
                        swal("Sukses!", "Status periode berhasil diperbarui", "success");
                        // Refresh halaman untuk menampilkan perubahan
                        // location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Tanggapan error
                        console.error(error);
                        swal("Oops!", "Terjadi kesalahan saat memperbarui status periode", "error");
                    }
                });
            } else {
                // Jika pengguna membatalkan
                // Reset status tombol switch ke keadaan sebelumnya
                $('.switchButton[data-id="' + periodeId + '"]').prop('checked', !isChecked);
            }
        });
    }
    function updateStatusLembaga(periodeId, isChecked) {
        // Tampilkan konfirmasi dengan SweetAlert
        swal({
            title: "Konfirmasi",
            text: "Apakah Anda yakin akan merubah Assement Kelembagaan ini?",
            icon: "warning",
            buttons: {
                cancel: "Batal",
                confirm: "Ya"
            }

        })
        .then((willActivate) => {
            // Jika pengguna mengonfirmasi
            if (willActivate) {
                // Kirim permintaan AJAX untuk memperbarui status
                $.ajax({
                    url: '{{ route("periode.updateStatuslembaga") }}', // Ganti URL dengan route yang sesuai
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        periode_id: periodeId,
                        is_checked: isChecked ? 1 : 0 // Ubah nilai boolean ke integer
                    },
                    success: function(response) {
                        swal("Sukses!", "Status assement kelembagaan berhasil diperbarui", "success");
                        // Refresh halaman untuk menampilkan perubahan
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Tanggapan error
                        console.error(error);
                        swal("Oops!", "Terjadi kesalahan saat memperbarui status assement kelembagaan", "error");
                    }
                });
            } else {
                // Jika pengguna membatalkan
                // Reset status tombol switch ke keadaan sebelumnya
                $('.switchButtonLembaga[data-id="' + periodeId + '"]').prop('checked', !isChecked);
            }
        });
    }
    // Tangani perubahan status tombol switch
    $('.switchButton').change(function() {
        var periodeId = $(this).data('id'); // Dapatkan ID periode dari data atribut
        var isChecked = $(this).prop('checked'); // Dapatkan status tombol switch

        // Panggil fungsi updateStatus untuk memperbarui status di backend
        updateStatus(periodeId, isChecked);
    });
    $('.switchButtonLembaga').change(function() {
        var periodeId = $(this).data('id'); // Dapatkan ID periode dari data atribut
        var isChecked = $(this).prop('checked'); // Dapatkan status tombol switch

        // Panggil fungsi updateStatus untuk memperbarui status di backend
        updateStatusLembaga(periodeId, isChecked);
    });


</script>
@endsection
