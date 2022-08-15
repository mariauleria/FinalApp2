<?php

require('../dbaset.php');

if(isset($_POST['req_id'])){
    $id = $_POST['req_id'];
    $query = "SELECT * FROM requests WHERE request_id = $id;";
    $result = query($query)[0];

    $keperluan = $result['request_reason'];
    $book = $result['book_date'];
    $return = $result['return_date'];
    $taken_date = $result['taken_date'];
    $realize_return_date = $result['realize_return_date'];

    $user_id = $result['user_id'];
    $query = "SELECT * FROM users WHERE user_id = $user_id;";
    $user = query($query)[0];
    $nama = $user['username'];
    $bid = $user['binusian_id'];
    $prodiv = $user['user_kode_prodiv'];
    $hp = $user['user_phone'];

    require('fpdf.php');

    $pdf = new FPDF('p', 'mm', 'A4');
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 7, 'Formulir Peminjaman Peralatan', 0, 1, 'C');

    $pdf->Ln();

    $titikdua = ': ';
    // $lok = 'ruang multimedia';

    $nama = $titikdua . $nama;
    $bid = $titikdua . $bid;
    $prodiv = $titikdua . $prodiv;
    $hp = $titikdua . $hp;
    $keperluan = $titikdua . $keperluan;
    // $lok = $titikdua . $lok;
    $book = $titikdua . $book;
    $return = $titikdua . $return;
    $keterangan = '';

    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(50, 6, 'Nama Peminjam', 0, 0); $pdf->Cell(15, 6, $nama, 0, 1);
    $pdf->Cell(50, 6, 'Binusian ID', 0, 0); $pdf->Cell(40, 6, $bid, 0, 0); $pdf->Cell(25, 6, 'Prodi/Unit', 0, 0); $pdf->Cell(15, 6, $prodiv, 0, 1);
    $pdf->Cell(50, 6, 'No. Handphone', 0, 0); $pdf->Cell(40, 6, $hp, 0, 1);
    $pdf->Ln();
    $pdf->Cell(50, 6, 'Keperluan', 0, 0); $pdf->Cell(40, 6, $keperluan, 0, 1); 
    // $pdf->Cell(50, 6, 'Peralatan dipakai di', 0, 0); $pdf->Cell(40, 6, $lok, 0, 1); 
    $pdf->Cell(50, 6, 'Hari, Tanggal/Jam Pinjam', 0, 0); $pdf->Cell(40, 6, $book, 0, 1); 
    $pdf->Cell(50, 6, 'Hari, Tanggal/Jam Kembali', 0, 0); $pdf->Cell(40, 6, $return, 0, 1); 

    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(10, 6, 'No', 1, 0, 'C');
    $pdf->Cell(80, 6, 'Nama Barang', 1, 0, 'C');
    $pdf->Cell(20, 6, 'Qty', 1, 0, 'C');
    $pdf->Cell(70, 6, 'ID Barang', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 11);
    // TO DO: iterate request_items json
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
    $pdf->Cell(35, 6, $taken_date, 1, 0, 'C');
    $pdf->Cell(35, 6, $realize_return_date, 1, 1, 'C');
    $pdf->Cell(180, 6, '*Dokumen ini sah diketahui SCC koordinator dan peminjam meskipun tanpa tanda tangan', 0, 1, 'R');

    $pdf->Output();
}

?>