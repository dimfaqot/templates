<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>

    <div id="chatBox"></div>
    <input type="text" id="sender" placeholder="Nama">
    <input type="text" id="message" placeholder="Pesan">
    <button id="sendButton" onclick="sendMessage()" disabled>Kirim</button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        const socket = new WebSocket("wss://templates.walisongosragen.com:8081/");
        const sendButton = document.getElementById("sendButton");

        socket.onopen = function(event) {
            console.log("Koneksi WebSocket dibuka!");
            // Aktifkan tombol kirim setelah koneksi terbuka
            sendButton.disabled = false;
        };

        socket.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);
                const chatBox = document.getElementById("chatBox");
                if (data.sender && data.message) {
                    chatBox.innerHTML += `<p>${data.sender}: ${data.message}</p>`;
                } else if (data.type === 'chat_data_updated' && data.payload) {
                    chatBox.innerHTML += `<p>${data.payload.sender}: ${data.payload.message}</p>`;
                } else if (data.type === 'success') {
                    const statusElement = document.getElementById("status");
                    if (statusElement) {
                        statusElement.innerText = "SUKSES";
                    }
                }
                // ... penanganan tipe pesan lain ...
            } catch (error) {
                console.error("Error parsing JSON:", error, event.data);
                document.getElementById("chatBox").innerHTML += `<p>${event.data}</p>`; // Fallback jika bukan JSON
            }
        };

        function sendMessage() {
            if (socket.readyState === WebSocket.OPEN) {
                const sender = document.getElementById("sender").value;
                const message = document.getElementById("message").value;
                const data = {
                    sender: sender,
                    message: message
                };
                socket.send(JSON.stringify(data));
                document.getElementById("message").value = ""; // Kosongkan input pesan setelah dikirim
            } else {
                console.error("WebSocket belum terbuka, pesan tidak dapat dikirim.");
            }
        }

        socket.onclose = function(event) {
            console.log("Koneksi WebSocket ditutup.");
            // Nonaktifkan tombol kirim jika koneksi ditutup
            sendButton.disabled = true;
        };

        socket.onerror = function(error) {
            console.error("Error WebSocket:", error);
            // Nonaktifkan tombol kirim jika terjadi error
            sendButton.disabled = true;
        };
    </script>
</body>

</html>