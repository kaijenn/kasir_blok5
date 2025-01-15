<section class="section">
    <div class="row">
        <!-- Kolom kiri untuk Input Pelanggan dan Pemesanan Makanan -->
        <div class="col-12 col-md-7">
            <!-- Form Pemesanan Makanan -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">KASIR</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">

                <!-- BARCODE SCANN -->

                <div id="scanner-container" style="display:none;">
    <div id="interactive" class="viewport"></div>
    <div id="scanner-result" class="text-center">
        <div id="result-alert" class="alert" style="display:none;"></div>
    </div>
</div>

<!-- Tombol untuk Memulai Scan -->
<button id="start-scan" class="btn btn-primary">Mulai Scan Barcode</button>



<form action="<?= base_url('home/aksi_t_pemesanan') ?>" method="POST">
    <input type="hidden" name="nomor_struk" id="nomor_struk" value="">

    <div class="form-group">
        <label for="kode_barang">Kode Barang</label>
        <input type="text" class="form-control" id="kode_barang" name="kode_barang" placeholder="Masukkan Kode Barang" oninput="cariBarang()">
    </div>
    
    <!-- Tombol untuk menambah ke transaksi -->
    <button type="button" class="btn btn-primary" id="tambah-transaksi" onclick="tambahTransaksi()">Tambah</button>

    <h5>Member</h5>
    <div class="form-group">
        <label for="email_member">Email Member:</label>
        <input type="text" class="form-control" id="email_member" name="email_member" placeholder="Masukkan Email Member" >
    </div>
    <div class="form-group">
        <label for="no_hp_member">Nomor Telepon Member:</label>
        <input type="text" class="form-control" id="no_hp_member" name="no_hp_member" placeholder="Masukkan Nomor Telepon Member" >
    </div>

       

    <div class="card-footer">
        <div class="form-group">
            <label>Total Bayar</label>
            <input type="text" class="form-control" id="total-bayar" name="total" value="Rp 0" readonly>
        </div>

        <div class="form-group">
            <label>Bayar</label>
            <input type="text" class="form-control" id="bayar" name="bayar" oninput="hitungKembalian()">
        </div>

        <div class="form-group">
            <label>Kembalian</label>
            <input type="text" class="form-control" id="kembalian" name="kembalian" readonly>
        </div>

        <div class="d-flex justify-content-end">
        <input type="hidden" name="id_member" value="1"> <!-- ID member -->

            <button type="submit" class="btn btn-info">Bayar</button>
        </div>
    </div>
</form>

<script>
function cariMember() {
    const memberId = document.getElementById('member_id').value;

    // Simulasi pencarian member (ganti dengan logika pencarian yang sesuai)
    if (memberId) {
        // Misalnya, jika member ditemukan, tampilkan detail member
        document.getElementById('form-member').style.display = 'block';
        document.getElementById('member_phone').value = '08123456789'; // Ganti dengan nomor telepon member yang ditemukan
        document.getElementById('member_email').value = 'member@example.com'; // Ganti dengan email member yang ditemukan
    } else {
        // Jika tidak ada member, sembunyikan form member
        document.getElementById('form-member').style.display = 'none';
        document.getElementById('member_phone').value = '';
        document.getElementById('member_email').value = '';
    }
}
</script>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom kanan untuk Form Transaksi -->
       <div class="col-12 col-md-5">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Transaksi</h4>
        </div>
        <div class="card-content">
            <div class="row text-center">
                <div class="col-6 col-sm-7 pr-0">
                    <ul class="pl-0 small" style="list-style: none;text-transform: uppercase;">
                        <li>KASIR : <?= session()->get('nama') ?></li>
                    </ul>
                </div>
                <div class="col-4 col-sm-3 pl-0">
                    <ul class="pl-0 small" style="list-style: none;">
                        <li>TGL : <?php echo date("Y-m-d"); ?></li>
                        <li>JAM : <?php echo date("H:i:s"); ?></li>
                    </ul>
                </div>
            </div>
            <!-- Tabel untuk menampilkan barang yang dipilih -->
            <div class="table-responsive">
                <table id="tabel-transaksi" class="table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga Barang</th>
                            <th>Jumlah</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Menu yang dipilih akan ditambahkan di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    </div>
