@extends('adminlte::page')

@section('title', 'Hasil Laporan Absensi')


@section('content_header')
    <!-- <h1>Laporan Absensi</h1> -->
@stop

@section('content')
    <button class="btn btn-primary" onclick="printReport()"><i class="fa fa-print"></i> Cetak</button><br><br>
            <div id="report-content">
                <div class="card">
                    <div class="card-body">
                        <h3>Laporan Absensi Karyawan</h3>
                        <span>Per tanggal : {{ $startDate->format('d-m-Y') }} hingga {{ $endDate->format('d-m-Y') }}</span>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No. Anggota</th>
                                        <th>Nama Karyawan</th>
                                        <th>Total Jam Kerja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($id=1)
                                    @if($totalJamKerjaPerKaryawan->count()>0)
                                    @foreach($totalJamKerjaPerKaryawan as $karyawanId => $totalJam)
                                        <tr>
                                            <td>{{ $id }}</td>
                                            <td>{{ \App\Models\Karyawan::find($karyawanId)->nomor_anggota }}</td>
                                            <td>{{ \App\Models\Karyawan::find($karyawanId)->nama }}</td>
                                            <td>{{ $totalJam }} jam</td>
                                        </tr>
                                        @php($id++)
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada absensi</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
@stop
@section('js')
<script>
    function printReport() {
        var printContents = document.getElementById('report-content').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
@stop
