<?php
error_reporting(E_ALL ^ E_WARNING);
class Chat
{
    public function sendHeaders($headersText, $newSocket, $host, $port): void
    {
        $headers = [];
        $tmpLine = preg_split("/\r\n/", $headersText);

        foreach ($tmpLine as $line) {
            $line = trim($line);
            $params = explode(": ", $line);
            $headers[$params[0]] = $params[1];
        }

        $key = $headers['Sec-WebSocket-Key'];

        $sKey = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

        $strHeader = "HTTP/1.1 101 Switching Protocols \r\n"
                    . "Upgrade: websocket\r\n"
                    . "Connection: Upgrade\r\n"
                    . "WebSocket-Origin: $host\r\n"
                    . "WebSocket-Location: ws://$host:$port/server.php\r\n"
                    . "Sec-WebSocket-Accept: $sKey\r\n\r\n";

        socket_write($newSocket, $strHeader, strlen($strHeader));
    }

    public function newConnection($clientIpAddress): string
    {
        $message = "Новый пользователь: $clientIpAddress";
        $messageArray = [
            "message" => $message,
            "type" => "Новое соединение"
        ];
        return $this->sealMessage(json_encode($messageArray));
    }

    public function sealMessage($socketData): string
    {
        $b1 = 0x81;
        $length = strlen($socketData);
        $header = "";
        if ($length <= 125) {
            $header = pack("CC", $b1, $length);
        } elseif ($length < 65536) {
            $header = pack("CCn", $b1, 126, $length);
        } elseif ($length > 65536) {
            $header = pack("CCNN", $b1, 126, $length);
        }
        return $header . $socketData;
    }

    public function sendMessage($message, $socketArray): bool
    {
        $messageLength = strlen($message);
        foreach ($socketArray as $socket) {
            socket_write($socket, $message, $messageLength);
        }
        return true;
    }

    public function unsealMessage($socketData): string
    {
        $length = ord($socketData[1]) & 127;

        if ($length == 126) {
            $mask = substr($socketData, 4, 4);
            $data = substr($socketData, 8);
        } elseif ($length == 127) {
            $mask = substr($socketData, 10, 4);
            $data = substr($socketData, 14);
        } else {
            $mask = substr($socketData, 2, 4);
            $data = substr($socketData, 6);
        }

        $socketStr = "";
        for($i = 0; $i < strlen($data); $i++) {
            $socketStr .= $data[$i] ^ $mask[$i % 4];
        }

        return $socketStr;
    }

    public function createChatMessage($messageStr): string
    {
        $message = "<p>$messageStr</p>";
        $messageArray = [
            "message" => $message,
            "type" => "Сообщение от пользователя ${_SESSION['name']}"
        ];

        return $this->sealMessage(json_encode($messageArray));
    }
}