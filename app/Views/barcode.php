<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scan Barcode</title>
    <!-- Sertakan AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">
    <!-- Sertakan Quagga.js -->
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <style>
        #scanner-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }
        #scanner-result {
            margin-top: 20px;
        }
        #interactive.viewport {
            position: relative;
            width: 100%;
            height: auto;
        }
        #interactive.viewport > canvas, #interactive.viewport > video {
            max-width: 100%;
            width: 100%;
        }
        canvas.drawing, canvas.drawingBuffer {
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?= view('header') ?>
    
    <!-- Sidebar -->
    <?= view('menu') ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Scan Barcode</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <div id="scanner-container">
                                    <div id="interactive" class="viewport"></div>
                                    <div id="scanner-result" class="text-center">
                                        <div id="result-alert" class="alert" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <?= view('footer') ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Konfigurasi Quagga
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#interactive'),
            constraints: {
    width: { min: 1280 },
    height: { min: 720 },
    facingMode: "environment"
}

        },
        locator: {
    patchSize: "large", // Pilihan: small, medium, large
    halfSample: false
}
,
        numOfWorkers: navigator.hardwareConcurrency || 4,
        decoder: {
            readers: [
                "code_128_reader",
                "ean_reader",
                "ean_8_reader",
                "code_39_reader",
                "code_39_vin_reader",
                "codabar_reader",
                "upc_reader",
                "upc_e_reader",
                "i2of5_reader"
            ]
        },
        locate: true
    }, function(err) {
        if (err) {
            console.error("Inisialisasi Quagga gagal:", err);
            return;
        }
        console.log("Inisialisasi Quagga berhasil");
        Quagga.start();
    });

    // Fungsi untuk menampilkan hasil
    function showResult(type, message, isSuccess = true) {
        const resultAlert = document.getElementById('result-alert');
        resultAlert.innerHTML = message;
        resultAlert.className = 'alert ' + (isSuccess ? 'alert-success' : 'alert-danger');
        resultAlert.style.display = 'block';
    }

    // Event ketika barcode terdeteksi
    Quagga.onDetected(function(result) {
        const code = result.codeResult.code;
        
        // Hentikan sementara scanner
        Quagga.stop();

        // Kirim kode ke server
        fetch('<?= base_url('home/cariBarangDariBarcode') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'barcode=' + encodeURIComponent(code)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Tampilkan detail barang
                showResult('success', 
                    `Barang Ditemukan:<br>` +
                    `Nama: ${data.data.nama_barang}<br>` +
                    `Harga: Rp. ${data.data.harga_barang}<br>` +
                    `Stok: ${data.data.stok}`
                );
            } else {
                // Tampilkan pesan error
                showResult('error', data.message, false);
            }

            // Mulai ulang scanner setelah 3 detik
            setTimeout(() => {
                Quagga.start();
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            showResult('error', 'Terjadi kesalahan saat memproses barcode', false);
            
            // Mulai ulang scanner
            Quagga.start();
        });
    });
    Quagga.onProcessed(function(result) {
    if (result) {
        console.log('Processing frame:', result);
        if (result.boxes) {
            console.log('Detected boxes:', result.boxes);
        }
        if (result.codeResult) {
            console.log('Decoded barcode:', result.codeResult.code);
        } else {
            console.log('No barcode detected in this frame.');
        }
    }
});


    // Tambahkan event listener untuk menutup scanner
    window.addEventListener('beforeunload', () => {
        Quagga.stop();
    });
});
</script>
</body>
</html>