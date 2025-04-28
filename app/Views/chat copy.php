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
    <button onclick="sendMessage()">Kirim</button>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        const socket = new WebSocket("ws://192.168.1.34:8080");

        socket.onmessage = function(event) {
            document.getElementById("chatBox").innerHTML += `<p>${event.data}</p>`;
        };

        function sendMessage() {
            const sender = document.getElementById("sender").value;
            const message = document.getElementById("message").value;
            socket.send(`${sender}:${message}`); // Menggunakan ":" sebagai pemisah
            // Atau, jika Anda ingin menggunakan JSON:
            // socket.send(JSON.stringify({ sender: sender, message: message }));
        }

        socket.onopen = function(event) {
            console.log("Koneksi WebSocket dibuka!");
        };

        socket.onclose = function(event) {
            console.log("Koneksi WebSocket ditutup.");
        };

        socket.onerror = function(error) {
            console.error("Error WebSocket:", error);
        };
    </script>


</body>

</html>