<section class="section">
    <div class="row" id="basic-table">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Member</h4>
                </div>
                <div class="card-body">
                    <!-- Form Utama -->
                    <form method="POST" action="<?= base_url('home/aksi_e_member') ?>" id="modalForm" enctype="multipart/form-data">
    <div id="form-container">
        <!-- Form Tambah Modal 1 (Form Pertama) -->
        <div class="modal-form">
            <div class="row">
                <div class="col-md-7 mb-3">
                    <label for="nama_member">Nama member:</label>
                    <input type="text" class="form-control" name="nama_member" value="<?= $oke->nama_member ?>" required>
                </div>
                <div class="col-md-7 mb-3">
                    <label for="kode_barang">Email Member:</label>
                    <input type="text" class="form-control" name="email_member" value="<?= $oke->email_member ?>" required>
                </div>
                <div class="col-md-7 mb-3">
                    <label for="harga_barang">Nomor Telepon Member:</label>
                    <input type="text" class="form-control" name="no_hp_member" value="<?= $oke->no_hp_member ?>" required>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
        <input type="hidden" name="id_member" value="<?= $oke->id_member ?>">
            <button type="submit" class="btn btn-info">Save</button>
        </div>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</section>

