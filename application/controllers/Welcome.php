<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Welcome extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model("GetYoutube"); 
	}
 
	public function index()
	{
		$this->load->view('v_jpnn');
	}

	public function datatable(){
		$this->load->view('v_datatable');
	}

	public function apiDatatable(){
		$start_date = $this->input->post('start_date') ?? date('Y-m-d');
        $end_date	= $this->input->post('end_date') ?? date('Y-m-d');
		$list 		= $this->GetYoutube->get_datatables_youtube($start_date, $end_date);
        $data 		= array();
        // $no = $this->input->post('start');
		foreach ($list as $key) {
			array_push($data, array(
				// 'action'		=> '<a href="'.base_url('jpnn/detail/'.$key->id_list).'" class="btn btn-sm btn-info">Detail</a>',
				'id_list' => $key->id_list,
				'title' => $key->title,
				'youtubeID' => $key->youtubeID,
				'publishedAt' => $key->publishedAt,
				'thumbnail' => $key->thumbnail,
				'desciption' => $key->desciption,
				'tags' => $key->tags,
			));
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->GetYoutube->count_all_youtube($start_date, $end_date),
			"recordsFiltered" => $this->GetYoutube->count_filtered_youtube($start_date, $end_date),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ProcessInsertYoutube(){

		if($this->input->post()){

			$post = array();
			foreach($this->input->post() as $key => $value){
				$post[$key] = $value;
			}
			$this->db->insert('list_youtube', $post);
			
			echo json_encode(array('code'=>200,'status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => $post));

		}else{

			echo json_encode(array('code' => 400, 'status'=>'error','message'=>'No data post', 'data' => array()));
		}

	}

	public function export($result = array()){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$style_col = [
		  'font' => ['bold' => true], // Set font nya jadi bold
		  'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
		  'borders' => [
			'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
			'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
			'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
			'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
		  ]
		];
		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = [
		  'alignment' => [
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
		  'borders' => [
			'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
			'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
			'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
			'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
		  ]
		];
		$sheet->setCellValue('A1', "DATA SISWA"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
		// Buat header tabel nya pada baris ke 3
		$sheet->setCellValue('A3', "Title"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B3', "Youtube ID"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('C3', "Description"); // Set kolom C3 dengan tulisan "NAMA"
		$sheet->setCellValue('D3', "Thumbnail"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('E3', "PublihedAt"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('F3', "Tags"); // Set kolom E3 dengan tulisan "ALAMAT"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);
		
		// Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
		// $siswa = $this->SiswaModel->view();
		$start_date = $this->input->post('start_date') ?? date('Y-m-d');
        $end_date	= $this->input->post('end_date') ?? date('Y-m-d');
		$this->db->where('publishedAt >=', $start_date);
		$this->db->where('publishedAt <=', $end_date);
		$list 		= $this->db->get('list_youtube')->result_array();
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach($list as $data){ // Lakukan looping pada variabel siswa
		  $sheet->setCellValue('A'.$numrow, $data['title']);
		  $sheet->setCellValue('B'.$numrow, $data['youtubeID']);
		  $sheet->setCellValue('C'.$numrow, $data['desciption']);
		  $sheet->setCellValue('D'.$numrow, $data['thumbnail']);
		  $sheet->setCellValue('E'.$numrow, $data['publishedAt']);
		  $sheet->setCellValue('F'.$numrow, $data['tags']);
		  
		  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
		  $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
		  $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
		  
		  $no++; // Tambah 1 setiap kali looping
		  $numrow++; // Tambah 1 setiap kali looping
		}

		// Set width kolom
		$sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
		$sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
		$sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
		$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
		$sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
		$sheet->getColumnDimension('F')->setWidth(30); // Set width kolom E
		
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("Laporan Data");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Data All.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}

