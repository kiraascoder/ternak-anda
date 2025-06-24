<p>Ternak Anda : {{ Auth::user()->nama }}</p>

<a href="{{ route('peternak.dashboard') }}">Dashboard</a>

<ul>
    <a href="{{ route('ternak.addView') }}">Tambah Ternak</a>
    @foreach ($ternakSaya as $ternak)
        <p>{{ $ternak->namaTernak }}</p>
        <p> {{ $ternak->tanggalLahir }}</p>
        <p> {{ $ternak->berat }}</p>
        <p> {{ $ternak->fotoTernak }}</p>
        <a href="{{ route('ternak.detailView', $ternak->idTernak) }}">Detail</a>
        <form action="{{ route('ternak.delete', $ternak->idTernak) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus data ternak ini?');" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Hapus</button>
        </form>
    @endforeach
</ul>
