<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(FCPATH . '/vendor/setasign/fpdf/fpdf.php');
require_once(FCPATH . '/vendor/setasign/fpdi/src/autoload.php');
require_once APPPATH . "libraries/phpqrcode/qrlib.php";

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;




class Sertifikat extends MX_Controller
{
    private $module = 'ediklat';

    private $validation_for = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model($this->module . '/sertifikat_model', 'sertifikat');
        if (isLogin() == false) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You must login first!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
            die();
        }
    }

    public function data()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/sertifikat')
            ],
            [
                "text" => "Sertifikat",
            ]
        ], 'Data Sertifikat');
        $data['data'] = $this->load->view($this->module . '/sertifikat/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function list()
    {
        $userPermission = getPermissionFromUser();
        $list = $this->sertifikat->get_datatables();
        $data = array();
        foreach ($list as $result) {
            $row = array();
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $result->name . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $result->number . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($result->position == NULL ? 'Posisi Belum Diatur' : 'Posisi Sudah Diatur') . '</p>';
            //add html for action
            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('RCERTIFICATE', $userPermission)) ? '<span class="btn btn-sm btn-primary text-xs my-auto py-1 mx-1" role="button" title="Posisi" onclick="posisiData(' . $result->certificateCode . ')">Atur Posisi</span>' : '') . "
                " . ((in_array('UCERTIFICATE', $userPermission)) ? '<i class="ri-edit-2-line ri-lg text-warning  my-auto mx-1" role="button" title="Ubah" onclick="editData(' . $result->certificateCode . ')"></i>' : '') . "
                " . ((in_array('DCERTIFICATE', $userPermission)) ? '<i class="ri-delete-bin-line ri-lg text-danger my-auto mx-1" role="button" title="Hapus" onclick="deleteData(' . $result->certificateCode . ')"></i>' : '') . "
                </div>
                ";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->sertifikat->count_all(),
            "recordsFiltered" => $this->sertifikat->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }



    public function addHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {

            $data['status'] = TRUE;
            $params = [
                'title' => 'Tambah Data Sertifikat',
                'certificateCode' => '',
                'name' => '',
                'number' => '',
                'file' => '',
                'position' => '',
                'action' => 'add'
            ];
            $data['breadcrumb'] = breadcrumb([
                [
                    "text" => "E-Diklat",
                    "url" => base_url('ediklat/sertifikat')
                ],
                [
                    "text" => "Sertifikat",
                    "action" => "back()"
                ],
                [
                    "text" => "Tambah Sertifikat",
                ]
            ], 'Tambah Sertifikat');
            $data['data'] = $this->load->view($this->module . '/sertifikat/form', $params, TRUE);
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function editHTML(string $sertifikatCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($sertifikatCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Certificate code is required"
                );
            } else {
                $sertifikat = $this->sertifikat->get_by_id($sertifikatCode);
                if ($sertifikat == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Sertifikat tidak ditemukan!"
                    );
                } else {

                    $params = [
                        'title' => 'Ubah Data Sertifikat',
                        'certificateCode' => $sertifikat->certificateCode,
                        'name' => $sertifikat->name,
                        'number' => $sertifikat->number,
                        'file' => $sertifikat->file,
                        'position' => $sertifikat->position,
                        'action' => 'edit'
                    ];
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "E-Diklat",
                            "url" => base_url('ediklat/sertifikat')
                        ],
                        [
                            "text" => "Sertifikat",
                            "action" => "back()"
                        ],
                        [
                            "text" => "Ubah Sertifikat",
                        ]
                    ], 'Ubah Sertifikat');
                    $data['data'] = $this->load->view($this->module . '/sertifikat/form', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function add()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'add';
            $data = array();
            $data['status'] = TRUE;

            $this->_validate();

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'name' => form_error('name'),
                    'number' => form_error('number'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $insert = array(
                    'name' => $this->input->post('name'),
                    'number' => $this->input->post('number'),
                );

                if (!isset($_FILES['file']) || $_FILES['file']['name'] == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => 'File harus diisi'
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                $config['upload_path']          = FCPATH . '/assets/img/certificate/';
                $config['allowed_types']        = 'pdf';
                $config['file_name']            = $file_name;
                $config['overwrite']            = true;
                $config['max_size']             = 10240;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('file')) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => 'File gagal di upload'
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $uploaded_data = $this->upload->data();
                    $insert['file'] = $uploaded_data['file_name'];
                }

                $insert = $this->sertifikat->save($insert);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil menambah sertifikat";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal menambah sertifikat";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function update()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'update';
            $data = array();
            $data['status'] = TRUE;

            $this->_validate();

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'name' => form_error('name'),
                    'number' => form_error('number'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $result = $this->sertifikat->get_by_id($this->input->post('certificateCode'));
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Sertifikat tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $update = array(
                        'name' => $this->input->post('name'),
                        'number' => $this->input->post('number'),
                    );

                    if (isset($_FILES['file']) && $_FILES['file']['name'] != NULL) {

                        $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                        $config['upload_path']          = FCPATH . '/assets/img/certificate/';
                        $config['allowed_types']        = 'pdf';
                        $config['file_name']            = $file_name;
                        $config['overwrite']            = true;
                        $config['max_size']             = 10240;

                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('file')) {
                            $data = array(
                                'status'         => FALSE,
                                'message'         => 'File gagal di upload'
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        } else {
                            $uploaded_data = $this->upload->data();
                            $update['file'] = $uploaded_data['file_name'];
                        }
                    }
                    $up = $this->sertifikat->update(array('certificateCode' => $this->input->post('certificateCode')), $update);
                    if ($up) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil mengubah sertifikat";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal mengubah sertifikat";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function delete($id)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('DCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($id == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Sertifikat code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $sertifikat = $this->sertifikat->get_by_id($id);
                if ($sertifikat == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Sertifikat tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $del = $this->sertifikat->delete_by_id($id);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil menghapus sertifikat";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal menghapus sertifikat";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function posisiHTML(string $sertifikatCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($sertifikatCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Sertifikat code is required"
                );
            } else {
                $sertifikat = $this->sertifikat->get_by_id($sertifikatCode);
                if ($sertifikat == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Sertifikat tidak ditemukan!"
                    );
                } else {
                    $pdf = new Fpdi();
                    $pageCount = $pdf->setSourceFile(path_by_os(FCPATH . '/assets/img/certificate/' . $sertifikat->file));
                    if ($pageCount == 0) {
                        $data = array(
                            'status'         => FALSE,
                            'message'         => "File sertifikat kosong!"
                        );
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                    if ($sertifikat->position == NULL) {
                        $halaman = [];
                        for ($i = 1; $i <= $pageCount; $i++) {
                            $halaman[$i] = [
                                'aktif' => ($i == 1 ? true : false),
                                'nomer' => [
                                    'type' => 'text',
                                    'left' => 128,
                                    'top' => 66,
                                    'example' => '123/B.1/2022'
                                ],
                                'nama' => [
                                    'type' => 'cell',
                                    'x' => 130,
                                    'y' => 78,
                                    'width' => 20,
                                    'height' => 10,
                                    'example' => 'DIKI RAHMAD SANDI'
                                ],
                                'kegiatan' => [
                                    'type' => 'multiCell',
                                    'x' => 55,
                                    'y' => 110,
                                    'width' => 175,
                                    'height' => 10,
                                    'example' => 'PROFIL PELAJAR PANCASILA SEBAGAI BINTANG PENUNTUN PEMBELAJARAN'
                                ],
                                'tanggalKegiatan' => [
                                    'type' => 'text',
                                    'left' => 141,
                                    'top' => 131,
                                    'example' => '20 Juni 2022'
                                ],
                                'tanggalTandaTangan' => [
                                    'type' => 'text',
                                    'left' => 148,
                                    'top' => 155,
                                    'example' => 'Senin,01 Agustus 2022'
                                ],
                                "QRCode" => [
                                    "x" => 230,
                                    "y" => 170,
                                    "example" => "15-12-2022"
                                ],
                                "textQRCode" => [
                                    "type" => "text",
                                    "left" => 232,
                                    "top" => 200,
                                    "example" => "15-12-2022"
                                ]
                            ];
                        }
                    } else {
                        $halaman = json_decode($sertifikat->position, TRUE);
                    }
                    $this->session->set_userdata([
                        'pdf' => [
                            'path' => path_by_os(FCPATH . '/assets/img/certificate/' . $sertifikat->file),
                            'totalHalaman' => $pageCount,
                            'halaman' => $halaman
                        ]
                    ]);
                    $params = [
                        'title' => 'Posisi Data Sertifikat',
                        'certificateCode' => $sertifikat->certificateCode,
                        'name' => $sertifikat->name,
                        'number' => $sertifikat->number,
                        'file' => $sertifikat->file,
                        'position' => $halaman,
                    ];
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "E-Diklat",
                            "url" => base_url('ediklat/sertifikat')
                        ],
                        [
                            "text" => "Sertifikat",
                            "action" => "back()"
                        ],
                        [
                            "text" => "Posisi Data Sertifikat",
                        ]
                    ], 'Posisi Data Sertifikat');
                    $data['data'] = $this->load->view($this->module . '/sertifikat/posisi', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function getPDF()
    {
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
                $nomerExample = $v['nomer']['example'];

                $namaX = $v['nama']['x'];
                $namaY = $v['nama']['y'];
                $namaWidth = $v['nama']['width'];
                $namaHeight = $v['nama']['height'];
                $namaExample = $v['nama']['example'];

                $kegiatanX = $v['kegiatan']['x'];
                $kegiatanY = $v['kegiatan']['y'];
                $kegiatanWidth = $v['kegiatan']['width'];
                $kegiatanHeight = $v['kegiatan']['height'];
                $kegiatanExample = $v['kegiatan']['example'];

                $tanggalKegiatanLeft = $v['tanggalKegiatan']['left'];
                $tanggalKegiatanTop = $v['tanggalKegiatan']['top'];
                $tanggalKegiatanExample = $v['tanggalKegiatan']['example'];

                $tanggalTandaTanganLeft = $v['tanggalTandaTangan']['left'];
                $tanggalTandaTanganTop = $v['tanggalTandaTangan']['top'];
                $tanggalTandaTanganExample = $v['tanggalTandaTangan']['example'];

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

                $textQRCode = $v['QRCode']['example'];
                QRcode::png($textQRCode, path_by_os(FCPATH . '/assets/img/qrcode/' . $textQRCode . '.png'), 'L', 5, 2);
                $pdf->Image(path_by_os(FCPATH . '/assets/img/qrcode/' . $textQRCode . '.png'), $v['QRCode']['x'], $v['QRCode']['y'], 25);

                $pdf->SetFont("helvetica", "B", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($v['textQRCode']['left'], $v['textQRCode']['top'], $textQRCode);
            }
        }
        $return = $pdf->Output(path_by_os(FCPATH . '/assets/img/certificate/temp.pdf'), 'F');
        $data = [
            'status' => TRUE,
            'url' => base_url('/assets/img/certificate/temp.pdf')
        ];
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function _validate()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('name', 'nama', 'trim|required');
        $this->form_validation->set_rules('number', 'no sertifikat', 'trim|required');
    }

    public function updatePDF(string $sertifikatCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($sertifikatCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Sertifikat code is required"
                );
            } else {
                $sertifikat = $this->sertifikat->get_by_id($sertifikatCode);
                if ($sertifikat == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Sertifikat tidak ditemukan!"
                    );
                } else {
                    $dataPDF = $this->session->userdata('pdf');
                    $halaman = [];
                    for ($i = 1; $i <= count($this->input->post('halaman[]')); $i++) {
                        $halaman[$i] = [
                            'aktif' => $this->input->post('status[' . $i . ']'),
                            'nomer' => [
                                'type' => 'text',
                                'left' => $this->input->post('nomerLeft[' . $i . ']'),
                                'top' => $this->input->post('nomerTop[' . $i . ']'),
                                'example' => '123/B.1/2022'
                            ],
                            'nama' => [
                                'type' => 'cell',
                                'x' => $this->input->post('namaX[' . $i . ']'),
                                'y' => $this->input->post('namaY[' . $i . ']'),
                                'width' => $this->input->post('namaWidth[' . $i . ']'),
                                'height' => $this->input->post('namaHeight[' . $i . ']'),
                                'example' => 'DIKI RAHMAD SANDI'
                            ],
                            'kegiatan' => [
                                'type' => 'multiCell',
                                'x' => $this->input->post('kegiatanX[' . $i . ']'),
                                'y' => $this->input->post('kegiatanY[' . $i . ']'),
                                'width' => $this->input->post('kegiatanWidth[' . $i . ']'),
                                'height' => $this->input->post('kegiatanHeight[' . $i . ']'),
                                'example' => 'PROFIL PELAJAR PANCASILA SEBAGAI BINTANG PENUNTUN PEMBELAJARAN'
                            ],
                            'tanggalKegiatan' => [
                                'type' => 'text',
                                'left' => $this->input->post('tanggalKegiatanLeft[' . $i . ']'),
                                'top' => $this->input->post('tanggalKegiatanTop[' . $i . ']'),
                                'example' => '20 Juni 2022'
                            ],
                            'tanggalTandaTangan' => [
                                'type' => 'text',
                                'left' => $this->input->post('tanggalTandaTanganLeft[' . $i . ']'),
                                'top' => $this->input->post('tanggalTandaTanganTop[' . $i . ']'),
                                'example' => 'Senin,01 Agustus 2022'
                            ],
                            "QRCode" => [
                                "x" => $this->input->post('QRCodeX[' . $i . ']'),
                                "y" => $this->input->post('QRCodeY[' . $i . ']'),
                                "example" => "15-12-2022"
                            ],
                            "textQRCode" => [
                                "type" => "text",
                                "left" => $this->input->post('textQRCodeLeft[' . $i . ']'),
                                "top" => $this->input->post('textQRCodeTop[' . $i . ']'),
                                "example" => "15-12-2022"
                            ]
                        ];
                    }
                    $params = [
                        'position' => ($halaman != null) ? json_encode($halaman, TRUE) : NULL
                    ];
                    $up = $this->sertifikat->update(['certificateCode' => $sertifikatCode], $params);

                    $dataPDF['halaman'] = $halaman;
                    $this->session->set_userdata(['pdf' => $dataPDF]);
                    if ($up) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil mengubah sertifikat";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal mengubah sertifikat";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function data_member()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCERTIFICATE', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/sertifikat')
            ],
            [
                "text" => "Sertifikat",
            ]
        ], 'Data Sertifikat');
        $data['data'] = $this->load->view($this->module . '/sertifikat/member/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function list_member()
    {
        $this->load->model($this->module . '/sertifikat_member_model', 'sertifikat_member');
        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();
        $list = $this->sertifikat_member->get_datatables($member['memberCode']);
        $data = array();
        foreach ($list as $result) {
            $row = array();
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $result->activityName . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $result->participantCode . '-' . $result->activityCodeActivity . '-' . substr($result->participantCreateAt, 0, 4) . '</p>';
            //add html for action
            $row[] = "<a href='" . base_url('kegiatan/downloadSertifikat/' . base64_encode($result->participantCode . '-' . $result->activityCodeActivity)) . "' class='text-primary'><i class='ri-download-line' role='button' title='Download'></i></a>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->sertifikat_member->count_all($member['memberCode']),
            "recordsFiltered" => $this->sertifikat_member->count_filtered($member['memberCode']),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function member_listEssay()
    {
        $this->load->model($this->module . '/member_essay_model', 'member_essay');

        $userPermission = getPermissionFromUser();
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $list = $this->member_essay->get_datatables($member['memberCode']);
        $data = array();
        foreach ($list as $v) {
            $row = array();
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->judul . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . status($v->status) . '</p>';
            //add html for action
            if ($v->status == '2') {
                $download = "<a href='" . base_url('ediklat/lms/downloadSertifikat/' . base64_encode($member['memberCode'] . '-' . $v->essayCode)) . "' class='text-primary'><i class='ri-download-line' role='button' title='Download'></i></a>";
            } else {
                $download = "";
            }
            $row[] = $download;
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->member_essay->count_all($member['memberCode']),
            "recordsFiltered" => $this->member_essay->count_filtered($member['memberCode']),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
