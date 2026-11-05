<style>
    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ccc;
    }

    table tr td {
        padding: 6px;
        font-weight: normal;
        border: 1px solid #ccc;
        font-size: 12px;
    }

    table th {
        border: 1px solid #ccc;
        padding: 8px;
        background-color: #f4f4f4;
        font-size: 12px;
        text-align: center;
    }
</style>

<table>
    <!-- <tr>
        <td align="center">
            <img src="{{ asset('images/header.png') }}" width="50%">
        </td>
    </tr> -->
    <tr>
        <td align="left">
            <strong>Perihal :</strong> {{ $judul }} <br>
            <strong>Periode Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggalAwal)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d-m-Y') }}
        </td>
    </tr>
</table>

<p></p>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th>Nama Acara</th>
            <th>Penyelenggara</th>
            <th width="15%">Jenis Surat</th>
            <th width="12%">Tanggal</th>
            <th width="10%">Waktu</th>
            <th>Tempat</th>
            <th width="12%">Penyusun</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cetak as $row)
        <tr>
            <td align="center"> {{ $loop->iteration }}</td>
            <td> {{ $row->acara }} </td>
            <td> {{ $row->penyelenggara }} </td>
            <td align="center"> {{ $row->jenis_surat }} </td>
            <td align="center"> {{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }} </td>
            <td align="center"> {{ $row->waktu }} </td>
            <td> {{ $row->tempat }} </td>
            <td align="center"> {{ $row->penyusun ?? '-' }} </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    window.onload = function() {
        printStruk();
    }

    function printStruk() {
        window.print();
    }
</script>