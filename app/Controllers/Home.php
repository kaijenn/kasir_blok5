<?php

namespace App\Controllers;
Use App\Models\M_siapake;
use Picqer\Barcode\BarcodeGeneratorPNG;
Use dompdf\Dompdf;
use Dompdf\Options;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    public function member(){

        $model= new M_siapake();
        $data['yoga'] = $model->tampilactive('member');
        $where = array('id_setting' => '1');
            $data['yogi'] = $model->getWhere1('setting', $where)->getRow();
            $id_user = session()->get('id');
            $activityLog = [
                'id_user' => $id_user,
                'menu' => 'Masuk ke Member',
                'time' => date('Y-m-d H:i:s')
            ];
            $model->logActivity($activityLog);
        echo view('header', $data);
        echo view('menu');
        echo view('member', $data);
        echo view('footer');
    
    }


    public function soft_delete(){

        $model = new M_siapake;
        $data['yoga'] = $model->tampilrestore('member');
        $where = array('id_setting' => '1');
        $data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_user = session()->get('id');
        $activityLog = [
            'id_user' => $id_user,
            'menu' => 'Masuk ke Soft Delete',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);
        echo view('header', $data);
        echo view('menu');
        echo view('soft_delete_member', $data);
        echo view('footer');
    }


    public function hapus_member($id)
    {
        $model = new M_siapake();
        $where = array('id_member' => $id);
        $array = array(
            'deleted_at' => date('Y-m-d H:i:s'),
        );
        $model->edit('member', $array, $where);
        // $this->logmemberActivity('Menghapus Pemesanan');

        return redirect()->to('home/member');
    }

    public function restore_member($id)
    {
        $model = new M_siapake();
        $where = array('id_member' => $id);
        $array = array(
            'deleted_at' => NULL, // Mengatur deleted_at menjadi null
        );
        $model->edit('member', $array, $where);
    
        return redirect()->to('home/member');
    }

    public function hapus_member_permanent($id)
    {
        $model = new M_siapake();
        // $this->logmemberActivity('Menghapus Pemesanan Permanent');
        $where = array('id_member' => $id);
        $model->hapus('member', $where);
    
        return redirect()->to('Home/member');
    }



    public function restore_edit_member(){

        $model = new M_siapake;
        $data['yoga'] = $model->tampil('member_backup');
        $where = array('id_setting' => '1');
        $data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_member = session()->get('id');
        $activityLog = [
            'id_member' => $id_member,
            'menu' => 'Masuk ke Restore Edit member',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);
        echo view('header', $data);
        echo view('menu');
        echo view('restore_edit_member', $data);
        echo view('footer');
    }

    public function aksi_restore_edit_member($id)
{
    $model = new M_siapake();
    
    $backupData = $model->getWhere('member_backup', ['id_member' => $id]);

    if ($backupData) {
       
        $restoreData = [
            'nama_member' => $backupData->nama_member,
            'email_member' => $backupData->email_member,
            'no_hp_member' => $backupData->no_hp_member,
           
            // tambahkan field lainnya sesuai dengan struktur tabel menu
        ];
        unset($restoreData['id_member']);
        $model->edit('member', $restoreData, ['id_member' => $id]);
    }
    
    return redirect()->to('home/member');
}



    public function t_member(){

        $model= new M_siapake();
    
        $where = array('id_setting' => '1');
            $data['yogi'] = $model->getWhere1('setting', $where)->getRow();
            $id_user = session()->get('id');
            $activityLog = [
                'id_user' => $id_user,
                'menu' => 'Masuk ke Tambah Member',
                'time' => date('Y-m-d H:i:s')
            ];
            $model->logActivity($activityLog);
        echo view('header', $data);
        echo view('menu');
        echo view('t_member', $data);
    
    }

    public function aksi_t_member()
{
    if(session()->get('id') > 0) {
        $nama_member = $this->request->getPost('nama_member');
        $email_member = $this->request->getPost('email_member');
        $no_hp_member = $this->request->getPost('no_hp_member');
        
        // Hash the password using MD5

        $darren = array(
            'nama_member' => $nama_member,
            'email_member' => $email_member, 
            'no_hp_member' => $no_hp_member, 
        );

        // Initialize the model
        $model = new M_siapake;
        $model->tambah('member', $darren);

        // Redirect to the 'tb_user' page
        return redirect()->to('home/member');
    } else {
        // If no session or user is logged in, redirect to the login page
        return redirect()->to('home/login');
    }
}


