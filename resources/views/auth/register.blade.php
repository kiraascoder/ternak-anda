<!-- resources/views/admin/register.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Register Admin</title>
</head>

<body>
    <h2>Register</h2>

    @if ($errors->any())
        <ul style="color: red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.register.submit') }}">
        @csrf
        <label>Nama:</label>
        <input type="text" name="nama" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>No Hp:</label>
        <input type="text" name="phone" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password:</label>
        <input type="password" name="password_confirmation" required><br><br>

        <label>Role:</label>
        <select name="role" required>
            <option value="Peternak">Peternak</option>
            <option value="Penyuluh">Penyuluh</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Sudah punya akun? <a href="{{ route('admin.login') }}">Login di sini</a></p>
</body>

</html>
