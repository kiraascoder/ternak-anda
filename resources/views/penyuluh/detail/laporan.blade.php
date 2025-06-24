<h1>Halaman Detail Laporan Kesehatan</h1>


<a href="{{ route('penyuluh.laporan') }}">Kembali</a>

<p>{{ $laporan->tanggalLaporan }}</p>
<p> {{ $laporan->ternak->namaTernak ?? 'Ternak Tidak Ditemukan' }}</p>
<p> {{ $laporan->peternak->nama ?? 'Peternak Tidak Ditemukan' }}</p>
<p> {{ $laporan->kondisi }}</p>
<p> {{ $laporan->catatan }}</p>