public function e_member($id_member){

    $model= new M_siapake();

    $wheremember = array('id_member' => $id_member);
        $data['oke'] = $model->getWhere1('member', $wheremember)->getRow(); 

    $where = array('id_setting' => '1');
        $data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_user = session()->get('id');
        $activityLog = [
            'id_user' => $id_user,
            'menu' => 'Masuk ke Edit Member',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);
    echo view('header', $data);
    echo view('menu');
    echo view('e_member', $data);

}

// public function aksi_e_member()
// {
//     if(session()->get('id') > 0) {
//         $nama_member = $this->request->getPost('nama_member');
//         $email_member = $this->request->getPost('email_member');
//         $no_hp_member = $this->request->getPost('no_hp_member');
//         $id = $this->request->getPost('id_member');
        
//         $where = array('id_member' => $id);

//         $yoga = array(
//             'nama_member' => $nama_member,
//             'email_member' => $email_member, 
//             'no_hp_member' => $no_hp_member, 
//         );

//         // Initialize the model
//         $model = new M_siapake;
//         $model->edit('member', $yoga, $where);

//         // Redirect to the 'tb_user' page
//         return redirect()->to('home/member');
//     } else {
//         // If no session or user is logged in, redirect to the login page
//         return redirect()->to('home/login');
//     }
// }


public function aksi_e_member()
{
if(session()->get('id') > 0){
    $nama_member = $this->request->getPost('nama_member');
        $email_member = $this->request->getPost('email_member');
        $no_hp_member = $this->request->getPost('no_hp_member');
        $id = $this->request->getPost('id_member');


    $model = new M_siapake;
    $oldData = $model->getWhere('member', ['id_member' => $id]);

        // Simpan data lama ke tabel backup
        if ($oldData) {
            $backupData = [
                'id_member' => $oldData->id_member,  // integer
                'nama_member' => $oldData->nama_member,     
                'email_member' => $oldData->email_member,    
                'no_hp_member' => $oldData->no_hp_member,      // integer
                'backup_by' => $oldData->backup_by,         // integer
                'backup_at' => $oldData->backup_at,         // datetime
            ];

            // Debug: cek hasil insert ke tabel backup
            if ($model->saveToBackup('member_backup', $backupData)) {
                echo "Data backup berhasil disimpan!";
            } else {
                echo "Gagal menyimpan data ke backup.";
            }
        } else {
            echo "Data lama tidak ditemukan.";
        }

        // Data baru yang akan diupdate
        $yoga = array(
           'nama_member' => $nama_member,
           'email_member' => $email_member,
           'no_hp_member' => $no_hp_member,
                'updated_by' => session()->get('id'),
                'updated_at' => date('Y-m-d H:i:s'),
        );

        // Update data di tabel pemesanan
        $where = array('id_member' => $id);
        $model->edit('member', $yoga, $where);

        return redirect()->to('home/member');
    }
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
            'menu' => 'Masuk ke Tambah Barang',
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
    if (session()->get('id') > 0) {
        // Ambil data input
        $nama_barang = $this->request->getPost('nama_barang');
        $kode_barang = $this->request->getPost('kode_barang');
        $harga_barang = $this->request->getPost('harga_barang');
        $stok = $this->request->getPost('stok');

        // Validasi input (basic)
        if (empty($nama_barang) || empty($kode_barang) || empty($harga_barang) || empty($stok)) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }

        // Format data untuk disimpan
        $data = [
            'nama_barang' => $nama_barang,
            'kode_barang' => $kode_barang,
            'harga_barang' => $harga_barang,
            'stok' => $stok,
        ];

        // Simpan data ke database
        $model = new M_siapake();
        // Simpan data barang
$model->tambah('barang', $data);

// Data yang akan diencode menjadi barcode hanya kode_barang
$dataToEncode = $kode_barang;  // Hanya kode_barang yang dimasukkan

// Buat barcode
$barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
$barcodeData = $barcodeGenerator->getBarcode($dataToEncode, $barcodeGenerator::TYPE_CODE_128);

// Path untuk menyimpan barcode
$barcodeFile = FCPATH . 'uploads/' . $kode_barang . '.png';

// Tambahkan teks pada barcode
$this->addTextToBarcode($barcodeData, $kode_barang, $barcodeFile);

// Redirect ke halaman daftar barang
return redirect()->to('home/barang')->with('success', 'Data barang berhasil ditambahkan.');

    } else {
        return redirect()->to('home/login');
    }
}
public function cariBarangDariBarcode()
{
    if ($this->request->getPost('barcode')) {
        $kode_barang = $this->request->getPost('barcode'); // Nilai barcode yang dipindai

        // Ambil data barang dari database berdasarkan kode_barang
        $model = new M_siapake();
        $barang = $model->getWhere('barang', ['kode_barang' => $kode_barang]);

        if ($barang) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'nama_barang' => $barang->nama_barang,
                    'harga_barang' => $barang->harga_barang,
                    'stok' => $barang->stok,
                ],
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan!',
            ]);
        }
    }
    return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Barcode tidak valid!',
    ]);
}

