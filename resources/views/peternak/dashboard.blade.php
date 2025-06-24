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
    <nav>
        <ul>
            <li><a href="{{ route('ternak.index') }}">Ternak</a></li>
            <li><a href="{{ route('konsultasi.index') }}">Konsultasi</a></li>
        </ul>
    </nav>
</body>

</html>
