<h1>
    Daftar Laporan Kesehatan Ternak Pakeng Ternak
</h1>



<a href="{{ route('penyuluh.dashboard') }}">Kembali</a>

<a href="{{ route('perawatan.addView') }}">Tambah Perawatan</a>

@foreach ($perawatans as $perawatan)
    <p>{{ $perawatan->ternak->namaTernak }}</p>
    <p>Nama penyuluh : {{ $perawatan->penyuluh->nama ?? 'Peternak Tidak Ditemukan' }}</p>
    <p>Diberikan Pada Tanggal : {{ $perawatan->tanggalPerawatan }}</p>
    <p>Jenis Perawatan : {{ $perawatan->jenisPerawatan }}</p>
    <p> Deskripsi dari Penyuluh : {{ $perawatan->deskripsi }}</p>
    <a href="{{ route('perawatan.detailView', $perawatan->idPerawatan) }}">Detail</a>
    <a href="{{ route('perawatan.editView', $perawatan->idPerawatan) }}">Edit</a>
    <form action="{{ route('perawatan.destroy', $perawatan->idPerawatan) }}" method="POST"
        onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endforeach
