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

                            {{-- <a href="javascript:void(0)"  class="btn btn-primary lihatpengisian" data-id="{{$pengisian[0]->id}}"><span>{{$pengisian[0]->totalisi}}/{{$pengisian[0]->totalsoal}}</span></a> --}}

                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <table id="kt_datatable_fixed_header" class="table table-striped table-row-bordered gy-5 gs-7">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800">
                                        <th>No</th>
                                        <th class="text-left">Pertanyaan</th>
                                        <th class="text-center">Status Pengisian</th>
                                        <th class="text-center">Validasi</th>
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
<div class="modal fade" id="urlinfo" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateLabel">Pengisian Pertatanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <table id="kt_datatable_pengisian" class="table table-striped table-row-bordered gy-5 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800">
                                <th>No</th>
                                <th class="text-left">Tatanan</th>
                                <th class="text-center">Total Soal</th>
                                <th class="text-center">Sudah Diisi</th>
                                <th class="text-center">Belum Diisi</th>
                                <th class="text-center">Disetujui</th>
                                <th class="text-center">Ditolak</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="footer-s">

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

                    var link = '<a href="javascript:void(0)" class="btn btn-primary btn-sm waves-effect waves-float waves-light tambahurl" data-id="'+item.id+'" data-url="'+item.file+'">Lihat</a ';

                    var jawaban = '';
                    var penilaian = '';
                    var justify = '';


                    // Periksa apakah jawaban_a ada
                    if(item.jawaban_a) {
                        var checkedA = item.jawaban === 'a' ? 'checked' : '';
                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="a" value="'+item.nilai_a+'" ' + checkedA + ' disabled > '+item.jawaban_a + '<br><br>';
                    }

                    // Periksa apakah jawaban_b ada
                    if(item.jawaban_b) {
                        var checkedB = item.jawaban === 'b' ? 'checked' : '';
                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="b" value="'+item.nilai_b+'" ' + checkedB + ' disabled> '+item.jawaban_b + '<br><br>';
                    }

                    // Periksa apakah jawaban_c ada
                    if(item.jawaban_c) {
                        var checkedC = item.jawaban === 'c' ? 'checked' : '';

                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="c" value="'+item.nilai_c+'" ' + checkedC + ' disabled> '+item.jawaban_c + '<br><br>';
                    }

                    // Periksa apakah jawaban_d ada
                    if(item.jawaban_d) {
                        var checkedD = item.jawaban === 'd' ? 'checked' : '';

                        jawaban += '<input type="radio" class="form-check-input jawaban" name="'+item.id+'" data-id="'+item.id+'" data-jwb="d" value="'+item.nilai_d+'" ' + checkedD + ' disabled> '+item.jawaban_d + '<br><br>';
                    }


                    if (item.status == 1) {
                        validasi = '<span class="badge badge-success ">Disetujui</span>';
                    }else if (item.status == 2) {
                        validasi = '<span class="badge badge-danger ">Ditolak</span>';
                    }else{
                        validasi = '<span class="badge badge-secondary ">Belum divalidasi</span>';
                    }

                    var indikator = '';
                    var telahisi = '<span class="badge badge-danger ">Belum disi</span>';
                    if (item.nilai != null) {
                        telahisi = '<span class="badge badge-success ">Sudah disi</span>';
                    }

                    // <span class="badge badge-primary ">' + nilaix +'</span>

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
                            '<td> '+telahisi+' </td>' +
                            '<td>'+validasi+'</td>' +


                        '</tr>'
                    );

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
                title: "Perhatian!",
                icon: "warning",
                text: "Anda telah ada di akhir pertanyaan",
            });
        }
    });
    $('body').on('click', '.lihatpengisian', function (e) {
        e.preventDefault();
        let dataTarget = e.target;
        id = dataTarget.getAttribute('data-id')
        $.ajax({
            url: "/validator/pengisiansoal/"+id,
            type: "GET",
            success: function(response) {
                $('#footer-s').empty();
                var tableBody = $('#kt_datatable_pengisian tbody');
                tableBody.empty(); // Kosongkan isi tabel sebelumnya jika ada

                var total_pertanyaan = 0;
                var total_dijawab = 0;
                var total_belumdisi = 0;
                var total_disetujui = 0;
                var total_ditolak = 0;
                var total_belumdiperiksa = 0;

                $.each(response, function(index, data) {
                    total_pertanyaan += parseInt(data.total_pertanyaan);
                    total_dijawab += parseInt(data.dijawab);
                    total_belumdisi += parseInt(data.belumdisi);
                    total_disetujui += parseInt(data.disetujui);
                    total_ditolak += parseInt(data.ditolak);
                    total_belumdiperiksa += parseInt(data.belumdiperiksa);

                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td class="text-left">' + data.nama_tatanan + '</td>' +
                        '<td class="text-center">' + data.total_pertanyaan + '</td>' +
                        '<td class="text-center">' + data.dijawab + '</td>' +
                        '<td class="text-center">' + data.belumdisi + '</td>' +
                        '<td class="text-center">' + data.disetujui + '</td>' +
                        '<td class="text-center">' + data.ditolak + '</td>' +
                    '</tr>';
                    tableBody.append(row);
                });

                // Menambahkan baris total di akhir tabel
                var totalRow = '<tr>' +
                    '<td colspan="2" class="text-left"><strong>Total</strong></td>' +
                    '<td class="text-center"><strong>' + total_pertanyaan + '</strong></td>' +
                    '<td class="text-center"><strong>' + total_dijawab + '</strong></td>' +
                    '<td class="text-center"><strong>' + total_belumdisi + '</strong></td>' +
                    '<td class="text-center"><strong>' + total_disetujui + '</strong></td>' +
                    '<td class="text-center"><strong>' + total_ditolak + '</strong></td>' +
                '</tr>';
                tableBody.append(totalRow);

                $('#footer-s').append(
                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>'
                );

            },
            error: function(xhr) {
                console.log(xhr);
            }
        });

        $('#urlinfo').modal('show');
    });
</script>
@endsection
