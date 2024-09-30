<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(FCPATH . '/vendor/setasign/fpdf/fpdf.php');
require_once(FCPATH . '/vendor/setasign/fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

class Kegiatan extends MX_Controller
{
    private $module = 'ediklat';

    private $validation_for = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model($this->module . '/kegiatan_model', 'kegiatan');
        $this->load->model($this->module . '/sertifikat_model', 'sertifikat');
        $this->load->model($this->module . '/peserta_model', 'peserta');
        $this->load->model($this->module . '/materi_model', 'materi');
        $this->load->model($this->module . '/member_model', 'member');
        $this->load->model($this->module . '/participant_model', 'participant');
        $this->load->model($this->module . '/tugas_model', 'tugas');
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
        if (!in_array('RACTIVITY', $userPermission)) {
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
                "url" => base_url('ediklat/kegiatan')
            ],
            [
                "text" => "Kegiatan",
            ]
        ], 'Data Kegiatan');
        $data['data'] = $this->load->view($this->module . '/kegiatan/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function list()
    {
        $userPermission = getPermissionFromUser();
        $list = $this->kegiatan->get_datatables();
        $data = array();
        foreach ($list as $result) {
            $participant = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $result->activityCode, 'deleteAt' => NULL])->result_array();
            $row = array();

            $row[] = '
            <div class="d-flex px-2 py-1">
                <div>
                    <img src="' . base_url('assets/img/activity/' . $result->image) . '" class="avatar avatar-sm me-3" alt="user2" role="button" onclick="viewImage(' . $result->activityCode . ')">
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <p class="text-xs d-flex py-auto my-auto text-bold">' . character_limiter($result->name, 15) . '</p>
                    <p class="text-xs d-flex py-auto my-auto">kuota : ' . $result->kuota . '</p>
                </div>
            </div>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $result->organizer . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $result->media . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($result->type == 'general' ? 'Terbuka' : 'Tertutup') . '</p>';
            $row[] = '
            <p class="text-xs d-flex py-auto my-auto">Mulai: ' . tanggal_indo($result->startDate) . '</p>
            <p class="text-xs d-flex py-auto my-auto">Selesai: ' . tanggal_indo($result->endDate) . '</p>
            ';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . count($participant) . ' Peserta</p>';
            $row[] = '
                <p class="text-xs d-flex py-auto my-auto">Kegiatan: ' . translateOpenClose($result->status) . '</p>
                <p class="text-xs d-flex py-auto my-auto">Absen: ' . translateOpenClose($result->attendance) . '</p>
            ';
            //add html for action
            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('RACTIVITY', $userPermission)) ? '<i class="ri-information-line ri-lg text-primary m-1" role="button" title="Ubah" onclick="detailData(' . $result->activityCode . ')"></i>' : '') . "
                " . ((in_array('UACTIVITY', $userPermission)) ? '<i class="ri-edit-2-line ri-lg text-warning m-1" role="button" title="Ubah" onclick="editData(' . $result->activityCode . ')"></i>' : '') . "
                " . ((in_array('DACTIVITY', $userPermission)) ? '<i class="ri-delete-bin-line ri-lg text-danger m-1" role="button" title="Hapus" onclick="deleteData(' . $result->activityCode . ')"></i>' : '') . "
                </div>
                ";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->kegiatan->count_all(),
            "recordsFiltered" => $this->kegiatan->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {

            $data['status'] = TRUE;
            $params = [
                'title' => 'Tambah Data Kegiatan',
                'activityCode' => NULL,
                'name' => NULL,
                'startDate' => NULL,
                'endDate' => NULL,
                'organizer' => NULL,
                'media' => '',
                'status' => '',
                'image' => NULL,
                'description' => NULL,
                'attendance' => '',
                'category' => '',
                'type' => '',
                'kuota' => '',
                'action' => 'add'
            ];
            $data['breadcrumb'] = breadcrumb([
                [
                    "text" => "E-Diklat",
                    "url" => base_url('ediklat/kegiatan')
                ],
                [
                    "text" => "Kegiatan",
                    "action" => "back()"
                ],
                [
                    "text" => "Tambah Kegiatan",
                ]
            ], 'Tambah Kegiatan');
            $data['data'] = $this->load->view($this->module . '/kegiatan/form', $params, TRUE);
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function editHTML(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Ubah Data Kegiatan',
                        'activityCode' => $result->activityCode,
                        'name' => $result->name,
                        'startDate' => $result->startDate,
                        'endDate' => $result->endDate,
                        'organizer' => $result->organizer,
                        'media' => $result->media,
                        'status' => $result->status,
                        'image' => $result->image,
                        'description' => $result->description,
                        'attendance' => $result->attendance,
                        'type' => $result->type,
                        'category' => $result->category,
                        'kuota' => $result->kuota,
                        'action' => 'edit'
                    ];
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "E-Diklat",
                            "url" => base_url('ediklat/kegiatan')
                        ],
                        [
                            "text" => "Kegiatan",
                            "action" => "back()"
                        ],
                        [
                            "text" => "Ubah Kegiatan",
                        ]
                    ], 'Ubah Kegiatan');
                    $data['data'] = $this->load->view($this->module . '/kegiatan/form', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function add()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CACTIVITY', $userPermission)) {
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
                    'startDate' => form_error('startDate'),
                    'endDate' => form_error('endDate'),
                    'media' => form_error('media'),
                    'description' => form_error('description'),
                    'organizer' => form_error('organizer'),
                    'type' => form_error('type'),
                    'category' => form_error('category'),
                    'kuota' => form_error('kuota'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $insert = array(
                    'name' => $this->input->post('name'),
                    'startDate' => $this->input->post('startDate'),
                    'endDate' => $this->input->post('endDate'),
                    'media' => $this->input->post('media'),
                    'description' => $this->input->post('description'),
                    'organizer' => $this->input->post('organizer'),
                    'type' => $this->input->post('type'),
                    'category' => $this->input->post('category'),
                    'kuota' => $this->input->post('kuota'),
                    'userCode' => $this->session->userdata('userCode'),
                    'status' => 'close',
                    'attendance' => 'close',
                );
                if (!isset($_FILES['image'])) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => 'Gambar harus diisi'
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                $config['upload_path']          = FCPATH . '/assets/img/activity/';
                $config['allowed_types']        = 'gif|jpg|jpeg|png';
                $config['file_name']            = $file_name;
                $config['overwrite']            = true;
                $config['max_size']             = 10240;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('image')) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => 'Gambar gagal di upload'
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $uploaded_data = $this->upload->data();
                    $insert['image'] = $uploaded_data['file_name'];
                }
                $insert = $this->kegiatan->save($insert);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil menambah kegiatan";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal menambah kegiatan";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function update()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UACTIVITY', $userPermission)) {
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
                    // 'startDate' => form_error('startDate'),
                    // 'endDate' => form_error('endDate'),
                    'media' => form_error('media'),
                    'description' => form_error('description'),
                    'organizer' => form_error('organizer'),
                    'type' => form_error('type'),
                    'category' => form_error('category'),
                    'kuota' => form_error('kuota'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $result = $this->kegiatan->get_by_id($this->input->post('activityCode'));
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $update = array(
                        'name' => $this->input->post('name'),
                        // 'startDate' => $this->input->post('startDate'),
                        // 'endDate' => $this->input->post('endDate'),
                        'media' => $this->input->post('media'),
                        'description' => $this->input->post('description'),
                        'organizer' => $this->input->post('organizer'),
                        'type' => $this->input->post('type'),
                        'category' => $this->input->post('category'),
                        'kuota' => $this->input->post('kuota'),
                        // 'userCode' => $this->session->userdata('userCode'),
                    );
                    if (isset($_FILES['image']) && $_FILES['image']['name'] != NULL) {
                        $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                        $config['upload_path']          = FCPATH . '/assets/img/activity/';
                        $config['allowed_types']        = 'gif|jpg|jpeg|png';
                        $config['file_name']            = $file_name;
                        $config['overwrite']            = true;
                        $config['max_size']             = 10240;

                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('image')) {
                            $data = array(
                                'status'         => FALSE,
                                'message'         => 'Gambar gagal di upload'
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        } else {
                            $uploaded_data = $this->upload->data();
                            $update['image'] = $uploaded_data['file_name'];
                        }
                    }
                    $up = $this->kegiatan->update(array('activityCode' => $this->input->post('activityCode')), $update);
                    if ($up) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil mengubah kegiatan";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal mengubah kegiatan";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function delete(string $id = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('DACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($id == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $kegiatan = $this->kegiatan->get_by_id($id);
                if ($kegiatan == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $del = $this->kegiatan->delete_by_id($id);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil menghapus kegiatan";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal menghapus kegiatan";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    private function _validate()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('name', 'nama kegiatan', 'trim|required');
        if($this->validation_for == 'add'){
            $this->form_validation->set_rules('startDate', 'waktu mulai', 'trim|required');
            $this->form_validation->set_rules('endDate', 'waktu selesai', 'trim|required');
        }
        $this->form_validation->set_rules('media', 'media', 'trim|required');
        $this->form_validation->set_rules('description', 'deskripsi', 'required');
        $this->form_validation->set_rules('organizer', 'deskripsi', 'trim|required');
        $this->form_validation->set_rules('type', 'tipe', 'trim|required');
        $this->form_validation->set_rules('category', 'kategori', 'trim|required');
        $this->form_validation->set_rules('kuota', 'kuota', 'trim|required');
    }

    private function _validateTheory()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('name', 'nama', 'trim|required');
        $this->form_validation->set_rules('description', 'deskripsi', 'required');
    }

    private function _validateTugas()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('task', 'tugas', 'trim|required');
    }

    public function imageHTML(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => $result->name,
                        'image' => $result->image
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/image', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function detailHTML(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $participant = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $result->activityCode, 'deleteAt' => NULL])->result_array();
                    $user = $this->db->select('email')->get_where('user', ['userCode' => $result->userCode, 'deleteAt' => NULL])->row_array();
                    $params = [
                        'title' => 'Detail Data Kegiatan',
                        'activityCode' => $result->activityCode,
                        'name' => $result->name,
                        'startDate' => $result->startDate,
                        'endDate' => $result->endDate,
                        'organizer' => $result->organizer,
                        'media' => $result->media,
                        'category' => $result->category,
                        'kuota' => $result->kuota,
                        'status' => $result->status,
                        'type' => $result->type,
                        'image' => $result->image,
                        'description' => $result->description,
                        'attendance' => $result->attendance,
                        'jumlahPeserta' => count($participant),
                        'userName' => ($user != NULL ? $user['email'] : '')
                    ];
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "E-Diklat",
                            "url" => base_url('ediklat/kegiatan')
                        ],
                        [
                            "text" => "Kegiatan",
                            "action" => "back()"
                        ],
                        [
                            "text" => "Detail Kegiatan",
                        ]
                    ], 'Detail Kegiatan');
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/index', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function inviteParticipant(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $state = [];
                    $dataState = $this->db->get_where('state', ['deleteAt' => NULL])->result_array();
                    foreach ($dataState as $k => $v) {
                        $state[$v['stateCode']] = $v['state'];
                    }
                    $params = [
                        'activityCode' => $kegiatanCode,
                        'state' => $state
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/peserta/invite', $params, TRUE);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function memberList(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $list = $this->member->get_datatables($kegiatanCode);
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
                                    <i class="fa fa-plus text-primary" role="button" onclick="addMemberToActivity(' . $v->memberCode . ')"></i>
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
                        "recordsTotal" => $this->member->count_all($kegiatanCode),
                        "recordsFiltered" => $this->member->count_filtered($kegiatanCode),
                        "data" => $data,
                    );
                    //output to json format
                    echo json_encode($output);
                }
            }
        }
    }

    public function participantList(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $list = $this->participant->get_datatables($kegiatanCode);
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
                                    <i class="fa fa-trash text-danger" role="button" onclick="deleteMemberToActivity(' . $v->memberCode . ')"></i>
                                </div>
                                <div>
                                    <img src="' . $urlImage . '" class="avatar avatar-sm me-3" alt="user2" role="button" onclick="viewImageParticipantDetail(' . $v->participantCode . ')">
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
                        "recordsTotal" => $this->participant->count_all($kegiatanCode),
                        "recordsFiltered" => $this->participant->count_filtered($kegiatanCode),
                        "data" => $data,
                    );
                    //output to json format
                    echo json_encode($output);
                }
            }
        }
    }

    public function imageMemberHTMLDetail(string $memberCode = '')
    {
        $data['status'] = TRUE;
        if ($memberCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Parcipant code is required"
            );
        } else {
            $result = $this->member->get_by_id($memberCode);
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Member tidak ditemukan!"
                );
            } else {
                $params = [
                    'title' => $result->name,
                    'image' => $result->picture
                ];
                $data['data'] = $this->load->view($this->module . '/kegiatan/detail/peserta/image_member', $params, TRUE);
            }
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function imageParticipantHTMLDetail(string $pesertaCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($pesertaCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Parcipant code is required"
                );
            } else {
                $result = $this->peserta->get_by_id($pesertaCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Peserta tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => $result->name,
                        'image' => $result->picture
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/peserta/image_participant', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function addMemberToActivity(string $kegiatanCode = '', string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $kegiatan = $this->kegiatan->get_by_id($kegiatanCode);
                if ($kegiatan == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $member = $this->member->get_by_id($memberCode);
                    if ($member == NULL) {
                        $data = array(
                            'status'         => FALSE,
                            'message'         => "Member tidak ditemukan!"
                        );
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $participant = $this->db->select('participantCode')->get_where('participant', [
                            'deleteAt' => NULL,
                            'activityCode' => $kegiatanCode
                        ])->result_array();

                        $totalParticipant = count($participant);
                        if ($totalParticipant >= $kegiatan->kuota) {
                            $data = array(
                                'status'         => FALSE,
                                'message'         => "Kuota habis!"
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        }
                        $check = $this->db->get_where('participant', [
                            'deleteAt' => NULL,
                            'activityCode' => $kegiatanCode,
                            'memberCode' => $memberCode
                        ])->row_array();
                        if ($check == NULL) {
                            $betweenDate = getBetweenDates($kegiatan->startDate,$kegiatan->endDate);
                            $tempDate = [];
                            foreach($betweenDate as $t => $g){
                                $tempDate[$g] = '0';
                            }
                            $in = [
                                'memberCode' => $member->memberCode,
                                'name' => $member->name,
                                'npsn' => $member->npsn,
                                'nik' => $member->nik,
                                'phone' => $member->phone,
                                'address' => $member->address,
                                'npwp' => $member->npwp,
                                'agency' => $member->agency,
                                'rank' => $member->rank,
                                'rank_dinas' => $member->rank_dinas,
                                'gender' => $member->gender,
                                'education' => $member->education,
                                'education_service' => $member->education_service,
                                'birthplace' => $member->birthplace,
                                'birthdate' => $member->birthdate,
                                'stateCode' => $member->stateCode,
                                'picture' => $member->picture,
                                'email' => getOneValue('user', ['userCode' => $member->userCode], 'email'),
                                'activityCode' => $kegiatanCode,
                                'status' => '0',
                                'statusDetail' => json_encode($tempDate,TRUE),
                                'verify' => '0',
                                'invite' => '1',
                                'accept' => '2',
                            ];
                            $insert = $this->participant->save($in);

                            $par = [
                                'broadcastCode' => NULL,
                                'type' => 'kegiatan',
                                'text' => 'Anda di undang dalam kegiatan ' . $kegiatan->name . ' silahkan cek menu undangan',
                                'memberCode' => $member->memberCode,
                                'createAt' => date('Y-m-d H:i:s')
                            ];
                            $inn = $this->db->insert('notif', $par);
                            if ($insert) {
                                $data['status'] = TRUE;
                                $data['message'] = "Berhasil menambah peserta di kegiatan";
                            } else {
                                $data['status'] = FALSE;
                                $data['message'] = "Gagal menambah peserta di kegiatan";
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
            }
        }
    }

    public function deleteMemberToActivity(string $kegiatanCode = '', string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $kegiatan = $this->kegiatan->get_by_id($kegiatanCode);
                if ($kegiatan == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $check = $this->db->get_where('participant', [
                        'deleteAt' => NULL,
                        'activityCode' => $kegiatanCode,
                        'memberCode' => $memberCode
                    ])->row_array();
                    if ($check == NULL) {
                        $data = array(
                            'status'         => FALSE,
                            'message'         => "Peserta tidak ditemukan!"
                        );
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $up = $this->db->where([
                            'deleteAt' => NULL,
                            'activityCode' => $kegiatanCode,
                            'memberCode' => $memberCode
                        ])->update('participant', ['deleteAt' => date('Y-m-d H:i:s')]);
                        $par = [
                            'broadcastCode' => NULL,
                            'type' => 'kegiatan',
                            'text' => 'Undang dalam kegiatan ' . $kegiatan->name . ' telah dibatalkan',
                            'memberCode' => $memberCode,
                            'createAt' => date('Y-m-d H:i:s')
                        ];
                        $inn = $this->db->insert('notif', $par);
                        if ($up) {
                            $data['status'] = TRUE;
                            $data['message'] = "Berhasil menghapus peserta dari kegiatan";
                        } else {
                            $data['status'] = FALSE;
                            $data['message'] = "Gagal menghapus peserta dari kegiatan";
                        }
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                }
            }
        }
    }

    public function peserta(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
            'activityCode' => $kegiatanCode,
            'activity' => $this->kegiatan->get_by_id($kegiatanCode)
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/kegiatan/detail/peserta/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function pesertaList(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $tugas = $this->db->get_where('task', [
                        'deleteAt' => NULL,
                        'activityCode' => $kegiatanCode
                    ])->result_array();
                    $list = $this->peserta->get_datatables($kegiatanCode);
                    $data = array();
                    foreach ($list as $v) {
                        $row = array();

                        if (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $v->picture))) {
                            $urlImage = base_url('assets/img/participant/' . $v->picture);
                        } else {
                            $urlImage = base_url('assets/img/participant/default.png');
                        }
                        $row[] = '
                            <div class="d-flex px-2 py-1">
                                <div>
                                    <img src="' . $urlImage . '" class="avatar avatar-sm me-3" alt="user2" role="button" onclick="viewImageParticipant(' . $v->participantCode . ')">
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <p class="text-xs text-bold d-flex py-auto my-auto">' . $v->name . '</p>
                                    <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                                </div>
                            </div>';

                        $absen = '<ul>';
                        $dataAbsen = json_decode($v->statusDetail,TRUE);
                        if($dataAbsen != NULL){
                            foreach($dataAbsen as $f => $e){
                                if($f <= date('Y-m-d')){
                                    $absen .= '<li class="text-xs">'.tanggal_indo($f).' - '.($e == '1' ? 'Hadir' : 'Tidak Hadir').'</li>';    
                                }
                            }
                        }
                        $absen .= '</ul>';
                        $row[] = $absen;
                        // $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($v->status == '1' ? 'Hadir' : 'Tidak Hadir') . '</p>';
                        $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($v->verify == '1' ? 'Lulus' : ($v->verify == '2' ? 'Dicek' : 'Tidak Lulus')) . '</p>';
                        $tugasHTML = '';
                        if (count($tugas) == 0) {
                            $tugasHTML = '<p class="text-xs d-flex py-auto my-auto">Tidak ada tugas di kegiatan ini</p>';
                        } else {
                            $sudah = 0;
                            foreach ($tugas as $k => $t) {
                                $tugas_peserta = $this->db->get_where('participant_task', [
                                    'deleteAt' => NULL,
                                    'participantCode' => $v->participantCode,
                                    'taskCode' => $t['taskCode']
                                ])->row_array();
                                if ($tugas_peserta != NULL) {
                                    $sudah = 1;
                                }
                            }
                            if ($sudah == 0) {
                                $tugasHTML = '<p class="text-xs d-flex py-auto my-auto">Tugas belum dikerjakan</p>';
                            } else {
                                $tugasHTML = '<p class="text-xs d-flex align-items-center my-auto" onclick="detailTugasPeserta(' . $v->activityCode . ',' . $v->participantCode . ')" role="button">Tugas
                                <i class="ri-information-line ri-lg text-primary m-1" role="button"></i>
                                </p>';
                            }
                        }

                        $row[] = $tugasHTML;
                        if ($result->type == 'special') {
                            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($v->accept == '1' ? 'Member menerima undangan' : ($v->accept == '2' ? 'Undangan terkirim' : 'Member menolak undangan')) . '</p>';
                        }
                        //add html for action
                        $row[] = '
                        <div class="d-flex justify-content-center">
                            <i class="ri-information-line ri-lg text-primary m-1" role="button" title="Detail" onclick="detailDataPeserta(' . $v->participantCode . ')"></i>
                            <i class="ri-edit-2-line ri-lg text-warning m-1" role="button" title="Ubah" onclick="editDataPeserta(' . $v->participantCode . ')"></i>
                            <i class="ri-delete-bin-line ri-lg text-danger m-1" role="button" title="Hapus" onclick="deleteDataPeserta(' . $v->participantCode . ')"></i>
                        
                            <a href="' . base_url('ediklat/kegiatan/download_peserta/' . $v->participantCode) . '"><i class="fa fa-download text-primary m-1" role="button" title="Download"></i></a>
                        </div>
                        ';

                        $data[] = $row;
                    }

                    $output = array(
                        "draw" => @$_POST['draw'],
                        "recordsTotal" => $this->peserta->count_all($kegiatanCode),
                        "recordsFiltered" => $this->peserta->count_filtered($kegiatanCode),
                        "data" => $data,
                    );
                    //output to json format
                    echo json_encode($output);
                }
            }
        }
    }

    public function editPesertaHTML(string $kegiatanCode = '', string $pesertaCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                $peserta = $this->peserta->get_by_id($pesertaCode);
                if ($result == NULL || $peserta == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan atau peserta tidak ditemukan!"
                    );
                } else {
                    $dataState = $this->db->get_where('state', ['deleteAt' => NULL])->result();
                    $state = [];
                    foreach ($dataState as $k => $v) {
                        $state[$v->stateCode] = $v->state;
                    }
                    $params = [
                        'title' => 'Ubah Data Peserta',
                        'activityCode' => $result->activityCode,
                        'participantCode' => $peserta->participantCode,
                        'name' => $peserta->name,
                        'nik' => $peserta->nik,
                        'npsn' => $peserta->npsn,
                        'birthplace' => $peserta->birthplace,
                        'birthdate' => $peserta->birthdate,
                        'phone' => $peserta->phone,
                        'agency' => $peserta->agency,
                        'gender' => ($peserta->gender == NULL ? '-' : $peserta->gender),
                        'rank' => $peserta->rank,
                        'rank_dinas' => $peserta->rank_dinas,
                        'education' => ($peserta->education == NULL ? '-' : $peserta->education),
                        'education_service' => ($peserta->education_service == NULL ? '-' : $peserta->education_service),
                        'npwp' => $peserta->npwp,
                        'email' => $peserta->email,
                        'stateCode' => ($peserta->stateCode == NULL ? '-' : $peserta->stateCode),
                        'address' => $peserta->address,
                        'picture' => $peserta->picture,
                    ];
                    $params['state'] = $state;
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/peserta/form', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function updatePeserta()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data = array();
            $data['status'] = TRUE;
            $kegiatanCode = $this->input->post('activityCode');
            $pesertaCode = $this->input->post('participantCode');
            $result = $this->kegiatan->get_by_id($kegiatanCode);
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Kegiatan tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
                $this->form_validation->set_rules('npsn', 'NPSN', 'trim|required');
                $this->form_validation->set_rules('name', 'nama', 'trim|required');
                $this->form_validation->set_rules('phone', 'no handphone', 'trim|required');
                $this->form_validation->set_rules('address', 'alamat', 'trim|required');
                $this->form_validation->set_rules('npwp', 'NPWP', 'trim|required');
                $this->form_validation->set_rules('agency', 'instansi', 'trim|required');
                $this->form_validation->set_rules('rank_dinas', 'jabatan dalam dinas', 'required');
                $this->form_validation->set_rules('rank', 'pangkat/golongan', 'trim|required');
                $this->form_validation->set_rules('gender', 'jenis kelamin', 'trim|required');
                $this->form_validation->set_rules('birthplace', 'tempat lahir', 'trim|required');
                $this->form_validation->set_rules('birthdate', 'tanggal lahir', 'trim|required');
                $this->form_validation->set_rules('education', 'pendidikan terakhir', 'trim|required');
                $this->form_validation->set_rules('education_service', 'layanan pendidikan', 'trim|required');
                $this->form_validation->set_rules('stateCode', 'stateCode', 'trim|required');
                $this->form_validation->set_rules('activityCode', 'kegiatan', 'trim|required');
                $this->form_validation->set_rules('participantCode', 'peserta', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $errors = array(
                        'name' => form_error('name'),
                        'npsn' => form_error('npsn'),
                        'nik' => form_error('nik'),
                        'phone' => form_error('phone'),
                        'address' => form_error('address'),
                        'npwp' => form_error('npwp'),
                        'agency' => form_error('agency'),
                        'rank' => form_error('rank'),
                        'rank_dinas' => form_error('rank_dinas'),
                        'gender' => form_error('gender'),
                        'birthplace' => form_error('birthplace'),
                        'birthdate' => form_error('birthdate'),
                        'education' => form_error('education'),
                        'education_service' => form_error('education_service'),
                        'stateCode' => form_error('stateCode'),
                    );
                    $data = array(
                        'status'         => FALSE,
                        'errors'         => $errors
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $update = array(
                        'name' => $this->input->post('name'),
                        'nik' => $this->input->post('nik'),
                        'npsn' => $this->input->post('npsn'),
                        'phone' => $this->input->post('phone'),
                        'address' => $this->input->post('address'),
                        'npwp' => $this->input->post('npwp'),
                        'agency' => $this->input->post('agency'),
                        'rank' => $this->input->post('rank'),
                        'rank_dinas' => $this->input->post('rank_dinas'),
                        'gender' => $this->input->post('gender'),
                        'education' => $this->input->post('education'),
                        'education_service' => $this->input->post('education_service'),
                        'birthplace' => $this->input->post('birthplace'),
                        'birthdate' => $this->input->post('birthdate'),
                        'stateCode' => $this->input->post('stateCode'),
                        'status' => '0',
                        'verify' => '2',
                    );
                    if (isset($_FILES['picture']) && $_FILES['picture']['name'] != NULL) {

                        $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                        $config['upload_path']          = FCPATH . '/assets/img/participant/';
                        $config['allowed_types']        = 'gif|jpg|jpeg|png';
                        $config['file_name']            = $file_name;
                        $config['overwrite']            = true;
                        $config['max_size']             = 10240;

                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('picture')) {
                            $data = array(
                                'status'         => FALSE,
                                'message'         => 'Foto gagal di upload'
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        } else {
                            $uploaded_data = $this->upload->data();
                            $update['picture'] = $uploaded_data['file_name'];
                        }
                    }
                    $update = $this->peserta->update(['activityCode' => $kegiatanCode, 'participantCode' => $pesertaCode], $update);
                    if ($update) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil mengubah data perserta";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal mengubah data perserta";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function deletePeserta($id = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($id == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Peserta code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $peserta = $this->peserta->get_by_id($id);
                if ($peserta == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Peserta tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $del = $this->peserta->delete_by_id($id);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil menghapus peserta";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal menghapus peserta";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function detailPesertaHTML(string $pesertaCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($pesertaCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->peserta->get_by_id($pesertaCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Peserta tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Detail Data Peserta',
                        'activityCode' => $result->activityCode,
                        'participantCode' => $result->participantCode,
                        'name' => $result->name,
                        'npsn' => $result->npsn,
                        'nik' => $result->nik,
                        'birthplace' => $result->birthplace,
                        'birthdate' => $result->birthdate,
                        'phone' => $result->phone,
                        'agency' => $result->agency,
                        'gender' => $result->gender,
                        'rank' => $result->rank,
                        'rank_dinas' => $result->rank_dinas,
                        'education_service' => $result->education_service,
                        'education' => $result->education,
                        'state' => getOneValue('state', ['deleteAt' => NULL, 'stateCode' => $result->stateCode], 'state'),
                        'npwp' => $result->npwp,
                        'email' => $result->email,
                        'address' => $result->address,
                        'picture' => $result->picture,
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/peserta/detail', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function imageParticipantHTML(string $pesertaCode = '')
    {
        $userPermission = getPermissionFromUser();
        $data['status'] = TRUE;
        if ($pesertaCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Parcipant code is required"
            );
        } else {
            $result = $this->peserta->get_by_id($pesertaCode);
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Peserta tidak ditemukan!"
                );
            } else {
                $params = [
                    'title' => $result->name,
                    'image' => $result->picture
                ];
                $data['data'] = $this->load->view($this->module . '/kegiatan/detail/peserta/image', $params, TRUE);
            }
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function materi()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
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
        $data['data'] = $this->load->view($this->module . '/kegiatan/detail/materi/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function materiList(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $list = $this->materi->get_datatables($kegiatanCode);
                    $data = array();
                    foreach ($list as $result) {
                        $row = array();

                        $row[] = '
                                    <p class="text-xs text-bold d-flex py-auto my-auto">' . $result->name . '</p>
                                ';
                        $row[] = '
                                    <p class="text-xs text-bold d-flex py-auto my-auto">' . $result->description . '</p>
                                ';

                        //add html for action
                        $row[] = '
                        <div class="d-flex justify-content-center">
                            <i class="ri-information-line ri-lg text-primary m-1" role="button" title="Detail" onclick="detailDataTheory(' . $result->theoryCode . ')"></i>
                            <i class="ri-edit-2-line ri-lg text-warning m-1" role="button" title="Ubah" onclick="editDataTheory(' . $result->theoryCode . ')"></i>
                            <i class="ri-delete-bin-line ri-lg text-danger m-1" role="button" title="Hapus" onclick="deleteDataTheory(' . $result->theoryCode . ')"></i>
                        </div>
                        ';

                        $data[] = $row;
                    }

                    $output = array(
                        "draw" => @$_POST['draw'],
                        "recordsTotal" => $this->materi->count_all($kegiatanCode),
                        "recordsFiltered" => $this->materi->count_filtered($kegiatanCode),
                        "data" => $data,
                    );
                    //output to json format
                    echo json_encode($output);
                }
            }
        }
    }

    public function addTheoryHTML(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Tambah Data Materi',
                        'activityCode' => $result->activityCode,
                        'theoryCode' => '',
                        'name' => '',
                        'description' => '',
                        'file' => '',
                        'type' => '',
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/materi/form', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function addTheory()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'add';
            $data = array();
            $data['status'] = TRUE;

            $this->_validateTheory();

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'name' => form_error('name'),
                    'description' => form_error('description'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $insert = array(
                    'activityCode' => $this->input->post('activityCode'),
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                );
                if (!isset($_FILES['file'])) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => 'File harus diisi'
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                $config['upload_path']          = FCPATH . '/assets/img/theory/';
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
                    $insert['type'] = $uploaded_data['file_type'];
                }
                $insert = $this->materi->save($insert);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil menambah materi";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal menambah materi";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function editTheoryHTML(string $kegiatanCode = '', string $materiCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                $materi = $this->materi->get_by_id($materiCode);
                if ($result == NULL || $materi == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan atau materi tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Ubah Data Materi',
                        'activityCode' => $result->activityCode,
                        'theoryCode' => $materi->theoryCode,
                        'name' => $materi->name,
                        'description' => $materi->description,
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/materi/form', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function updateTheory()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'add';
            $data = array();
            $data['status'] = TRUE;

            $this->_validateTheory();

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'name' => form_error('name'),
                    'description' => form_error('description'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $update = array(
                    'activityCode' => $this->input->post('activityCode'),
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                );
                if (isset($_FILES['file']) && $_FILES['file']['name'] != NULL) {
                    $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                    $config['upload_path']          = FCPATH . '/assets/img/theory/';
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
                        $update['type'] = $uploaded_data['file_type'];
                    }
                }
                $update = $this->materi->update([
                    'theoryCode' => $this->input->post('theoryCode')
                ], $update);
                if ($update) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil mengubah materi";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal mengubah materi";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function deleteTheory($id = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($id == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Theory code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $materi = $this->materi->get_by_id($id);
                if ($materi == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Materi tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $del = $this->materi->delete_by_id($id);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil menghapus materi";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal menghapus materi";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function detailTheoryHTML(string $materiCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($materiCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->materi->get_by_id($materiCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Detail Data Materi',
                        'name' => $result->name,
                        'description' => $result->description,
                        'file' => $result->file,
                        'type' => $result->type,
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/materi/detail', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function changeKegiatan($kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $update = array(
                        'status' => ($result->status == 'open' ? 'close' : 'open'),
                    );
                    $up = $this->kegiatan->update(array('activityCode' => $kegiatanCode), $update);
                    if ($up) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil mengubah status kegiatan";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal mengubah status kegiatan";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function changeAbsen($kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $update = array(
                        'attendance' => ($result->attendance == 'open' ? 'close' : 'open'),
                    );
                    $up = $this->kegiatan->update(array('activityCode' => $kegiatanCode), $update);
                    if ($up) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil mengubah status absen kegiatan";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal mengubah status absen kegiatan";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function sertifikat($id = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if ($id == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Activity code is required"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $kegiatan = $this->kegiatan->get_by_id($id);
            if ($kegiatan == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Kegiatan tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $dataCertificate = $this->db->get_where('certificate', ['deleteAt' => NULL, 'position !=' => NULL])->result_array();
                $certificate = [];
                foreach ($dataCertificate as $k => $v) {
                    $certificate[$v['certificateCode']] = $v['name'];
                }
                $params = [
                    'userPermission' => $userPermission,
                    'activityCode' => $kegiatan->activityCode,
                    'certificateCode' => ($kegiatan->certificateCode == NULL) ? '' : $kegiatan->certificateCode,
                    'certificate' => $certificate
                ];
                if ($kegiatan->certificateCode != NULL) {
                    $sertifikat = $this->db->get_where('certificate', ['certificateCode' => $kegiatan->certificateCode])->row();
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
                $data['data'] = $this->load->view($this->module . '/kegiatan/detail/sertifikat/index', $params, TRUE);
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function updateCertificate($id = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if ($id == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Activity code is required"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $kegiatan = $this->kegiatan->get_by_id($id);
            if ($kegiatan == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Kegiatan tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
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
                $up = $this->kegiatan->update(array('activityCode' => $id), $params);
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


    public function tugas(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
            'activityCode' => $kegiatanCode,
            'activity' => $this->kegiatan->get_by_id($kegiatanCode)
        ];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/kegiatan/detail/tugas/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function tugasList(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $list = $this->tugas->get_datatables($kegiatanCode);
                    $data = array();
                    foreach ($list as $v) {
                        $row = array();

                        $row[] = '<p class="text-xs d-flex py-auto my-auto">' . word_limiter($v->task, 30) . '</p>';

                        //add html for action
                        $row[] = '
                        <div class="d-flex justify-content-center">
                            <i class="ri-information-line ri-lg text-primary m-1" role="button" title="Detail" onclick="detailDataTugas(' . $v->taskCode . ')"></i>
                            <i class="ri-edit-2-line ri-lg text-warning m-1" role="button" title="Ubah" onclick="editDataTugas(' . $v->taskCode . ')"></i>
                            <i class="ri-delete-bin-line ri-lg text-danger m-1" role="button" title="Hapus" onclick="deleteDataTugas(' . $v->taskCode . ')"></i>
                        </div>
                        ';

                        $data[] = $row;
                    }

                    $output = array(
                        "draw" => @$_POST['draw'],
                        "recordsTotal" => $this->tugas->count_all($kegiatanCode),
                        "recordsFiltered" => $this->tugas->count_filtered($kegiatanCode),
                        "data" => $data,
                    );
                    //output to json format
                    echo json_encode($output);
                }
            }
        }
    }

    public function addTugasHTML(string $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Tambah Data Tugas',
                        'activityCode' => $result->activityCode,
                        'taskCode' => '',
                        'task' => '',
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/tugas/form', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function addTugas()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'add';
            $data = array();
            $data['status'] = TRUE;

            $this->_validateTugas();

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'task' => form_error('task'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $insert = array(
                    'activityCode' => $this->input->post('activityCode'),
                    'task' => $this->input->post('task'),
                );
                if (!isset($_FILES['file'])) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => 'File harus diisi'
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                $config['upload_path']          = FCPATH . '/assets/img/task/';
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
                    $insert['type'] = $uploaded_data['file_type'];
                }
                $insert = $this->tugas->save($insert);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil menambah tugas";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal menambah tugas";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function editTugasHTML(string $kegiatanCode = '', string $tugasCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                $tugas = $this->tugas->get_by_id($tugasCode);
                if ($result == NULL || $tugas == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan atau tugas tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Ubah Data Tugas',
                        'activityCode' => $result->activityCode,
                        'taskCode' => $tugas->taskCode,
                        'task' => $tugas->task,
                        'file' => $tugas->file,
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/tugas/form', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function updateTugas()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'add';
            $data = array();
            $data['status'] = TRUE;

            $this->_validateTugas();

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'task' => form_error('task'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $update = array(
                    'activityCode' => $this->input->post('activityCode'),
                    'task' => $this->input->post('task'),
                );
                if (isset($_FILES['file']) && $_FILES['file']['name'] != NULL) {
                    $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                    $config['upload_path']          = FCPATH . '/assets/img/task/';
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
                        $update['type'] = $uploaded_data['file_type'];
                    }
                }
                $update = $this->tugas->update([
                    'taskCode' => $this->input->post('taskCode')
                ], $update);
                if ($update) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil mengubah tugas";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal mengubah tugas";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function deleteTugas($id = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($id == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Task code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $tugas = $this->tugas->get_by_id($id);
                if ($tugas == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Tugas tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $del = $this->tugas->delete_by_id($id);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil menghapus tugas";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal menghapus tugas";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function detailTugasHTML(string $tugasCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($tugasCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Task code is required"
                );
            } else {
                $result = $this->tugas->get_by_id($tugasCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Tugas tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Detail Data Tugas',
                        'task' => $result->task,
                        'file' => $result->file,
                    ];
                    $data['data'] = $this->load->view($this->module . '/kegiatan/detail/tugas/detail', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function verify($pesertaCode = '', $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                $keg = $result;
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    if ($pesertaCode == '') {
                        $data = array(
                            'status'         => FALSE,
                            'message'         => "Participant code is required"
                        );
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $result = $this->db->get_where('participant', [
                            'deleteAt' => NULL,
                            'activityCode' => $kegiatanCode,
                            'participantCode' => $pesertaCode
                        ])->row();
                        if ($result == NULL) {
                            $data = array(
                                'status' => FALSE,
                                'message' => "Peserta tidak ditemukan!"
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        } else {
                            $update = array(
                                'verify' => '1',
                            );
                            $up = $this->db->where(['participantCode' => $pesertaCode])->update('participant', $update);
                            $par = [
                                'broadcastCode' => NULL,
                                'type' => 'kegiatan',
                                'text' => 'Anda lulus dalam kegiatan ' . $keg->name,
                                'memberCode' => $result->memberCode,
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
                }
            }
        }
    }

    public function notVerify($pesertaCode = '', $kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                $keg = $result;
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    if ($pesertaCode == '') {
                        $data = array(
                            'status'         => FALSE,
                            'message'         => "Participant code is required"
                        );
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $result = $this->db->get_where('participant', [
                            'deleteAt' => NULL,
                            'activityCode' => $kegiatanCode,
                            'participantCode' => $pesertaCode
                        ])->row();
                        if ($result == NULL) {
                            $data = array(
                                'status' => FALSE,
                                'message' => "Peserta tidak ditemukan!"
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        } else {
                            $update = array(
                                'verify' => '0',
                            );
                            $up = $this->db->where(['participantCode' => $pesertaCode])->update('participant', $update);
                            $par = [
                                'broadcastCode' => NULL,
                                'type' => 'kegiatan',
                                'text' => 'Anda tidak lulus dalam kegiatan ' . $keg->name,
                                'memberCode' => $result->memberCode,
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
            }
        }
    }

    public function verifyAll($kegiatanCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($kegiatanCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $result = $this->kegiatan->get_by_id($kegiatanCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Kegiatan tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $update = array(
                        'verify' => '1',
                        'status' => '1'
                    );
                    $up = $this->db->where(['activityCode' => $kegiatanCode])->update('participant', $update);
                    $parti = $this->db->select('memberCode')->get_where('participant', ['memberCode !=' => NULL, 'deleteAt' => NULL, 'activityCode' => $kegiatanCode])->result_array();
                    foreach($parti as $y => $t){
                        $par = [
                            'broadcastCode' => NULL,
                            'type' => 'kegiatan',
                            'text' => 'Anda diluluskan dalam kegiatan ' . $result->name,
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
            }
        }
    }

    public function taskModal(string $activityCode = '', string $participantCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACTIVITY', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $this->load->model('ediklat/tugas_model', 'tugas');

        if ($activityCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Activity code is required"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $result = $this->db->get_where('task', [
                'deleteAt' => NULL,
                'activityCode' => $activityCode
            ])->result_array();
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Tugas tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $return = [];
                if ($result != NULL) {
                    foreach ($result as $k => $v) {
                        $temp = $v;
                        $temp['answer'] = $this->db->get_where('participant_task', [
                            'participantCode' => $participantCode,
                            'taskCode' => $v['taskCode'],
                            'deleteAt' => NULL
                        ])->result_array();
                        $return[] = $temp;
                    }
                } else {
                    $return = [];
                }
                // var_dump($return);
                // die;
                $data = [
                    'status' => TRUE,
                ];
                $params = [
                    'data' => $return,
                    'participantCode' => $participantCode,
                    'activityCode' => $activityCode
                ];
                $data['data'] = modal('taskModal', $this->load->view($this->module . '/kegiatan/detail/peserta/task_modal', $params, TRUE));
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function jawabanHTML(string $taskCode = '', string $participantCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $this->load->model('ediklat/tugas_model', 'tugas');
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');

        if ($taskCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Task code is required"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $result = $this->tugas->get_by_id($taskCode);
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Tugas tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {

                $data = [
                    'status' => TRUE,
                ];
                $params = [
                    'kegiatan' =>  $this->kegiatan->get_by_id($result->activityCode),
                    'data' => $result,
                    'answer' => $this->db->get_where('participant_task', [
                        'participantCode' => $participantCode,
                        'taskCode' => $taskCode,
                        'deleteAt' => NULL
                    ])->result_array(),
                ];
                $data['data'] = $this->load->view($this->module . '/kegiatan/detail/peserta/jawaban', $params, TRUE);
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function hapusJawaban(string $ptCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        if ($ptCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Code is required"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'deleteAt' => date('Y-m-d H:i:s')
        ];
        $up = $this->db->where('ptCode', $ptCode)->update('participant_task', $params);
        if ($up) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menghapus jawaban";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menghapus jawaban";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
