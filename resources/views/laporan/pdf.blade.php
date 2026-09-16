<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Laporan Surat</title>

<style>

body{
    font-family: DejaVu Sans;
    font-size:12px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid black;
    padding:8px;
}

th{
    background:#dddddd;
}

h2{
    text-align:center;
}

</style>

</head>

<body>

<h2>

LAPORAN SERAH TERIMA SURAT

</h2>

<table>

<tr>

<th>No Agenda</th>
<th>Jenis</th>
<th>Pengirim</th>
<th>Penerima</th>
<th>Status</th>
<th>Tanggal</th>

</tr>

@foreach($surats as $surat)

<tr>

<td>{{ $surat->no_agenda }}</td>
<td>{{ $surat->jenis }}</td>
<td>{{ $surat->pengirim }}</td>
<td>{{ $surat->penerima }}</td>
<td>{{ $surat->status }}</td>
<td>{{ $surat->tanggal }}</td>

</tr>

@endforeach

</table>

</body>

</html>
