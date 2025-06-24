<h1>Halaman Detail Laporan Kesehatan</h1>


<a href="{{ route('penyuluh.pakan') }}">Kembali</a>

<p>{{ $pakan->tanggalPakan }}</p>
<p> {{ $pakan->ternak->namaTernak ?? 'Ternak Tidak Ditemukan' }}</p>
<p> {{ $pakan->penyuluh->nama ?? 'Peternak Tidak Ditemukan' }}</p>
<p> {{ $pakan->jumlah }}</p>
<p> {{ $pakan->saran }}</p>
