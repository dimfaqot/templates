<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu King...</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        #countdown {
            font-size: 2em;
            margin-bottom: 20px;
        }

        #status {
            font-size: 1.2em;
            color: #888;
        }

        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Menunggu Kedatangan King...</h1>
    <div id="countdown"></div>
    <div id="status"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countdownDuration = 60; // Durasi countdown dalam detik
            let timeLeft = countdownDuration;
            const countdownElement = document.getElementById('countdown');
            const statusElement = document.getElementById('status');
            let countdownInterval;
            const socket = new WebSocket("ws://192.168.1.34:8080"); // Sesuaikan dengan URL server Anda

            function updateCountdown() {
                countdownElement.innerText = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    statusElement.innerText = "Waktu habis, menutup jendela...";
                    setTimeout(() => {
                        window.close(); // Menutup seluruh jendela browser
                    }, 2000);
                }
                timeLeft--;
            }

            socket.onopen = function(event) {
                console.log("Koneksi WebSocket dibuka untuk menunggu King.");
                statusElement.innerText = "Menghubungkan ke server...";
                countdownInterval = setInterval(updateCountdown, 1000);
            };

            socket.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    if (data.type === 'king_found') {
                        console.log("King telah bergabung!");
                        clearInterval(countdownInterval);
                        statusElement.innerText = "King telah bergabung!";
                        statusElement.className = "success";
                        // Anda bisa menambahkan tindakan lain di sini, misalnya redirect
                        // setTimeout(() => { window.location.href = 'halaman_tujuan'; }, 2000);
                    }
                } catch (error) {
                    console.error("Error parsing JSON:", error, event.data);
                }
            };

            socket.onclose = function(event) {
                console.log("Koneksi WebSocket ditutup.");
                statusElement.innerText = "Koneksi terputus.";
            };

            socket.onerror = function(error) {
                console.error("Error WebSocket:", error);
                statusElement.innerText = "Terjadi kesalahan koneksi.";
            };
        });
    </script>
</body>

</html>