</section>
<!-- Sertakan Quagga.js -->
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
   let menuCounts = {};


   function cariMember() {
    const noHpMember = document.getElementById('no_hp_member').value;

    if (noHpMember) {
        // Menggunakan AJAX untuk mendapatkan data member
        fetch(`<?= base_url('home/get_member_by_phone') ?>/${noHpMember}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('form-member').style.display = 'block';
                    document.getElementById('nama_member').value = data.nama_member; // Ganti dengan nama member yang ditemukan
                    document.getElementById('member_email').value = data.email_member; // Ganti dengan email member yang ditemukan
                } else {
                    // Jika tidak ada member, sembunyikan form member
                    document.getElementById('form-member').style.display = 'none';
                    document.getElementById('nama_member').value = '';
                    document.getElementById('member_email').value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        // Jika tidak ada nomor telepon, sembunyikan form member
        document.getElementById('form-member').style.display = 'none';
        document.getElementById('nama_member').value = '';
        document.getElementById('member_email').value = '';
    }
}
   // Variabel untuk menyimpan nomor struk
   let nomorStrukSementara = '';

// Fungsi untuk membuat/mendapatkan nomor struk
function dapatkanNomorStruk() {
    const nomorStrukInput = document.getElementById('nomor_struk');
    
    if (!nomorStrukInput.value) {
        // Biarkan kosong, server akan membuatnya
        nomorStrukInput.value = '';
    }
    
    return nomorStrukInput.value;
}

// Event listener untuk form submit
// Event listener untuk form submit
document.querySelector('.btn-info').addEventListener('click', function (event) {
    event.preventDefault(); // Mencegah form submit default

    // Ambil nilai email_member dan no_hp_member
    const emailMember = document.getElementById("email_member").value.trim();
    const noHpMember = document.getElementById("no_hp_member").value.trim();

    // Cek apakah email_member dan no_hp_member kosong
    if (emailMember === '' && noHpMember === '') {
        // Jika kosong, kirim request untuk menampilkan nota langsung
        const nomorStruk = document.getElementById("nomor_struk").value;

        // Menggunakan fetch untuk mendapatkan response dari server
        fetch('<?= base_url('home/printnota') ?>?nomor_struk=' + nomorStruk)
            .then(response => {
                if (response.ok) {
                    // Jika response berhasil, buka PDF di tab baru
                    window.open(response.url, '_blank');
                } else {
                    alert('Gagal mengunduh nota.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses nota');
            });

        return; // Hentikan eksekusi lebih lanjut
    }

    // Kumpulkan data untuk dikirim
    const formData = new FormData();

    // Tambahkan kode barang dan jumlah
    const kodeBarangArray = Object.keys(menuCounts);
    kodeBarangArray.forEach((kode) => {
        formData.append('kode_barang[]', kode);
        formData.append('jumlah[]', menuCounts[kode].count); // Ambil jumlah dari menuCounts
    });

    // Tambahkan data lainnya
    formData.append('total', document.getElementById("total-bayar").value.replace(/[^0-9]/g, ''));
    formData.append('bayar', document.getElementById("bayar").value.replace(/[^0-9]/g, ''));
    formData.append('kembalian', document.getElementById("kembalian").value.replace(/[^0-9]/g, ''));
    formData.append('nomor_struk', document.getElementById("nomor_struk").value);
    formData.append('email_member', emailMember); // Ambil email member dari input form
    formData.append('no_hp_member', noHpMember); // Ambil nomor HP member dari input form

    // Kirim data ke server
    fetch('<?= base_url('home/aksi_t_pemesanan') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Nota berhasil dikirim ke email dan WhatsApp.');
        } else {
            alert('Transaksi gagal: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses transaksi');
    });
});







function cariBarang() {
    const kodeBarang = document.getElementById("kode_barang").value;

    // Jika kode_barang kosong, keluar dari fungsi
    if (!kodeBarang) {
        return;
    }

    // Cari barang berdasarkan kode_barang
    // Misalkan Anda memiliki data barang dalam array atau dapat menggunakan AJAX untuk mengambil data
    const barang = <?= json_encode($oke) ?>; // $oke adalah data barang yang Anda ambil
    const barangDipilih = barang.find(item => item.kode_barang === kodeBarang);

    if (barangDipilih) {
        // Menampilkan nama barang, harga, dsb. untuk ditambahkan ke tabel
        document.getElementById("tambah-transaksi").disabled = false; // Mengaktifkan tombol tambah
    } else {
        document.getElementById("tambah-transaksi").disabled = true; // Menonaktifkan tombol tambah jika barang tidak ditemukan
    }
}

let isAdding = false; // Variabel untuk menandai apakah sedang menambahkan barang



// Fungsi untuk menambahkan barang ke keranjang
function pilihMakanan(namaBarang, hargaBarang, idBarang, kodeBarang, stokTersedia) {
    // Pastikan hargaBarang adalah angka
    let hargaBersih = typeof hargaBarang === 'string' 
        ? parseInt(hargaBarang.replace(/\./g, '')) 
        : hargaBarang;

    // Cek apakah barang sudah ada di keranjang
    if (menuCounts[kodeBarang]) {
        // Jika sudah ada, tambahkan jumlah
        menuCounts[kodeBarang].count++;
    } else {
        // Jika belum ada, tambahkan barang baru
        menuCounts[kodeBarang] = {
            nama: namaBarang,
            harga: hargaBersih,
            count: 1,
            id: idBarang
        };
    }

    // Update tabel transaksi
    updateTableTransaksi();
}

// Fungsi untuk update tabel transaksi
function updateTableTransaksi() {
    let table = document.getElementById("tabel-transaksi").getElementsByTagName('tbody')[0];
    table.innerHTML = ''; // Kosongkan tabel

    // Iterasi melalui semua barang di keranjang
    for (const [kodeBarang, data] of Object.entries(menuCounts)) {
        let row = table.insertRow();
        row.insertCell(0).innerText = data.nama;
        row.insertCell(1).innerText = formatRupiah(data.harga * data.count);
        row.insertCell(2).innerText = data.count;
        row.insertCell(3).innerHTML = `
            <button class="btn btn-danger btn-sm" onclick="hapusMakanan('${kodeBarang}')">Hapus</button>
        `;
    }

    // Update total bayar
    hitungTotalBayar();
}

// Fungsi untuk menghitung total bayar
function hitungTotalBayar() {
    let totalBayar = Object.values(menuCounts).reduce((total, item) => {
        return total + (item.harga * item.count);
    }, 0);

    document.getElementById("total-bayar").value = formatRupiah(totalBayar);
}

function tambahTransaksi() {
    const kodeBarang = document.getElementById("kode_barang").value;
    
    // Cari barang berdasarkan kode_barang
    const barang = <?= json_encode($oke) ?>;
    const barangDipilih = barang.find(item => item.kode_barang === kodeBarang);

    if (barangDipilih) {
        pilihMakanan(
            barangDipilih.nama_barang, 
            barangDipilih.harga_barang, 
            barangDipilih.id_barang,
            barangDipilih.kode_barang,
            barangDipilih.stok
        );
    } else {
        alert('Barang tidak ditemukan');
    }
}

function hitungKembalian() {
    // Hapus karakter non-digit dari input bayar
    let inputBayar = document.getElementById("bayar");
    let bayarValue = inputBayar.value.replace(/[^0-9]/g, '');
    
    // Format input bayar menjadi Rupiah saat mengetik
    inputBayar.value = formatRupiah(bayarValue);

    // Ambil total bayar dan bayar dalam bentuk angka
    let totalBayar = parseInt(document.getElementById("total-bayar").value.replace(/[^0-9]/g, ''));
    let bayar = parseInt(bayarValue);

    // Hitung kembalian
    if (bayar >= totalBayar) {
        let kembalian = bayar - totalBayar;
        document.getElementById("kembalian").value = formatRupiah(kembalian);
    } else {
        document.getElementById("kembalian").value = "Rp 0";
    }
}

// Tambahkan event listener untuk memastikan input hanya angka
document.getElementById("bayar").addEventListener('input', function(e) {
    // Hapus karakter non-digit
    this.value = this.value.replace(/[^0-9]/g, '');
});

function hapusMakanan(kodeBarang) {
    if (menuCounts[kodeBarang]) {
        if (menuCounts[kodeBarang].count > 1) {
            menuCounts[kodeBarang].count--;
        } else {
            delete menuCounts[kodeBarang];
        }
        updateTableTransaksi();
    }
}

// document.querySelector('form').addEventListener('submit', function(event) {
//     event.preventDefault(); // Mencegah form submit default

//     // Kumpulkan data untuk dikirim
//     const formData = new FormData();

//     // Tambahkan kode barang
//     const kodeBarangArray = Object.keys(menuCounts);
//     kodeBarangArray.forEach((kode, index) => {
//         formData.append('kode_barang[]', kode);
//     });

//     // Tambahkan data lainnya
//     formData.append('total', document.getElementById("total-bayar").value.replace(/[^0-9]/g, ''));
//     formData.append('bayar', document.getElementById("bayar").value.replace(/[^0-9]/g, ''));
//     formData.append('kembalian', document.getElementById("kembalian").value.replace(/[^0-9]/g, ''));

//     // Kirim data ke server
//     fetch('<?= base_url('home/aksi_t_pemesanan') ?>', {
//         method: 'POST',
//         body: formData
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.status === 'success') {
//             alert('Transaksi berhasil!');
//             // Reset keranjang dan form
//             menuCounts = {};
//             updateTableTransaksi();
//             document.getElementById("bayar").value = '';
//             document.getElementById("kembalian").value = '';
//         } else {
//             alert('Transaksi gagal: ' + data.message);
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         alert('Terjadi kesalahan saat memproses transaksi');
//     });
// });

// Fungsi untuk menambah barang (bisa dari input manual atau scan)
function tambahTransaksi() {
    const kodeBarang = document.getElementById("kode_barang").value;
    
    // Cari barang berdasarkan kode_barang
    const barang = <?= json_encode($oke) ?>;
    const barangDipilih = barang.find(item => item.kode_barang === kodeBarang);

    if (barangDipilih) {
        pilihMakanan(
            barangDipilih.nama_barang, 
            barangDipilih.harga_barang, 
            barangDipilih.id_barang,
            barangDipilih.kode_barang,
            barangDipilih.stok
        );
    } else {
        alert('Barang tidak ditemukan');
    }
}

// Fungsi format untuk menampilkan angka dalam format Rupiah
function formatRupiah(angka) {
    return "Rp " + angka.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
}



// Fungsi untuk memulai pemindaian barcode
document.addEventListener('DOMContentLoaded', function () {
        const scannerContainer = document.getElementById("scanner-container");
        const resultAlert = document.getElementById("result-alert");
        const startScanButton = document.getElementById("start-scan");

        // Fungsi untuk menampilkan hasil
        function showResult(message, isSuccess = true) {
            resultAlert.innerHTML = message;
            resultAlert.className = "alert " + (isSuccess ? "alert-success" : "alert-danger");
            resultAlert.style.display = "block";
        }

        // Fungsi untuk memulai scanner
        function startScanner() {
            scannerContainer.style.display = "block";
            Quagga.init({
                 inputStream: {
        name: "Live",
        type: "LiveStream",
        target: document.querySelector('#interactive'),
        constraints: {
            width: { ideal: 400 }, // Atur lebar kamera (contoh: 640 px)
            height: { ideal: 400 }, // Atur tinggi kamera (contoh: 480 px)
            facingMode: "environment" // Gunakan kamera belakang
        }
    },

                locator: {
                    patchSize: "large",
                    halfSample: false
                },
                numOfWorkers: navigator.hardwareConcurrency || 4,
                decoder: {
                    readers: [
                        "code_128_reader",
                        "ean_reader",
                        "ean_8_reader",
                        "code_39_reader",
                        "codabar_reader",
                        "upc_reader",
                        "i2of5_reader"
                    ]
                },
                locate: true
            }, function (err) {
                if (err) {
                    console.error("Inisialisasi Quagga gagal:", err);
                    showResult("Gagal mengakses kamera. Pastikan Anda memberikan izin.", false);
                    scannerContainer.style.display = "none";
                    return;
                }
                console.log("Inisialisasi Quagga berhasil");
                Quagga.start();
            });

            let isAdding = false; // Variabel untuk menandai apakah sedang menambahkan barang
let isScanning = false; // Variabel untuk menandai apakah sedang memindai

Quagga.onDetected(function (result) {
    if (isScanning) return; // Jika sedang memindai, keluar dari fungsi
    isScanning = true; // Set lock untuk pemindaian

    const scannedCode = result.codeResult.code;
    console.log("Barcode terdeteksi:", scannedCode);

    // Kirim hasil barcode ke server
    fetch('<?= base_url("home/cariBarangDariBarcode") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'barcode=' + encodeURIComponent(scannedCode)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            // Panggil fungsi pilihMakanan untuk menambahkan SATU barang ke tabel
            if (!isAdding) { // Pastikan tidak sedang menambahkan
                isAdding = true; // Set lock untuk penambahan
                tambahBarangSatuan(
                    data.data.nama_barang, 
                    data.data.harga_barang, 
                    data.data.id_barang,
                    data.data.stok
                );
                isAdding = false; // Reset lock setelah penambahan selesai
            }
            showResult(
                `Barang Ditemukan:<br>` +
                `Nama: ${data.data.nama_barang}<br>` +
                `Harga: Rp ${data.data.harga_barang}<br>` +
                `Stok: ${data.data.stok}`
            );
        } else {
            showResult(data.message, false);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        showResult("Terjadi kesalahan saat memproses barcode.", false);
    })
    .finally(() => {
        // Hentikan scanner sementara
        Quagga.stop();
        setTimeout(() => {
            Quagga.start();
            isScanning = false; // Reset lock pemindaian setelah scanner dimulai kembali
        }, 1500); // Tunggu 1.5 detik sebelum memulai kembali
    });
});

// Fungsi baru untuk menambahkan SATU barang
function tambahBarangSatuan(namaBarang, hargaBarang, idBarang, stokTersedia) {
    // Pastikan hargaBarang adalah string atau angka yang valid
    let hargaBersih = typeof hargaBarang === 'string' 
        ? parseInt(hargaBarang.replace(/\./g, '')) 
        : hargaBarang;
    
    let table = document.getElementById("tabel-transaksi").getElementsByTagName('tbody')[0];

    // Validasi stok
    if (stokTersedia === undefined || stokTersedia === null) {
        alert(`Stok barang ${namaBarang} tidak valid!`);
        return;
    }

    // Konversi stok ke integer
    const stok = parseInt(stokTersedia, 10); // Pastikan untuk menggunakan radix 10

    // Cek apakah sudah ada barang di keranjang
    const jumlahSekarang = menuCounts[namaBarang] 
        ? menuCounts[namaBarang].count 
        : 0;

    // Validasi stok sebelum menambahkan
    if (jumlahSekarang + 1 > stok) {
        alert(`Stok barang ${namaBarang} tidak mencukupi! Stok tersisa: ${stok}`);
        return;
    }

    // Proses tambah SATU barang ke keranjang
    if (menuCounts[namaBarang]) {
        // Jika barang sudah ada, tambahkan 1 ke jumlah
        menuCounts[namaBarang].count += 1; // Tambahkan 1 ke jumlah
        
        // Cari baris yang sesuai dengan nama barang
        let rows = table.getElementsByTagName('tr');
        for (let row of rows) {
            if (row.cells[0].innerText === namaBarang) {
                row.cells[1].innerText = formatRupiah(hargaBersih);
                row.cells[2].innerText = menuCounts[namaBarang].count; // Update jumlah
                break;
            }
        }
    } else {
        // Jika barang belum ada di keranjang, tambahkan baris baru
        menuCounts[namaBarang] = { 
            count: 1, 
            harga: hargaBersih, 
            id: idBarang 
        };

        let row = table.insertRow();
        let namaCell = row.insertCell(0);
        let hargaCell = row.insertCell(1);
        let jumlahCell = row.insertCell(2);
        let actionCell = row.insertCell(3);

        namaCell.innerHTML = namaBarang;
        hargaCell.innerHTML = formatRupiah(hargaBersih);
        jumlahCell.innerHTML = 1;
        actionCell.innerHTML = `<button class="btn btn-danger btn-sm" onclick="hapusMakanan(this, ${hargaBersih}, '${idBarang}')">Hapus</button>`;
    }

    // Update total bayar
    let totalBayar = Object.values(menuCounts).reduce((total, menu) => total + (menu.harga * menu.count), 0);
    document.getElementById("total-bayar").value = formatRupiah(totalBayar);

    // Reset input kode barang
    document.getElementById("kode_barang").value = '';
}



// Modifikasi fungsi startScanner
function startScanner() {
    // Reset semua variabel tracking
    isScanning = false;
    lastScannedCode = null;
    lastScanTime = 0;

    scannerContainer.style.display = "block";
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#interactive'),
            constraints: {
                width: { ideal: 400 },
                height: { ideal: 400 },
                facingMode: "environment"
            }
        },
        locator: {
            patchSize: "large",
            halfSample: false
        },
        numOfWorkers: navigator.hardwareConcurrency || 4,
        decoder: {
            readers: [
                "code_128_reader",
                "ean_reader",
                "ean_8_reader",
                "code_39_reader",
                "codabar_reader",
                "upc_reader",
                "i2of5_reader"
            ]
        },
        locate: true
    }, function (err) {
        if (err) {
            console.error("Inisialisasi Quagga gagal:", err);
            showResult("Gagal mengakses kamera. Pastikan Anda memberikan izin.", false);
            scannerContainer.style.display = "none";
            return;
        }
        console.log("Inisialisasi Quagga berhasil");
        Quagga.start();
    });
}


            Quagga.onProcessed(function (result) {
                if (result && result.boxes) {
                    console.log("Frame diproses:", result.boxes);
                }
            });
        }

        // Event untuk memulai scanner saat tombol diklik
        startScanButton.addEventListener("click", function () {
            startScanner();
        });

        // Hentikan scanner saat halaman dimuat ulang
        window.addEventListener("beforeunload", function () {
            Quagga.stop();
        });
    });
    
    function resetScanner() {
    Quagga.stop();
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#interactive'),
            constraints: {
                width: { ideal: 400 },
                height: { ideal: 400 },
                facingMode: "environment"
            }
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
        }
    }, function (err) {
        if (err) {
            console.error("Gagal menginisialisasi ulang Quagga:", err);
        } else {
            Quagga.start();
        }
    });
}

</script>