private function addTextToBarcode($barcodeData, $kodeBarang, $filePath)
{
    // Load barcode sebagai gambar
    $barcodeImage = imagecreatefromstring($barcodeData);
    if (!$barcodeImage) {
        throw new \Exception("Gagal membuat gambar barcode dari data.");
    }

    // Dapatkan ukuran barcode
    $barcodeWidth = imagesx($barcodeImage);
    $barcodeHeight = imagesy($barcodeImage);

    // Periksa dimensi
    if ($barcodeWidth <= 0 || $barcodeHeight <= 0) {
        throw new \Exception("Dimensi barcode tidak valid.");
    }

    // Tentukan ukuran kanvas baru
    $canvasHeight = $barcodeHeight + 20; // Tambahkan ruang untuk teks
    $canvas = imagecreatetruecolor($barcodeWidth, $canvasHeight);

    // Atur warna latar belakang putih
    $white = imagecolorallocate($canvas, 255, 255, 255);
    imagefill($canvas, 0, 0, $white);

    // Salin barcode ke kanvas
    imagecopy($canvas, $barcodeImage, 0, 0, 0, 0, $barcodeWidth, $barcodeHeight);

    // Tambahkan teks kode barang
    $black = imagecolorallocate($canvas, 0, 0, 0);
    $fontPath = FCPATH . 'fonts/arial.ttf'; // Lokasi file font
    if (!file_exists($fontPath)) {
        throw new \Exception("File font tidak ditemukan di {$fontPath}");
    }

    $fontSize = 10; // Ukuran font
    $textX = ($barcodeWidth - (strlen($kodeBarang) * $fontSize / 2)) / 2; // Pusatkan teks
    $textY = $barcodeHeight + 15; // Posisi teks

    // Tambahkan teks ke gambar
    imagettftext($canvas, $fontSize, 0, $textX, $textY, $black, $fontPath, $kodeBarang);

    // Simpan hasil sebagai file PNG
    imagepng($canvas, $filePath);

    // Bersihkan memori
    imagedestroy($canvas);
    imagedestroy($barcodeImage);
}


public function e_barang($id_barang){

    $model= new M_siapake();

    $wherebarang = array('id_barang' => $id_barang);
    $data['oke'] = $model->getWhere1('barang', $wherebarang)->getRow();


    $where = array('id_setting' => '1');
		$data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_user = session()->get('id');
        $activityLog = [
            'id_user' => $id_user,
            'menu' => 'Masuk ke Edit Barang',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);
	echo view('header', $data);
	echo view('menu');
    echo view('e_barang', $data);
    echo view('footer');

}


public function aksi_e_barang()
{
if(session()->get('id') > 0){
    $nama_barang = $this->request->getPost('nama_barang');
    $harga_barang = $this->request->getPost('harga_barang');
    $stok = $this->request->getPost('stok');
    $id = $this->request->getPost('id_barang');
        
    $where = array('id_barang' => $id);


    $yoga = array(
        'nama_barang' => $nama_barang,
        'harga_barang' => $harga_barang,
        'stok' => $stok,
    );

    $model = new M_siapake;
    // print_r($yoga);
    $model->edit('barang', $yoga, $where); // Menyimpan data barang ke database
    return redirect()->to('home/barang');
} else {
    return redirect()->to('home/login');
}
}



