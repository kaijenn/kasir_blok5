<?php

namespace App\Controllers;
Use App\Models\M_siapake;
use Picqer\Barcode\BarcodeGeneratorPNG;

class Home extends BaseController
{


	public function dashboard()
{
    $model = new M_siapake();
    $where = array('id_setting' => '1');
    $data['yogi'] = $model->getWhere1('setting', $where)->getRow();

    // Ambil nama pengguna dari session
    $session = session();
    $data['username'] = $session->get('username');

    $id_user = session()->get('id');
    $activityLog = [
        'id_user' => $id_user,
        'menu' => 'Masuk ke Dashboard',
        'time' => date('Y-m-d H:i:s')
    ];
    $model->logActivity($activityLog);
    echo view('header', $data);
    echo view('menu');
    echo view('dashboard', $data);
    echo view('footer');
}
	public function login()
	{
		$model= new M_siapake();
		$where = array('id_setting' => '1');
		$data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_user = session()->get('id');
        $activityLog = [
            'id_user' => $id_user,
            'menu' => 'Masuk ke Login',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);
	echo view('header', $data);
	echo view('login');
	}

public function barang(){

    $model= new M_siapake();
    $data['oke']= $model->tampil('barang');

    $where = array('id_setting' => '1');
		$data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_user = session()->get('id');
        $activityLog = [
            'id_user' => $id_user,
            'menu' => 'Masuk ke Barang',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);
	echo view('header', $data);
	echo view('menu');
    echo view('barang', $data);
    echo view('footer');

}

public function t_barang(){

    $model= new M_siapake();
    $where = array('id_setting' => '1');
		$data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_user = session()->get('id');
        $activityLog = [
            'id_user' => $id_user,
            'menu' => 'Masuk ke Barang',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);
	echo view('header', $data);
	echo view('menu');
    echo view('t_barang', $data);
    echo view('footer');

}

public function aksi_t_barang()
{
    if(session()->get('id') > 0){
        $nama_barang = $this->request->getPost('nama_barang');
        $kode_barang = $this->request->getPost('kode_barang');
        $harga_barang = $this->request->getPost('harga_barang');
        $jumlah = $this->request->getPost('jumlah');

        // Format data yang akan disimpan
        $yoga = array(
            'nama_barang' => $nama_barang,
            'kode_barang' => $kode_barang,
            'harga_barang' => $harga_barang,
            'jumlah' => $jumlah,
        );

        // Simpan data barang
        $model = new M_siapake;
        $model->tambah('barang', $yoga);

        // Generate Barcode
        $barcodeGenerator = new BarcodeGeneratorPNG();
        $barcodeData = $barcodeGenerator->getBarcode($kode_barang, $barcodeGenerator::TYPE_CODE_128);

        // File path untuk menyimpan barcode
        $barcodeFile = FCPATH . 'uploads/' . $kode_barang . '.png';

        // Tambahkan teks kode barang di bawah barcode menggunakan GD library
        $this->addTextToBarcode($barcodeData, $kode_barang, $barcodeFile);

        // Setelah simpan barcode, redirect ke halaman daftar barang
        return redirect()->to('home/barang');
    } else {
        return redirect()->to('home/login');
    }
}

/**
 * Tambahkan teks (kode barang) di bawah barcode
 * 
 * @param string $barcodeData Data gambar barcode
 * @param string $kodeBarang Kode barang untuk ditambahkan
 * @param string $filePath Lokasi untuk menyimpan barcode
 */
private function addTextToBarcode($barcodeData, $kodeBarang, $filePath)
{
    // Load barcode sebagai gambar dari data PNG
    $barcodeImage = imagecreatefromstring($barcodeData);

    // Dapatkan ukuran barcode
    $barcodeWidth = imagesx($barcodeImage);
    $barcodeHeight = imagesy($barcodeImage);

    // Tentukan ukuran kanvas baru untuk menambahkan teks
    $canvasHeight = $barcodeHeight + 20; // Tambahkan ruang untuk teks (20px)
    $canvas = imagecreatetruecolor($barcodeWidth, $canvasHeight);

    // Warna latar belakang putih
    $white = imagecolorallocate($canvas, 255, 255, 255);
    imagefill($canvas, 0, 0, $white);

    // Salin barcode ke kanvas
    imagecopy($canvas, $barcodeImage, 0, 0, 0, 0, $barcodeWidth, $barcodeHeight);

    // Tambahkan teks (kode barang) di bawah barcode
    $black = imagecolorallocate($canvas, 0, 0, 0);
    $fontPath = FCPATH . 'fonts/arial.ttf';
    $fontSize = 10; // Ukuran font
    $textX = ($barcodeWidth / 2) - (strlen($kodeBarang) * $fontSize / 4); // Pusatkan teks
    $textY = $barcodeHeight + 15; // Posisi di bawah barcode

    // Tambahkan teks ke kanvas
    imagettftext($canvas, $fontSize, 0, $textX, $textY, $black, $fontPath, $kodeBarang);

    // Simpan hasil sebagai file PNG
    imagepng($canvas, $filePath);

    // Bersihkan memori
    imagedestroy($canvas);
    imagedestroy($barcodeImage);
}


public function aksi_login()
{
    // Periksa koneksi internet
    if (!$this->checkInternetConnection()) {
        // Jika tidak ada koneksi, cek CAPTCHA gambar
        $captcha_code = $this->request->getPost('captcha_code');
        if (session()->get('captcha_code') !== $captcha_code) {
            session()->setFlashdata('toast_message', 'Invalid CAPTCHA');
            session()->setFlashdata('toast_type', 'danger');
            return redirect()->to('home/login');
        }
    } else {
        // Jika ada koneksi, cek Google reCAPTCHA
        $recaptchaResponse = trim($this->request->getPost('g-recaptcha-response'));
        $secret = '6LefTYMqAAAAAC1hYWZVpC0-nPwlZkdDZaDXlKi1'; // Ganti dengan Secret Key Anda
        $credential = array(
            'secret' => $secret,
            'response' => $recaptchaResponse
        );

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        curl_close($verify);

        $status = json_decode($response, true);

        if (!$status['success']) {
            session()->setFlashdata('toast_message', 'Captcha validation failed');
            session()->setFlashdata('toast_type', 'danger');
            return redirect()->to('home/login');
        }
    }


    
    // Proses login seperti biasa
    $u = $this->request->getPost('username');
    $p = $this->request->getPost('password');

    $where = array(
        'username' => $u,
        'password' => md5($p),
    );
    $model = new M_siapake;
    $cek = $model->getWhere('user', $where);

    if ($cek) {
        session()->set('nama', $cek->username);
        session()->set('id', $cek->id_user);
        session()->set('level', $cek->level);
        return redirect()->to('home/dashboard');
    } else {
        session()->setFlashdata('toast_message', 'Invalid login credentials');
        session()->setFlashdata('toast_type', 'danger');
        return redirect()->to('home/login');
    }
}



public function generateCaptcha()
{
    // Create a string of possible characters
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha_code = '';
    
    // Generate a random CAPTCHA code with letters and numbers
    for ($i = 0; $i < 6; $i++) {
        $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    // Store CAPTCHA code in session
    session()->set('captcha_code', $captcha_code);
    
    // Create an image for CAPTCHA
    $image = imagecreate(120, 40); // Increased size for better readability
    $background = imagecolorallocate($image, 200, 200, 200);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $line_color = imagecolorallocate($image, 64, 64, 64);
    
    imagefilledrectangle($image, 0, 0, 120, 40, $background);
    
    // Add some random lines to the CAPTCHA image for added complexity
    for ($i = 0; $i < 5; $i++) {
        imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
    }
    
    // Add the CAPTCHA code to the image
    imagestring($image, 5, 20, 10, $captcha_code, $text_color);
    
    // Output the CAPTCHA image
    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);
}




public function checkInternetConnection()
{
    $connected = @fsockopen("www.google.com", 80);
    if ($connected) {
        fclose($connected);
        return true;
    } else {
        return false;
    }
}



public function register()
	{
		$model= new M_siapake();
		$where = array('id_setting' => '1');
		$data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_user = session()->get('id');
    $activityLog = [
        'id_user' => $id_user,
        'menu' => 'Masuk ke Register',
        'time' => date('Y-m-d H:i:s')
    ];
    $model->logActivity($activityLog);
	echo view('header', $data);
	echo view('register');
	}


