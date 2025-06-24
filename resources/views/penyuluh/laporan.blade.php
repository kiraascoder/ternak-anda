<h1>
    Daftar Laporan Kesehatan Ternak Pakeng Ternak
</h1>



<a href="{{ route('penyuluh.dashboard') }}">Kembali</a>

<a href="{{ route('laporan.addView') }}">Tambah Laporan Kesehatan</a>

<ul>
    @foreach ($laporans as $laporan)
        <p>{{ $laporan->tanggalLaporan }}</p>
        <p> {{ $laporan->ternak->namaTernak ?? 'Ternak Tidak Ditemukan' }}</p>
        <p> {{ $laporan->peternak->nama ?? 'Peternak Tidak Ditemukan' }}</p>
        <p> {{ $laporan->kondisi }}</p>
        <p> {{ $laporan->catatan }}</p>
        <a href="{{ route('laporan.detailView', $laporan->idLaporan) }}">Detail</a>
        <a href="{{ route('laporan.editView', $laporan->idLaporan) }}">Edit</a>
        <form action="{{ route('laporan.destroy', $laporan->idLaporan) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    @endforeach
</ul>