public function barcode()
{
    $model = new M_siapake();

    $where = array('id_setting' => '1');
    $data['yogi'] = $model->getWhere1('setting', $where)->getRow();
    
    $id_user = session()->get('id');
    $activityLog = [
        'id_user' => $id_user,
        'menu' => 'Masuk ke Barcode',
        'time' => date('Y-m-d H:i:s')
    ];
    $model->logActivity($activityLog);

    echo view('header', $data);
    echo view('menu');
    echo view('barcode'); // Panggil view barcode
    echo view('footer');
}


public function kasir(){

    $model= new M_siapake();
    $data['oke']= $model->tampil('barang');

    $where = array('id_setting' => '1');
		$data['yogi'] = $model->getWhere1('setting', $where)->getRow();
        $id_user = session()->get('id');
        $activityLog = [
            'id_user' => $id_user,
            'menu' => 'Masuk ke Kasir',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);
	echo view('header', $data);
	echo view('menu');
    echo view('kasir', $data);
    echo view('footer');

}


public function aksi_t_pemesanan() {
    if (session()->get('id') > 0) {
        // Ambil data dari request
        $kodeBarang = $this->request->getPost('kode_barang');
        $nomorStruk = $this->request->getPost('nomor_struk');
        $total = $this->request->getPost('total');
        $bayar = $this->request->getPost('bayar');
        $kembalian = $this->request->getPost('kembalian');
        $jumlah = $this->request->getPost('jumlah');
        $emailMember = $this->request->getPost('email_member'); // Email member langsung dari form
        $noHpMember = $this->request->getPost('no_hp_member'); // Nomor HP member langsung dari form
        $tanggal = date('Y-m-d H:i:s');

        // Cek jika email_member dan no_hp_member kosong
        if (empty($emailMember) && empty($noHpMember)) {
            // Jika kosong, panggil controller printnota dan hentikan eksekusi lebih lanjut
            return $this->printnota(); // Pastikan printnota mengembalikan respons yang sesuai
        }

        $model = new M_siapake;

        if (empty($nomorStruk)) {
            $nomorStruk = $model->buatNomorStrukBaru();
        }

        // Proses transaksi untuk setiap barang
        foreach ($kodeBarang as $index => $kode) {
            $dataTransaksi = [
                'nomor_struk' => $nomorStruk,
                'kode_barang' => $kode,
                'jumlah' => $jumlah[$index],
                'total' => $total,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
                'tanggal' => $tanggal
            ];

            $model->tambah('transaksi', $dataTransaksi);
        }

        // Generate PDF dan kirim ke email pelanggan
        $emailResult = $this->generateAndSendNota($nomorStruk, $emailMember);

        // Kirim pengumuman melalui WhatsApp
        $this->kirimPengumumanWhatsApp($nomorStruk, $noHpMember);

        if ($emailResult) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Nota berhasil dikirim ke email dan WhatsApp.',
                'nomor_struk' => $nomorStruk
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengirim nota ke email.'
            ]);
        }
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Silakan login untuk melanjutkan.'
        ]);
    }
}




