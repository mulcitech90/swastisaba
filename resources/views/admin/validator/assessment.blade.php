@extends('auth.layouts')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
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
                    <span class="card-label fw-bold text-gray-900">Daftar Periode dan Instrumen Penilaian ({{pemda($id_check)}})</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">{{$periode ? $periode->tahun_periode : '-' }}</span>
                    <input type="hidden" name="periode_id" id="periode_id" value="{{$periode->id_periode }}">
                    <input type="hidden" name="main_id" id="main_id" value="{{$periode->id }}">
                    <input type="hidden" name="user_id" id="user_id" value="{{$id_check}}">
                    <input type="hidden" name="dinas_id" id="dinas_id" value="{{Auth::user()->id_dinas}}">

                </h3>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                <div >
                    <div class="mb-3 row">
                        {{-- pilih dinas --}}
                        <div class="col-md-4 text-left">
                            <label for="pilihitatanan" class="form-label">Pilihan Tatanan</label>
                                <select class="form-control" id="pilihitatanan" name="pilihitatanan">
                                    @foreach ($tatanan as $item)
                                        <option value="{{$item->id_tatanan}}">{{$item->nama_tatanan}}</option>
                                    @endforeach
                                </select>
                        </div>

                        <div class="col-md-8 text-end">
                            <a href={{url('validator')}} class="btn btn-secondary"><span>Kembali</span></a>
                            <button type="button" class="btn btn-success simpansoal" id="simpan">
                                <span>Simpan Validasi</span>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <table id="kt_datatable_fixed_header" class="table table-striped table-row-bordered gy-5 gs-7">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800">
                                        <th>No</th>
                                        <th class="text-left">Pertanyaan</th>
                                        <th class="text-center">Nilai Self Assesment</th>
                                        <th class="text-center">Link Data Pendukung</th>
                                        <th class="text-center">Justifikasi</th>
                                        <th class="text-center">Penilaian</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary" id="prev">
                                        <span>Sebelumnya</span>
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-primary" id="next">
                                        <span>Selanjutnya</span>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        init();
    });
    function init(params) {
        var user_id = $('#user_id').val();
        var pilihitatanan = $("#pilihitatanan").val();
        var periode = $("#periode_id").val();
        loadDataPertanyaan(pilihitatanan, periode, user_id);
    }

    $('body').on('click', '.tambahurl', function (e) {
        e.preventDefault();
        let dataTarget = e.target;
        id = dataTarget.getAttribute('data-id')
        url = dataTarget.getAttribute('data-url')
        var open = '';
        if (url != 'null') {
            // open = '<a href="' + url + ' " target="_blank" class="btn btn-primary btn-sm waves-effect waves-float waves-light">Lihat Link Pendukung</a></br><span class="text-danger">Silahkan upload ulang jika ingin merubah link pendukung</span><br/><br/> ';
                open = '<a href="/downloadfiletatanan/' + btoa(id) + ' " target="_blank" class="btn btn-primary btn-sm waves-effect waves-float waves-light">Lihat Link Pendukung</a></br><span class="text-danger">Silahkan upload ulang jika ingin merubah file pendukung</span><br/><br/> ';
        }

        // alert(id);
        $('#info_url').empty();
        $("#info_url").html("");
        html = `
                <input id="idevidence" type="hidden" name="id" value="`+id+`">
                `+open+`
                <input id="urlevidence" type="file" class="form-control" name="urlevidence">`;
                // <input id="urlevidence" type="url" class="form-control" name="url" id="url" placeholder="Masukan url">;

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
                  postpenilaian();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire("Dibatalkan", "Pengisian tidak disimpan.", "error");
                }
            });

    });
    $('body').on('click', '.submitevidence', function (e) {

    // id = $('#idevidence').val();
    // url = $('#urlevidence').val();
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
            url: '/pengisianform/updatelink',
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

    // Fungsi untuk memeriksa apakah URL valid
    function isValidUrl(url) {
        // RegEx untuk memeriksa apakah URL valid
        var urlPattern = /^(http(s)?:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- ;,./?%&=]*)?$/;
        return urlPattern.test(url);
    }

    $('#pilihitatanan').select2();

    // Memuat data ketika indikator berubah
    $("#pilihitatanan").change(function() {
        var selectedIndicator = $(this).val();
        var periode = $("#periode_id").val();
        var user_id = $('#user_id').val();
        loadDataPertanyaan(selectedIndicator, periode, user_id);
    });

    // Fungsi untuk memuat data menggunakan Ajax
    function loadDataPertanyaan(indicator, periode, user_id) {
        var url = "/validator/pertanyaanlist/" + indicator + "?periode=" + periode + "&user=" + user_id;
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                // Bersihkan isi tabel
                $('#kt_datatable_fixed_header tbody').empty();

                // Loop melalui data yang diterima dan tambahkan ke dalam tabel
                $.each(response, function(index, item) {

                    // if (item.file != null) {
                    //     var link = '<a href="/downloadfiletatanan/' + btoa(item.id) + '" target="_blank" class="btn btn-primary btn-sm waves-effect waves-float waves-light">Lihat</a>';
                    // }else{
                    //     var link = '<a href="javascript:void(0)" class="btn btn-danger btn-sm waves-effect waves-float waves-light">Kosong</a>';
                    // }
                    var link = '<a href="javascript:void(0)" class="btn btn-primary btn-sm waves-effect waves-float waves-light tambahurl" data-id="'+item.id+'" data-url="'+item.file+'">Lihat</a ';

                    var jawaban = '';
                    var penilaian = '';
                    var justify = '';


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

                    // Periksa apakah jawaban_c ada
                    if(item.jawaban_c) {
                        var checkedC = item.jawaban === 'c' ? 'checked' : '';

                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="c" value="'+item.nilai_c+'" ' + checkedC + '> '+item.jawaban_c + '<br><br>';
                    }

                    // Periksa apakah jawaban_d ada
                    if(item.jawaban_d) {
                        var checkedD = item.jawaban === 'd' ? 'checked' : '';

                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="d" value="'+item.nilai_d+'" ' + checkedD + '> '+item.jawaban_d + '<br><br>';
                    }
                    if(item.status == 0) {
                        var checkedReject = item.jawaban === 'd' ? 'checked' : '';

                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="d" value="'+item.nilai_d+'" ' + checkedD + '> '+item.jawaban_d + '<br><br>';
                    }
                    var checkedV1 = '';
                    var checkedV0 = '';
                    if (item.status == 1) {
                        checkedV1 = 'checked';
                    }
                    if (item.status == 2) {
                        checkedV0 = 'checked';
                    }
                    var cacatan = '';
                    if (item.cacatan != null ) {
                        cacatan = item.cacatan;
                    }

                    penilaian += `<div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="validasi`+item.id+`" data-id="`+item.id+`" value="2" `+checkedV0+` />
                                    <label class="form-check-label">
                                        Ditolak
                                    </label>
                                </div>
                                <br>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="validasi`+item.id+`" data-id="`+item.id+`" value="1" `+checkedV1+`  />
                                    <label class="form-check-label">
                                        Disetujui
                                    </label>
                                </div>`;
                    justify += `<div >
                                    <textarea class="form-control"  id="cacatan`+item.id+`" style="height: 50px">`+ cacatan +`</textarea>
                                </div>`;

                    var indikator = '';
                    var nilaix = 'Belum disi';
                    if (item.nilai == null) {
                        nilaix = 'Belum disi';
                    }else{
                        nilaix = item.nilai;
                    }

                    // Periksa apakah indikator ada
                    $.each(item.indikator, function(index, item) {
                        indikator += '<b>' + item + '</b>';
                    });
                    // munculkan solusi berdasarkan indikator dari tatanan
                    // Tambahkan data ke dalam tabel
                    $('#kt_datatable_fixed_header tbody').append(
                        '<tr>' +
                            '<td colspan="6">' + indikator + '</td>' +
                        '</tr>' +
                        '<tr>' +
                            // '<td>' + (index + 1) + '</td>' +
                            '<td>' + item.no_pertanyaan + '</td>' +
                            '<td>' + item.pertanyaan +'</br></br>'+jawaban+ '</td>' +
                            '<td>  <span class="badge badge-primary ">' + nilaix +'</span></td>' +

                            '<td class="text-center">' + link + '</td>' +
                            '<td class="text-center">'+justify+'</td>' +
                            '<td>'+penilaian+'</td>' +


                        '</tr>'
                    );
                    $("input[name="+item.id+"]").on('click', function(){
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
                                        url: "/validator/pengiisiansoal",
                                        type: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        data: {
                                            dataId: dataId,
                                            jawaban: jawaban,
                                            tatanan_id: tatanan_id,
                                            periode_id: periode_id,
                                            nilai:nilai,
                                            tag : 'not'
                                        },
                                        success: function(response) {
                                            var user_id = $('#user_id').val();
                                        loadDataPertanyaan(tatanan_id, periode_id, user_id);
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Terjadi kesalahan:', error);
                                            // Tangani kesalahan jika ada
                                        }
                                    });
                            }
                    });
                    $("input[name=validasi"+item.id+"]").on('click', function(){
                            var radioValue = $("input[name=validasi"+item.id+"]:checked").val();

                            if(radioValue){
                                var nilai = $(this).val();
                                var dataId = $(this).data('id');
                                var tatanan_id = $("#pilihitatanan").val();
                                var periode_id = $("#periode_id").val();

                                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                                // ajax post
                                $.ajax({
                                        url: "/validator/pengiisiansoal",
                                        type: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        data: {
                                            dataId: dataId,
                                            jawaban: jawaban,
                                            tatanan_id: tatanan_id,
                                            periode_id: periode_id,
                                            validator:nilai,
                                            tag : 'validator'
                                        },
                                        success: function(response) {
                                            var user_id = $('#user_id').val();
                                        loadDataPertanyaan(tatanan_id, periode_id, user_id);
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Terjadi kesalahan:', error);
                                            // Tangani kesalahan jika ada
                                        }
                                    });
                            }
                    });
                    $("#cacatan"+item.id+"").on('input', function() {
                        var value = $(this).val();

                        var dataId = item.id;
                        var tatanan_id = $("#pilihitatanan").val();
                        var periode_id = $("#periode_id").val();

                        var csrfToken = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                                url: "/validator/pengiisiansoal",
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                data: {
                                    dataId: dataId,
                                    jawaban: jawaban,
                                    tatanan_id: tatanan_id,
                                    periode_id: periode_id,
                                    cacatan:value,
                                    tag : 'cacatan'
                                },
                                success: function(response) {
                                //     var user_id = $('#user_id').val();
                                // loadDataPertanyaan(tatanan_id, periode_id, user_id);
                                },
                                error: function(xhr, status, error) {
                                    // console.error('Terjadi kesalahan:', error);
                                    // // Tangani kesalahan jika ada
                                }
                            });
                    });

                });
            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
    }
     // button previus
     $('body').on('click', '#prev', function (e) {
        e.preventDefault();
        var tatanan = $('#pilihitatanan').val();
        if (tatanan == 1) {
            Swal.fire({
                title: "Perhatian!",
                icon: "warning",
                text: "Anda sudah berada di awal pertanyaan",
            });
        }else if (tatanan == 2) {
            $('#pilihitatanan').val(1);
            $('#pilihitatanan').change();
        }else if (tatanan == 3) {
            $('#pilihitatanan').val(2);
            $('#pilihitatanan').change();
        }else if (tatanan == 4) {
            $('#pilihitatanan').val(3);
            $('#pilihitatanan').change();
        }else if (tatanan == 5) {
            $('#pilihitatanan').val(4);
            $('#pilihitatanan').change();
        }else if (tatanan == 6) {
            $('#pilihitatanan').val(5);
            $('#pilihitatanan').change();
        }else if (tatanan == 7) {
            $('#pilihitatanan').val(6);
            $('#pilihitatanan').change();
        }else if (tatanan == 8) {
            $('#pilihitatanan').val(7);
            $('#pilihitatanan').change();
        }else if (tatanan == 9) {
            $('#pilihitatanan').val(8);
            $('#pilihitatanan').change();
        }
    });
    // button next
    $('body').on('click', '#next', function (e) {
        e.preventDefault();

        var tatanan = $('#pilihitatanan').val();
        if (tatanan == 1) {
            $('#pilihitatanan').val(2);
            $('#pilihitatanan').change();
        }else if (tatanan == 2) {
            $('#pilihitatanan').val(3);
            $('#pilihitatanan').change();
        }else if (tatanan == 3) {
            $('#pilihitatanan').val(4);
            $('#pilihitatanan').change();
        }else if (tatanan == 4) {
            $('#pilihitatanan').val(5);
            $('#pilihitatanan').change();
        }else if (tatanan == 5) {
            $('#pilihitatanan').val(6);
            $('#pilihitatanan').change();
        }else if (tatanan == 6) {
            $('#pilihitatanan').val(7);
            $('#pilihitatanan').change();
        }else if (tatanan == 7) {
            $('#pilihitatanan').val(8);
            $('#pilihitatanan').change();
        }else if (tatanan == 8) {
            $('#pilihitatanan').val(9);
            $('#pilihitatanan').change();
        }else if (tatanan == 9) {
            Swal.fire({
                title: "Konfirmasi",
                icon: "question",
                text: "Anda telah ada di akhir pertanyaan , apakah anda ingin mensetujui pengisian ini?",
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    postpenilaian();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Perhatian!",
                        icon: "warning",
                        text: "Assement tidak simpan",
                    });
                }
            }
        );
        }
    });


    function postpenilaian(){
        var periode_id = $("#periode_id").val();
        var main_id = $("#main_id").val();
        var dinas_id = $("#dinas_id").val();
        var user_id = $("#user_id").val();

        var postData = {
                id: periode_id,
                id_dinas : dinas_id,
                id_main : main_id,
                id_user : user_id,
            };
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/validator/penilaian', // Ganti dengan URL endpoint yang sesuai di server Anda
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
                        window.location.href = "/validator";
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire("Gagal", "Pengisian tidak disimpan.", "error");
                }
            });
    }
    function postpertanyaan(){
        var periode_id = $("#periode_id").val();
        var main_id = $("#main_id").val();
        var dinas_id = $("#dinas_id").val();
        var user_id = $("#user_id").val();

        var postData = {
                id: periode_id,
                id_main : main_id,
                status : 3,
                prosess : 'revisi',
            };
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/validator/submitpengisian', // Ganti dengan URL endpoint yang sesuai di server Anda
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
                        window.location.href = "/validator";
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire("Gagal", "Pengisian tidak disimpan.", "error");
                }
            });
    }

</script>
@endsection