	public function aksi_t_register()
{
    if(session()->get('id') > 0) {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // Hash the password using MD5
        $hashedPassword = md5($password);

        $darren = array(
            'username' => $username,
            'password' => $hashedPassword, 
			'level' => 'pengguna',  // Store the hashed password
        );

        // Initialize the model
        $model = new M_siapake;
        $model->tambah('user', $darren);

        // Redirect to the 'tb_user' page
        return redirect()->to('home/login');
    } else {
        // If no session or user is logged in, redirect to the login page
        return redirect()->to('home/login');
    }
}


public function log_activity(){

	$model = new M_siapake;
	$data['users'] = $model->getAllUsers();

	$userId = $this->request->getGet('user_id');

	// Fetch logs with optional filtering
	if (!empty($userId)) {
		$data['logs'] = $model->getLogsByUser($userId);
	} else {
		$data['logs'] = $model->getLogs();
	}
	$where = array('id_setting' => '1');
	$data['yogi'] = $model->getWhere1('setting', $where)->getRow();
	$id_user = session()->get('id');
	$activityLog = [
		'id_user' => $id_user,
		'menu' => 'Masuk ke Log Activity',
		'time' => date('Y-m-d H:i:s')
	];
	$model->logActivity($activityLog);
	echo view('header', $data);
	echo view('menu');
	echo view('log_activity', $data);
	echo view('footer');
}
}
