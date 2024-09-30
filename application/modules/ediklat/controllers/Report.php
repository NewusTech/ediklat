<?php
defined('BASEPATH') or exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

class Report extends MX_Controller
{
    private $module = 'ediklat';

    public function __construct()
    {
        parent::__construct();
        (isLogin() == false) ? redirect('authentication/logout') : '';
    }

    public function index()
    {
        $userPermission = getPermissionFromUser();
        (!in_array('RREPORT', $userPermission)) ? redirect('authentication/logout') : '';

        $data['_view'] = $this->module . '/report';
        $this->load->view('layouts/back/main', $data);
    }

    public function download()
    {
        $this->load->model($this->module . '/report_model', 'report');

        $report = $this->report->get_all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'Q') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        // $sheet->mergeCells('A1:L1');
        // $sheet->setCellValue('A1', $report->name);
        // $sheet->getStyle('A1')->getFont()->getSize(14);
        // $sheet->getStyle('A1')->getFont()->setBold(true);
        // $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // $sheet->mergeCells('A2:B2');
        // $sheet->setCellValue('A2', 'Penyelenggara');
        // $sheet->getStyle('B2')->getFont()->getSize(12);
        // $sheet->setCellValue('C2', $report->organizer);
        // $sheet->getStyle('C2')->getFont()->getSize(12);

        // $sheet->mergeCells('A3:B3');
        // $sheet->setCellValue('A3', 'Tanggal');
        // $sheet->getStyle('B3')->getFont()->getSize(12);
        // $sheet->setCellValue('C3', tanggal_indo($report->startDate));
        // $sheet->getStyle('C3')->getFont()->getSize(12);

        // $sheet->mergeCells('A4:B4');
        // $sheet->setCellValue('A4', 'Media');
        // $sheet->getStyle('B4')->getFont()->getSize(12);
        // $sheet->setCellValue('C4', $report->media);
        // $sheet->getStyle('C4')->getFont()->getSize(12);


        // $sheet->getStyle('A6:L6')->getFont()->setBold(true);
        // $sheet->getStyle('A6:L6')->getAlignment()->setHorizontal('center');
        // $sheet->getStyle('A6:L6')->getFont()->getSize(10);
        
        $sheet->mergeCells('A6:A7');
        $sheet->setCellValue('A6', 'Nama');
        $sheet->getStyle('A6')->getFont()->getSize(14);
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');

        
        $sheet->mergeCells('B6:B7');
        $sheet->setCellValue('B6', 'Tempat/Tanggal Lahir');
        $sheet->getStyle('B6')->getFont()->getSize(14);
        $sheet->getStyle('B6')->getFont()->setBold(true);
        $sheet->getStyle('B6')->getAlignment()->setHorizontal('center');
        
        $sheet->mergeCells('C6:C7');
        $sheet->setCellValue('C6', 'NIK');
        $sheet->getStyle('C6')->getFont()->getSize(14);
        $sheet->getStyle('C6')->getFont()->setBold(true);
        $sheet->getStyle('C6')->getAlignment()->setHorizontal('center');
        
        
        $sheet->mergeCells('D6:D7');
        $sheet->setCellValue('D6', 'NUPTK');
        $sheet->getStyle('D6')->getFont()->getSize(14);
        $sheet->getStyle('D6')->getFont()->setBold(true);
        $sheet->getStyle('D6')->getAlignment()->setHorizontal('center');
        
        $sheet->mergeCells('E6:E7');
        $sheet->setCellValue('E6', 'Layanan Pendidikan');
        $sheet->getStyle('E6')->getFont()->getSize(14);
        $sheet->getStyle('E6')->getFont()->setBold(true);
        $sheet->getStyle('E6')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('F6:H6');
        $sheet->setCellValue('F6', 'Kegiatan');
        $sheet->getStyle('F6')->getFont()->getSize(14);
        $sheet->getStyle('F6')->getFont()->setBold(true);
        $sheet->getStyle('F6')->getAlignment()->setHorizontal('center');
        
        $sheet->setCellValue('F7', 'Nama');
        $sheet->setCellValue('G7', 'Waktu');
        $sheet->setCellValue('H7', 'Penyelenggara');

        $row = 8;
        $no = 1;
        foreach ($report as $k => $v) {
            $sheet->getStyle('A' . $row . ':H' . $row)->getFont()->getSize(10);

           $keg = $this->db->select('*,activity.name as nameActivity')->join('activity','activity.activityCode=participant.activityCode')->get_where('participant',['memberCode' => $v->memberCode,'participant.deleteAt' => NULL,'activity.deleteAt' => NULL])->result();
           $sheet->mergeCells('A' . $row.':A'.((count($keg) == 0) ? $row : (($row + count($keg))-1)));
           $sheet->mergeCells('B' . $row.':B'.((count($keg) == 0) ? $row : (($row + count($keg))-1)));
           $sheet->mergeCells('C' . $row.':C'.((count($keg) == 0) ? $row : (($row + count($keg))-1)));
           $sheet->mergeCells('D' . $row.':D'.((count($keg) == 0) ? $row : (($row + count($keg))-1)));
           $sheet->mergeCells('E' . $row.':E'.((count($keg) == 0) ? $row : (($row + count($keg))-1)));
            $sheet->setCellValue('A' . $row, $v->nameParticipant);
            $sheet->setCellValue('B' . $row, $v->birthPlace . ($v->birthDate != NULL ? '/' . tanggal_indo($v->birthDate) : ''));
           $sheet->getCell('C' . $row)->setValueExplicit(
                $v->nik,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->getCell('D' . $row)->setValueExplicit(
                $v->npsn,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValue('E' . $row, $v->educationService);
           $row_sub = $row;
               foreach($keg as $g => $r){
                    $sheet->setCellValue('F' . $row_sub, $r->nameActivity);
                    $sheet->setCellValue('G' . $row_sub, tanggal_indo($r->startDate) . ' sampai ' . tanggal_indo($r->endDate));
                    $sheet->setCellValue('H' . $row_sub, $r->organizer);
                    $row_sub += 1;
               }           
            // $drawing->setWorksheet($spreadsheet->getActiveSheet());
            $row = ((count($keg) == 0) ? $row : (($row + count($keg))-1));
            $no += 1;
            $row += 1;
        }


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('Data-Peserta.xlsx') . '"');
        $writer->save('php://output');
    }
}
