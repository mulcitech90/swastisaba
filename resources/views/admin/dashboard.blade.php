@extends('auth.layouts')
@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @else
        <div class="alert alert-success">
            You are logged in!
        </div>
    @endif
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
                    <div class="me-12 my-1">
                        <select id="kt_filter_year" name="year" data-control="select2" data-hide-search="true" class="form-select form-select-solid form-select-sm">
                            <option value="All" selected="selected">Wilayah</option>
                            <option value="Jakarta Barat">Jakarta Barat</option>
                            <option value="Jakarta Pusat">Jakarta Pusat</option>
                            <option value="Jakarta Selatan">Jakarta Selatan</option>
                            <option value="Jakarta Timur"> Jakarta Timur</option>
                            <option value="Jakarta Utara">Jakarta Utara</option>
                            <option value="Kepulauan Seribu">Kepulauan Seribu</option>


                        </select>
                    </div>
                    <!--end::Select-->
                    <!--begin::Select-->
                    <div class="me-6 my-1">
                        <select id="kt_filter_orders" name="orders" data-control="select2" data-hide-search="true" class="form-select form-select-solid form-select-sm">
                            <option value="All" selected="selected">Periode</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>

                        </select>
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
                                        <td>
                                            Assesment Kab/Kota
                                        </td>
                                        <td> <span class="badge badge-light-danger fw-bold px-4 py-3">64,45%</span></td>
                                        <td>94,36%</td>
                                        <td>98,99%</td>
                                        <td>92,02%</td>
                                        <td>95,94%</td>
                                        <td>92,28%</td>
                                        <td>92,85%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
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
                                        <th>Total</th>
                                        <th>Rata-Rata</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-6">
                                    <tr>
                                        <td> Jakarta Barat </td>
                                        <td>PADAPA</td>
                                        <td>94,36%</td>
                                        <td>98,99%</td>
                                        <td>92,02%</td>
                                        <td>95,94%</td>
                                        <td>92,28%</td>
                                        <td>92,85%</td>
                                        <td> <span class="badge badge-light-success fw-bold px-4 py-3">90,45%</span></td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                    </tr>
                                    <tr>
                                        <td> Jakarta Pusat </td>
                                        <td>WISTARA</td>
                                        <td>94,36%</td>
                                        <td>98,99%</td>
                                        <td>92,02%</td>
                                        <td>95,94%</td>
                                        <td>92,28%</td>
                                        <td>92,85%</td>
                                        <td>96,47%</td>
                                        <td> <span class="badge badge-light-success fw-bold px-4 py-3">90,45%</span></td>
                                        <td>88,00%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                    </tr>
                                    {{-- Jakarta Selatan --}}
                                    <tr>
                                        <td> Jakarta Selatan  </td>
                                        <td>WISTARA</td>
                                        <td>94,36%</td>
                                        <td>94,36%</td>

                                        <td>98,99%</td>
                                        <td>92,02%</td>
                                        <td>95,94%</td>
                                        <td>92,28%</td>
                                        <td>92,85%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                        <td> <span class="badge badge-light-success fw-bold px-4 py-3">90,45%</span></td>
                                        <td>88,00%</td>
                                    </tr>
                                    <tr>
                                        <td> Jakarta Timur  </td>
                                        <td>WISTARA</td>
                                        <td>94,36%</td>
                                        <td>98,99%</td>
                                        <td>92,02%</td>
                                        <td>94,36%</td>
                                        <td>95,94%</td>
                                        <td> <span class="badge badge-light-success fw-bold px-4 py-3">90,45%</span></td>
                                        <td>92,85%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                    </tr>
                                    <tr>
                                        <td> Jakarta Utara  </td>
                                        <td>WISTARA</td>
                                        <td>94,36%</td>
                                        <td>98,99%</td>
                                        <td>92,02%</td>
                                        <td>95,94%</td>
                                        <td>92,28%</td>
                                        <td>94,36%</td>
                                        <td>92,85%</td>
                                        <td> <span class="badge badge-light-warning fw-bold px-4 py-3">70,45%</span></td>
                                        <td>88,00%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                    </tr>
                                    <tr>
                                        <td> Kepulauan Seribu  </td>
                                        <td>WISTARA</td>
                                        <td>94,36%</td>
                                        <td> <span class="badge badge-light-success fw-bold px-4 py-3">90,45%</span></td>
                                        <td>98,99%</td>
                                        <td>92,02%</td>
                                        <td>95,94%</td>
                                        <td>92,28%</td>
                                        <td>92,85%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                        <td>96,47%</td>
                                        <td>88,00%</td>
                                    </tr>
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

