@extends('auth.layouts')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">Master indikator</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Data Master indikator</span>
                </h3>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-success" onclick="showTambahModal()"><i class="ki-duotone ki-plus fs-2"></i>Tambah</a>
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
                                <th>indikator</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_indikator }}</td>
                                  <td>
                                  <div class="text-center">
                                        <a href="#" class="menu-link px-3" onclick="editData({{ $item->id }})">
                                            <i class="bi bi-pencil"></i> <!-- Ikon Edit -->
                                        </a>
                                        <a href="#" class="menu-link px-3" onclick="confirmDelete({{ $item->id }})">
                                            <i class="bi bi-trash"></i> <!-- Ikon Delete -->
                                        </a>
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
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data indikator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk memasukkan data dinas -->
                <form id="formTambah">
                    @csrf <!-- Tambahkan CSRF token -->
                    <div class="mb-3">
                        <label for="namaindikator" class="form-label">Nama indikator</label>
                        <input type="text" class="form-control" id="namaindikator" name="namaindikator" placeholder="Masukkan nama indikator">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <!-- Button untuk menyimpan data -->
                <button type="button" class="btn btn-success" onclick="submitTambah()">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    $("#kt_datatable_fixed_header").DataTable();

    function showTambahModal() {
        $('#tambahModal').modal('show');
    }

    function submitTambah() {
        // Ambil data dari form
        var namaindikator = $('#namaindikator').val();

        // Validasi data
        if (namaindikator == '') {
            // Jika input kosong, tampilkan pesan error dengan SweetAlert
            swal("Oops!", "Nama dinas harus diisi", "error");
            return;
        }

        // Kirim request tambah ke server
        $.ajax({
            url: '{{ route("master.indikator.store") }}', // Ganti URL dengan route yang sesuai
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nama: namaindikator
            },
            success: function(response) {
                // Jika tambah berhasil, tampilkan pesan sukses dengan SweetAlert
                swal("Data berhasil ditambahkan!", {
                    icon: "success",
                });
                // Tutup modal tambah
                $('#tambahModal').modal('hide');
                // Refresh halaman untuk menampilkan perubahan
                location.reload();
            },
            error: function(xhr, status, error) {
                // Jika terjadi kesalahan, tampilkan pesan error dengan SweetAlert
                swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
            }
        });
    }

   // Fungsi untuk menampilkan form edit dalam SweetAlert
   function editData(id) {
        // AJAX request untuk mendapatkan data dinas yang akan diedit
        $.get("indikator/" + id + "/edit", function(response) { // Ganti URL dengan URL yang sesuai
            // Menampilkan SweetAlert dengan form edit
            swal({
                title: "Edit Data Indikator",
                content: {
                    element: "input",
                    attributes: {
                        value: response.nama_indikator,
                        placeholder: "Masukkan nama indikator",
                        type: "text",
                    },
                },
                buttons: {
                    cancel: "Batal",
                    confirm: {
                        text: "Simpan",
                        closeModal: false,
                    },
                },
            })
            .then((value) => {
                // Jika pengguna menekan tombol "Simpan", kirim request update
                if (value) {
                    updateData(id, value);
                } else {
                    // Jika pengguna membatalkan, tampilkan pesan bahwa aksi dihentikan
                    swal("Aksi dibatalkan!", {
                        icon: "info",
                    });
                }
            });
        });
    }

    // Fungsi untuk mengirim data edit ke server
    function updateData(id, newValue) {
        // AJAX request untuk mengupdate data ke server
        $.ajax({
            url: "indikator/" + id + "/update",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                nama: newValue
            },
            success: function(response) {
                // Jika update berhasil, tampilkan pesan berhasil dengan SweetAlert
                swal("Data berhasil diupdate!", {
                    icon: "success",
                });
                // Refresh halaman untuk menampilkan perubahan
                location.reload();
            },
            error: function(xhr, status, error) {
                // Jika terjadi kesalahan, tampilkan pesan error dengan SweetAlert
                swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
            }
        });
    }

    // Fungsi untuk menampilkan modal konfirmasi delete dengan SweetAlert
    function confirmDelete(id) {
        // Tampilkan SweetAlert dengan pesan konfirmasi
        swal({
            title: "Apakah Anda yakin?",
            text: "Data Indikator ini akan dihapus!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                // Jika pengguna mengklik tombol "Ya", kirimkan request delete
                deleteData(id);
            } else {
                // Jika pengguna membatalkan, tampilkan pesan bahwa aksi dihentikan
                swal("Aksi dibatalkan!", {
                    icon: "info",
                });
            }
        });
    }

    // Fungsi untuk mengirim request delete ke server
    function deleteData(id) {
        // AJAX request untuk menghapus data dari server
        $.ajax({
            url: "indikator/" + id + "/delete",
            method: 'get',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                // Jika delete berhasil, tampilkan pesan berhasil dengan SweetAlert
                swal("Data berhasil dihapus!", {
                    icon: "success",
                });
                // Refresh halaman untuk menampilkan perubahan
                location.reload();
            },
            error: function(xhr, status, error) {
                // Jika terjadi kesalahan, tampilkan pesan error dengan SweetAlert
                swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
            }
        });
    }
</script>

@endsection
