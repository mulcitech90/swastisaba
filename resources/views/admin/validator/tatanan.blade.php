@extends('auth.layouts')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
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
                    <span class="card-label fw-bold text-gray-900">Daftar Validasi Instrumen Penilaian</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Home-Validasi-Daftar Validasi Instrumen Penilaian</span>
                </h3>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <div class="mb-3 row">
                    {{-- pilih dinas --}}
                    <div class="col-md-4 text-left">
                        <label for="pilihitatanan" class="form-label">Pilihan Periode</label>
                        <select class="form-control" id="pilihperiode" name="pilihperiode">
                            @foreach ($periode as $item)
                                <option value="{{$item->id}}" data-id="{{$item->periode}}">{{$item->periode}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-8 text-end">

                    </div>
                </div>
                <div class="card card-flush mt-6 mt-xl-9">

                    <table id="kt_datatable_fixed_header" class="table table-striped table-row-bordered gy-5 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800">
                                <th>No</th>
                                <th>Wilayah</th>
                                <th class="text-center">Periode</th>
                                <th class="text-center">Jumlah Tatanan</th>
                                {{-- <th class="text-center">Persentase</th> --}}
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

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

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    // Memastikan kode dieksekusi setelah dokumen siap
$(document).ready(function() {
    // Inisialisasi select2 setelah dokumen siap
    $('#pilihperiode').select2();
    var periode = $('#pilihperiode').val(); // Mengambil nilai dari pilihan periode
    var dataname = $('#pilihperiode option:selected').data('id');// Mengambil nilai data-name dari pilihan periode
    init(periode, dataname);
    // Menambahkan event listener untuk pemilihan periode
    $('#pilihperiode').change(function() {
        var id_periode = $(this).val(); // Mengambil nilai dari pilihan periode
        var dataname = $(this).find('option:selected').data('id'); // Mengambil nilai data-name dari pilihan periode
        init(id_periode, dataname); // Memanggil fungsi init dengan id_periode dan dataname yang dipilih
    });

    function init(periode, dataname) {
        $.ajax({
            url: "/validator/pemdalist/"+periode,
            type: "GET",
            success: function(response) {
                $('#kt_datatable_fixed_header tbody').empty();
                $.each(response, function(index, item) {
                    var status = '';
                    if (item.status == 'Belum mengisi') {
                        status = '<span  class="badge badge-light-info ">Belum mengisi</span>';
                    } else if(item.status == 'Dalam Pengisian') {
                        status = '<span  class="badge badge-light-primary ">Dalam Pengisian</span>';
                    }else if(item.status == 'Verifikasi') {
                        status = '<span  class="badge badge-secondary ">Verifikasi</span>';
                    }else if(item.status == 'Perbaikan') {
                        status = '<span  class="badge badge-light-warning ">Perbaikan</span>';
                    }else if(item.status == 'Selesai') {
                        status = '<span  class="badge badge-light-success ">Selesai</span>';
                    }

                    var action = '<a href="/validator/assessment/'+ btoa(item.id_user)+'?pr='+ btoa(periode)+'" class="menu-link px-3"><i class="bi bi-clipboard-check"></i></a>';
                    $('#kt_datatable_fixed_header tbody').append(
                        '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + item.name + '</td>' +
                            '<td class="text-center">' + dataname +'</td>' +
                            '<td class="text-center">9</td>' +
                            // '<td class="text-center">9</td>' +
                            '<td class="text-center">'+status+'</td>' +
                            '<td class="text-center">'+action+'</td>' +
                        '</tr>'
                    );
                });
            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
    }
});

</script>
@endsection