private function generateAndSendNota($nomorStruk, $emailMember)
{
    $model = new M_siapake();
    $data['transaksi'] = $model->joinresult('transaksi', 'barang', 'transaksi.kode_barang=barang.kode_barang', ['nomor_struk' => $nomorStruk]);

    if (empty($data['transaksi'])) {
        log_message('error', "Transaksi dengan nomor struk {$nomorStruk} tidak ditemukan.");
        return false; // Transaksi tidak ditemukan
    }

    require_once APPPATH . '../vendor/autoload.php';

    // Inisialisasi TCPDF
    $pdf = new \TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nama Toko');
    $pdf->SetTitle('Nota Transaksi');
    $pdf->SetHeaderData('', 0, 'Nota Transaksi', date('D d/m/Y H:i:s'));
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Tambahkan halaman dengan ukuran A6
    $pdf->AddPage('P', 'A6');

    // Konten HTML untuk Nota
    $html = '<h2 style="text-align: center;">Nota Transaksi</h2>';
    $html .= '<p>No Transaksi: <strong>' . htmlspecialchars($nomorStruk, ENT_QUOTES, 'UTF-8') . '</strong></p>';
    $html .= '<p>Tanggal: <strong>' . htmlspecialchars($data['transaksi'][0]->tanggal, ENT_QUOTES, 'UTF-8') . '</strong></p>';
    $html .= '<hr>';
    $html .= '<table style="width: 100%; border-collapse: collapse;">';
    $html .= '<thead><tr><th>Barang</th><th>Jumlah</th><th>Harga</th></tr></thead>';
    $html .= '<tbody>';

    $totalAmount = 0;
    foreach ($data['transaksi'] as $item) {
        $itemTotal = (float)$item->harga_barang * (int)$item->jumlah;
        $totalAmount += $itemTotal;
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($item->nama_barang, ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td>' . htmlspecialchars($item->jumlah, ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td>Rp ' . number_format($itemTotal, 0, ',', '.') . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<hr>';
    $html .= '<p>Total: Rp ' . number_format($totalAmount, 0, ',', '.') . '</p>';

    // Ambil informasi bayar dan kembalian
    $bayar = floatval(str_replace(['Rp ', '.'], ['', ''], $data['transaksi'][0]->bayar));
    $kembalian = floatval(str_replace(['Rp ', '.'], ['', ''], $data['transaksi'][0]->kembalian));

    // Menambahkan informasi bayar dan kembalian
    $html .= '<p>Bayar: Rp ' . number_format($bayar, 0, ',', '.') . '</p>';
    $html .= '<p>Kembalian: Rp ' . number_format($kembalian, 0, ',', '.') . '</p>';

    // Tambahkan ke PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Tentukan lokasi penyimpanan file PDF
    $filePath = WRITEPATH . "uploads/nota_transaksi_{$nomorStruk}.pdf";
    $pdf->Output($filePath, 'F'); // Simpan file PDF

    // Kirim email dengan file terlampir
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kaizenesia@gmail.com';
        $mail->Password = 'cgjn ewgg bhhv invz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kaizenesia@gmail.com', 'Nama Toko'); // Ganti dengan email pengirim
        $mail->addAddress($emailMember); // Email tujuan
        $mail->Subject = 'Nota Transaksi';
        $mail->Body = 'Berikut adalah nota transaksi Anda. Terima kasih atas pembelian Anda.';
        $mail->addAttachment($filePath); // Lampirkan file PDF

        $mail->send();

        // Hapus file setelah terkirim
        unlink($filePath);

        return true;
    } catch (Exception $e) {
        log_message('error', "Gagal mengirim email: {$mail->ErrorInfo}");
        return false;
    }
}

private function kirimPengumumanWhatsApp($nomorStruk, $noHpMember)
{
    $model = new M_siapake();

    // Ambil data transaksi untuk detail pesan
    $data['transaksi'] = $model->joinresult('transaksi', 'barang', 'transaksi.kode_barang=barang.kode_barang', ['nomor_struk' => $nomorStruk]);

    if (empty($data['transaksi'])) {
        log_message('error', "Transaksi dengan nomor struk {$nomorStruk} tidak ditemukan.");
        return false; // Transaksi tidak ditemukan
    }

    // Persiapkan pesan untuk WhatsApp
    $message = "*Pengumuman Transaksi:*\n";
    $message .= "Nomor Struk: *$nomorStruk*\n";
    $message .= "Terima kasih telah berbelanja. Berikut adalah detail transaksi Anda:\n\n";

    $totalAmount = 0;
    foreach ($data['transaksi'] as $item) {
        $itemTotal = (float)$item->harga_barang * (int)$item->jumlah;
        $totalAmount += $itemTotal;
        $message .= "Barang: *" . htmlspecialchars($item->nama_barang) . "*\n";
        $message .= "Jumlah: " . htmlspecialchars($item->jumlah) . "\n";
        $message .= "Harga: Rp " . number_format($itemTotal, 0, ',', '.') . "\n\n";
    }

    $message .= "Total: Rp " . number_format($totalAmount, 0, ',', '.') . "\n";
    $message .= "Terima kasih atas transaksi Anda.";

    // Kirim pesan WhatsApp menggunakan sendWhatsAppMessage
    return $this->sendWhatsAppMessage($noHpMember, $message);
}



