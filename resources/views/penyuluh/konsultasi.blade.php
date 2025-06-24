<p>Konsultasi Ternak {{ Auth::user()->nama }}</p>

<a href="{{ route('penyuluh.dashboard') }}">Dashboard</a>

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
            <th>Berikan Respon</th>
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
                    <a href="{{ route('konsultasi.editView', $konsultasi->idKonsultasi) }}">Berikan Respon</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</a>
