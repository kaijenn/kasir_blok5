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
                        <form action="<?= base_url('home/aksi_t_pemesanan') ?>" method="POST">
                            <input type="hidden" name="nomor_pemesanan" value="<?= uniqid() ?>">

                            <div class="form-group">
    <label for="kode_barang">Kode Barang</label>
    <input type="text" class="form-control" id="kode_barang" name="kode_barang" placeholder="Masukkan Kode Barang" oninput="cariBarang()">
</div>
<!-- Tombol untuk menambah ke transaksi -->
<button type="button" class="btn btn-primary" id="tambah-transaksi" onclick="tambahTransaksi()">Tambah</button>


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
                                    <button type="submit" class="btn btn-success">Bayar</button>
                                </div>
                            </div>
                        </form>
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

<script>
   let menuCounts = {};

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

function pilihMakanan(namaMenu, hargaMenu, idMenu, stokTersedia) {
    let hargaBersih = parseInt(hargaMenu.replace(/\./g, '')); 
    let table = document.getElementById("tabel-transaksi").getElementsByTagName('tbody')[0];

    // Cek stok tersedia dari database
    const barang = <?= json_encode($oke) ?>; // Pastikan $oke memiliki informasi stok
    const barangDipilih = barang.find(item => item.id_barang === idMenu);
    
    // Jika stok tidak tersedia atau akan melebihi stok
    if (!barangDipilih || 
        (menuCounts[namaMenu] && 
         menuCounts[namaMenu].count + 1 > barangDipilih.stok)) {
        alert(`Stok ${namaMenu} tidak mencukupi! Stok tersisa: ${barangDipilih ? barangDipilih.stok : 0}`);
        return;
    }

    if (menuCounts[namaMenu]) {
        menuCounts[namaMenu].count++;
        let row = Array.from(table.rows).find(row => row.cells[0].innerText === namaMenu);
        let newTotal = hargaBersih * menuCounts[namaMenu].count;
        row.cells[1].innerText = formatRupiah(newTotal);
        row.cells[2].innerText = menuCounts[namaMenu].count;
    } else {
        menuCounts[namaMenu] = { 
            count: 1, 
            harga: hargaBersih, 
            id: idMenu 
        };

        // Tambahkan baris baru ke tabel
        let row = table.insertRow();
        let namaCell = row.insertCell(0);
        let hargaCell = row.insertCell(1);
        let jumlahCell = row.insertCell(2);
        let actionCell = row.insertCell(3);

        namaCell.innerHTML = namaMenu;
        hargaCell.innerHTML = formatRupiah(hargaBersih);
        jumlahCell.innerHTML = 1;
        actionCell.innerHTML = `<button class="btn btn-danger btn-sm" onclick="hapusMakanan(this, ${hargaBersih}, '${idMenu}')">Hapus</button>`;
    }

    // Update total bayar
    let totalBayar = Object.values(menuCounts).reduce((total, menu) => total + (menu.harga * menu.count), 0);
    document.getElementById("total-bayar").value = formatRupiah(totalBayar);

    // Reset input kode barang
    document.getElementById("kode_barang").value = '';
}

function tambahTransaksi() {
    const kodeBarang = document.getElementById("kode_barang").value;
    if (!kodeBarang) {
        alert("Kode barang belum dimasukkan!");
        return;
    }

    // Cari barang berdasarkan kode_barang
    const barang = <?= json_encode($oke) ?>;
    const barangDipilih = barang.find(item => item.kode_barang === kodeBarang);

    if (barangDipilih) {
        // Tambahkan parameter stok
        pilihMakanan(
            barangDipilih.nama_barang, 
            barangDipilih.harga_barang, 
            barangDipilih.id_barang,
            barangDipilih.stok  // Pastikan ada kolom stok di database
        );
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

function hapusMakanan(button, hargaMenu, idMenu) {
    let row = button.parentNode.parentNode;
    let namaMenu = row.cells[0].innerText;

    if (menuCounts[namaMenu]) {
        if (menuCounts[namaMenu].count > 1) {
            menuCounts[namaMenu].count--;
            row.cells[2].innerText = menuCounts[namaMenu].count;
            let newTotal = hargaMenu * menuCounts[namaMenu].count;
            row.cells[1].innerText = formatRupiah(newTotal);
        } else {
            delete menuCounts[namaMenu];
            row.remove();
        }

        // Update total bayar
        let totalBayar = Object.values(menuCounts).reduce((total, menu) => total + (menu.harga * menu.count), 0);
        document.getElementById("total-bayar").value = formatRupiah(totalBayar);
    }
}

// Fungsi format untuk menampilkan angka dalam format Rupiah
function formatRupiah(angka) {
    return "Rp " + angka.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
}


</script>
