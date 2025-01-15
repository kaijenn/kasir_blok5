<section class="section">
    <div class="row" id="basic-table">
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">MEMBER</h4>
                </div>


                <div class="card-content" id="userTableContent">  <!-- This will contain the user table -->
                    <div class="card-body">
                        <!-- Table with outer spacing -->
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Nama Member</th>
                                    <th>Email Member</th>
                                    <th>Nomor Telepon Member</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($yoga as $key) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $key->nama_member ?></td>
                                        <td><?= $key->email_member ?></td>
                                        <td><?= $key->no_hp_member ?></td>
                                        <td>
                                            <!-- Button for "Edit User" -->

                                            <a href="<?= base_url('home/restore_member/' . $key->id_member) ?>">
                                                <button class="btn btn-info">
                                                    <i class="now-ui-icons ui-1_check"></i> Restore
                                                </button>
                                            </a>

                                            <a href="<?= base_url('home/hapus_member_permanent/' . $key->id_member) ?>">
                                                <button class="btn btn-secondary">
                                                    <i class="now-ui-icons ui-1_check"></i> Delete
                                                </button>
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
</section>

<!-- Dynamic Content Placeholder -->
<div id="dynamicContent"></div>

<script>
    // Function to load "Tambah User" form dynamically
    function loadTambahMemberForm() {
        // Hide the "Tambah User" button
        document.getElementById('btnTambahMember').style.display = 'none';

        // Hide the user table content
        document.getElementById('userTableContent').style.display = 'none';

        // Fetch and load the form for adding a new user
        fetch('<?= base_url('home/t_member') ?>') // Endpoint for adding user form
            .then(response => response.text()) // Convert response to HTML
            .then(data => {
                // Display the form inside the dynamicContent div
                document.getElementById('dynamicContent').innerHTML = data;

                // Add a back button
                let backButton = `
                    <button class="btn btn-secondary" onclick="backToMemberList()">
                        <i class="fe fe-arrow-left"></i> Back to Member List
                    </button>
                `;
                document.getElementById('dynamicContent').insertAdjacentHTML('beforeend', backButton);
            })
            .catch(error => {
                console.error('Error:', error); // Log any errors
                alert('Terjadi kesalahan saat memuat form tambah user.');
            });
    }

    // Function to load "Edit User" form dynamically
    function loadEditMemberForm(id_member) {
        // Hide the "Tambah User" button
        document.getElementById('btnTambahMember').style.display = 'none';

        // Hide the user table content
        document.getElementById('userTableContent').style.display = 'none';

        // Fetch and load the edit form for the user
        fetch('<?= base_url('home/e_member') ?>/' + id_member)
// Endpoint for editing user
            .then(response => response.text()) // Convert response to HTML
            .then(data => {
                // Display the edit form inside the dynamicContent div
                document.getElementById('dynamicContent').innerHTML = data;

                // Add a back button
                let backButton = `
                    <button class="btn btn-secondary" onclick="backToMemberList()">
                        <i class="fe fe-arrow-left"></i> Back to Member List
                    </button>
                `;
                document.getElementById('dynamicContent').insertAdjacentHTML('beforeend', backButton);
            })
            .catch(error => {
                console.error('Error:', error); // Log any errors
                alert('Terjadi kesalahan saat memuat form edit user.');
            });
    }

    // Function to return to the user list
    function backToMemberList() {
        // Show the "Tambah User" button again
        document.getElementById('btnTambahMember').style.display = 'inline-block';

        // Show the user table content again
        document.getElementById('userTableContent').style.display = 'block';

        // Clear the dynamic content area (form area)
        document.getElementById('dynamicContent').innerHTML = '';
    }
</script>
