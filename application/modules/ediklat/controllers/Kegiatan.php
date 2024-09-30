<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require_once(FCPATH . 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php');
// require_once(FCPATH . 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/Xlsx.php');
require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

class Kegiatan extends MX_Controller
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
        (!in_array('RACTIVITY', $userPermission)) ? redirect('authentication/logout') : '';

        $data['_view'] = $this->module . '/kegiatan';
        $this->load->view('layouts/back/main', $data);
    }

    public function download_absen($activityCode = '')
    {

        $kegiatan = $this->db->get_where('activity', ['activityCode' => $activityCode])->row();
        $peserta = $this->db->get_where('participant', ['activityCode' => $activityCode, 'deleteAt' => NULL])->result();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', $kegiatan->name);
        $sheet->getStyle('A1')->getFont()->getSize(14);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A2:B2');
        $sheet->setCellValue('A2', 'Penyelenggara');
        $sheet->getStyle('B2')->getFont()->getSize(12);
        $sheet->setCellValue('C2', $kegiatan->organizer);
        $sheet->getStyle('C2')->getFont()->getSize(12);

        $sheet->mergeCells('A3:B3');
        $sheet->setCellValue('A3', 'Tanggal');
        $sheet->getStyle('B3')->getFont()->getSize(12);
        $sheet->setCellValue('C3', tanggal_indo($kegiatan->startDate));
        $sheet->getStyle('C3')->getFont()->getSize(12);

        $sheet->mergeCells('A4:B4');
        $sheet->setCellValue('A4', 'Media');
        $sheet->getStyle('B4')->getFont()->getSize(12);
        $sheet->setCellValue('C4', $kegiatan->media);
        $sheet->getStyle('C4')->getFont()->getSize(12);


        $sheet->getStyle('A6:L6')->getFont()->setBold(true);
        $sheet->getStyle('A6:L6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A6:L6')->getFont()->getSize(10);

        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'Nama');
        $sheet->setCellValue('C6', 'NIK');
        $sheet->setCellValue('D6', 'Tempat/Tanggal Lahir');
        $sheet->setCellValue('E6', 'No Hp');
        $sheet->setCellValue('F6', 'Instansi Pengirim');
        $sheet->setCellValue('G6', 'Jenis Kelamin');
        $sheet->setCellValue('H6', 'Pangkat/Golongan');
        $sheet->setCellValue('I6', 'Jabatan Dalam Dinas');
        $sheet->setCellValue('J6', 'Pendidikan Terakhir');
        $sheet->setCellValue('K6', 'NPWP');
        $sheet->setCellValue('L6', 'Email');

        $row = 7;
        $no = 1;
        foreach ($peserta as $k => $v) {
            $sheet->getStyle('A' . $row . ':K' . $row)->getFont()->getSize(9);

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $v->name);
            $sheet->getCell('C' . $row)->setValueExplicit(
                $v->nik,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValue('D' . $row, $v->birthplace);
            $sheet->getCell('E' . $row)->setValueExplicit(
                $v->phone,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValue('F' . $row, $v->agency);
            $sheet->setCellValue('G' . $row, $v->gender);
            $sheet->setCellValue('H' . $row, $v->rank);
            $sheet->setCellValue('I' . $row, $v->rank_dinas);
            $sheet->setCellValue('J' . $row, $v->education);
            $sheet->getCell('K' . $row)->setValueExplicit(
                $v->npwp,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValue('L' . $row, $v->email);
            $no += 1;
            $row += 1;
        }


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('Data-Peserta.xlsx') . '"');
        $writer->save('php://output');
    }

    public function download_peserta($pesertaCode = '')
    {
        $pdf = new Fpdi();
        $pdf->AddPage();
        $peserta = $this->db->get_where('participant', ['participantCode' => $pesertaCode])->row();
        $img = (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $peserta->picture))) ? path_by_os(FCPATH . 'assets/img/participant/' . $peserta->picture) : path_by_os(FCPATH . 'assets/img/participant/default.png');
        $pdf->Image($img, 20, 20, 45, 60);
        $pdf->SetFont("helvetica", "", 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Text(82, 23, 'Nama');
        $pdf->Text(82, 27, 'NIK');
        $pdf->Text(82, 31, 'NPWP');
        $pdf->Text(82, 35, 'Tempat/Tanggal Lahir');
        $pdf->Text(82, 39, 'No Hp');
        $pdf->Text(82, 43, 'Instansi');
        $pdf->Text(82, 47, 'Pangkat/Golongan');
        $pdf->Text(82, 51, 'Jabatan Dalam Dinas');
        $pdf->Text(82, 55, 'Pendidikan Terakhir');
        $pdf->Text(82, 59, 'Email');
        $pdf->Text(82, 63, 'Alamat');

        $pdf->Text(130, 23, ':');
        $pdf->Text(130, 27, ':');
        $pdf->Text(130, 31, ':');
        $pdf->Text(130, 35, ':');
        $pdf->Text(130, 39, ':');
        $pdf->Text(130, 43, ':');
        $pdf->Text(130, 47, ':');
        $pdf->Text(130, 51, ':');
        $pdf->Text(130, 55, ':');
        $pdf->Text(130, 59, ':');
        $pdf->Text(130, 63, ':');


        $pdf->Text(132, 23, $peserta->name);
        $pdf->Text(132, 27, $peserta->nik);
        $pdf->Text(132, 31, $peserta->npwp);
        $pdf->Text(132, 35, $peserta->birthplace);
        $pdf->Text(132, 39, $peserta->phone);
        $pdf->Text(132, 43, $peserta->agency);
        $pdf->Text(132, 47, $peserta->rank);
        $pdf->Text(132, 51, $peserta->rank_dinas);
        $pdf->Text(132, 55, $peserta->education);
        $pdf->Text(132, 59, $peserta->email);
        $pdf->Text(132, 63, $peserta->address);
        $pdf->Output('D', "peserta.pdf", true);
    }
}
