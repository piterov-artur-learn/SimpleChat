<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../style/style.css">
    <title>Вход</title>
</head>
<body>
<h2>Вход</h2>
<form action="auth.php" method="post">
    <label for="login">Введите логин:</label>
    <input type="text" name="login" id="login" placeholder="Введите логин">
    <label for="password">Введите пароль:</label>
    <input type="password" name="password" id="password" placeholder="Введите пароль">
    <input type="submit" name="submit" value="Войти">
</form>
<a href="reg.php">Зарегистрироваться</a>
</body>
</html>
