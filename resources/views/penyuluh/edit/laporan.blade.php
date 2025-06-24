<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Ternak</title>
    <script>
        function setIdPeternak(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const idPemilik = selectedOption.getAttribute('data-idpemilik');
            document.getElementById('idPeternak').value = idPemilik;
        }


        window.onload = function() {
            const selectElement = document.getElementById('idTernak');
            if (selectElement) {
                setIdPeternak(selectElement);
            }
        };
    </script>

</head>

<body>
    <h2>Form Tambah Laporan Kesehatan</h2>
    <a href="{{ route('penyuluh.laporan') }}">Kembali</a>
    <form action="{{ route('laporan.edit', $laporan->idLaporan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div>
            <input type="hidden" name="idPeternak" id="idPeternak" required value="">
        </div>

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
            <label for="tanggalLaporan">Tanggal Laporan:</label><br>
            <input type="date" name="tanggalLaporan" id="tanggalLaporan" required
                value="{{ $laporan->tanggalLaporan }}">
        </div>

        <div>
            <label for="kondisi">Kondisi</label><br>
            <input type="text" name="kondisi" id="kondisi" required value="{{ $laporan->kondisi }}">
        </div>

        <div>
            <label for="catatan">Catatan</label><br>
            <input type="text" name="catatan" id="catatan" required value="{{ $laporan->catatan }}">
        </div>
        <br>
        <button type="submit">Simpan</button>
    </form>
</body>

</html>
