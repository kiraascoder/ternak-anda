<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Perawatan</title>

</head>


<body>
    <h2>Form Tambah Perawatan</h2>
    <a href="{{ route('penyuluh.perawatan') }}">Kembali</a>
    <form action="{{ route('perawatan.store') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div>
            <label for="idTernak">Pilih Ternak:</label><br>
            <select name="idTernak" id="idTernak" onchange="setIdPeternak(this)">
                @foreach ($ternaks as $ternak)
                    <option value="{{ $ternak->idTernak }}" data-idpemilik="{{ $ternak->pemilik->idUser }}">
                        {{ $ternak->idTernak }}. {{ $ternak->namaTernak }} - Nama Pemilik : {{ $ternak->pemilik->nama }}
                    </option>
                @endforeach
            </select>

        </div>
        <div>
            <label for="tanggalPerawatan">Tanggal Perawatan:</label><br>
            <input type="date" name="tanggalPerawatan" id="tanggalPerawatan" required>
        </div>

        <div>
            <label for="jenisPerawatan">Jenis Perawatan:</label><br>
            <input type="text" name="jenisPerawatan" id="jenisPerawatan" required>
        </div>
        <div>
            <label for="deskripsi">Deskripsi</label><br>
            <input type="text" name="deskripsi" id="deskripsi" required>
        </div>


        <br>
        <button type="submit">Simpan</button>
    </form>
</body>

</html>
