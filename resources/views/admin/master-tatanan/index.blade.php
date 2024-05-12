@extends('auth.layouts')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                    <span class="card-label fw-bold text-gray-900">Master Tatanan</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Data Master Tatanan</span>
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
                                <th>Indikator</th>
                                <th>Tatanan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ IndikatorName(json_decode($item->id_indikator)) }}</td>
                                <td>{{ $item->nama_tatanan }}</td>
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
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data Tatanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk memasukkan data dinas -->
                <form id="formTambah">
                    @csrf <!-- Tambahkan CSRF token -->
                    <div class="mb-3">
                        <label for="namaTatanan" class="form-label">Nama Tatanan</label>
                        <input type="text" class="form-control" id="namaTatanan" name="namaTatanan" placeholder="Masukkan nama tatanan">
                    </div>
                    <!-- Tambahkan input select2 multiple -->
                    <div class="mb-3">
                        <label for="pilihanTatanan" class="form-label">Pilihan Indikator</label>
                        <select class="form-control" id="pilihanTatanan" name="pilihanTatanan[]" multiple>
                            @foreach ($indikator as $item)
                            <option value="{{$item->id}}">{{$item->nama_indikator}}</option>
                            @endforeach
                        </select>
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

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Tatanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk mengedit data dinas -->
                <form id="formEdit">
                    @csrf <!-- Tambahkan CSRF token -->
                    <div class="mb-3">
                        <label for="editNamaTatanan" class="form-label">Nama Tatanan</label>
                        <input type="hidden" class="form-control" id="idtatanan" name="idtatanan">
                        <input type="text" class="form-control" id="editNamaTatanan" name="editNamaTatanan" placeholder="Masukkan nama tatanan">

                    </div>
                    <!-- Tambahkan input select2 multiple -->
                    <div class="mb-3">
                        <label for="editPilihanTatanan" class="form-label">Pilihan Indikator</label>
                        <select class="form-control" id="editPilihanTatanan" name="editPilihanTatanan[]" multiple>

                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <!-- Button untuk menyimpan data -->
                <button type="button" class="btn btn-success" onclick="submitEdit()">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    function showTambahModal() {
        $('#tambahModal').modal('show');
    }
    $('#pilihanTatanan').select2();
    $('#editPilihanTatanan').select2();
    function submitTambah() {
        // Ambil data dari form
        var namaTatanan = $('#namaTatanan').val();
        var pilihanTatanan = $('#pilihanTatanan').val();

        // Validasi data
        if (namaTatanan == '') {
            // Jika input kosong, tampilkan pesan error dengan SweetAlert
            swal("Oops!", "Nama dinas harus diisi", "error");
            return;
        }
        if (pilihanTatanan == '') {
        // Jika input kosong, tampilkan pesan error dengan SweetAlert
        swal("Oops!", "Nama dinas harus diisi", "error");
        return;
        }

        // Kirim request tambah ke server
        $.ajax({
            url: '{{ route("master.tatanan.store") }}', // Ganti URL dengan route yang sesuai
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nama: namaTatanan,
                pilihanTatanan: pilihanTatanan
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

    function editData(id) {
    // AJAX request untuk mendapatkan data tatanan yang akan diedit
    $.get("tatanan/" + id + "/edit", function(response) {

        // Ubah string ID menjadi array
        var id_indikator = JSON.parse(response.tatanan.id_indikator);
        console.log(id_indikator);
        // Kosongkan dulu elemen select sebelum menambahkan opsi baru
        $('#editPilihanTatanan').empty();

        // Loop through response.indikator untuk menambahkan opsi-opsi baru ke dalam select
        response.indikator.forEach(function(item) {
            var selected = id_indikator.indexOf(item.id.toString()) !== -1 ? 'selected' : ''; // Pilih opsi jika item.id ada dalam array id_indikator
            $('#editPilihanTatanan').append('<option value="' + item.id + '" ' + selected + '>' + item.nama_indikator + '</option>');
        });

        // Inisialisasi kembali Select2 setelah opsi-opsi baru ditambahkan
        $('#editPilihanTatanan').select2();

        // Isi data ke dalam modal edit
        $('#editNamaTatanan').val(response.tatanan.nama_tatanan);
        $('#idtatanan').val(id);



        // Tampilkan modal edit
        $('#editModal').modal('show');
    })
    .fail(function(xhr, status, error) {
        // Jika terjadi kesalahan dalam pengambilan data, tampilkan pesan error
        swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
    });
}



    // Fungsi untuk mengirim data edit ke server
    function submitEdit() {
        // Ambil data dari form edit
        var id = $('#idtatanan').val();
        var namaTatanan = $('#editNamaTatanan').val();
        var pilihanTatanan = $('#editPilihanTatanan').val();

        // Kirim request update ke server
        $.ajax({
            url: "tatanan/" + id + "/update",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                nama: namaTatanan,
                pilihanTatanan: pilihanTatanan
            },
            success: function(response) {
                // Jika update berhasil, tampilkan pesan berhasil dengan SweetAlert
                swal("Data berhasil diupdate!", {
                    icon: "success",
                });
                // Tutup modal edit
                $('#editModal').modal('hide');
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
            text: "Data dinas ini akan dihapus!",
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
            url: "tatanan/" + id + "/delete",
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
