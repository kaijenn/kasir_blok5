<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($yogi) ? htmlspecialchars($yogi->nama_website) : 'Update Setting' ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .form-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container h6 {
            color: #007bff;
        }
        .form-container .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .form-container .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<section class="section">
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h6 class="mb-4 text-center">Settings</h6>
                    <form action="<?= base_url('home/aksi_e_setting') ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= isset($yogi) ? htmlspecialchars($yogi->id_setting) : '' ?>">
                        
                        <div class="form-group">
                            <label for="siteName">Nama Website</label>
                            <input type="text" class="form-control" id="siteName" name="namawebsite" placeholder="Masukkan Nama Website" value="<?= isset($yogi) ? htmlspecialchars($yogi->nama_website) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="favicon">Upload Favicon</label>
                            <?php if (isset($yogi->tab_icon) && !empty($yogi->tab_icon)): ?>
                                <img src="<?= base_url('images/' . htmlspecialchars($yogi->tab_icon)) ?>" alt="Favicon" width="30" height="30">
                            <?php endif; ?>
                            <input type="file" class="form-control" id="favicon" name="img" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="logo">Logo Website</label>
                            <?php if (isset($yogi->logo_website) && !empty($yogi->logo_website)): ?>
                                <img src="<?= base_url('images/' . htmlspecialchars($yogi->logo_website)) ?>" alt="Logo Website" width="100" height="auto">
                            <?php endif; ?>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="login">Login Icon</label>
                            <?php if (isset($yogi->login_icon) && !empty($yogi->login_icon)): ?>
                                <img src="<?= base_url('images/' . htmlspecialchars($yogi->login_icon)) ?>" alt="Login Icon" width="100" height="40">
                            <?php endif; ?>
                            <input type="file" class="form-control" id="login" name="login" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </section>

    <script src="<?= base_url('https://code.jquery.com/jquery-3.3.1.slim.min.js') ?>"></script>
    <script src="<?= base_url('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js') ?>"></script>
    <script src="<?= base_url('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js') ?>"></script>
</body>
</html>
