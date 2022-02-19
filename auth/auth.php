<?php
session_start();
if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = htmlentities($_POST['login']);
    $password = htmlentities($_POST['password']);
}
if (empty($login) || empty($password)) {
    die("Вы не ввели все данные!");
}
$db = mysqli_connect($argv[2], $argv[3], $argv[4]);
mysqli_select_db($db, $argv[5]);
$result = mysqli_query($db, "SELECT * FROM users WHERE login='$login'");
$myRow = mysqli_fetch_array($result);
if (empty($myRow['password'])) {
    die("Неверный логин или пароль.");
} else {
    if ($myRow['password'] == $password) {
        $_SESSION['login'] = $myRow['login'];
        $_SESSION['name'] = $myRow['name'];
        header("Location: ../index.php");
    } else {
        die("Неверный логин или пароль.");
    }
}