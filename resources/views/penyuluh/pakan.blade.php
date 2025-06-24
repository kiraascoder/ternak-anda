<h1>
    Daftar Laporan Kesehatan Ternak Pakeng Ternak
</h1>



<a href="{{ route('penyuluh.dashboard') }}">Kembali</a>

<a href="{{ route('pakan.addView') }}">Tambah Rekomendasi Pakan</a>

@foreach ($pakans as $pakan)
    <p>{{ $pakan->ternak->namaTernak }}</p>
    <p>Nama penyuluh : {{ $pakan->penyuluh->nama ?? 'Peternak Tidak Ditemukan' }}</p>
    <p>Diberikan Pada Tanggal : {{ $pakan->tanggalPakan }}</p>
    <p>Jenis Pakan : {{ $pakan->jenisPakan }}</p>
    <p>Jumlah yang Diberikan : {{ $pakan->jumlah }}</p>
    <p> Saran dari Penyuluh : {{ $pakan->saran }}</p>
    <a href="{{ route('pakan.detailView', $pakan->idRekomendasi) }}">Detail</a>
    <a href="{{ route('pakan.editView', $pakan->idRekomendasi) }}">Edit</a>
    <form action="{{ route('pakan.destroy', $pakan->idRekomendasi) }}" method="POST"
        onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endforeach
