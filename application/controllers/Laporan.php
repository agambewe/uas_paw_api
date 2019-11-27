<?php
use Restserver\Libraries\REST_Controller;
	defined('BASEPATH') OR exit('No direct script access allowed');

	require APPPATH . 'libraries/REST_Controller.php';
    require APPPATH . 'libraries/Format.php';	
Class Laporan extends REST_Controller{
    
    function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization");
        parent::__construct();
        $this->load->library('Pdf');
        $this->load->model("PotongModel"); 
        $this->load->model("MakeupModel"); 
        
    }
    function index_get(){
        echo "Laporan pdf";
    }

    function potong_get(){
        $pdf = new FPDF('l','mm','A4');
        // Menambah halaman baru
        $pdf->AddPage();
        // Setting jenis font
        $pdf->SetFont('Arial','B',16);
        // Membuat string
        $pdf->Cell(260,7,'Logs Potong',0,1,'C');
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

    function makeup_get(){
        $pdf = new FPDF('l','mm','A4');
        // Menambah halaman baru
        $pdf->AddPage();
        // Setting jenis font
        $pdf->SetFont('Arial','B',16);
        // Membuat string
        $pdf->Cell(260,7,'Logs Makeup',0,1,'C');
        // Setting spasi kebawah supaya tidak rapat
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(10,6,'NO',1,0);
        $pdf->Cell(43,6,'EMAIL',1,0);
        $pdf->Cell(40,6,'NAMA',1,0);
        $pdf->Cell(27,6,'KATEGORI',1,0);
        $pdf->Cell(21,6,'TANGGAL',1,0);
        $pdf->Cell(30,6,'NO TELEPON',1,0);
        $pdf->Cell(20,6,'PEMAKEUP',1,1);

        $pdf->SetFont('Arial','',9);
        $query = $this->MakeupModel->getTerlayaniMakeup();
        $no = 1;
        foreach( $query as $row )
        {
            $pdf->Cell(10,6,$no,1,0);
            $pdf->Cell(43,6,$row->email,1,0);
            $pdf->Cell(40,6,$row->nama,1,0);
            $pdf->Cell(27,6,$row->kategori,1,0);
            $pdf->Cell(21,6,$row->tanggal,1,0);
            $pdf->Cell(30,6,$row->noTelepon,1,0);
            $pdf->Cell(20,6,$row->pemakeup,1,1);
            $no++;
        }
    $pdf->Output();
    }
}