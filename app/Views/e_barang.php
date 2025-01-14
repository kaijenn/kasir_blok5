<section class="section">
    <div class="row" id="basic-table">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Barang</h4>
                </div>
                <div class="card-body">
                    <!-- Form Utama -->
                    <form method="POST" action="<?= base_url('home/aksi_e_barang') ?>" id="modalForm" enctype="multipart/form-data">
    <div id="form-container">
        <!-- Form Tambah Modal 1 (Form Pertama) -->
        <div class="modal-form">
            <div class="row">
                <div class="col-md-7 mb-3">
                    <label for="nama_barang">Nama barang:</label>
                    <input type="text" class="form-control" name="nama_barang" value="<?= $oke->nama_barang ?>"  required>
                </div>
                <div class="col-md-7 mb-3">
                    <label for="kode_barang">Kode Barang:</label>
                    <input type="text" class="form-control" name="kode_barang" value="<?= $oke->kode_barang ?>"  readonly>
                </div>
                <div class="col-md-7 mb-3">
                    <label for="harga_barang">Harga Barang:</label>
                    <input type="text" class="form-control" name="harga_barang" value="<?= $oke->harga_barang ?>"  required>
                </div>
                <div class="col-md-7 mb-3">
                    <label for="stok">Stok:</label>
                    <input type="text" class="form-control" name="stok" value="<?= $oke->stok ?>"  required>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
        <input type="hidden" name="id_barang" value="<?= $oke->id_barang ?>">
            <button type="submit" class="btn btn-info">Save</button>
        </div>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</section>

