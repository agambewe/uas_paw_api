<?php
Class Laporanpdf extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->library('pdf');
        $this->load->model("PotongModel"); 
    }
    function index(){
        $pdf = new FPDF('l','mm','A4');
// Menambah halaman baru
$pdf->AddPage();
// Setting jenis font
$pdf->SetFont('Arial','B',16);
// Membuat string
$pdf->Cell(260,7,'Jadwal Kuliah Mahasiswa',0,1,'C');
// Setting spasi kebawah supaya tidak rapat
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,6,'NO',1,0);
$pdf->Cell(43,6,'EMAIL',1,0);
$pdf->Cell(40,6,'NO TELEPON',1,0);
$pdf->Cell(27,6,'MODEL',1,0);
$pdf->Cell(21,6,'WARNA',1,0);
$pdf->Cell(30,6,'TANGGAL',1,0);
$pdf->Cell(20,6,'JAM',1,0);
$pdf->Cell(22,6,'PEMOTONG',1,0);
$pdf->Cell(30,6,'PAKET',1,1);

$pdf->SetFont('Arial','',9);
$query = $this->PotongModel->getTerlayaniPotong();
$no = 1;
foreach( $query as $row )
{
    $pdf->Cell(10,6,$no,1,0);
    $pdf->Cell(43,6,$row->email,1,0);
    $pdf->Cell(40,6,$row->noTelepon,1,0);
    $pdf->Cell(27,6,$row->modelRambut,1,0);
    $pdf->Cell(21,6,$row->warna,1,0);
    $pdf->Cell(30,6,$row->tanggal,1,0);
    $pdf->Cell(20,6,$row->jam,1,0);
    $pdf->Cell(22,6,$row->pemotong,1,0);
    $pdf->Cell(30,6,$row->paket,1,1);
    $no++;
}

$pdf->Output();
    }
}