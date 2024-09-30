<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
require_once APPPATH . "libraries/phpqrcode/qrlib.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
class Lms extends MX_Controller
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
        ((count(array_intersect($userPermission, ['RCV', 'RESSAY'])) > 0) ? '' : redirect('authentication/logout'));
        if (checkRole(3)) {
            $member = $this->db->get_where('member', [
                'deleteAt' => NULL,
                'userCode' => $this->session->userdata('userCode'),
            ])->row_array();
            if($member == NULL){
                redirect('dashboard/index');
            }
            if ($member['verify'] == '0') {
                redirect('dashboard/index');
            }
            $data['breadcrumb'] = breadcrumb([
                [
                    "text" => "E-Diklat",
                    "url" => base_url('ediklat/lms')
                ],
                [
                    "text" => "LMS",
                ]
            ], 'LMS');
            $data['_view'] = $this->module . '/lms/member/index';
        } elseif (checkRole(2)) {
            $data['breadcrumb'] = breadcrumb([
                [
                    "text" => "E-Diklat",
                    "url" => base_url('ediklat/lms')
                ],
                [
                    "text" => "LMS",
                ]
            ], 'LMS');
            $data['_view'] = $this->module . '/lms/su/index';
        } else {
            $data['breadcrumb'] = breadcrumb([
                [
                    "text" => "E-Diklat",
                    "url" => base_url('ediklat/lms')
                ],
                [
                    "text" => "LMS",
                ]
            ], 'LMS');
            $data['_view'] = $this->module . '/lms/su/index';
        }
        $this->load->view('layouts/back/main', $data);
    }

    public function downloadSertifikat(string $data = '')
    {
        // $this->load->model('ediklat/kegiatan_model', 'kegiatan');
        // $this->load->model('ediklat/peserta_model', 'peserta');
        $this->load->model('ediklat/sertifikat_model', 'sertifikat');
        $d = base64_decode($data);
        $data = explode('-', $d);
        // $kegiatan = $this->kegiatan->get_by_id($data[1]);
        // var_dump($kegiatan);
        // die;
        // $peserta = $this->peserta->get_by_id($data[0]);
        $essay = $this->db->get_where('essay',[
            'deleteAt' => NULL,
            'essayCode' => $data[1]
        ])->row();
        if($essay == NULL){
            redirect('authentication/logout');
        }
        $member = $this->db->get_where('member',[
            'deleteAt' => NULL,
            'memberCode' => $data[0]
        ])->row();
        if($member == NULL){
            redirect('authentication/logout');
        }
        $essay_member = $this->db->get_where('essay_member',[
            'deleteAt' => NULL,
            'essayCode' => $data[1],
            'memberCode' => $data[0]
        ])->row();
        if($essay_member == NULL){
            redirect('authentication/logout');
        }
        $sertifikat = $this->sertifikat->get_by_id($essay->certificateCode);

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(path_by_os(FCPATH . '/assets/img/certificate/' . $sertifikat->file));

        $halaman = json_decode($sertifikat->position, TRUE);

        $this->session->set_userdata([
            'pdf' => [
                'path' => path_by_os(FCPATH . '/assets/img/certificate/' . $sertifikat->file),
                'totalHalaman' => $pageCount,
                'halaman' => $halaman
            ]
        ]);

        $dataPDF = $this->session->userdata('pdf');
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($dataPDF['path']);
        foreach ($dataPDF['halaman'] as $k => $v) {
            $template   = $pdf->importPage($k);
            $size       = $pdf->getTemplateSize($template);
            $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
            $pdf->useTemplate($template);

            if ($v['aktif'] == true) {

                $nomerLeft = $v['nomer']['left'];
                $nomerTop = $v['nomer']['top'];
                $nomerExample = $sertifikat->number;

                $namaX = $v['nama']['x'];
                $namaY = $v['nama']['y'];
                $namaWidth = $v['nama']['width'];
                $namaHeight = $v['nama']['height'];
                $namaExample = $member->name;

                $kegiatanX = $v['kegiatan']['x'];
                $kegiatanY = $v['kegiatan']['y'];
                $kegiatanWidth = $v['kegiatan']['width'];
                $kegiatanHeight = $v['kegiatan']['height'];
                $kegiatanExample = $essay->judul;

                $tanggalKegiatanLeft = $v['tanggalKegiatan']['left'];
                $tanggalKegiatanTop = $v['tanggalKegiatan']['top'];
                $tanggalKegiatanExample = tanggal_indo($essay->waktuMulai);
                $tanggal = date('Y-m-d');
                $day = date('D', strtotime($tanggal));
                $dayList = array(
                    'Sun' => 'Minggu',
                    'Mon' => 'Senin',
                    'Tue' => 'Selasa',
                    'Wed' => 'Rabu',
                    'Thu' => 'Kamis',
                    'Fri' => 'Jumat',
                    'Sat' => 'Sabtu'
                );
                $tanggalTandaTanganLeft = $v['tanggalTandaTangan']['left'];
                $tanggalTandaTanganTop = $v['tanggalTandaTangan']['top'];
                $tanggalTandaTanganExample = $dayList[$day] . ',' . tanggal_indo($tanggal);

                // Set font
                $pdf->SetFont('courier', 'B', 26);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->setXY($namaX, $namaY);
                $pdf->Cell($namaWidth, $namaHeight, $namaExample, 0, 1, 'C', false);

                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->setXY($kegiatanX, $kegiatanY);
                $pdf->MultiCell($kegiatanWidth, $kegiatanHeight, $kegiatanExample, 0, 'C', false);

                $pdf->SetFont("helvetica", "B", 14);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($nomerLeft, $nomerTop, $nomerExample);

                $pdf->SetFont("helvetica", "B", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($tanggalKegiatanLeft, $tanggalKegiatanTop, $tanggalKegiatanExample);

                $pdf->SetFont("helvetica", "B", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($tanggalTandaTanganLeft, $tanggalTandaTanganTop, $tanggalTandaTanganExample);
                $textQRCode = 'ES-'.$member->memberCode . '-' . $essay->essayCode . '-' . substr($essay->createAt, 0, 4);
                QRcode::png($textQRCode, path_by_os(FCPATH . '/assets/img/qrcode/' . $textQRCode . '.png'), 'L', 5, 2);
                $pdf->Image(path_by_os(FCPATH . '/assets/img/qrcode/' . $textQRCode . '.png'), $v['QRCode']['x'], $v['QRCode']['y'], 25);
                
                $pdf->SetFont("helvetica", "B", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($v['textQRCode']['left'], $v['textQRCode']['top'], $textQRCode);
            }
        }
        $pdf->Output('D', "sertifkat.pdf", true);
    }
}