private function sendWhatsAppMessage($phone, $message, $file = null)
{
    // Ganti dengan URL API UltraMsg Anda
    $instance_id = 'instance104196'; // Instance ID Anda
    $token = 'f11cl1p92ip3v79a'; // API Key Anda

    // URL API UltraMsg
    $url = "https://api.ultramsg.com/$instance_id/messages/chat";
    
    // Data untuk mengirim pesan
    $data = [
        'token' => $token,
        'to' => $phone,
        'body' => $message
    ];

    // Jika ada file, kirimkan file sebagai attachment
    if ($file) {
        $data['mediaUrl'] = $file;  // Attach file URL under mediaUrl
    }

    // Mengirim HTTP POST request menggunakan file_get_contents
    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    // Mengecek apakah ada error dalam respon API
    if ($response === FALSE) {
        // Jika gagal, kirimkan status error
        log_message('error', "Gagal mengirim pesan ke $phone. Response: $response");
        return [
            'status' => 'failure',
            'message' => 'Gagal mengirim pesan ke WhatsApp.'
        ];
    }

    // Tampilkan response dari API
    log_message('info', "Pesan WhatsApp berhasil dikirim ke $phone. Response: $response");

    // Decode response JSON
    $responseData = json_decode($response, true);

    // Periksa apakah ada kesalahan dalam respons
    if (isset($responseData['error'])) {
        log_message('error', "Error dari API: " . $responseData['error']);
        return [
            'status' => 'failure',
        ];
    }

    // Mengembalikan status sukses
    return [
        'status' => 'success',
        'message' => 'Pesan berhasil dikirim ke WhatsApp.'
    ];
}



