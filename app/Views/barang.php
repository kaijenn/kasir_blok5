<section class="section">
    <div class="row" id="basic-table">
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" style="text-transform: uppercase; font-size: 30px;">BARANG</h4>
                </div>

                <!-- Button to trigger "Tambah Barang" form -->
                <button class="btn btn-primary" id="btnTambahBarang" onclick="loadTambahBarangForm()">
                    <i class="fe fe-plus"></i> ADD BARANG
                </button>

                <!-- Content area -->
                <div id="content">
                    <!-- Initial content (table of barang) -->
                    <div class="card-content">
                        <div class="card-body">
                            <table class="table table-lg">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Harga Barang</th>
                                        <th>Jumlah</th>
                                        <th>Barcode</th> <!-- New column for barcode -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                        $no = 1;
                                        foreach ($oke as $okei) {
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= ($okei->nama_barang) ?></td>
                                            <td><?= ($okei->kode_barang) ?></td>
                                            <td><?= ($okei->harga_barang) ?></td>
                                            <td><?= ($okei->jumlah) ?></td>
                                            <td>
    <!-- Display Barcode Image with Popup -->
    <?php if (file_exists(FCPATH . 'uploads/' . $okei->kode_barang . '.png')): ?>
        <img src="<?= base_url('uploads/' . $okei->kode_barang . '.png') ?>" alt="Barcode" 
             style="width: 100px; cursor: pointer;" onclick="openBarcodeModal('<?= base_url('uploads/' . $okei->kode_barang . '.png') ?>')">
    <?php else: ?>
        <span>Barcode tidak tersedia</span>
    <?php endif; ?>
</td>

                                            <td>
                                                <!-- Edit button -->
                                                <button class="btn btn-info" onclick="loadEditBarangForm(<?= $okei->id_barang ?>)">
                                                    <i class="fe fe-edit"></i> Edit
                                                </button>
                                                <a href="<?= base_url('home/hapus_barang/'.$okei->id_barang) ?>">
                                                    <button class="btn btn-danger">Delete</button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="barcodeModal" tabindex="-1" role="dialog" aria-labelledby="barcodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="barcodeModalLabel">Barcode</h5>
        </button>
      </div>
      <div class="modal-body">
        <!-- Image of Barcode will be displayed here -->
        <img id="barcodeImage" src="" alt="Barcode" class="img-fluid" style="max-width: auto; height: 100px;">
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap CSS -->

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


<div id="dynamicContent"></div>

<script>

function openBarcodeModal(imageUrl) {
    // Set the source of the image in the modal
    document.getElementById('barcodeImage').src = imageUrl;

    // Open the modal
    $('#barcodeModal').modal('show');
}

    // Function to load "Tambah Jurusan" form dynamically
    function loadTambahBarangForm() {
        // Fetch and load the form for adding a new jurusan
        fetch('<?= base_url('home/t_barang') ?>') // Endpoint for adding jurusan form
            .then(response => response.text()) // Convert response to HTML
            .then(data => {
                // Hide the entire section
                document.querySelector('.section').style.display = 'none';

                // Display the form inside the dynamicContent div
                document.getElementById('dynamicContent').innerHTML = data;

                // Add a back button
                let backButton = `
                    <button class="btn btn-secondary" onclick="backToBarangList()">
                        <i class="fe fe-arrow-left"></i> Back to Barang List
                    </button>
                `;
                document.getElementById('dynamicContent').insertAdjacentHTML('beforeend', backButton);
            })
            .catch(error => {
                console.error('Error:', error); // Log any errors
                alert('Terjadi kesalahan saat memuat form tambah jurusan.');
            });
    }

    // Function to load "Edit Jurusan" form dynamically
    function loadEditBarangForm(id_barang) {
        // Fetch and load the edit form for the jurusan
        fetch('<?= base_url('home/e_barang') ?>/' + id_barang) // Endpoint for editing jurusan
            .then(response => response.text()) // Convert response to HTML
            .then(data => {
                // Hide the entire section
                document.querySelector('.section').style.display = 'none';

                // Display the form inside the dynamicContent div
                document.getElementById('dynamicContent').innerHTML = data;

                // Add a back button
                let backButton = `
                    <button class="btn btn-secondary" onclick="backToBarangList()">
                        <i class="fe fe-arrow-left"></i> Back to Barang List
                    </button>
                `;
                document.getElementById('dynamicContent').insertAdjacentHTML('beforeend', backButton);
            })
            .catch(error => {
                console.error('Error:', error); // Log any errors
                alert('Terjadi kesalahan saat memuat form edit jurusan.');
            });
    }

    // Function to return to the jurusan list
    function backToBarangList() {
        // Show the section again
        document.querySelector('.section').style.display = 'block';

        // Clear the dynamic content area (form area)
        document.getElementById('dynamicContent').innerHTML = '';
    }
</script>
