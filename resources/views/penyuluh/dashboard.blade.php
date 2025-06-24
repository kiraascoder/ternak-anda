<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Document</title>
</head>

<body>
    <p>Selamat Datang {{ Auth::user()->nama }} Sebagai {{ Auth::user()->role }}</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>


    <a href="{{ route('penyuluh.laporan') }}">Laporan</a>
    <a href="{{ route('penyuluh.pakan') }}">Pakan</a>
    <a href="{{ route('penyuluh.perawatan') }}">Perawatan</a>
    <a href="{{ route('penyuluh.konsultasi') }}">Konsultasi</a>

</body>

</html>
