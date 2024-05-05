@extends('auth.layouts')

@section('style')
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
                    <span class="card-label fw-bold text-gray-900">Daftar Assesment Kelembagaan</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">{{$periode ? $periode->periode : '-' }}</span>
                    <input type="hidden" name="periode_id" id="periode_id" value="{{$periode->id }}">
                </h3>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                <div >

                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <table id="kt_datatable_fixed_header" class="table table-striped table-row-bordered gy-5 gs-7">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800">
                                        <th>No</th>
                                        <th class="text-left">Pertanyaan</th>
                                        <th class="text-end">Link Data Pendukung</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <!--end::Post-->
</div>

<!-- Modal -->
<div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateLabel">Update Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdate">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="updatedData" class="form-label">Dokumen Pendukung</label>
                        <input type="file" class="form-control" id="updatedData" name="updatedData">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    $(document).ready(function() {
        var periode_id = $("#periode_id").val();
        loadDataPertanyaan(periode_id);

        $(document).on('click', 'input[type="radio"][name="jawaban"]', function() {
        // Mendapatkan nilai yang dipilih
        var selectedValue = $(this).val();
        // data-id
        var dataId = $(this).data('id');
        var jawaban = $(this).data('jwb');
        var tatanan_id = $("#pilihitatanan").val();


        // Lakukan apa pun yang perlu Anda lakukan dengan nilai yang dipilih dan nomor indikator
        console.log('Periode_id:', periode_id);
        console.log('Jawaban yang dipilih:', jawaban);
        console.log('Nilai yang dipilih:', selectedValue);
        console.log('id_pertanyaan:', dataId);
    });

    });
    // Mendaftarkan event listener untuk input URL
    $('#updatedData').on('blur', function() {
        var url = $(this).val();
        if (!isValidUrl(url)) {
            // swalt information
            swal("Oops!", "Link Pendukung tidak valid", "error");
            $(this).val('');
            return;
        }
    });

    // Fungsi untuk memeriksa apakah URL valid
    function isValidUrl(url) {
        // RegEx untuk memeriksa apakah URL valid
        var urlPattern = /^(http(s)?:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- ;,./?%&=]*)?$/;
        return urlPattern.test(url);
    }


    // Fungsi untuk melakukan update dengan ID tertentu
    function upload(id, currentData) {
        // Mengisi nilai awal pada form modal dengan data yang ada saat ini
        $('#updatedData').val(currentData);

        // Menetapkan ID data yang akan diupdate pada form modal (jika diperlukan)
        $('#formUpdate').attr('data-item-id', id);

        // Menampilkan modal update
        $('#modalUpdate').modal('show');
    }

    // Event listener untuk menangani submit form update
    $('#formUpdate').submit(function(e) {
        e.preventDefault(); // Mencegah aksi default form

        // Mengambil nilai yang diperbarui dari input
        var updatedLink = $('#updatedData').val();

        // Mendapatkan ID data yang akan diupdate
        var itemId = $(this).attr('data-item-id');

        // Data yang akan dikirimkan ke server dalam format objek
        var postData = {
            id: itemId,
            linkPendukung: updatedLink
        };
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Kirim data pembaruan ke server menggunakan Ajax
        $.ajax({
            url: '/pengisianform/updatefilelembaga/', // Ganti dengan URL endpoint yang sesuai di server Anda
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: postData,
            success: function(response) {
                swal("Update link successfully posted to server " + response. linkPendukung + ".");
                // Tutup modal update
                $('#modalUpdate').modal('hide');
            },
            error: function(xhr, status, error) {
                // Kesalahan saat melakukan request Ajax
                console.error('Terjadi kesalahan saat melakukan permintaan Ajax:', error);

                // Tambahkan logika untuk menangani kesalahan jika diperlukan
            }
        });
    });



    // Fungsi untuk memuat data menggunakan Ajax
    function loadDataPertanyaan(indicator) {
        $.ajax({
            url: "/pengisianform/pertanyaanlembaga/" + indicator,
            type: "GET",
            success: function(response) {
                // Bersihkan isi tabel
                $('#kt_datatable_fixed_header tbody').empty();

                // Loop melalui data yang diterima dan tambahkan ke dalam tabel
                $.each(response, function(index, item) {
                    // Mengubah tautan berdasarkan kondisi item.file
                    if (item.file != null) {
                        // Tautan untuk menampilkan file
                        var link = '<a href="' + item.file + '" class="btn btn-sm btn-primary" target="_blank">Unduh</a>';
                    } else {
                        // Tautan untuk menampilkan modal upload
                        var link = '<button type="button" class="btn btn-sm btn-primary" onclick="upload(' + item.id + ')">Upload</button>';
                    }

                    // jawaban radio button
                    var jawaban = '';

                    // Periksa apakah jawaban_a ada
                    if(item.jawaban_a) {
                        jawaban += '<input type="radio" class="form-check-input jawaban" name="jawaban" data-id="'+item.id+'" data-jwb="a" value="'+item.jawaban_a+'"> '+item.jawaban_a + '<br><br>';
                    }

                    // Periksa apakah jawaban_b ada
                    if(item.jawaban_b) {
                        jawaban += '<input type="radio" class="form-check-input jawaban" name="jawaban" data-id="'+item.id+'" data-jwb="b" value="'+item.jawaban_b+'"> '+item.jawaban_b + '<br><br>';
                    }


                    var indikator = '';

                    // Periksa apakah indikator ada
                    $.each(item.indikator, function(index, item) {
                        indikator += '<b>' + item + '</b>';
                    });
                    // munculkan solusi berdasarkan indikator dari tatanan
                    // Tambahkan data ke dalam tabel
                    $('#kt_datatable_fixed_header tbody').append(
                        '<tr>' +
                            '<td colspan="4">' + indikator + '</td>' +
                        '</tr>' +
                        '<tr>' +
                            // '<td>' + (index + 1) + '</td>' +
                            '<td>' + item.no_pertanyaan + '</td>' +
                            '<td>' + item.pertanyaan +'</br></br>'+jawaban+ '</td>' +
                            '<td class="text-end">' + link + '</td>' +
                        '</tr>'
                    );
                });
            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
    }

</script>
@endsection
