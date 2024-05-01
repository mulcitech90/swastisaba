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
                    <span class="card-label fw-bold text-gray-900">Master Pertanyaan Tatanan</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Data Master Pertanyaan Tatanan</span>
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
                                <th>Tatanan</th>
                                <th><center>Pertanyaan Tatanan</center></th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pertanyaan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ TatananName($item->tatanan_id)}}</td>
                                <td>{{ $item->pertanyaan}}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="#" class="menu-link px-3" onclick="editData({{ $item->id }})">
                                            <i class="bi bi-pencil"></i> <!-- Ikon Edit -->
                                        </a>
                                        <a href="#" class="menu-link px-3" onclick="confirmDelete({{ $item->id }})">
                                            <i class="bi bi-trash"></i> <!-- Ikon Delete -->
                                        </a>
                                    </div>
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
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data Pertanyaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk memasukkan data dinas -->
                <form id="formTambah">
                    @csrf
                    <div class="mb-3 row">
                        {{-- pilih dinas --}}
                        <div class="col-md-12">
                        <label for="dinas" class="form-label">Pilih Dinas</label>
                        <select class="form-select" id="dinas" name="dinas" aria-label="Default select example">
                            <option selected>Pilih Dinas</option>
                            @foreach ($dinas as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_dinas }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-12">
                        <label for="tatanan" class="form-label">Pilih Tatanan</label>
                        <select class="form-select" id="tatanan" name="tatanan" aria-label="Default select example">
                            <option selected>Pilih Tatanan</option>
                            @foreach ($tatanan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_tatanan }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-12">
                            <label for="kat" class="form-label col-md-12">Apakah Soal KAT (Untuk Kondisi Sosial)</label>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kat" id="kat_ya" value="1">
                                        <label class="form-check-label" for="kat_ya">Ya</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kat" id="kat_tidak" value="0">
                                        <label class="form-check-label" for="kat_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>


                        </div>
                        {{-- input pertanyaan --}}
                        <div class="col-md-12">
                            <label for="pertanyaan" class="form-label">Pertanyaan</label>
                            <textarea class="form-control input form input- centered" id="pertanyaan" name="pertanyaan"></textarea>
                        </div>

                        {{-- jawaban --}}
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Jawaban A</label>
                            <textarea class="form-control input form input- centered" id="jawaban_a" name="jawaban_a"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Nilai A</label>
                            <select class="form-select" aria-label="Default select example" id="jawaban_a_select" name="jawaban_a_select">
                                <option value="0">-</option>
                                <option value="0">0</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        {{-- jawaban --}}
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Jawaban B</label>
                            <textarea class="form-control input form input- centered" id="jawaban_b" name="jawaban_b"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Nilai B</label>
                            <select class="form-select" aria-label="Default select example" id="jawaban_b_select" name="jawaban_b_select">
                                <option value="0">-</option>
                                <option value="0">0</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        {{-- jawaban --}}
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Jawaban C</label>
                            <textarea class="form-control input form input- centered" id="jawaban_c" name="jawaban_c"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Nilai C</label>
                            <select class="form-select" aria-label="Default select example" id="jawaban_c_select" name="jawaban_c_select">
                                <option value="0">-</option>
                                <option value="0">0</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        {{-- jawaban --}}
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Jawaban D</label>
                            <textarea class="form-control input form input- centered" id="jawaban_d" name="jawaban_d"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Nilai D</label>
                            <select class="form-select" aria-label="Default select example" id="jawaban_d_select" name="jawaban_d_select">
                                <option value="0">-</option>
                                <option value="0">0</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        {{-- pilih dinas --}}

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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Pertanyaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk memasukkan data dinas -->
                <form id="formEdit">
                    @csrf
                    <!-- Tambahkan input hidden untuk menyimpan ID -->
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3 row">
                        {{-- pilih dinas --}}
                        <div class="col-md-12">
                        <label for="dinas" class="form-label">Pilih Dinas</label>
                        <select class="form-select" id="editpilihdinas" name="editpilihdinas" aria-label="Default select example">

                        </select>
                        </div>
                        <div class="col-md-12">
                        <label for="tatanan" class="form-label">Pilih Tatanan</label>
                        <select class="form-select" id="editpilihtatanan" name="editpilihtatanan" aria-label="Default select example">

                        </select>
                        </div>
                        <div class="col-md-12">
                            <label for="kat" class="form-label col-md-12">Apakah Soal KAT (Untuk Kondisi Sosial)</label>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="editkat" id="kat_ya" value="1">
                                        <label class="form-check-label" for="kat_ya">Ya</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="editkat" id="kat_tidak" value="0">
                                        <label class="form-check-label" for="kat_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>


                        </div>
                        {{-- input pertanyaan --}}
                        <div class="col-md-12">
                            <label for="pertanyaan" class="form-label">Pertanyaan</label>
                            <textarea class="form-control input form input- centered" id="editpertanyaan" name="editpertanyaan"></textarea>
                        </div>

                        {{-- jawaban --}}
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Jawaban A</label>
                            <textarea class="form-control input form input- centered" id="editjawaban_a" name="editjawaban_a"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Nilai A</label>
                            <select class="form-select" aria-label="Default select example" id="editjawaban_a_select" name="editjawaban_a_select">
                                <option value="0">-</option>
                                <option value="0">0</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        {{-- jawaban --}}
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Jawaban B</label>
                            <textarea class="form-control input form input- centered" id="editjawaban_b" name="editjawaban_b"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Nilai B</label>
                            <select class="form-select" aria-label="Default select example" id="editjawaban_b_select" name="editjawaban_b_select">
                                <option value="0">-</option>
                                <option value="0">0</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        {{-- jawaban --}}
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Jawaban C</label>
                            <textarea class="form-control input form input- centered" id="editjawaban_c" name="editjawaban_c"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Nilai C</label>
                            <select class="form-select" aria-label="Default select example" id="editjawaban_c_select" name="editjawaban_c_select">
                                <option value="0">-</option>
                                <option value="0">0</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        {{-- jawaban --}}
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Jawaban D</label>
                            <textarea class="form-control input form input- centered" id="editjawaban_d" name="editjawaban_d"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="jawaban" class="form-label">Nilai D</label>
                            <select class="form-select" aria-label="Default select example" id="editjawaban_d_select" name="editjawaban_d_select">
                                <option value="0">-</option>
                                <option value="0">0</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        {{-- pilih dinas --}}

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
    $('#dinas').select2();
    $('#tatanan').select2();

    function showTambahModal() {
        $('#tambahModal').modal('show');
    }
    function submitTambah() {
        // Ambil data dari form
        var tatanan = $('#tatanan').val();
        var pertanyaan = $('#pertanyaan').val();
        var jawaban_a = $('#jawaban_a').val();
        var jawaban_b = $('#jawaban_b').val();
        var jawaban_c = $('#jawaban_c').val();
        var jawaban_d = $('#jawaban_d').val();
        var jawaban_a_select = $('#jawaban_a_select').val();
        var jawaban_b_select = $('#jawaban_b_select').val();
        var jawaban_c_select = $('#jawaban_c_select').val();
        var jawaban_d_select = $('#jawaban_d_select').val();
        var kat = $('input[name="kat"]:checked').val();
        var dinas = $('#dinas').val();

        console.log(tatanan, dinas);


        // Validasi data
        if (pertanyaan == '' || jawaban_a == '' || jawaban_b == '' || jawaban_c == '' || jawaban_d == '' || !kat || dinas == 'Pilih Dinas' || tatanan == 'Pilih Tatanan') {
            swal("Oops!", "Semua kolom harus diisi", "error");
            return;
        }

        // Kirim request tambah ke server
        $.ajax({
            url: '{{ route("master.pertanyaan-tatanan.store") }}', // Ganti URL dengan route yang sesuai
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tatanan : tatanan,
                pertanyaan: pertanyaan,
                jawaban_a: jawaban_a,
                jawaban_b: jawaban_b,
                jawaban_c: jawaban_c,
                jawaban_d: jawaban_d,
                jawaban_a_select: jawaban_a_select,
                jawaban_b_select: jawaban_b_select,
                jawaban_c_select: jawaban_c_select,
                jawaban_d_select: jawaban_d_select,
                kat: kat,
                dinas: dinas,

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
    // AJAX request untuk mendapatkan data pertanyaan yang akan diedit
    $.get("pertanyaan-tatanan/" + id + "/edit", function(response) {
        // Ubah string ID menjadi array
        var id_tatanan = response.pertanyaan.tatanan_id;
        console.log(response);
        // Kosongkan dulu elemen select sebelum menambahkan opsi baru
        $('#editpilihtatanan').empty();
        $('#editpilihdinas').empty();

        // Loop through response.tatanan untuk menambahkan opsi-opsi baru ke dalam select
        response.tatanan.forEach(function(item) {
            var selected = id_tatanan == item.id ? 'selected' : ''; // Pilih opsi jika item.id ada dalam array id_tatanan
            $('#editpilihtatanan').append('<option value="' + item.id + '" ' + selected + '>' + item.nama_tatanan + '</option>');
        });
        response.dinas.forEach(function(item) {
            var selected = id_tatanan == item.id ? 'selected' : ''; // Pilih opsi jika item.id ada dalam array id_tatanan
            $('#editpilihdinas').append('<option value="' + item.id + '" ' + selected + '>' + item.nama_dinas + '</option>');
        });

        // Isi data ke dalam modal edit
        $('#editpertanyaan').val(response.pertanyaan.pertanyaan);
        $('#editjawaban_a').val(response.pertanyaan.jawaban_a);
        $('#editjawaban_b').val(response.pertanyaan.jawaban_b);
        $('#editjawaban_c').val(response.pertanyaan.jawaban_c);
        $('#editjawaban_d').val(response.pertanyaan.jawaban_d);
        $('#editjawaban_a_select').val(response.pertanyaan.nilai_a);
        $('#editjawaban_b_select').val(response.pertanyaan.nilai_b);
        $('#editjawaban_c_select').val(response.pertanyaan.nilai_c);
        $('#editjawaban_d_select').val(response.pertanyaan.nilai_d);
        $("input[name='editkat'][value='" + response.pertanyaan.kat + "']").prop('checked', true);


        $('#edit_id').val(id);

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
    var id = $('#edit_id').val();
    var editpertanyaan = $('#editpertanyaan').val();
    var editpilihdinas = $('#editpilihdinas').val();
    var editpilihtatanan = $('#editpilihtatanan').val();
    var kat = $("input[name='kat']:checked").val();

    // Kirim request update ke server
    $.ajax({
        url: "pertanyaan-tatanan/" + id,
        method: "PUT",
        data: {
            _token: '{{ csrf_token() }}',
            pertanyaan: editpertanyaan,
            pilihdinas: editpilihdinas,
            pilihtatanan: editpilihtatanan,
            kat: kat
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
            url: "pertanyaan-tatanan/" + id + "/update",
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
            url: "pertanyaan-tatanan/" + id + "/delete",
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
