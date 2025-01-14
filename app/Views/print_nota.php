<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
</head>
<body>

<?php
// Include Dompdf library
require_once 'vendor/autoload.php'; // Pastikan path ini sesuai dengan lokasi autoload.php Anda

use Dompdf\Dompdf;
use Dompdf\Options;

// Inisialisasi Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// Prepare HTML content with inline styles
$html = '<div style="font-family: Arial, sans-serif; margin: 0; padding: 20px;">';
$html .= '<div style="text-align: center; margin-bottom: 20px;">';
$html .= '<p>' . date('D d/m/Y H:i:s') . '</p>';
$html .= '</div>';

$html .= '<div style="text-align: left; margin: 10px;">';
$html .= '<h2 style="margin-bottom: 0px;">Detail Pemesanan</h2>';
$html .= '<p style="margin: 5px 0;">Kasir: <strong>' . htmlspecialchars(session()->get('nama'), ENT_QUOTES, 'UTF-8') . '</strong></p>'; // Kasir name
$html .= '<p style="margin: 5px 0;">No Transaksi: <strong>' . htmlspecialchars($transaksi->nomor_struk, ENT_QUOTES, 'UTF-8') . '</strong></p>'; // Transaction number
$html .= '<p style="margin: 5px 0;">Tanggal: <strong>' . htmlspecialchars($transaksi->tanggal, ENT_QUOTES, 'UTF-8') . '</strong></p>'; // Transaction date

// Table for menu details
$html .= '<hr style="border: 1px solid black; margin: 10px 0;">'; // Horizontal line above table
$html .= '<br>'; // Adding one line break for spacing
$html .= '<table style="width: 100%; border-collapse: collapse; margin: 10px 0; border: none;">';
$html .= '<thead><tr>';
$html .= '<th style="padding: 10px; text-align: left;">Nama Barang</th>'; // Changed from 'Nama Menu' to 'Nama Barang'
$html .= '<th style="padding: 10px; text-align: left;">Jumlah</th>';
$html .= '<th style="padding: 10px; text-align: left;">Harga</th>'; // Changed from 'Harga Menu' to 'Harga Barang'
$html .= '</tr></thead>';
$html .= '<tbody>';

$totalAmount = 0; // Initialize total amount
foreach ($pemesanan as $item) {
    $itemTotal = floatval(str_replace(['Rp ', '.'], ['', ''], $item->harga_barang)) * (float)$item->jumlah; // Changed from 'harga_menu' to 'harga_barang'
    $totalAmount += $itemTotal;
    $html .= '<tr>';
    $html .= '<td style="padding: 10px;">' . htmlspecialchars($item->nama_barang, ENT_QUOTES, 'UTF-8') . '</td>'; // Changed from 'nama_menu' to 'nama_barang'
    $html .= '<td style="padding: 10px;">' . htmlspecialchars($item->jumlah, ENT_QUOTES, 'UTF-8') . '</td>';
    // Format item total
    $html .= '<td style="padding: 10px;">Rp ' . number_format($itemTotal, 0, ',', '.') . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';

// Add a horizontal line
$html .= '<hr style="border: 1px solid black; margin: 10px 0;">';

// Total calculation
$total = floatval(str_replace(['Rp ', '.'], ['', ''], $transaksi->total));
$bayar = floatval(str_replace(['Rp ', '.'], ['', ''], $transaksi->bayar));
$kembalian = floatval(str_replace(['Rp ', '.'], ['', ''], $transaksi->kembalian));

// Create a table for total, bayar, and kembalian
// Create a table for total, bayar, and kembalian
$html .= '<table style="width: 100%; margin: 10px 0; border: none;">';
$html .= '<tr>';
$html .= '<td style="text-align:right; padding: 5px; font-weight:bold;">Total:</td>'; // Total label bold
$html .= '<td style="text-align:right; padding: 5px; font-weight:bold;">Rp ' . number_format($totalAmount, 0, ',', '.') . '</td>'; // Total amount bold
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

$html .= '</div>'; // Close the outer div

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A6', 'portrait');

// Render the PDF
$dompdf->render();

// Output the generated PDF to the browser
$dompdf->stream("nota_transaksi.pdf", array("Attachment" => false)); // Set Attachment to false to open in browser

// Exit the script
exit;
?>