<?php
session_start();
if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['name'])) {
    $login = htmlentities($_POST['login']);
    $password = htmlentities($_POST['password']);
    $name = htmlentities($_POST['name']);
}
if (empty($login) || empty($password) || empty($name)) {
    die("Вы не ввели все данные!");
}
$db = mysqli_connect("localhost", $argv[1], $argv[2]);
mysqli_select_db($db, $argv[3]);
$result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
$myRow = mysqli_fetch_array($result);
if (!empty($myRow['id'])) {
    die("Пользователь с таким именем уже существует.");
}
$result = mysqli_query($db,
    "INSERT INTO users (login, password, name) VALUES ('$login', '$password', '$name')");

if ($result == 'TRUE') {
    $_SESSION['login'] = $login;
    $_SESSION['name'] = $name;
    echo "Регистрация прошла успешно. <br>";
    echo "<a href='../index.php'>Перейти на мою страницу</a>";
} else {
    echo "Произошла ошибка. Попробуйте еще раз.";
}
