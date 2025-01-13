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

                            <table class="table table-lg">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Kode Menu</th>
                                        <th>Nama Menu</th>
                                        <th>Harga Barang</th>
                                        <th>Jumlah</th>
                                        <th>Stok</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($oke as $key) {
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $key->kode_barang ?></td>
                                        <td><?= $key->nama_barang ?></td>
                                        <td><?= $key->harga_menu ?></td>
                                        <td><?= $key->jumlah ?></td>
                                        <td><?= $key->stok ?></td>
                                        <td>
                                            <button type="button" class="btn btn-secondary" onclick="pilihMakanan('<?= $key->nama_menu ?>', '<?= $key->harga_menu ?>', '<?= $key->id_menu ?>')">
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

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
                    <!-- Tabel untuk menampilkan menu yang dipilih -->
                    <div class="table-responsive">
                        <table id="tabel-transaksi" class="table">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
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

<!-- JavaScript untuk memilih makanan, menghitung transaksi, dan hapus makanan -->
<script>
    let menuCounts = {};

    function pilihMakanan(namaMenu, hargaMenu, idMenu) {
    let hargaBersih = parseInt(hargaMenu.replace(/\./g, ''));
    let table = document.getElementById("tabel-transaksi").getElementsByTagName('tbody')[0];

    if (menuCounts[namaMenu]) {
        // If already exists, increase the count
        menuCounts[namaMenu].count++;
        let row = Array.from(table.rows).find(row => row.cells[0].innerText === namaMenu);
        let newTotal = hargaBersih * menuCounts[namaMenu].count;
        row.cells[1].innerText = formatRupiah(newTotal);
        row.cells[2].innerText = menuCounts[namaMenu].count; // Update quantity
        
        // Update the hidden input for jumlah
        let hiddenInput = document.querySelector(`input[name="menu[${Object.keys(menuCounts).indexOf(namaMenu)}][jumlah]"]`);
        if (hiddenInput) {
            hiddenInput.value = menuCounts[namaMenu].count; // Update the hidden input
        }
    } else {
        // If not exists, add to the table
        menuCounts[namaMenu] = { count: 1, harga: hargaBersih, id: idMenu };
        
        let row = table.insertRow();
        let namaCell = row.insertCell(0);
        let hargaCell = row.insertCell(1);
        let jumlahCell = row.insertCell(2);
        let actionCell = row.insertCell(3);
        
        namaCell.innerHTML = namaMenu;
        hargaCell.innerHTML = formatRupiah(hargaBersih);
        jumlahCell.innerHTML = 1; // Initialize quantity
        actionCell.innerHTML = `<button class="btn btn-danger btn-sm" onclick="hapusMakanan(this, ${hargaBersih}, '${idMenu}')">Hapus</button>`;
        
        // Create hidden inputs for the form
        let menuIndex = Object.keys(menuCounts).length - 1; // Get the current index
        let form = document.querySelector('form');
        
        // Create hidden inputs for both the ID and quantity
        form.insertAdjacentHTML('beforeend', `<input type="hidden" name="menu[${menuIndex}][id_menu]" value="${idMenu}">`);
        form.insertAdjacentHTML('beforeend', `<input type="hidden" name="menu[${menuIndex}][jumlah]" value="${menuCounts[namaMenu].count}">`);
    }

    // Update total bayar
    let totalBayar = Object.values(menuCounts).reduce((total, menu) => total + (menu.harga * menu.count), 0);
    document.getElementById("total-bayar").value = formatRupiah(totalBayar);

    // Update customer name display
    document.getElementById('pelanggan-nama').innerText = document.getElementById('nama-pelanggan').value;
}




    function hapusMakanan(button, hargaMenu, idMenu) {
        let row = button.parentNode.parentNode;
        let namaMenu = row.cells[0].innerText;

        // Decrease the count and check if count becomes 0
        menuCounts[namaMenu].count--;
        if (menuCounts[namaMenu].count === 0) {
            delete menuCounts[namaMenu]; // Remove from object if count is 0
            row.parentNode.removeChild(row); // Remove row from table
        } else {
            // Update price and quantity in the row
            let newTotal = menuCounts[namaMenu].count * menuCounts[namaMenu].harga;
            row.cells[1].innerText = formatRupiah(newTotal);
            row.cells[2].innerText = menuCounts[namaMenu].count; // Update quantity
        }

        // Update total bayar
        let totalBayar = Object.values(menuCounts).reduce((total, menu) => total + (menu.harga * menu.count), 0);
        document.getElementById("total-bayar").value = formatRupiah(totalBayar);

        let form = document.querySelector('form');
        let inputMenu = form.querySelector(`input[value="${idMenu}"]`);
        let inputHarga = form.querySelector(`input[value="${hargaMenu}"]`);
        if (inputMenu) inputMenu.remove();
        if (inputHarga) inputHarga.remove();
    }

    function hitungKembalian() {
        let totalBayar = parseInt(document.getElementById("total-bayar").value.replace(/\./g, '').replace('Rp ', ''));
        let bayar = parseInt(document.getElementById("bayar").value.replace(/\./g, '').replace('Rp ', '') || 0);
        let kembalian = bayar - totalBayar;
        document.getElementById("kembalian").value = formatRupiah(kembalian);
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.getElementById("bayar").addEventListener("input", function () {
        // Format the input value as rupiah while typing
        let inputValue = this.value.replace(/\./g, '').replace('Rp ', '');
        this.value = formatRupiah(inputValue);
        hitungKembalian(); // Update kembalian when bayar changes
    });

</script>
