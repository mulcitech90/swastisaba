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
                    <span class="card-label fw-bold text-gray-900">Daftar Periode dan Instrumen Penilaian</span>
                </h3>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                <div class="card card-flush mt-6 mt-xl-9">
                    <table id="kt_datatable_fixed_header" class="table table-striped table-row-bordered gy-5 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800">
                                <th>Periode</th>
                                <th>Tahun</th>
                                <th class="text-center">Jumlah Tatanan</th>
                                <th class="text-center">Jumlah Soal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $prevPeriode = null; // Variabel untuk menyimpan nilai periode sebelumnya
                            @endphp

                            @forelse ($data as $item)
                                @if ($item->periode_name != $prevPeriode)
                                    <tr>
                                        <td colspan="6">{{ $item->periode_name }}</td>
                                        @php $prevPeriode = $item->periode_name @endphp
                                    </tr>
                                @endif
                                <tr>
                                    <td></td>
                                    <td>{{ $item->tahun_periode }}</td>
                                    <td class="text-center">{{CountSoal($item->id_periode, 'jumlahtatanan')}}</td>
                                    <td class="text-center">{{CountSoal($item->id_periode, 'jumlahsoal')}}</td>
                                    <td>
                                        <div class="text-center">
                                            @if ($item->status_akses == 0)
                                            <a href="#" class="menu-link px-3">
                                                <span class="badge badge-light-danger fs-9">Belum Aktif</span>
                                            </a>
                                            @else
                                            <a href="#" class="menu-link px-3" onclick="Pengisian({{ $item->id_periode }})">
                                                <i class="bi bi-clipboard-check"></i> <!-- Ikon Delete -->
                                            </a>

                                            @endif
                                       </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Data tidak ditemukan</td>
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

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>

    function Pengisian(periodeId) {
        Swal.fire({
            title: "Konfirmasi",
            icon: "question",
            text: "Anda yakin akan melanjutkan untuk instrumen pengisian ?",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                var postData = {
                    prosess: "tatanan",
                }
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/pengisianform/start', // Ganti dengan URL endpoint yang sesuai di server Anda
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: postData,
                    success: function(response) {
                        init();
                        Swal.fire({
                            title: "Berhasil!",
                            icon: "success",
                            text: "Data Berhasil di upload",
                        });               // Tutup modal update
                        init();
                        $('#urlevidence').val('');
                        $('#urlinfo').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
                window.location.href = "/pengisianform/assessment/" + btoa(periodeId);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire("Dibatalkan", "Pengisian tidak disimpan.", "error");
            }
        });
    }
</script>
@endsection
