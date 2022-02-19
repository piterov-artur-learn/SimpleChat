<?php
session_start();
if (empty($_SESSION['login']) || empty($_SESSION['password'])) {
    die("Вы не вошли на сайт.");
}
$args = getopt("h:l:p:db");
$db = mysqli_connect($args['h'], $args['l'], $args['p']);
mysqli_select_db($db, $args['db']);
$result = mysqli_query($db, "SELECT * FROM users WHERE login='${_SESSION['login']}'");
$myRow = mysqli_fetch_array($result);
echo "Логин: ${myRow['login']}"
    . "Имя: ${myRow['name']}"
    . "Данные: ${myRow['info']}";

echo "<a href='logout.php'>Выйти</a>";