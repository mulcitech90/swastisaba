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
                <div>
                    <div class="mb-3 row">
                        <div>
                            {{-- <div class="col-6">
                                <a href={{url('pengisianform/lembaga')}} class="btn btn-secondary"><span>Kembali</span></a>

                            </div> --}}
                            <div class="col-12 text-end">
                                <a href={{url('pengisianform/lembaga')}} class="btn btn-secondary"><span>Kembali</span></a>
                                <button type="button" class="btn btn-success simpansoal" id="simpan">
                                    <span>Simpan</span>
                                </button>
                            </div>
                        </div>
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
                            <div class="row">
                                <div class="col-6">
                                    <a href={{url('pengisianform/lembaga')}} class="btn btn-secondary"><span>Kembali</span></a>

                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-success simpansoal" id="simpan">
                                        <span>Submit</span>
                                    </button>
                                </div>
                            </div>
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
<div class="modal fade" id="urlinfo" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateLabel">Update Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div id="info_url"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary submitevidence">Update</button>
                </div>

        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    $(document).ready(function() {
        init();
    });
    function init(params) {
        var periode_id = $("#periode_id").val();
        loadDataPertanyaan(periode_id);
    }

    $('body').on('click', '.tambahurl', function (e) {
        e.preventDefault();
        let dataTarget = e.target;
        id = dataTarget.getAttribute('data-id')
        url = dataTarget.getAttribute('data-url')
        var open = '';
        if (url != 'null') {
            open = '<a href="/downloadfile/' + btoa(id) + ' " target="_blank" class="btn btn-primary btn-sm waves-effect waves-float waves-light">Lihat Link Pendukung</a></br><span class="text-danger">Silahkan upload ulang jika ingin merubah file pendukung</span><br/><br/> ';
        }

        // alert(id);
        $('#info_url').empty();
        $("#info_url").html("");
        html = `
                <input id="idevidence" type="hidden" name="id" value="`+id+`">
                `+open+`
                <input id="urlevidence" type="file" class="form-control" name="urlevidence">`;
        $('#info_url').html(html);
        $('#urlinfo').modal('show');
    });
    $('body').on('click', '.simpansoal', function (e) {
        e.preventDefault();
        Swal.fire({
                title: "Konfirmasi",
                icon: "question",
                text: "Apakah Anda ingin menyimpan pengisian ini? Setelah disimpan soal akan di verifikasi oleh Dinas !!!",
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    postpertanyaan();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire("Dibatalkan", "Pengisian tidak disimpan.", "error");
                }
            });

    });
    $('body').on('click', '.submitevidence', function (e) {
        e.preventDefault();

        var fileData = new FormData();
        fileData.append('file', $('#urlevidence')[0].files[0]);
        fileData.append('id', $('#idevidence').val());
        var id = $('#idevidence').val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');


        if (url == '') {
            Swal.fire({
                title: "Perhatian!",
                icon: "warning",
                text: "Data tidak boleh kosong",
            });
        }else{
            $.ajax({
                url: '/pengisianform/uploadfile',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: fileData,
                processData: false, // Tambahkan ini agar FormData tidak diproses
                contentType: false, // Tambahkan ini agar FormData tidak diproses
                success: function(response) {
                    init();
                    Swal.fire({
                        title: "Berhasil!",
                        icon: "success",
                        text: "Data Berhasil di upload",
                    });
                    $('#urlevidence').val('');
                    $('#urlinfo').modal('hide');

                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
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
                    var link = '<a href="javascript:void(0)" class="btn btn-primary btn-sm waves-effect waves-float waves-light tambahurl" data-id="'+item.id+'" data-url="'+item.file+'">Upload</a>';
                    var jawaban = '';

                    // Periksa apakah jawaban_a ada
                    if(item.jawaban_a) {
                        var checkedA = item.jawaban === 'a' ? 'checked' : '';
                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="a" value="'+item.nilai_a+'" ' + checkedA + ' > '+item.jawaban_a + '<br><br>';
                    }

                    // Periksa apakah jawaban_b ada
                    if(item.jawaban_b) {
                        var checkedB = item.jawaban === 'b' ? 'checked' : '';
                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="b" value="'+item.nilai_b+'" ' + checkedB + '> '+item.jawaban_b + '<br><br>';
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
                            '<td>' + item.no_pertanyaan + '</td>' +
                            '<td>' + item.pertanyaan +'</br></br>'+jawaban+ '</td>' +
                            '<td class="text-end">' + link + '</td>' +
                        '</tr>'
                    );
                    $("input[type='radio']").on('click', function(){
                            var radioValue = $("input[name="+item.id+"]:checked").val();
                            if(radioValue){
                                var nilai = $(this).val();
                                // data-id
                                var dataId = $(this).data('id');
                                var jawaban = $(this).data('jwb');
                                var tatanan_id = $("#pilihitatanan").val();
                                var periode_id = $("#periode_id").val();

                                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                                // ajax post
                                $.ajax({
                                        url: "/pengisianform/pengiisiansoallembaga",
                                        type: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        data: {
                                            dataId: dataId,
                                            jawaban: jawaban,
                                            tatanan_id: tatanan_id,
                                            periode_id: periode_id,
                                        },
                                        success: function(response) {
                                        loadDataPertanyaan(periode_id);
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Terjadi kesalahan:', error);
                                            // Tangani kesalahan jika ada
                                        }
                                    });
                            }
                    });
                });
            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
    }
    function postpertanyaan(){
        var periode_id = $("#periode_id").val();
        var postData = {
                id: periode_id,
                status : 3,
                prosess: 'lembaga',
            };
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/pengisianform/submitpengisian', // Ganti dengan URL endpoint yang sesuai di server Anda
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: postData,
                success: function(response) {
                    Swal.fire({
                    title: "Berhasil!",
                    icon: "success",
                    text: "Data Berhasil disimpan",
                        }).then(() => {
                        // Mengarahkan ulang setelah menyimpan pengisian
                        window.location.href = "/pengisianform/lembaga";
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire("Gagal", "Pengisian tidak disimpan.", "error");
                }
            });
    }


</script>
@endsection
