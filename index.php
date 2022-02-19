<?php
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
    header("Location: auth/index.php");
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Simple Chat</title>
    <link rel="stylesheet" href="style/style.css">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="script/script.js"></script>
</head>
<body>
<form id="chat">
    <div class="chat-result" id="chat-result">
        <label for="chat-message">Ваше сообщение</label>
        <input type="text" id="chat-message" name="chat-message" placeholder="Ваше сообщение">
        <input type="submit" value="Отправить">
    </div>
</form>
<a href="auth/logout.php">Выйти</a>
</body>
</html>