public function printnota() {
    $model = new M_siapake();
    
    // Ambil nomor struk dari query string
    $nomor_struk = $this->request->getGet('nomor_struk');

    if (session()->get('id') > 0) {
        // Retrieve user and setting information
        $data['dua'] = $model->getWhere('user', ['id_user' => session()->get('id')]);
        $data['setting'] = $model->getWhere('setting', ['id_setting' => 1]);
        
        // Retrieve transaksi data
        $data['transaksi'] = $model->joinresult('transaksi', 'barang', 'transaksi.kode_barang=barang.kode_barang', ['nomor_struk' => $nomor_struk]);

        // Check if the transaction exists
        if (empty($data['transaksi'])) {
            return redirect()->to('home/kasir')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Log activity
        $id_user = session()->get('id');
        $activityLog = [
            'id_user' => $id_user,
            'menu' => 'Masuk ke Print Nota',
            'time' => date('Y-m-d H:i:s')
        ];
        $model->logActivity($activityLog);

        // Load TCPDF library
        require_once APPPATH . '../vendor/autoload.php';

        // Inisialisasi TCPDF
        $pdf = new \TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Nota Transaksi');
        $pdf->SetHeaderData('', 0, 'Nota Transaksi', date('D d/m/Y H:i:s'));
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // Set ukuran kertas menjadi A6
        $pdf->AddPage('P', 'A6'); // 'P' untuk portrait, 'A6' untuk ukuran kertas A6

        // Prepare HTML content with inline styles
        $html = '<h2 style="text-align: center;">Detail Pemesanan</h2>';
        $html .= '<p>Kasir: <strong>' . htmlspecialchars(session()->get('nama'), ENT_QUOTES, 'UTF-8') . '</strong></p>'; // Kasir name
        $html .= '<p>No Transaksi: <strong>' . htmlspecialchars($data['transaksi'][0]->nomor_struk, ENT_QUOTES, 'UTF-8') . '</strong></p>'; // Transaction number
        $html .= '<p>Tanggal: <strong>' . htmlspecialchars($data['transaksi'][0]->tanggal, ENT_QUOTES, 'UTF-8') . '</strong></p>'; // Transaction date

        // Table for menu details
        $html .= '<table style="width: 100%; border-collapse: collapse; margin: 10px 0;">';
        $html .= '<thead><tr>';
        $html .= '<th style="padding: 10px; text-align: left;">Nama Barang</th>';
        $html .= '<th style="padding: 10px; text-align: left;">Jumlah</th>';
        $html .= '<th style="padding: 10px; text-align: left;">Harga</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';

        $totalAmount = 0; // Initialize total amount
        foreach ($data['transaksi'] as $item) {
            // Pastikan harga_barang ada
            if (isset($item->harga_barang)) {
                $itemTotal = floatval(str_replace(['Rp ', '.'], ['', ''], $item->harga_barang)) * (float)$item->jumlah; // Menghitung total item
                $totalAmount += $itemTotal;
                $html .= '<tr>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($item->nama_barang, ENT_QUOTES, 'UTF-8') . '</td>'; // Nama barang
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($item->jumlah, ENT_QUOTES, 'UTF-8') . '</td>'; // Jumlah
                $html .= '<td style="padding: 10px;">Rp ' . number_format($itemTotal, 0, ',', '.') . '</td>'; // Total harga per item
                $html .= '</tr>';
            } else {
                // Jika harga_barang tidak ada, tampilkan pesan error
                $html .= '<tr><td colspan="3">Harga barang tidak tersedia</td></tr>';
            }
        }

        $html .= '</tbody>';
        $html .= '</table>';

        // Tambahkan garis horizontal
        $html .= '<hr style="border: 1px solid black; margin: 10px 0;">';

        // Total calculation
        $total = floatval(str_replace(['Rp ', '.'], ['', ''], $data['transaksi'][0]->total));
        $bayar = floatval(str_replace(['Rp ', '.'], ['', ''], $data['transaksi'][0]->bayar));
        $kembalian = floatval(str_replace(['Rp ', '.'], ['', ''], $data['transaksi'][0]->kembalian));

        // Buat tabel untuk total, bayar, dan kembalian
        $html .= '<table style="width: 100%; margin: 10px 0;">';
        $html .= '<tr>';
        $html .= '<td style="text-align:right; padding: 5px; font-weight:bold;">Total:</td>'; // Label total
        $html .= '<td style="text-align:right; padding: 5px; font-weight:bold;">Rp ' . number_format($totalAmount, 0, ',', '.') . '</td>'; // Total amount
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="text-align:right; padding: 5px;">Bayar:</td>';
        $html .= '<td style="text-align:right; padding: 5px;">Rp ' . number_format($bayar, 0, ',', '.') . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="text-align:right; padding: 5px;">Kembalian:</td>';
        $html .= '<td style="text-align:right; padding: 5px;">Rp ' . number_format($kembalian, 0, ',', '.') . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        // Tutup div
        $html .= '</div>';

        // Load HTML content into TCPDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output the generated PDF to the browser
        $pdf->Output("nota_transaksi.pdf", 'I'); // 'I' untuk menampilkan di browser

        // Exit the script
        exit;
    } else {
        return redirect()->to('login')->with('error', 'Silakan login untuk melanjutkan.');
    }
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


public function setting()
    {
      
                $model = new M_siapake;
                $where = array('id_setting' => '1');
                $data['yogi'] = $model->getWhere1('setting', $where)->getRow();

                $id_user = session()->get('id');
    $activityLog = [
        'id_user' => $id_user,
        'menu' => 'Masuk ke Setting',
        'time' => date('Y-m-d H:i:s')
    ];
    $model->logActivity($activityLog);
                echo view('header', $data);
                echo view('menu');
                echo view('setting', $data);
                echo view('footer');
           
    }

    public function aksi_e_setting()
    {
        $model = new M_siapake();
        // $this->logUserActivity('Melakukan Setting');
        $namaWebsite = $this->request->getPost('namawebsite');
        $id = $this->request->getPost('id');
        $id_user = session()->get('id');
        $where = array('id_setting' => '1');

        $data = array(
            'nama_website' => $namaWebsite,
            'update_by' => $id_user,
            'update_at' => date('Y-m-d H:i:s')
        );

        // Cek apakah ada file yang diupload untuk favicon
        $favicon = $this->request->getFile('img');
        if ($favicon && $favicon->isValid() && !$favicon->hasMoved()) {
            // Beri nama file unik
            $faviconNewName = $favicon->getRandomName();
            // Pindahkan file ke direktori public/images
            $favicon->move(WRITEPATH . '../public/images', $faviconNewName);

            // Tambahkan nama file ke dalam array data
            $data['tab_icon'] = $faviconNewName;
        }

        // Cek apakah ada file yang diupload untuk logo
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            // Beri nama file unik
            $logoNewName = $logo->getRandomName();
            // Pindahkan file ke direktori public/images
            $logo->move(WRITEPATH . '../public/images', $logoNewName);

            // Tambahkan nama file ke dalam array data
            $data['logo_website'] = $logoNewName;
        }

        // Cek apakah ada file yang diupload untuk logo
        $login = $this->request->getFile('login');
        if ($login && $login->isValid() && !$login->hasMoved()) {
            // Beri nama file unik
            $loginNewName = $login->getRandomName();
            // Pindahkan file ke direktori public/images
            $login->move(WRITEPATH . '../public/images', $loginNewName);

            // Tambahkan nama file ke dalam array data
            $data['login_icon'] = $loginNewName;
        }

        $model->edit('setting', $data, $where);

        // Optionally set a flash message here
        return redirect()->to('home/setting');
    }
}
