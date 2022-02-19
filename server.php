<?php
define("PORT", $argv[1]);
require_once 'classes/Chat.php';

$chat = new Chat();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, '127.0.0.1', PORT);

socket_listen($socket);

$clientSocketArray = [$socket];

while (true) {
    $newSocketArray = $clientSocketArray;
    $except = null;
    socket_select($newSocketArray, $except, $except, 0, 10);

    if (in_array($socket, $newSocketArray)) {
        $newSocket = socket_accept($socket);
        $clientSocketArray[] = $newSocket;

        $header = socket_read($newSocket, 1024);
        $chat->sendHeaders($header, $newSocket, "localhost/simplechat", PORT);

        socket_getpeername($newSocket, $clientIpAddress);
        echo $clientIpAddress . PHP_EOL;

        $connection = $chat->newConnection($clientIpAddress);
        $chat->sendMessage($connection, $clientSocketArray);

        $newSocketArrayIndex = array_search($socket, $newSocketArray);
        unset($newSocketArray[$newSocketArrayIndex]);
    }

    foreach ($newSocketArray as $newSocketArrayResource) {
        while (socket_recv($newSocketArrayResource, $socketData, 1024, 0) >= 1) {
            $socketMessage = $chat->unsealMessage($socketData);
            $messageObject = json_decode($socketMessage);

            $chatMessage = $chat->createChatMessage($messageObject->chat_message);
            $chat->sendMessage($chatMessage, $clientSocketArray);
            break 2;
        }

        $socketData = @socket_read($newSocketArrayResource, 1024, PHP_NORMAL_READ);
        if ($socketData === false) {
            $newSocketArrayIndex = array_search($newSocketArrayResource, $clientSocketArray);
            unset($newSocketArray[$newSocketArrayIndex]);
        }
    }
}
