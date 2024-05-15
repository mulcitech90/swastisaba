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
                        <form method="GET" action="{{ route('pelaporan.tatanan') }}"> <!-- Ganti dengan route yang sesuai -->
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
                            <button onclick="exportTableToExcel('tableID', 'Rekapitulasi_Hasil_Bedah_Dokumen_KKS')" class="btn btn-success btn-sm mb-3" style="float: right;">
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </button>
                            <table id="tableID" class="table table-row-dashed table-row-gray-800 gy-3 fs-8">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" colspan="24">
                                            <h3 class="text-center">REKAPITULASI HASIL BEDAH DOKUMEN KKS</h3>
                                            <h6 class="text-center">NILAI DOKUMEN SELF ASSESMENT KABUPATEN/KOTA SEHAT</h6>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="align-middle" rowspan="2">NO</th>
                                        <th class="align-middle" rowspan="2">KABUPATEN/KOTA</th>
                                        <th colspan="2" class="text-center">TATANAN-1</th>
                                        <th colspan="2" class="text-center">TATANAN-2</th>
                                        <th colspan="2" class="text-center">TATANAN-3</th>
                                        <th colspan="2" class="text-center">TATANAN-4</th>
                                        <th colspan="2" class="text-center">TATANAN-5</th>
                                        <th colspan="2" class="text-center">TATANAN-6</th>
                                        <th colspan="2" class="text-center">TATANAN-7</th>
                                        <th colspan="4" class="text-center">TATANAN-8</th>
                                        <th colspan="2" class="text-center">TATANAN-9</th>
                                        <th class="text-center align-middle" rowspan="2">RATA-RATA PERSEN TOTAL NILAI</th>
                                        <th class="text-center align-middle" rowspan="2">KRITERIA NILAI DOKUMEN KKS (MS/TMS)</th>
                                        <th class="text-center align-middle" rowspan="2" colspan="2">KEPUTUSAN AKHIR</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center align-middle">TOTAL NILAI</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI (MEMILIKI KAT)</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI (TIDAK MEMILIKI KAT)</th>
                                        <th class="text-center align-middle">%</th>
                                        <th class="text-center align-middle">TOTAL NILAI</th>
                                        <th class="text-center align-middle">%</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-9">
                                    {{-- @dd($data_pertatanan, $wilayahs ); --}}
                                    @foreach ($data_pertatanan as $wilayah)
                                        <tr>
                                            {{-- no index --}}
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle">{{ $wilayah['namaWilayah'] }}</td>

                                            @foreach ($wilayah['nilaiTatanan'] as $nilai)
                                                <td class="text-center align-middle">{{ number_format($nilai['totalNilai'], 2) }}</td>
                                                <td class="text-center align-middle">{{ $nilai['nilai'] }}</td>
                                                @if ($nilai['idTatanan'] == 8)
                                                    <td class="text-center align-middle">0</td>
                                                    <td class="text-center align-middle">0%</td>
                                                @endif
                                            @endforeach
                                            <td class="text-center align-middle">
                                                @foreach ($wilayahs as $item)
                                                    @if ($item['namaWilayah'] == $wilayah['namaWilayah'])
                                                        {{ $item['rataRataGlobal'] }}%
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="text-center align-middle"></td>
                                            <td class="text-center align-middle"></td>
                                            <td>
                                                @foreach ($wilayahs as $item)
                                                    @if ($item['namaWilayah'] == $wilayah['namaWilayah'])
                                                        @if ($item['kategori'] == 'WISTARA')
                                                            <span class="badge badge-success fs-10">{{ $item['kategori'] }}</span>
                                                        @elseif ($item['kategori'] == 'WIWERDA')
                                                            <span class="badge badge-light-success fs-10">{{ $item['kategori'] }}</span>
                                                        @elseif ($item['kategori'] == 'PADAPA')
                                                            <span class="badge badge-light-warning fs-10">{{ $item['kategori'] }}</span>
                                                        @elseif ($item['kategori'] == 'Tidak Lolos')
                                                            <span class="badge badge-light-danger fs-10">{{ $item['kategori'] }}</span>
                                                        @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename = '') {
        var table = document.getElementById(tableID);
        var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet 1" });

        // Center align the header cells
        var sheet = workbook.Sheets["Sheet 1"];
        var range = XLSX.utils.decode_range(sheet['!ref']);
        for (var C = range.s.c; C <= range.e.c; ++C) {
            var address = XLSX.utils.encode_cell({ r: 0, c: C });
            if (!sheet[address]) continue;
            if (!sheet[address].s) sheet[address].s = {};
            sheet[address].s.alignment = { vertical: "center", horizontal: "center" };
        }

        var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'binary' });

        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }

        var downloadLink = document.createElement("a");
        var blob = new Blob([s2ab(wbout)], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        var url = URL.createObjectURL(blob);
        downloadLink.href = url;
        downloadLink.download = filename ? filename + '.xlsx' : 'excel_data.xlsx';
        downloadLink.click();
    }
</script>
@endsection

