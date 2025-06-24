<h1>Detail Perawatan</h1>

<a href="{{ route('penyuluh.perawatan') }}">Kembali</a>


<p>{{ $perawatan->ternak->namaTernak ?? 'Ternak Tidak Ditemukan' }}</p>
<p> {{ $perawatan->penyuluh->nama ?? 'Peternak Tidak Ditemukan' }}</p>
<p> {{ $perawatan->tanggalPerawatan }}</p>
<p> {{ $perawatan->jenisPerawatan }}</p>
<p> {{ $perawatan->deskripsi }}</p>
