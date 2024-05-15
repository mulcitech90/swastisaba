@extends('auth.layouts')
@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Table-->
        <div class="card card-flush mt-6 mt-xl-9">
            <!--begin::Card header-->
            <div class="card-header mt-5">
                <!--begin::Card title-->
                <div class="card-title flex-column">


                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar my-1">
                    <span>Filter :</span>
                    <!--begin::Select-->
                    <div class="me-6 my-1">
                        <form method="GET" action="{{ route('dashboard') }}"> <!-- Ganti dengan route yang sesuai -->
                            <select id="kt_filter_orders" name="periode_id" data-control="select2" data-hide-search="true" class="form-select form-select-solid form-select-sm" onchange="this.form.submit()">
                                @foreach ($periode_list as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $periodeId ? 'selected' : '' }}>{{ $item->periode }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <!--end::Select-->

                </div>
                <!--begin::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <div id="kt_profile_overview_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 gy-7">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800">
                                       <th></th>
                                        <th>Sehat Mandiri</th>
                                        <th>Perkim</th>
                                        <th>Pendidikan</th>
                                        <th>Pasar</th>
                                        <th>Pariwisata</th>
                                        <th>Transportasi</th>
                                        <th>Perindustrian</th>
                                        <th>Sosial</th>
                                        <th>Bencana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> Assesment Kab/Kota</td>
                                        @foreach ($tatanan_averages as $average)
                                            <th>{{ $average }}</th>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                            <span class="d-flex text-gray-900 fw-bold m-0 fs-7 py-2 py-lg-0 gap-2"">Skor Self Assesment Kabupaten/Kota Mengikuti Penghargaan Swasti Saba </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 gy-7">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800">
                                       <th></th>
                                        <th>Swasti Saba</th>
                                        <th>Sehat Mandiri</th>
                                        <th>Perkim</th>
                                        <th>Pendidikan</th>
                                        <th>Pasar</th>
                                        <th>Pariwisata</th>
                                        <th>Transportasi</th>
                                        <th>Perindustrian</th>
                                        <th>Sosial</th>
                                        <th>Bencana</th>
                                        <th>Rata-Rata</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-6">
                                    @foreach ($data_pertatanan as $wilayah)
                                        <tr>
                                            <td>{{ $wilayah['namaWilayah'] }}</td>
                                            <td>
                                                @foreach ($wilayahs as $item)
                                                @if ($item['namaWilayah'] == $wilayah['namaWilayah'])
                                                    {{ $item['kategori'] }}
                                                @endif

                                            @endforeach
                                            </td>
                                            @foreach ($wilayah['nilaiTatanan'] as $nilai)
                                                <td>{{ $nilai['nilai'] }}</td>
                                            @endforeach
                                            <td>
                                                @foreach ($wilayahs as $item)
                                                @if ($item['namaWilayah'] == $wilayah['namaWilayah'])
                                                    {{ $item['rataRataGlobal']}}%
                                                @endif

                                            @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    <!--end::Table-->
                </div>
                <!--end::Table container-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Post-->
</div>
@endsection

@section('scripts')

@endsection

