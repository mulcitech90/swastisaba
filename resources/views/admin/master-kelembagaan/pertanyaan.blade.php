@extends('auth.layouts')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
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
                    <span class="card-label fw-bold text-gray-900">Master Pertanyaan Kelembagaan</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Data Master Pertanyaan Kelembagaan</span>
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
                                <th>Lembaga</th>
                                <th><center>Pertanyaan Kelembagaan</center></th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ LembagaName($item->id_kelembagaan)}}</td>
                                <td>{{ $item->pertanyaan}}</td>
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
                                <td></td>
                                <td></td>
                                <td><center>Data tidak ditemukan</center></td>
                                <td></td>
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
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data Pertanyaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk memasukkan data dinas -->
                <form id="formTambah">
                    @csrf
                    <div class="mb-3">
                        <label for="pilihanTatanan" class="form-label">Pilihan Lembaga</label>
                        <select class="form-control" id="pilihkelembagaan" name="pilihkelembagaan">
                            @foreach ($lembaga as $item)
                                <option value="{{$item->id}}">{{$item->nama_kelembagaan}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pertanyaan_lembaga" class="form-label">Pertanyaan Kelembagaan</label>
                        <input type="text" class="form-control" id="pertanyaan_lembaga" name="pertanyaan_lembaga" placeholder="Pertanyaan Kelembagaan">
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
                <h5 class="modal-title" id="editModalLabel">Edit Pertanyaan Kelembagaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk mengedit data dinas -->
                <form id="formEdit">
                    @csrf <!-- Tambahkan CSRF token -->
                    <!-- Tambahkan input select2 multiple -->
                    <div class="mb-3">
                        <label for="editpilihlembaga" class="form-label">Pilih Lembaga</label>
                        <select class="form-control" id="editpilihlembaga" name="editpilihlembaga">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editpertanyaan" class="form-label">Pertanyaan</label>
                        <input type="hidden" class="form-control" id="idpertanyaanlembaga" name="idpertanyaanlembaga">
                        <input type="text" class="form-control" id="editpertanyaan" name="editpertanyaan" placeholder="Masukkan nama tatanan">

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
    $("#kt_datatable_fixed_header").DataTable();
    $('#pilihkelembagaan').select2();
    $('#editpilihlembaga').select2();

    function showTambahModal() {
        $('#tambahModal').modal('show');
    }
    function submitTambah() {
        // Ambil data dari form
        var pertanyaan_lembaga = $('#pertanyaan_lembaga').val();
        var id_lembaga = $('#pilihkelembagaan').val();

        // Validasi data
        if (pertanyaan_lembaga == '') {
            // Jika input kosong, tampilkan pesan error dengan SweetAlert
            swal("Oops!", "Nama dinas harus diisi", "error");
            return;
        }

        // Kirim request tambah ke server
        $.ajax({
            url: '{{ route("master.pertanyaan-lembaga.store") }}', // Ganti URL dengan route yang sesuai
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                pertanyaan: pertanyaan_lembaga,
                id_lembaga : id_lembaga,
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
    // AJAX request untuk mendapatkan data tatanan yang akan diedit
    $.get("pertanyaan-lembaga/" + id + "/edit", function(response) {

        // Ubah string ID menjadi array
        var id_lembaga = response.pertanyaan.id_kelembagaan;
        // Kosongkan dulu elemen select sebelum menambahkan opsi baru
        $('#editpilihlembaga').empty();

        // Loop through response.indikator untuk menambahkan opsi-opsi baru ke dalam select
        response.lembaga.forEach(function(item) {
            var selected = id_lembaga == item.id? 'selected' : ''; // Pilih opsi jika item.id ada dalam array id_indikator
            $('#editpilihlembaga').append('<option value="' + item.id + '" ' + selected + '>' + item.nama_kelembagaan + '</option>');
        });

        // Inisialisasi kembali Select2 setelah opsi-opsi baru ditambahkan
        $('#editpilihlembaga').select2();

        // Isi data ke dalam modal edit
        $('#editpertanyaan').val(response.pertanyaan.pertanyaan);
        $('#idpertanyaanlembaga').val(id);



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
        var id = $('#idpertanyaanlembaga').val();
        var editpertanyaan = $('#editpertanyaan').val();
        var editpilihlembaga = $('#editpilihlembaga').val();

        // Kirim request update ke server
        $.ajax({
            url: "pertanyaan-lembaga/" + id + "/update",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                nama: editpertanyaan,
                pilihlembaga: editpilihlembaga
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

    // Fungsi untuk mengirim data edit ke server
    function updateData(id, newValue, id_lembaga) {
        // AJAX request untuk mengupdate data ke server
        $.ajax({
            url: "pertanyaan-lembaga/" + id + "/update",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                nama: newValue,
                id_lembaga: id_lembaga, // Teruskan id_lembaga ke server
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
            url: "pertanyaan-lembaga/" + id + "/delete",
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
