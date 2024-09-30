<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(FCPATH . '/vendor/setasign/fpdf/fpdf.php');
require_once(FCPATH . '/vendor/setasign/fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

class Lms extends MX_Controller
{
    private $module = 'ediklat';

    private $validation_for = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model($this->module . '/peserta_essay_model', 'peserta_essay');
        $this->load->model($this->module . '/member_essay_model', 'member_essay');
        $this->load->model($this->module . '/soal_model', 'soal');
        $this->load->model($this->module . '/member_model', 'member');
        $this->load->model($this->module . '/essay_model', 'essay');
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

    public function member_cvHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $dataDiri = $this->db->get_where('data_diri', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->row_array();

        $params = [
            'userPermission' => $userPermission,
            'active' => ($this->input->post('active') == '' ? 'collapseIU' : $this->input->post('active')),
            'user' => $user,
            'member' => $member,
            'dataDiri' => $dataDiri
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    //informasi umum
    public function member_dataIUHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $dataDiri = $this->db->get_where('data_diri', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->row_array();

        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataDiri' => $dataDiri
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/IU/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_formIUHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $dataDiri = $this->db->get_where('data_diri', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->row_array();
        $dataState = $this->db->get_where('state', ['deleteAt' => NULL])->result();
        $state = [];
        foreach ($dataState as $k => $v) {
            $state[$v->stateCode] = $v->state;
        }
        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataDiri' => $dataDiri,
            'state' => $state
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/IU/form', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_saveIU()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validateIU();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'name' => form_error('name'),
                'npsn' => form_error('npsn'),
                'nik' => form_error('nik'),
                'address' => form_error('address'),
                'npwp' => form_error('npwp'),
                'agency' => form_error('agency'),
                'rank' => form_error('rank'),
                'birthplace' => form_error('birthplace'),
                'birthdate' => form_error('birthdate'),
                'education' => form_error('education'),
                'education_service' => form_error('education_service'),
                'stateCode' => form_error('stateCode'),
                'kelasDiajar' => form_error('kelasDiajar'),
                'mapel' => form_error('mapel'),
                'lamaMengajar' => form_error('lamaMengajar'),
                'instansiPT' => form_error('instansiPT'),
                'bidangPT' => form_error('bidangPT'),
                'jurusanPT' => form_error('jurusanPT'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $updateMember = array(
                'name' => $this->input->post('name'),
                'nik' => $this->input->post('nik'),
                'npsn' => $this->input->post('npsn'),
                'address' => $this->input->post('address'),
                'npwp' => $this->input->post('npwp'),
                'agency' => $this->input->post('agency'),
                'rank' => $this->input->post('rank'),
                'education' => $this->input->post('education'),
                'education_service' => $this->input->post('education_service'),
                'birthplace' => $this->input->post('birthplace'),
                'birthdate' => $this->input->post('birthdate'),
                'stateCode' => $this->input->post('stateCode'),
            );

            $updateDataDiri = [
                'kelasDiajar' => $this->input->post('kelasDiajar'),
                'mapel' => $this->input->post('mapel'),
                'lamaMengajar' => $this->input->post('lamaMengajar'),
                'instansiPT' => $this->input->post('instansiPT'),
                'bidangPT' => $this->input->post('bidangPT'),
                'jurusanPT' => $this->input->post('jurusanPT'),
            ];

            $updateMemberAction = $this->db->where([
                'memberCode' => $member['memberCode']
            ])->update('member', $updateMember);

            $checkDataDiri = $this->db->get_where('data_diri', [
                'deleteAt' => NULL,
                'memberCode' => $member['memberCode']
            ])->row_array();
            if ($checkDataDiri == NULL) {
                $updateDataDiri['memberCode'] = $member['memberCode'];
                $updateDataDiriAction = $this->db->insert('data_diri', $updateDataDiri);
            } else {
                $updateDataDiriAction = $this->db->where([
                    'memberCode' => $member['memberCode']
                ])->update('data_diri', $updateDataDiri);
            }

            if ($updateMemberAction && $updateDataDiriAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah data informasi umum";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah data informasi umum";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    private function _validateIU()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
        $this->form_validation->set_rules('npsn', 'NUPTK', 'trim|required');
        $this->form_validation->set_rules('name', 'nama', 'trim|required');
        $this->form_validation->set_rules('address', 'alamat', 'trim|required');
        $this->form_validation->set_rules('npwp', 'NPWP', 'trim|required');
        $this->form_validation->set_rules('agency', 'instansi', 'trim|required');
        $this->form_validation->set_rules('rank', 'pangkat/golongan', 'trim|required');
        $this->form_validation->set_rules('birthplace', 'tempat lahir', 'trim|required');
        $this->form_validation->set_rules('birthdate', 'tanggal lahir', 'trim|required');
        $this->form_validation->set_rules('education', 'pendidikan terakhir', 'trim|required');
        $this->form_validation->set_rules('education_service', 'layanan pendidikan', 'trim|required');
        $this->form_validation->set_rules('stateCode', 'kabupaten/kota', 'trim|required');
        $this->form_validation->set_rules('kelasDiajar', 'kelas yang diajar', 'trim|required');
        $this->form_validation->set_rules('mapel', 'mata pelajaran', 'trim|required');
        $this->form_validation->set_rules('lamaMengajar', 'lama mengajar', 'trim|required');
        $this->form_validation->set_rules('instansiPT', 'instansi pendidikan terakhir', 'trim|required');
        $this->form_validation->set_rules('bidangPT', 'bidang pendidikan terakhir', 'trim|required');
        $this->form_validation->set_rules('jurusanPT', 'jurusan pendidikan terakhir', 'trim|required');
    }


    //pengalaman mengikuti pelatihan
    public function member_dataPMPHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $dataPMP = $this->db->get_where('data_pengalaman_pelatihan', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->result_array();

        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataPMP' => $dataPMP
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/PMP/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_formPMPHTML($dppCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if ($this->input->post('actionPMP') == 'edit') {
            $dpp = $this->db->get_where('data_pengalaman_pelatihan', [
                'deleteAt' => NULL,
                'dppCode' => $dppCode
            ])->row_array();
            if ($dpp == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Data tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        if ($this->input->post('actionPMP') == 'add') {
            $dataPMP = [
                'dppCode' => '',
                'namaPelatihan' => '',
                'penyelenggara' => '',
                'mulaiTahun' => '',
                'sampaiTahun' => '',
            ];
        } else {
            $check = $this->db->get_where('data_pengalaman_pelatihan', [
                'deleteAt' => NULL,
                'dppCode' => $dppCode
            ])->row_array();
            if ($check == NULL) {
                $data = [
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ];
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $dataPMP = $check;
        }
        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataPMP' => $dataPMP
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/PMP/form', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_addPMP()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validatePMP();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'namaPelatihan' => form_error('namaPelatihan'),
                'penyelenggara' => form_error('penyelenggara'),
                'mulaiTahun' => form_error('mulaiTahun'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $insertDataPMP = array(
                'namaPelatihan' => $this->input->post('namaPelatihan'),
                'penyelenggara' => $this->input->post('penyelenggara'),
                'mulaiTahun' => $this->input->post('mulaiTahun'),
                'sampaiTahun' => $this->input->post('sampaiTahun'),
                'memberCode' => $member['memberCode']
            );

            $insertDataPMPAction = $this->db->insert('data_pengalaman_pelatihan', $insertDataPMP);

            if ($insertDataPMPAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil menambahkan data pengalaman pelatihan";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal menambahkan data pengalaman pelatihan";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function member_editPMP($dppCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dpp = $this->db->get_where('data_pengalaman_pelatihan', [
            'deleteAt' => NULL,
            'dppCode' => $dppCode
        ])->row_array();
        if ($dpp == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validatePMP();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'namaPelatihan' => form_error('namaPelatihan'),
                'penyelenggara' => form_error('penyelenggara'),
                'mulaiTahun' => form_error('mulaiTahun'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $updateDataPMP = array(
                'namaPelatihan' => $this->input->post('namaPelatihan'),
                'penyelenggara' => $this->input->post('penyelenggara'),
                'mulaiTahun' => $this->input->post('mulaiTahun'),
                'sampaiTahun' => $this->input->post('sampaiTahun')
            );


            $updateDataPMPAction = $this->db->where([
                'dppCode' => $dppCode
            ])->update('data_pengalaman_pelatihan', $updateDataPMP);


            if ($updateDataPMPAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah data pengalaman pelatihan";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah data pengalaman pelatihan";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function member_deletePMP($dppCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dpp = $this->db->get_where('data_pengalaman_pelatihan', [
            'deleteAt' => NULL,
            'dppCode' => $dppCode
        ])->row_array();
        if ($dpp == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $updateDataPMPAction = $this->db->where([
            'dppCode' => $dppCode
        ])->update('data_pengalaman_pelatihan', [
            'deleteAt' => date('Y-m-d H:i:s')
        ]);

        if ($updateDataPMPAction) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menghapus data pengalaman pelatihan";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menghapus data pengalaman pelatihan";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function _validatePMP()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('namaPelatihan', 'Nama Pelatihan', 'trim|required');
        $this->form_validation->set_rules('penyelenggara', 'Penyelenggara', 'trim|required');
        $this->form_validation->set_rules('mulaiTahun', 'Tahun Mulai Pelatihan', 'trim|required');
    }


    //pengalaman berorganisasi pendidikan
    public function member_dataPBPHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $dataPBP = $this->db->get_where('data_pengalaman_organisasi', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->result_array();

        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataPBP' => $dataPBP
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/PBP/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_formPBPHTML($dpoCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if ($this->input->post('actionPBP') == 'edit') {
            $dpp = $this->db->get_where('data_pengalaman_organisasi', [
                'deleteAt' => NULL,
                'dpoCode' => $dpoCode
            ])->row_array();
            if ($dpp == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Data tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        if ($this->input->post('actionPBP') == 'add') {
            $dataPBP = [
                'dpoCode' => '',
                'namaOrganisasi' => '',
                'deskripsiOrganisasi' => '',
                'kedudukanOrganisasi' => '',
                'posisi' => '',
                'deskripsi' => '',
                'mulaiTahun' => '',
                'sampaiTahun' => '',
            ];
        } else {
            $check = $this->db->get_where('data_pengalaman_organisasi', [
                'deleteAt' => NULL,
                'dpoCode' => $dpoCode
            ])->row_array();
            if ($check == NULL) {
                $data = [
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ];
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $dataPBP = $check;
        }
        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataPBP' => $dataPBP
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/PBP/form', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_addPBP()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validatePBP();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'namaOrganisasi' => form_error('namaOrganisasi'),
                'deskripsiOrganisasi' => form_error('deskripsiOrganisasi'),
                'kedudukanOrganisasi' => form_error('kedudukanOrganisasi'),
                'posisi' => form_error('posisi'),
                'deskripsi' => form_error('deskripsi'),
                'mulaiTahun' => form_error('mulaiTahun'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $insertDataPBP = array(
                'namaOrganisasi' => $this->input->post('namaOrganisasi'),
                'deskripsiOrganisasi' => $this->input->post('deskripsiOrganisasi'),
                'kedudukanOrganisasi' => $this->input->post('kedudukanOrganisasi'),
                'posisi' => $this->input->post('posisi'),
                'deskripsi' => $this->input->post('deskripsi'),
                'mulaiTahun' => $this->input->post('mulaiTahun'),
                'sampaiTahun' => $this->input->post('sampaiTahun'),
                'memberCode' => $member['memberCode']
            );

            $insertDataPBPAction = $this->db->insert('data_pengalaman_organisasi', $insertDataPBP);

            if ($insertDataPBPAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil menambahkan data pengalaman berorganisasi";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal menambahkan data pengalaman berorganisasi";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function member_editPBP($dpoCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dpp = $this->db->get_where('data_pengalaman_organisasi', [
            'deleteAt' => NULL,
            'dpoCode' => $dpoCode
        ])->row_array();
        if ($dpp == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validatePBP();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'namaOrganisasi' => form_error('namaOrganisasi'),
                'deskripsiOrganisasi' => form_error('deskripsiOrganisasi'),
                'kedudukanOrganisasi' => form_error('kedudukanOrganisasi'),
                'posisi' => form_error('posisi'),
                'deskripsi' => form_error('deskripsi'),
                'mulaiTahun' => form_error('mulaiTahun'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $updateDataPBP = array(
                'namaOrganisasi' => $this->input->post('namaOrganisasi'),
                'deskripsiOrganisasi' => $this->input->post('deskripsiOrganisasi'),
                'kedudukanOrganisasi' => $this->input->post('kedudukanOrganisasi'),
                'posisi' => $this->input->post('posisi'),
                'deskripsi' => $this->input->post('deskripsi'),
                'mulaiTahun' => $this->input->post('mulaiTahun'),
                'sampaiTahun' => $this->input->post('sampaiTahun')
            );


            $updateDataPBPAction = $this->db->where([
                'dpoCode' => $dpoCode
            ])->update('data_pengalaman_organisasi', $updateDataPBP);


            if ($updateDataPBPAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah data pengalaman berorganisasi";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah data pengalaman berorganisasi";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function member_deletePBP($dpoCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dpp = $this->db->get_where('data_pengalaman_organisasi', [
            'deleteAt' => NULL,
            'dpoCode' => $dpoCode
        ])->row_array();
        if ($dpp == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $updateDataPBPAction = $this->db->where([
            'dpoCode' => $dpoCode
        ])->update('data_pengalaman_organisasi', [
            'deleteAt' => date('Y-m-d H:i:s')
        ]);

        if ($updateDataPBPAction) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menghapus data pengalaman berorganisasi";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menghapus data pengalaman berorganisasi";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function _validatePBP()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('namaOrganisasi', 'Nama Organisasi', 'trim|required');
        $this->form_validation->set_rules('deskripsiOrganisasi', 'Deskripsi Organisasi', 'trim|required');
        $this->form_validation->set_rules('kedudukanOrganisasi', 'Kedudukan Organisasi', 'trim|required');
        $this->form_validation->set_rules('posisi', 'posisi', 'trim|required');
        $this->form_validation->set_rules('mulaiTahun', 'Tahun Mulai Organisasi', 'trim|required');
        $this->form_validation->set_rules('deskripsi', 'Peran dan dampak', 'trim|required');
    }



    //pengalaman menjadi sukarelawan
    public function member_dataPMSHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $dataPMS = $this->db->get_where('data_pengalaman_sukarelawan', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->result_array();

        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataPMS' => $dataPMS
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/PMS/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_formPMSHTML($dpsCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if ($this->input->post('actionPMS') == 'edit') {
            $dpp = $this->db->get_where('data_pengalaman_sukarelawan', [
                'deleteAt' => NULL,
                'dpsCode' => $dpsCode
            ])->row_array();
            if ($dpp == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Data tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        if ($this->input->post('actionPMS') == 'add') {
            $dataPMS = [
                'dpsCode' => '',
                'namaProgram' => '',
                'penyelenggaraProgram' => '',
                'ruangLingkupProgram' => '',
                'deskripsi' => '',
            ];
        } else {
            $check = $this->db->get_where('data_pengalaman_sukarelawan', [
                'deleteAt' => NULL,
                'dpsCode' => $dpsCode
            ])->row_array();
            if ($check == NULL) {
                $data = [
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ];
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $dataPMS = $check;
        }
        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataPMS' => $dataPMS
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/PMS/form', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_addPMS()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validatePMS();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'namaProgram' => form_error('namaProgram'),
                'penyelenggaraProgram' => form_error('penyelenggaraProgram'),
                'ruangLingkupProgram' => form_error('ruangLingkupProgram'),
                'deskripsi' => form_error('deskripsi'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $insertDataPMS = array(
                'namaProgram' => $this->input->post('namaProgram'),
                'penyelenggaraProgram' => $this->input->post('penyelenggaraProgram'),
                'ruangLingkupProgram' => $this->input->post('ruangLingkupProgram'),
                'deskripsi' => $this->input->post('deskripsi'),
                'memberCode' => $member['memberCode']
            );

            $insertDataPMSAction = $this->db->insert('data_pengalaman_sukarelawan', $insertDataPMS);

            if ($insertDataPMSAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil menambahkan data pengalaman menjadi sukarelawan";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal menambahkan data pengalaman menjadi sukarelawan";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function member_editPMS($dpsCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dpp = $this->db->get_where('data_pengalaman_sukarelawan', [
            'deleteAt' => NULL,
            'dpsCode' => $dpsCode
        ])->row_array();
        if ($dpp == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validatePMS();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'namaProgram' => form_error('namaProgram'),
                'penyelenggaraProgram' => form_error('penyelenggaraProgram'),
                'ruangLingkupProgram' => form_error('ruangLingkupProgram'),
                'deskripsi' => form_error('deskripsi'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $updateDataPMS = array(
                'namaProgram' => $this->input->post('namaProgram'),
                'penyelenggaraProgram' => $this->input->post('penyelenggaraProgram'),
                'ruangLingkupProgram' => $this->input->post('ruangLingkupProgram'),
                'deskripsi' => $this->input->post('deskripsi'),
            );


            $updateDataPMSAction = $this->db->where([
                'dpsCode' => $dpsCode
            ])->update('data_pengalaman_sukarelawan', $updateDataPMS);


            if ($updateDataPMSAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah data pengalaman menjadi sukarelawan";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah data pengalaman menjadi sukarelawan";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function member_deletePMS($dpsCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dpp = $this->db->get_where('data_pengalaman_sukarelawan', [
            'deleteAt' => NULL,
            'dpsCode' => $dpsCode
        ])->row_array();
        if ($dpp == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $updateDataPMSAction = $this->db->where([
            'dpsCode' => $dpsCode
        ])->update('data_pengalaman_sukarelawan', [
            'deleteAt' => date('Y-m-d H:i:s')
        ]);

        if ($updateDataPMSAction) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menghapus data pengalaman menjadi sukarelawan";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menghapus data pengalaman menjadi sukarelawan";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function _validatePMS()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('namaProgram', 'Nama Program', 'trim|required');
        $this->form_validation->set_rules('penyelenggaraProgram', 'Penyelenggara Program', 'trim|required');
        $this->form_validation->set_rules('ruangLingkupProgram', 'Ruang Lingkup Program', 'trim|required');
        $this->form_validation->set_rules('deskripsi', 'Peran dan dampak', 'trim|required');
    }


    //pengalaman melatih
    public function member_dataPMOHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $dataPMO = $this->db->get_where('data_pengalaman_melatih', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->result_array();

        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataPMO' => $dataPMO
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/PMO/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_formPMOHTML($dpmCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if ($this->input->post('actionPMO') == 'edit') {
            $dpp = $this->db->get_where('data_pengalaman_melatih', [
                'deleteAt' => NULL,
                'dpmCode' => $dpmCode
            ])->row_array();
            if ($dpp == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Data tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        if ($this->input->post('actionPMO') == 'add') {
            $dataPMO = [
                'dpmCode' => '',
                'namaAktivitas' => '',
                'sasaranAktivitas' => '',
                'deskripsi' => '',
            ];
        } else {
            $check = $this->db->get_where('data_pengalaman_melatih', [
                'deleteAt' => NULL,
                'dpmCode' => $dpmCode
            ])->row_array();
            if ($check == NULL) {
                $data = [
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ];
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $dataPMO = $check;
        }
        $params = [
            'userPermission' => $userPermission,
            'user' => $user,
            'member' => $member,
            'dataPMO' => $dataPMO
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/cv/PMO/form', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_addPMO()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validatePMO();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'namaAktivitas' => form_error('namaAktivitas'),
                'sasaranAktivitas' => form_error('sasaranAktivitas'),
                'deskripsi' => form_error('deskripsi'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $insertDataPMO = array(
                'namaAktivitas' => $this->input->post('namaAktivitas'),
                'sasaranAktivitas' => $this->input->post('sasaranAktivitas'),
                'deskripsi' => $this->input->post('deskripsi'),
                'memberCode' => $member['memberCode']
            );

            $insertDataPMOAction = $this->db->insert('data_pengalaman_melatih', $insertDataPMO);

            if ($insertDataPMOAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil menambahkan data pengalaman melatih";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal menambahkan data pengalaman melatih";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function member_editPMO($dpmCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dpp = $this->db->get_where('data_pengalaman_melatih', [
            'deleteAt' => NULL,
            'dpmCode' => $dpmCode
        ])->row_array();
        if ($dpp == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validatePMO();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'namaAktivitas' => form_error('namaAktivitas'),
                'sasaranAktivitas' => form_error('sasaranAktivitas'),
                'deskripsi' => form_error('deskripsi'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $updateDataPMO = array(
                'namaAktivitas' => $this->input->post('namaAktivitas'),
                'sasaranAktivitas' => $this->input->post('sasaranAktivitas'),
                'deskripsi' => $this->input->post('deskripsi'),
            );


            $updateDataPMOAction = $this->db->where([
                'dpmCode' => $dpmCode
            ])->update('data_pengalaman_melatih', $updateDataPMO);


            if ($updateDataPMOAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah data pengalaman melatih";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah data pengalaman melatih";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function member_deletePMO($dpmCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dpp = $this->db->get_where('data_pengalaman_melatih', [
            'deleteAt' => NULL,
            'dpmCode' => $dpmCode
        ])->row_array();
        if ($dpp == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $updateDataPMOAction = $this->db->where([
            'dpmCode' => $dpmCode
        ])->update('data_pengalaman_melatih', [
            'deleteAt' => date('Y-m-d H:i:s')
        ]);

        if ($updateDataPMOAction) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menghapus data pengalaman melatih";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menghapus data pengalaman melatih";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function _validatePMO()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('namaAktivitas', 'Nama Aktivitas', 'trim|required');
        $this->form_validation->set_rules('sasaranAktivitas', 'Sasaran Aktivitas', 'trim|required');
        $this->form_validation->set_rules('deskripsi', 'Peran dan dampak', 'trim|required');
    }


    // SU
    public function su_memberHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/su/cv/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function su_listHTML()
    {
        $userPermission = getPermissionFromUser();
        $list = $this->member->get_datatables();
        $data = array();
        foreach ($list as $v) {
            $row = array();

            if (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $v->picture))) {
                $urlImage = base_url('assets/img/participant/' . $v->picture);
            } else {
                $urlImage = base_url('assets/img/participant/default.png');
            }

            $row[] = '
                <div class="d-flex px-2 py-1 gap-2">
                    <div>
                        <img src="' . $urlImage . '" class="avatar avatar-sm me-3" alt="user2" role="button" onclick="viewImageMemberDetail(' . $v->memberCode . ')">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <p class="text-xs text-bold d-flex py-auto my-auto">' . $v->name . '</p>
                        <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                    </div>
                </div>';

            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('RCV', $userPermission)) ? '<span class="btn btn-sm btn-primary text-xs my-auto py-1 mx-1" role="button" title="Detail" onclick="detailData(' . $v->memberCode . ')">Detail</span>' : '') . "
                </div>
                ";
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->member->count_all(),
            "recordsFiltered" => $this->member->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function su_detailMemberHTML(string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'memberCode' => $memberCode,
        ])->row_array();
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $dataDiri = $this->db->get_where('data_diri', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->row_array();

        $params = [
            'userPermission' => $userPermission,
            'active' => ($this->input->post('active') == '' ? 'collapseIU' : $this->input->post('active')),
            'member' => $member,
            'dataDiri' => $dataDiri
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/su/cv/detail', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    //informasi umum
    public function su_dataIUHTML(string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'memberCode' => $memberCode,
        ])->row_array();
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dataDiri = $this->db->get_where('data_diri', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->row_array();

        $params = [
            'userPermission' => $userPermission,
            'member' => $member,
            'dataDiri' => $dataDiri
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/cv/IU/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    //pengalaman mengikuti pelatihan
    public function su_dataPMPHTML(string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'memberCode' => $memberCode,
        ])->row_array();
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $dataPMP = $this->db->get_where('data_pengalaman_pelatihan', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->result_array();

        $params = [
            'userPermission' => $userPermission,
            'member' => $member,
            'dataPMP' => $dataPMP
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/cv/PMP/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    //pengalaman berorganisasi pendidikan
    public function su_dataPBPHTML(string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'memberCode' => $memberCode,
        ])->row_array();
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $dataPBP = $this->db->get_where('data_pengalaman_organisasi', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->result_array();

        $params = [
            'userPermission' => $userPermission,
            'member' => $member,
            'dataPBP' => $dataPBP
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/cv/PBP/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    //pengalaman menjadi sukarelawan
    public function su_dataPMSHTML(string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'memberCode' => $memberCode,
        ])->row_array();
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dataPMS = $this->db->get_where('data_pengalaman_sukarelawan', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->result_array();

        $params = [
            'userPermission' => $userPermission,
            'member' => $member,
            'dataPMS' => $dataPMS
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/cv/PMS/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    //pengalaman melatih
    public function su_dataPMOHTML(string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'memberCode' => $memberCode,
        ])->row_array();
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $dataPMO = $this->db->get_where('data_pengalaman_melatih', [
            'deleteAt' => NULL,
            'memberCode' => $member['memberCode'],
        ])->result_array();

        $params = [
            'userPermission' => $userPermission,
            'member' => $member,
            'dataPMO' => $dataPMO
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/cv/PMO/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    //ESSAY MEMBER
    public function member_essayHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/member/essay/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_listEssayHTML()
    {

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
            $row[] = "
                    <div class='d-flex justify-content-center'>
                    " . ((in_array('RESSAY', $userPermission)) ? '<i class="ri-information-line ri-lg text-primary m-1" role="button" title="Ubah" onclick="detailEssay(' . $v->essayCode . ')"></i>' : '') . "
                    </div>
                    ";

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

    public function member_essayDetailHTML(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
            'essay' => $essay
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/member/essay/detail', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_soal(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
            'essayCode' => $essayCode,
            'essay' => $essay
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/essay/soal/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_soalListEssay(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $list = $this->soal->get_datatables($essayCode);
        $data = array();
        foreach ($list as $v) {
            $row = array();

            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . word_limiter($v->soal, 30) . '</p>';

            //add html for action
            $row[] = '
                        <div class="d-flex justify-content-center">
                            <i class="ri-information-line ri-lg text-primary m-1" role="button" title="Detail" onclick="detailDataSoal(' . $v->esCode . ')"></i>
                        </div>
                        ';

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->soal->count_all($essayCode),
            "recordsFiltered" => $this->soal->count_filtered($essayCode),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function member_detailSoalHTML(string $esCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay_soal = $this->db->get_where('essay_soal', [
            'deleteAt' => NULL,
            'esCode' => $esCode
        ])->row_array();
        if ($essay_soal == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essay_soal['essayCode']
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();
        $check = $this->db->get_where('essay_jawaban_member', [
            'deleteAt' => NULL,
            'esCode' => $esCode,
            'memberCode' => $member['memberCode']
        ])->row_array();
        $params = [
            'title' => 'Detail Data Soal',
            'essay' => $essay,
            'esCode' => $esCode,
            'soal' => $essay_soal['soal'],
            'file' => $essay_soal['file'],
            'jawaban' => (isset($check['jawaban']) ? $check['jawaban'] : ''),
            'file_jawaban' => (isset($check['file']) ? $check['file'] : NULL)
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/member/essay/soal/detail', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function member_saveJawaban()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay_soal = $this->db->get_where('essay_soal', [
            'deleteAt' => NULL,
            'esCode' => $this->input->post('esCode')
        ])->row_array();
        if ($essay_soal == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();
        $data = array();

        $this->form_validation->set_rules('jawaban', 'jawaban', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'jawaban' => form_error('jawaban'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $check = $this->db->get_where('essay_jawaban_member', [
                'deleteAt' => NULL,
                'esCode' => $essay_soal['esCode'],
                'memberCode' => $member['memberCode']
            ])->row_array();
            if ($check == NULL) {
                $insert = array(
                    'esCode' => $this->input->post('esCode'),
                    'jawaban' => $this->input->post('jawaban'),
                    'memberCode' => $member['memberCode']
                );
                if (isset($_FILES['file']) && $_FILES['file']['name'] != NULL) {
                    $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                    $config['upload_path']          = FCPATH . '/assets/img/jawaban/';
                    $config['allowed_types']        = 'jpg|jpeg|png|pdf';
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
                }
                $insert = $this->db->insert('essay_jawaban_member', $insert);
            } else {
                $insert = array(
                    'esCode' => $this->input->post('esCode'),
                    'jawaban' => $this->input->post('jawaban'),
                    'memberCode' => $member['memberCode']
                );
                if (isset($_FILES['file']) && $_FILES['file']['name'] != NULL) {
                    $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                    $config['upload_path']          = FCPATH . '/assets/img/jawaban/';
                    $config['allowed_types']        = 'jpg|jpeg|png|pdf';
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
                }
                $insert = $this->db->where([
                    'ejmCode' => $check['ejmCode']
                ])->update('essay_jawaban_member', $insert);
            }
            if ($insert) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil menjawab soal";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal menjawab soal";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    //ESSAY SU
    public function su_essayHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function su_listEssayHTML()
    {

        $userPermission = getPermissionFromUser();
        $list = $this->essay->get_datatables();
        $data = array();
        foreach ($list as $v) {
            $row = array();
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->judul . '</p>';
            //add html for action
            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('RESSAY', $userPermission)) ? '<i class="ri-information-line ri-lg text-primary m-1" role="button" title="Ubah" onclick="detailEssay(' . $v->essayCode . ')"></i>' : '') . "
                " . ((in_array('UESSAY', $userPermission)) ? '<i class="ri-edit-2-line ri-lg text-warning m-1" role="button" title="Ubah" onclick="editEssay(' . $v->essayCode . ')"></i>' : '') . "
                " . ((in_array('DESSAY', $userPermission)) ? '<i class="ri-delete-bin-line ri-lg text-danger m-1" role="button" title="Hapus" onclick="deleteEssay(' . $v->essayCode . ')"></i>' : '') . "
                </div>
                ";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->essay->count_all(),
            "recordsFiltered" => $this->essay->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function su_essayFormHTML(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if ($this->input->post('actionEssay') == 'edit') {
            $essay = $this->db->get_where('essay', [
                'deleteAt' => NULL,
                'essayCode' => $essayCode
            ])->row_array();
            if ($essay == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Data tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
        $params = [
            'userPermission' => $userPermission,
        ];
        if ($this->input->post('actionEssay') == 'edit') {
            $params['essay'] = $essay;
        } else {
            $params['essay'] = [
                'essayCode' => '',
                'userCode' => '',
                'judul' => '',
                'deskripsi' => '',
                'waktuMulai' => '',
                'waktuSelesai' => '',
            ];
        }

        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/form', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function su_addEssay()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validateEssay();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'judul' => form_error('judul'),
                'waktuMulai' => form_error('waktuMulai'),
                'waktuSelesai' => form_error('waktuSelesai'),
                'deskripsi' => form_error('deskripsi'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $insertDataEssay = array(
                'judul' => $this->input->post('judul'),
                'waktuMulai' => $this->input->post('waktuMulai'),
                'waktuSelesai' => $this->input->post('waktuSelesai'),
                'deskripsi' => $this->input->post('deskripsi'),
            );
            if (checkAdmin()) {
                $insertDataEssay['userCode'] = $user['userCode'];
            } else {
                $insertDataEssay['userCode'] = '0';
            }

            $insertDataEssayAction = $this->db->insert('essay', $insertDataEssay);

            if ($insertDataEssayAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil menambahkan essay";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal menambahkan essay";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function su_editEssay($essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $user = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row_array();

        $this->_validateEssay();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'judul' => form_error('judul'),
                'waktuMulai' => form_error('waktuMulai'),
                'waktuSelesai' => form_error('waktuSelesai'),
                'deskripsi' => form_error('deskripsi'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $updateDataEssay = array(
                'judul' => $this->input->post('judul'),
                'waktuMulai' => $this->input->post('waktuMulai'),
                'waktuSelesai' => $this->input->post('waktuSelesai'),
                'deskripsi' => $this->input->post('deskripsi'),
            );


            $updateDataEssayAction = $this->db->where([
                'essayCode' => $essayCode
            ])->update('essay', $updateDataEssay);


            if ($updateDataEssayAction) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah essay";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah essay";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function su_deleteEssay($essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UCV', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $updateDataEssayAction = $this->db->where([
            'essayCode' => $essayCode
        ])->update('essay', [
            'deleteAt' => date('Y-m-d H:i:s')
        ]);

        if ($updateDataEssayAction) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menghapus essay";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menghapus essay";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function _validateEssay()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
        $this->form_validation->set_rules('waktuMulai', 'Tanggal Mulai', 'trim|required');
        $this->form_validation->set_rules('waktuSelesai', 'Tanggal Selesai', 'trim|required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|required');
    }

    public function su_essayDetailHTML(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
            'essay' => $essay
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/detail', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function pesertaEssay(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
            'essay' => $essay
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/peserta/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function pesertaListEssay(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $list = $this->peserta_essay->get_datatables($essayCode);
        $data = array();
        foreach ($list as $v) {
            $row = array();

            if (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $v->picture))) {
                $urlImage = base_url('assets/img/participant/' . $v->picture);
            } else {
                $urlImage = base_url('assets/img/participant/default.png');
            }

            $row[] = '
                <div class="d-flex px-2 py-1 gap-2">
                    <div>
                        <img src="' . $urlImage . '" class="avatar avatar-sm me-3" alt="user2">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <p class="text-xs text-bold d-flex py-auto my-auto">' . $v->name . '</p>
                        <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                    </div>
                </div>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto status' . $v->memberCode . '">' . status($v->status) . '</p>';

            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('RESSAY', $userPermission)) ? '<span class="btn btn-sm btn-primary text-xs my-auto py-1 mx-1" role="button" title="Detail" onclick="detailData(' . $v->memberCode . ')">Detail</span>' : '') . "
                </div>
                ";
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->peserta_essay->count_all($essayCode),
            "recordsFiltered" => $this->peserta_essay->count_filtered($essayCode),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function inviteMemberEssay(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dataState = $this->db->get_where('state', ['deleteAt' => NULL])->result_array();
        foreach ($dataState as $k => $v) {
            $state[$v['stateCode']] = $v['state'];
        }
        $params = [
            'userPermission' => $userPermission,
            'essay' => $essay,
            'state' => $state
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/lms')
            ],
            [
                "text" => "LMS",
            ]
        ], 'LMS');
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/peserta/invite', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function memberListEssay(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $list = $this->member->get_datatables($essayCode);
        $data = array();
        foreach ($list as $v) {
            $row = array();

            if (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $v->picture))) {
                $urlImage = base_url('assets/img/participant/' . $v->picture);
            } else {
                $urlImage = base_url('assets/img/participant/default.png');
            }

            $row[] = '
                <div class="d-flex px-2 py-1 gap-2">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-plus text-primary" role="button" onclick="addMemberToEssay(' . $v->memberCode . ')"></i>
                    </div>
                    <div>
                        <img src="' . $urlImage . '" class="avatar avatar-sm me-3" alt="user2" role="button" onclick="viewImageMemberDetail(' . $v->memberCode . ')">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <p class="text-xs text-bold d-flex py-auto my-auto">' . $v->name . '</p>
                        <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                    </div>
                </div>';

            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->npsn . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . getOneValue('state', ['deleteAt' => NULL, 'stateCode' => $v->stateCode], 'state') . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->education_service . '</p>';

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->member->count_all($essayCode),
            "recordsFiltered" => $this->member->count_filtered($essayCode),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function participantListEssay(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $list = $this->peserta_essay->get_datatables($essayCode);
        $data = array();
        foreach ($list as $v) {
            $row = array();

            if (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $v->picture))) {
                $urlImage = base_url('assets/img/participant/' . $v->picture);
            } else {
                $urlImage = base_url('assets/img/participant/default.png');
            }

            $row[] = '
                <div class="d-flex px-2 py-1 gap-2">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-trash text-danger" role="button" onclick="deleteMemberToEssay(' . $v->memberCode . ')"></i>
                    </div>
                    <div>
                        <img src="' . $urlImage . '" class="avatar avatar-sm me-3" alt="user2" role="button" onclick="viewImageMemberDetail(' . $v->memberCode . ')">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <p class="text-xs text-bold d-flex py-auto my-auto">' . $v->name . '</p>
                        <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                    </div>
                </div>';

            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->npsn . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . getOneValue('state', ['deleteAt' => NULL, 'stateCode' => $v->stateCode], 'state') . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->education_service . '</p>';

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->peserta_essay->count_all($essayCode),
            "recordsFiltered" => $this->peserta_essay->count_filtered($essayCode),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addMemberToEssay(string $essayCode = '', string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->member->get_by_id($memberCode);
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Member tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $participant = $this->db->select('memberCode')->get_where('essay_member', [
                'deleteAt' => NULL,
                'essayCode' => $essayCode,
                'memberCode' => $memberCode,
            ])->row_array();

            if ($participant == NULL) {
                $in = [
                    'essayCode' => $essayCode,
                    'memberCode' => $memberCode,
                    'status' => '0'
                ];
                $insert = $this->db->insert('essay_member', $in);

                $par = [
                    'broadcastCode' => NULL,
                    'type' => 'kegiatan',
                    'text' => 'Anda di undang dalam essay ' . $essay['judul'] . ' silahkan cek menu LMS',
                    'memberCode' => $member->memberCode,
                    'createAt' => date('Y-m-d H:i:s')
                ];
                $inn = $this->db->insert('notif', $par);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil menambah member di essay";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal menambah member di essay";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Member telah ditambahkan sebelumnya!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function deleteMemberToEssay(string $essayCode = '', string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->member->get_by_id($memberCode);
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Member tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $participant = $this->db->select('memberCode')->get_where('essay_member', [
                'deleteAt' => NULL,
                'essayCode' => $essayCode,
                'memberCode' => $memberCode,
            ])->row_array();

            if ($participant != NULL) {
                $where = [
                    'essayCode' => $essayCode,
                    'memberCode' => $memberCode,
                ];
                $insert = $this->db->where($where)->update('essay_member', [
                    'deleteAt' => date('Y-m-d H:i:s')
                ]);

                $par = [
                    'broadcastCode' => NULL,
                    'type' => 'kegiatan',
                    'text' => 'Undang dalam essay ' . $essay['judul'] . ' telah dibatalkan',
                    'memberCode' => $member->memberCode,
                    'createAt' => date('Y-m-d H:i:s')
                ];
                $inn = $this->db->insert('notif', $par);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil menghapus peserta di essay";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal menghapus peserta di essay";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Member tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }


    public function certificateEssay(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $dataCertificate = $this->db->get_where('certificate', ['deleteAt' => NULL, 'position !=' => NULL])->result_array();
        $certificate = [];
        foreach ($dataCertificate as $k => $v) {
            $certificate[$v['certificateCode']] = $v['name'];
        }
        $params = [
            'userPermission' => $userPermission,
            'essayCode' => $essay['essayCode'],
            'certificateCode' => ($essay['certificateCode'] == NULL) ? '' : $essay['certificateCode'],
            'certificate' => $certificate
        ];
        if ($essay['certificateCode'] != NULL) {
            $sertifikat = $this->db->get_where('certificate', ['certificateCode' => $essay['certificateCode']])->row();
            if ($sertifikat == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Sertifikat tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
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

                $halaman = json_decode($sertifikat->position, TRUE);

                $this->session->set_userdata([
                    'pdf' => [
                        'path' => path_by_os(FCPATH . '/assets/img/certificate/' . $sertifikat->file),
                        'totalHalaman' => $pageCount,
                        'halaman' => $halaman
                    ]
                ]);
            }
        }
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/sertifikat/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function updateCertificate(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if ($this->input->post('certificateCode') != NULL) {
            $sertifikat = $this->sertifikat->get_by_id($this->input->post('certificateCode'));
            if ($sertifikat == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Sertifikat tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
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

                $halaman = json_decode($sertifikat->position, TRUE);

                $this->session->set_userdata([
                    'pdf' => [
                        'path' => path_by_os(FCPATH . '/assets/img/certificate/' . $sertifikat->file),
                        'totalHalaman' => $pageCount,
                        'halaman' => $halaman
                    ]
                ]);
            }
        }
        $params = [
            'certificateCode' => $this->input->post('certificateCode')
        ];
        $up = $this->db->where([
            'essayCode' => $essayCode
        ])->update('essay', $params);
        if ($up) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil mengubah sertifikat";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal mengubah sertifikat";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    public function soal(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
            'essayCode' => $essayCode,
            'essay' => $essay
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/soal/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function soalListEssay(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $list = $this->soal->get_datatables($essayCode);
        $data = array();
        foreach ($list as $v) {
            $row = array();

            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . word_limiter($v->soal, 30) . '</p>';

            //add html for action
            $row[] = '
                        <div class="d-flex justify-content-center">
                            <i class="ri-information-line ri-lg text-primary m-1" role="button" title="Detail" onclick="detailDataSoal(' . $v->esCode . ')"></i>
                            <i class="ri-edit-2-line ri-lg text-warning m-1" role="button" title="Ubah" onclick="editDataSoal(' . $v->esCode . ')"></i>
                            <i class="ri-delete-bin-line ri-lg text-danger m-1" role="button" title="Hapus" onclick="deleteDataSoal(' . $v->esCode . ')"></i>
                        </div>
                        ';

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->soal->count_all($essayCode),
            "recordsFiltered" => $this->soal->count_filtered($essayCode),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addSoalHTML(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $params = [
            'title' => 'Tambah Data Soal',
            'essayCode' => $essay['essayCode'],
            'esCode' => '',
            'soal' => '',
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/soal/form', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function addSoal()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $this->input->post('essayCode')
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $this->validation_for = 'add';
        $data = array();
        $data['status'] = TRUE;

        $this->_validateSoal();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'soal' => form_error('soal'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $insert = array(
                'essayCode' => $this->input->post('essayCode'),
                'soal' => $this->input->post('soal'),
            );
            if (!isset($_FILES['file'])) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => 'File harus diisi'
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
            $config['upload_path']          = FCPATH . '/assets/img/soal/';
            $config['allowed_types']        = 'jpg|jpeg|png|pdf';
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
            $insert = $this->db->insert('essay_soal', $insert);
            if ($insert) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil menambah soal";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal menambah soal";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function editSoalHTML(string $essayCode = '', string $esCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $soal = $this->db->get_where('essay_soal', [
            'deleteAt' => NULL,
            'esCode' => $esCode
        ])->row_array();
        if ($soal == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $params = [
            'title' => 'Ubah Data Soal',
            'essayCode' => $essay['essayCode'],
            'esCode' => $soal['esCode'],
            'soal' => $soal['soal'],
            'file' => $soal['file'],
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/soal/form', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function updateSoal()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $this->input->post('essayCode')
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $soal = $this->db->get_where('essay_soal', [
            'deleteAt' => NULL,
            'esCode' => $this->input->post('esCode')
        ])->row_array();
        if ($soal == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $this->validation_for = 'add';
        $data = array();
        $data['status'] = TRUE;

        $this->_validateSoal();

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'soal' => form_error('soal'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $update = array(
                'essayCode' => $this->input->post('essayCode'),
                'soal' => $this->input->post('soal'),
            );
            if (isset($_FILES['file']) && $_FILES['file']['name'] != NULL) {
                $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                $config['upload_path']          = FCPATH . '/assets/img/soal/';
                $config['allowed_types']        = 'jpg|jpeg|png|pdf';
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
            $update = $this->db->where([
                'esCode' => $this->input->post('esCode')
            ])->update('essay_soal', $update);
            if ($update) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah soal";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah soal";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function deleteSoal(string $esCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $soal = $this->db->get_where('essay_soal', [
            'deleteAt' => NULL,
            'esCode' => $esCode
        ])->row_array();
        if ($soal == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $del = $this->db->where([
            'esCode' => $esCode
        ])->update('essay_soal', [
            'deleteAt' => date('Y-m-d H:i:s')
        ]);
        if ($del) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menghapus soal";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menghapus soal";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function _validateSoal()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('soal', 'soal', 'trim|required');
    }

    public function detailSoalHTML(string $esCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay_soal', [
            'deleteAt' => NULL,
            'esCode' => $esCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $params = [
            'title' => 'Detail Data Soal',
            'soal' => $essay['soal'],
            'file' => $essay['file'],
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/lms/su/essay/soal/detail', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function su_detailPesertaHTML(string $memberCode = '', string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->member->get_by_id($memberCode);
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Member tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $peserta = $this->db->select('*')->get_where('essay_member', [
                'deleteAt' => NULL,
                'essayCode' => $essayCode,
                'memberCode' => $memberCode,
            ])->row_array();
            if ($peserta == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Member bukan peserta!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $jawaban = $this->db
                ->select('ejmCode,es.soal as soal,es.file as fileSoal,ejm.jawaban as jawaban,ejm.file as fileJawaban')
                ->join('essay_soal as es', 'es.esCode=ejm.esCode')
                ->where([
                    'ejm.deleteAt' => NULL,
                    'ejm.memberCode' => $memberCode
                ])
                ->get('essay_jawaban_member as ejm')
                ->result_array();
            $params = [
                'jawaban' => $jawaban,
                'essayCode' => $essayCode,
                'memberCode' => $memberCode,
            ];
            $data['status'] = TRUE;
            $data['data'] = $this->load->view($this->module . '/lms/su/essay/jawaban/detail', $params, TRUE);
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }


    public function verifyAll(string $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $update = array(
            'status' => '2',
        );
        $up = $this->db->where(['essayCode' => $essayCode])->update('essay_member', $update);
        $parti = $this->db->select('memberCode')->get_where('essay_member', ['memberCode !=' => NULL, 'deleteAt' => NULL, 'essayCode' => $essayCode])->result_array();
        foreach ($parti as $y => $t) {
            $par = [
                'broadcastCode' => NULL,
                'type' => 'kegiatan',
                'text' => 'Anda diluluskan dalam essay ' . $essay['judul'],
                'memberCode' => $t['memberCode'],
                'createAt' => date('Y-m-d H:i:s')
            ];
            $inn = $this->db->insert('notif', $par);
        }
        if ($up) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil mengubah semua status peserta";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal mengubah semua status peserta";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function verify($memberCode = '', $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->member->get_by_id($memberCode);
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Member tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $peserta = $this->db->select('*')->get_where('essay_member', [
                'deleteAt' => NULL,
                'essayCode' => $essayCode,
                'memberCode' => $memberCode,
            ])->row_array();
            if ($peserta == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Member bukan peserta!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $update = array(
                'status' => '2',
            );
            $up = $this->db->where([
                'essayCode' => $essayCode,
                'memberCode' => $memberCode,
                'deleteAt' => NULL
            ])->update('essay_member', $update);
            $par = [
                'broadcastCode' => NULL,
                'type' => 'kegiatan',
                'text' => 'Anda lulus dalam essay ' . $essay['judul'],
                'memberCode' => $memberCode,
                'createAt' => date('Y-m-d H:i:s')
            ];
            $inn = $this->db->insert('notif', $par);
            if ($up) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah status peserta menjadi lulus";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah status peserta menjadi lulus";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function notVerify($memberCode = '', $essayCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RESSAY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $essay = $this->db->get_where('essay', [
            'deleteAt' => NULL,
            'essayCode' => $essayCode
        ])->row_array();
        if ($essay == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $member = $this->member->get_by_id($memberCode);
        if ($member == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Member tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $peserta = $this->db->select('*')->get_where('essay_member', [
                'deleteAt' => NULL,
                'essayCode' => $essayCode,
                'memberCode' => $memberCode,
            ])->row_array();
            if ($peserta == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Member bukan peserta!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $update = array(
                'status' => '1',
            );
            $up = $this->db->where([
                'essayCode' => $essayCode,
                'memberCode' => $memberCode,
                'deleteAt' => NULL
            ])->update('essay_member', $update);
            $par = [
                'broadcastCode' => NULL,
                'type' => 'kegiatan',
                'text' => 'Anda tidak lulus dalam essay ' . $essay['judul'],
                'memberCode' => $memberCode,
                'createAt' => date('Y-m-d H:i:s')
            ];
            $inn = $this->db->insert('notif', $par);
            if ($up) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil mengubah status peserta menjadi tidak lulus";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal mengubah status peserta menjadi tidak lulus";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
}
