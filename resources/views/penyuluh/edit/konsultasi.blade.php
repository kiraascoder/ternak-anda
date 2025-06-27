<p>Tambah Respon Konsultasi</p>

<a href="">Kembali</a>

<div class="container">
    <h2>Isi Respon Konsultasi</h2>

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

    <form action="{{ route('konsultasi.updateRespon', $konsultasi->idKonsultasi) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="respon">Respon:</label>
            <textarea name="respon" id="respon" rows="4" required>{{ old('respon', $konsultasi->respon) }}</textarea>
        </div>

        <button type="submit">Kirim Respon</button>
    </form>
</div>
