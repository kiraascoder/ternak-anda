<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Ternak</title>
</head>

<body>
    <h2>Form Tambah Data Ternak</h2>
    <a href="{{ route('ternak.index') }}">Kembali</a>
    <form action="{{ route('ternak.store') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div>
            <label for="namaTernak">Nama Ternak:</label><br>
            <input type="text" name="namaTernak" id="namaTernak" required>
        </div>

        <div>
            <label for="tanggalLahir">Tanggal Lahir:</label><br>
            <input type="date" name="tanggalLahir" id="tanggalLahir" required>
        </div>

        <div>
            <label for="berat">Berat (kg):</label><br>
            <input type="number" name="berat" id="berat" required>
        </div>

        <div>
            <label for="fotoTernak">Foto Ternak:</label><br>
            <input type="file" name="fotoTernak" id="fotoTernak" accept="image/*">
        </div>

        <br>
        <button type="submit">Simpan</button>
    </form>
</body>

</html>
