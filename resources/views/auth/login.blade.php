<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<form method="POST" action="/login">
    @csrf

    <div>
        <input type="email" name="email" placeholder="Correo">
    </div>

    <div>
        <input type="password" name="password" placeholder="Contraseña">
    </div>

    <button type="submit">Entrar</button>
</form>

</body>
</html>
