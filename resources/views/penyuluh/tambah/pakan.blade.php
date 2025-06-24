<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Pakan</title>

</head>


<body>
    <h2>Form Tambah Pakan Kesehatan</h2>
    <a href="{{ route('penyuluh.pakan') }}">Kembali</a>
    <form action="{{ route('pakan.store') }}" method="POST" enctype="multipart/form-data">
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
            <label for="tanggalRekomendasi">Tanggal Rekomendasi:</label><br>
            <input type="date" name="tanggalRekomendasi" id="tanggalRekomendasi" required>
        </div>

        <div>
            <label for="jenisPakan">Jenis Pakan:</label><br>
            <input type="text" name="jenisPakan" id="jenisPakan" required>
        </div>

        <div>
            <label for="jumlah">Jumlah</label><br>
            <input type="number" name="jumlah" id="jumlah" required>
        </div>
        <div>
            <label for="saran">Saran</label><br>
            <input type="text" name="saran" id="saran" required>
        </div>


        <br>
        <button type="submit">Simpan</button>
    </form>
</body>

</html>
