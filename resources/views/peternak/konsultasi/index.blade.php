<p>Konsultasi Ternak {{ Auth::user()->nama }}</p>

<a href="{{ route('peternak.dashboard') }}">Dashboard</a>

<a href="{{ route('konsultasi.addView') }}">Tambah Konsultasi</a>

<h2>Konsultasi Saya</h2>

<table border="1">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Ternak</th>
            <th>Penyuluh</th>
            <th>Peternak</th>
            <th>Keluhan</th>
            <th>Respon</th>
            <td>Aksi</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($konsultasis as $konsultasi)
            <tr>
                <td>{{ $konsultasi->tanggalKonsultasi }}</td>
                <td>{{ $konsultasi->ternak->namaTernak ?? 'Ternak Tidak Ditemukan' }}</td>
                <td>{{ $konsultasi->penyuluh->nama ?? 'Penyuluh Tidak Ditemukan' }}</td>
                <td>{{ $konsultasi->peternak->nama ?? 'Penyuluh Tidak Ditemukan' }}</td>
                <td>{{ $konsultasi->keluhan }}</td>
                <td>{{ $konsultasi->respon ?? '-' }}</td>
                <td>
                    <form action="{{ route('konsultasi.destroy', $konsultasi->idKonsultasi) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
</a>
