<?php

require('fpdf.php');

$pdf = new FPDF('p', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 7, 'Formulir Peminjaman Peralatan', 0, 1, 'C');

$pdf->Ln();

$titikdua = ': ';
$nama = 'Maria Auleria';
$bid = 'BN001768386';
$prodiv = 'Desain Interior';
$hp = '081384037069';
$keperluan = 'photoshoot mahasiswa';
$lok = 'ruang multimedia';
$book = 'Jumat, 26 November 2022 / 11:00';
$return = 'Jumat, 26 November 2022 / 15:00';

$nama = $titikdua . $nama;
$bid = $titikdua . $bid;
$prodiv = $titikdua . $prodiv;
$hp = $titikdua . $hp;
$keperluan = $titikdua . $keperluan;
$lok = $titikdua . $lok;
$book = $titikdua . $book;
$return = $titikdua . $return;
$keterangan = '';

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(50, 6, 'Nama Peminjam', 0, 0); $pdf->Cell(15, 6, $nama, 0, 1);
$pdf->Cell(50, 6, 'Binusian ID', 0, 0); $pdf->Cell(40, 6, $bid, 0, 0); $pdf->Cell(25, 6, 'Prodi/Unit', 0, 0); $pdf->Cell(15, 6, $prodiv, 0, 1);
$pdf->Cell(50, 6, 'No. Handphone', 0, 0); $pdf->Cell(40, 6, $hp, 0, 1);
$pdf->Ln();
$pdf->Cell(50, 6, 'Keperluan', 0, 0); $pdf->Cell(40, 6, $keperluan, 0, 1); 
$pdf->Cell(50, 6, 'Peralatan dipakai di', 0, 0); $pdf->Cell(40, 6, $lok, 0, 1); 
$pdf->Cell(50, 6, 'Hari, Tanggal/Jam Pinjam', 0, 0); $pdf->Cell(40, 6, $book, 0, 1); 
$pdf->Cell(50, 6, 'Hari, Tanggal/Jam Kembali', 0, 0); $pdf->Cell(40, 6, $return, 0, 1); 

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(10, 6, 'No', 1, 0, 'C');
$pdf->Cell(80, 6, 'Nama Barang', 1, 0, 'C');
$pdf->Cell(20, 6, 'Qty', 1, 0, 'C');
$pdf->Cell(70, 6, 'ID Barang', 1, 1, 'C');

$pdf->SetFont('Arial', '', 11);
// while(){
    $pdf->Cell(10, 6, '1', 1, 0, 'C');
    $pdf->Cell(80, 6, 'Camera', 1, 0, 'C');
    $pdf->Cell(20, 6, '1', 1, 0, 'C');
    $pdf->Cell(70, 6, '50', 1, 1, 'C');
// }

$pdf->Ln();

$pdf->Cell(30, 6, 'Keterangan pengembalian', 0, 1);
$pdf->Cell(180, 20, $keterangan, 1, 1);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(180, 6, 'Catatan: Peminjam (dan/atau anggota kelompoknya) bertanggung jawab dan bersedia menerima segala konsekuensi jika terjadi hal-hal yang tidak', 0, 1);
$pdf->Cell(180, 1, 'diinginkan terhadap peralatan yang dipinjam, serta bersedia menerima sanksi jika terlambat mengembalikan peralatan.', 0, 1);
$pdf->Cell(180, 8, '', 0, 1);

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(110, 6, '', 0, 0);
$pdf->Cell(35, 6, 'Tanggal ambil', 1, 0, 'C');
$pdf->Cell(35, 6, 'Tanggal kembali', 1, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(110, 6, '', 0, 0);
$pdf->Cell(35, 6, 'Jumat, 26 November 2022', 1, 0, 'C');
$pdf->Cell(35, 6, 'Jumat, 26 November 2022', 1, 1, 'C');
$pdf->Cell(180, 6, '*Dokumen ini sah diketahui SCC koordinator dan peminjam meskipun tanpa tanda tangan', 0, 1, 'R');

$pdf->Output();

?>