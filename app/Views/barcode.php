<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Scanner</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <style>
        #interactive {
            position: relative;
            width: 100%;
            max-width: 640px;
            margin: auto;
        }
        #interactive video {
            width: 100%;
        }
        #barcode-result {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Barcode Scanner</h2>
    <div id="interactive"></div>
    <div id="barcode-result">Scan a barcode...</div>

    <script>
        // Inisialisasi QuaggaJS untuk membaca barcode
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#interactive') // Tempat menampilkan kamera
            },
            decoder: {
                readers: ["code_128_reader"] // Jenis barcode yang didukung
            }
        }, function (err) {
            if (err) {
                console.error(err);
                document.getElementById('barcode-result').textContent = 'Error initializing barcode scanner!';
                return;
            }
            Quagga.start();
        });

        // Event untuk menangkap hasil pembacaan barcode
        Quagga.onDetected(function (data) {
            const barcode = data.codeResult.code; // Hasil barcode
            document.getElementById('barcode-result').textContent = `Barcode Detected: ${barcode}`;
            // Berhenti setelah membaca barcode
            Quagga.stop();
        });
    </script>
</body>
</html>
