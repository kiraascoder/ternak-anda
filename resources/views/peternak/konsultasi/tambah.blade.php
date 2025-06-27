<p>Tambah Konsultasi</p>

<a href="">Kembali</a>

<div class="container">
    <h2>Tambah Konsultasi</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('konsultasi.store') }}" method="POST">
        @csrf

        <div>
            <label for="idPenyuluh">Pilih Penyuluh:</label>
            <select name="idPenyuluh" required>
                @foreach ($penyuluhs as $penyuluh)
                    <option value="{{ $penyuluh->idUser }}">{{ $penyuluh->nama }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="idTernak">Pilih Ternak:</label>
            <select name="idTernak" required>
                @foreach ($ternaks as $ternak)
                    <option value="{{ $ternak->idTernak }}">{{ $ternak->namaTernak }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="tanggalKonsultasi">Tanggal Konsultasi:</label>
            <input type="date" name="tanggalKonsultasi" required>
        </div>

        <div>
            <label for="keluhan">Keluhan:</label>
            <textarea name="keluhan" required></textarea>
        </div>

        <button type="submit">Kirim</button>
    </form>
</div>
