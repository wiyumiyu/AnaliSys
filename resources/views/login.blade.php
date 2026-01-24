@if ($errors->any())
    <div style="color:red;">
        {{ $errors->first() }}
    </div>
@endif

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <form method="POST" action="/login">
        @csrf

        <input type="email" name="email" placeholder="Correo">
        <br><br>
        <input type="password" name="password" placeholder="Contraseña">
        <br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
