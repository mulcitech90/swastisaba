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
                    <div class="me-6 my-1">
                        <form method="GET" action="{{ route('pelaporan.lembaga') }}"> <!-- Ganti dengan route yang sesuai -->
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
                            <button onclick="exportTableToExcel('tableID', 'Laporan Kelembagaan')" class="btn btn-success btn-sm mb-3" style="float: right;">
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </button>
                             {{-- <span id="currentUrlDisplay"></span> --}}
                            <table id="tableID" class="table table-row-dashed table-row-gray-800 gy-3 fs-8">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" colspan="24">
                                            <h3 class="text-center">INSTRUMEN PENILIAN</h3>
                                            <h6 class="text-center">PENYELENGGARAN KABUPATEN/KOTA SEHAT </h6>
                                            <h6 class="text-center">(KELEMBAGAAN) </h6>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        @if(!empty($result))
                                            @php
                                                $kelembagaanHeaders = [];
                                                foreach ($result[0]['soal'] as $soal) {
                                                    $kelembagaanHeaders[$soal->nama_kelembagaan][] = $soal->pertanyaan;
                                                }
                                            @endphp

                                            @foreach($kelembagaanHeaders as $nama_kelembagaan => $pertanyaans)
                                                <th colspan="{{ count($pertanyaans) * 2 }}" class="text-center">{{ $nama_kelembagaan }}</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>Wilayah</th>
                                        <!-- Ambil header dari soal pertama -->
                                        @if(!empty($result))
                                            @foreach($result[0]['soal'] as $soal)
                                            <th colspan="3">{{ $soal->pertanyaan }}</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                    <tr>
                                        <th></th>
                                        @if(!empty($result))
                                            @foreach($result[0]['soal'] as $soal)
                                            <th>{{ $soal->jawaban_a }}</th>
                                            <th>{{ $soal->jawaban_b }}</th>
                                            <th>File Pendukung</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="fs-9">
                                    @foreach($result as $row)
                                        <tr>
                                            <td>{{ $row['wilayah'] }}</td>
                                            @foreach($row['soal'] as $soal)
                                                @if($soal->jawaban == 'Ada')
                                                    <td>{{$soal->jawaban}}</td></td>
                                                    <td></td>

                                                @elseif($soal->jawaban == 'Tidak Ada')
                                                    <td></td>
                                                    <td>{{$soal->jawaban}}</td>
                                                @endif
                                                <td>
                                                    @if ($soal->file != NULL)
                                                        {{ env('APP_URL') }}/downloadfile/{{ base64_encode($soal->id)}}</td>
                                                    @endif
                                                </td>
                                            @endforeach
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
    var currentUrl = window.location.origin;
    document.getElementById('currentUrlDisplay').innerText = currentUrl;
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

