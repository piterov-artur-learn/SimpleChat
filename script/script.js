const message = (text) => {
    $('#chat-result').append(text);
}

$(document).ready(() => {
    let socket = new WebSocket("ws://localhost:8080/simplechat/server.php");

    socket.onopen = () => {
        message("<p>Соединение установлено</p>");
    };

    socket.onerror = (error) => {
        message(`<p>Ошибка соединения: ${error.message}</p>`);
    };

    socket.onclose = () => {
        message("<p>Соединение закрыто</p>");
    };

    socket.onmessage = (event) => {
        let data = JSON.parse(event.data);
        message(`<p>${data.type} - ${data.message}</p>`);
    };

    $('#chat').on('submit', () => {
        let message = {
            chat_message: $('#chat-message').val(),
        };

        socket.send(JSON.stringify(message));

        return false;
    });
});