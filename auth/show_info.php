<?php
session_start();
if (empty($_SESSION['login']) || empty($_SESSION['password'])) {
    die("Вы не вошли на сайт.");
}
$db = mysqli_connect("localhost", "homestead", "secret");
mysqli_select_db($db, "SimpleChat");
$result = mysqli_query($db, "SELECT * FROM users WHERE login='${_SESSION['login']}'");
$myRow = mysqli_fetch_array($result);
echo "Логин: ${myRow['login']}"
    . "Имя: ${myRow['name']}"
    . "Данные: ${myRow['info']}";

echo "<a href='logout.php'>Выйти</a>";