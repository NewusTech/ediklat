<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Index extends MX_Controller
{
    private $module = 'dashboard';
    public function __construct()
    {
        parent::__construct();
        (isLogin() == false) ? redirect('authentication/logout') : '';
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');
    }

    public function index()
    {
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "Dashboard",
                "url" => base_url('dashboard/index')
            ],
            [
                "text" => "Home",
            ]
        ], 'Home');
        if (checkRole(3)) {
            if (checkMember() == FALSE) {
                $data['breadcrumb'] = breadcrumb([
                    [
                        "text" => "Dashboard",
                        "url" => base_url('dashboard/index')
                    ],
                    [
                        "text" => "Lengkapi Data Diri",
                    ]
                ], 'Lengkapi Data Diri');
                $dataState = $this->db->get_where('state', ['deleteAt' => NULL])->result();
                $state = [];
                foreach ($dataState as $k => $v) {
                    $state[$v->stateCode] = $v->state;
                }
                $data['state'] = $state;
                $data['title'] = 'Form Data Diri';
                $data['_view'] = $this->module . '/complete_member';
                $this->load->view('layouts/back/main', $data);
            } else {
                $member = $this->db->get_where('member', [
                    'deleteAt' => NULL,
                    'userCode' => $this->session->userdata('userCode'),
                ])->row();
                if ($member->verify == '0') {
                    $data23['_view'] = $this->module . '/waiting';
                    $this->load->view('layouts/back/main', $data23);

                } else {
                    $data34['_view'] = $this->module . '/member';
                    $this->load->view('layouts/back/main', $data34);
                }
              
            }
        } elseif (checkRole(2)) {
            $data['data'] = [
                'kegiatan' => count($this->db->get_where('activity', ['deleteAt' => NULL])->result_array()),
                'member' => count($this->db->get_where('member', ['deleteAt' => NULL])->result_array()),
                'sertifikat_terbit' => count($this->db->get_where('participant', ['deleteAt' => NULL, 'verify' => '1'])->result_array()),
            ];
            $data['_view'] = $this->module . '/admin';
            $this->load->view('layouts/back/main', $data);
        } else {
            $data['data'] = [
                'kegiatan' => count($this->db->get_where('activity', ['deleteAt' => NULL])->result_array()),
                'member' => count($this->db->get_where('member', ['deleteAt' => NULL])->result_array()),
                'sertifikat_terbit' => count($this->db->get_where('participant', ['deleteAt' => NULL, 'verify' => '1'])->result_array()),
            ];
            $data['_view'] = $this->module . '/admin';
            $this->load->view('layouts/back/main', $data);
        }
        
    }

    public function dataHTML()
    {
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "Dashboard",
                "url" => base_url('dashboard/index')
            ],
            [
                "text" => "Home",
            ]
        ], 'Home');

        $data = [
            'status' => TRUE,
        ];
        $params = [
            'sedang' => count($this->sedang()),
            'telah' => count($this->telah()),
            'undang' => count($this->undang())
        ];
        $member = $this->db->get_where('member', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode'),
        ])->row();
        $pengumuman = $this->db->order_by('notifCode', 'DESC')->get_where('notif', ['memberCode' => $member->memberCode, 'type' => 'pengumuman'])->row_array();
        if ($pengumuman == NULL) {
            $params['pengumuman'] = 'Tidak ada pengumuman';
        } else {
            $params['pengumuman'] = $pengumuman['text'];
        }
        $data['data'] = $this->load->view($this->module . '/member/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function semuaHTML()
    {
        $data = [
            'status' => TRUE,
        ];
        $params = [];
        $data['data'] = $this->load->view($this->module . '/member/semua', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function semuaKegiatan($page = 1)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if (checkRole(3)) {
            $limit = 3;
            if ($page == 1) {
                $offset = 0;
            } elseif ($page > 1) {
                $offset = ($page * $limit) - ($limit);
            } else {
                $offset = 0;
            }
            $data = [
                'status' => TRUE,
            ];
            $params = [
                'data' => $this->semua($limit, $offset),
                'pageActive' => $page,
                'total' => count($this->semua())
            ];
            $data['data'] = $this->load->view($this->module . '/member/semua_kegiatan', $params, TRUE);
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    private function semua($limit = '', $offset = '')
    {
        $where = [
            'deleteAt' => NULL,
            'certificateCode !=' => NULL,
            'type' => 'general'
        ];
        if ($this->input->post('status') != NULL) {
            $where['status'] = $this->input->post('status');
        }

        if ($this->input->post('media') != NULL) {
            $where['media'] = $this->input->post('media');
        }

        if ($this->input->post('category') != NULL) {
            $where['category'] = $this->input->post('category');
        }
        if (checkRole(3)) {
            $user = $this->db->get_where('member', [
                'deleteAt' => NULL,
                'userCode' => $this->session->userdata('userCode'),
            ])->row_array();
            $sudah = $this->db->get_where(
                'participant',
                [
                    'deleteAt' => NULL,
                    'memberCode' => $user['memberCode']
                ]
            )->result_array();

            $checkSudah = [];
            if ($sudah != NULL) {
                $checkSudah = array_values(array_column($sudah, 'activityCode'));
            }
        }
        if ($limit != '') {
            if (checkRole(3)) {
                if ($checkSudah != NULL) {
                    $this->db->where_not_in('activityCode', $checkSudah);
                }
            }
            $this->db->order_by('activityCode', 'DESC')->limit($limit, $offset);
            $data = $this->db->get_where('activity', $where)->result_array();
        } else {
            if (checkRole(3)) {
                if ($checkSudah != NULL) {
                    $this->db->where_not_in('activityCode', $checkSudah);
                }
            }
            $this->db->order_by('activityCode', 'DESC');
            $data = $this->db->get_where('activity', $where)->result_array();
        }

        $result = [];
        foreach ($data as $k => $v) {
            $activity = $v;
            $participant = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'deleteAt' => NULL])->result_array();
            $activity['jumlahPeserta'] = count($participant);
            $sertifikat = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'status' => '1', 'deleteAt' => NULL])->result_array();
            $activity['jumlahSertifikat'] = count($sertifikat);
            $result[] = $activity;
        }
        return $result;
    }

    public function sedangHTML()
    {
        $data = [
            'status' => TRUE,
        ];
        $params = [];
        $data['data'] = $this->load->view($this->module . '/member/sedang', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function sedangKegiatan($page = 1)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if (checkRole(3)) {
            $limit = 3;
            if ($page == 1) {
                $offset = 0;
            } elseif ($page > 1) {
                $offset = ($page * $limit) - ($limit);
            } else {
                $offset = 0;
            }
            $data = [
                'status' => TRUE,
            ];
            $params = [
                'data' => $this->sedang($limit, $offset),
                'pageActive' => $page,
                'total' => count($this->sedang())
            ];
            $data['data'] = $this->load->view($this->module . '/member/sedang_kegiatan', $params, TRUE);
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    private function sedang($limit = '', $offset = '')
    {
        $where = [
            'deleteAt' => NULL,
            'certificateCode !=' => NULL
        ];
        if ($this->input->post('status') != NULL) {
            $where['status'] = $this->input->post('status');
        }

        if ($this->input->post('media') != NULL) {
            $where['media'] = $this->input->post('media');
        }

        if ($this->input->post('category') != NULL) {
            $where['category'] = $this->input->post('category');
        }
        if (checkRole(3)) {
            $user = $this->db->get_where('member', [
                'deleteAt' => NULL,
                'userCode' => $this->session->userdata('userCode'),
            ])->row_array();
            $sedang = $this->db
                ->select('activity.activityCode, activity.status')
                ->join('activity', 'activity.activityCode=participant.activityCode')
                ->get_where(
                    'participant',
                    [
                        'participant.deleteAt' => NULL,
                        'participant.memberCode' => $user['memberCode'],
                        'activity.status' => 'open',
                        'activity.type' => 'general',
                    ]
                )->result_array();
            $sedangTertutup = $this->db
                ->select('activity.activityCode, activity.status')
                ->join('activity', 'activity.activityCode=participant.activityCode')
                ->get_where(
                    'participant',
                    [
                        'participant.deleteAt' => NULL,
                        'participant.memberCode' => $user['memberCode'],
                        'activity.type' => 'special',
                        'participant.accept' => '1',
                        'activity.status' => 'open',
                        'participant.invite' => '1',
                    ]
                )->result_array();
            $checkSudah = [];
            if ($sedang != NULL) {
                $checkSudah = array_values(array_column($sedang, 'activityCode'));
            }
            if ($sedangTertutup != NULL) {
                foreach ($sedangTertutup as $k => $v) {
                    if (!in_array($v['activityCode'], $checkSudah)) {
                        array_push($checkSudah, $v['activityCode']);
                    }
                }
            }
        }

        if ($limit != '') {
            if (checkRole(3)) {
                if ($checkSudah != NULL) {
                    $this->db->where_in('activityCode', $checkSudah);
                } else {
                    return [];
                }
            }
            $this->db->order_by('activityCode', 'DESC')->limit($limit, $offset);
            $data = $this->db->get_where('activity', $where)->result_array();
        } else {
            if (checkRole(3)) {
                if ($checkSudah != NULL) {
                    $this->db->where_in('activityCode', $checkSudah);
                } else {
                    return [];
                }
            }
            $this->db->order_by('activityCode', 'DESC');
            $data = $this->db->get_where('activity', $where)->result_array();
        }

        $result = [];
        foreach ($data as $k => $v) {
            $activity = $v;
            $participant = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'deleteAt' => NULL])->result_array();
            $activity['jumlahPeserta'] = count($participant);
            $sertifikat = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'status' => '1', 'deleteAt' => NULL])->result_array();
            $activity['jumlahSertifikat'] = count($sertifikat);
            $result[] = $activity;
        }
        return $result;
    }

    public function undangHTML()
    {
        $data = [
            'status' => TRUE,
        ];
        $params = [];
        $data['data'] = $this->load->view($this->module . '/member/undang', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function undangKegiatan($page = 1)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if (checkRole(3)) {
            $limit = 3;
            if ($page == 1) {
                $offset = 0;
            } elseif ($page > 1) {
                $offset = ($page * $limit) - ($limit);
            } else {
                $offset = 0;
            }
            $data = [
                'status' => TRUE,
            ];
            // var_dump($this->undang($limit, $offset));
            // die;
            $params = [
                'data' => $this->undang($limit, $offset),
                'pageActive' => $page,
                'total' => count($this->undang())
            ];
            $data['data'] = $this->load->view($this->module . '/member/undang_kegiatan', $params, TRUE);
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    private function undang($limit = '', $offset = '')
    {
        $where = [
            'deleteAt' => NULL,
            // 'status' => 'open',
            'certificateCode !=' => NULL
        ];
        if ($this->input->post('status') != NULL) {
            $where['status'] = $this->input->post('status');
        }

        if ($this->input->post('media') != NULL) {
            $where['media'] = $this->input->post('media');
        }

        if ($this->input->post('category') != NULL) {
            $where['category'] = $this->input->post('category');
        }
        if (checkRole(3)) {
            $user = $this->db->get_where('member', [
                'deleteAt' => NULL,
                'userCode' => $this->session->userdata('userCode'),
            ])->row_array();
            $undang = $this->db
                ->select('activity.activityCode, activity.status')
                ->join('activity', 'activity.activityCode=participant.activityCode')
                ->get_where(
                    'participant',
                    [
                        'participant.deleteAt' => NULL,
                        'participant.memberCode' => $user['memberCode'],
                        'activity.type' => 'special',
                        'participant.invite' => '1',
                    ]
                )->result_array();
            $checkSudah = [];
            if ($undang != NULL) {
                foreach ($undang as $k => $v) {
                    if (!in_array($v['activityCode'], $checkSudah)) {
                        array_push($checkSudah, $v['activityCode']);
                    }
                }
            }
        }

        if ($limit != '') {
            if (checkRole(3)) {
                if ($checkSudah != NULL) {
                    $this->db->where_in('activityCode', $checkSudah);
                } else {
                    return [];
                }
            }
            $this->db->order_by('activityCode', 'DESC')->limit($limit, $offset);
            $data = $this->db->get_where('activity', $where)->result_array();
        } else {
            if (checkRole(3)) {
                if ($checkSudah != NULL) {
                    $this->db->where_in('activityCode', $checkSudah);
                } else {
                    return [];
                }
            }
            $this->db->order_by('activityCode', 'DESC');
            $data = $this->db->get_where('activity', $where)->result_array();
        }

        $result = [];
        foreach ($data as $k => $v) {
            $activity = $v;
            $participant = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'deleteAt' => NULL])->result_array();
            $activity['jumlahPeserta'] = count($participant);
            $sertifikat = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'status' => '1', 'deleteAt' => NULL])->result_array();
            $activity['jumlahSertifikat'] = count($sertifikat);
            $activity['self'] = $this->db->select('participantCode,accept')->get_where('participant', ['activityCode' => $v['activityCode'], 'memberCode' => $user['memberCode'], 'deleteAt' => NULL])->row_array();
            $result[] = $activity;
        }
        return $result;
    }

    public function telahHTML()
    {
        $data = [
            'status' => TRUE,
        ];
        $params = [];
        $data['data'] = $this->load->view($this->module . '/member/telah', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function telahKegiatan($page = 1)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if (checkRole(3)) {
            $limit = 4;
            if ($page == 1) {
                $offset = 0;
            } elseif ($page > 1) {
                $offset = ($page * $limit) - ($limit);
            } else {
                $offset = 0;
            }
            $data = [
                'status' => TRUE,
            ];
            $params = [
                'data' => $this->telah($limit, $offset),
                'pageActive' => $page,
                'total' => count($this->telah())
            ];
            $data['data'] = $this->load->view($this->module . '/member/telah_kegiatan', $params, TRUE);
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    private function telah($limit = '', $offset = '')
    {
        $where = [
            'deleteAt' => NULL,
            'certificateCode !=' => NULL,
            // 'type' => 'general'
        ];
        if ($this->input->post('status') != NULL) {
            $where['status'] = $this->input->post('status');
        }

        if ($this->input->post('media') != NULL) {
            $where['media'] = $this->input->post('media');
        }

        if ($this->input->post('category') != NULL) {
            $where['category'] = $this->input->post('category');
        }
        if (checkRole(3)) {
            $user = $this->db->get_where('member', [
                'deleteAt' => NULL,
                'userCode' => $this->session->userdata('userCode'),
            ])->row_array();
            $telah = $this->db->select('activity.activityCode, activity.status')
                ->join('activity', 'activity.activityCode=participant.activityCode')
                ->get_where(
                    'participant',
                    [
                        'participant.deleteAt' => NULL,
                        'participant.memberCode' => $user['memberCode'],
                        'activity.status' => 'close'
                    ]
                )->result_array();

            $checkSudah = [];
            if ($telah != NULL) {
                $checkSudah = array_values(array_column($telah, 'activityCode'));
            }
        }
        if ($limit != '') {
            if (checkRole(3)) {
                if ($checkSudah != NULL) {
                    $this->db->where_in('activityCode', $checkSudah);
                } else {
                    return [];
                }
            }
            $this->db->order_by('activityCode', 'DESC')->limit($limit, $offset);
            $data = $this->db->get_where('activity', $where)->result_array();
        } else {
            if (checkRole(3)) {
                if ($checkSudah != NULL) {
                    $this->db->where_in('activityCode', $checkSudah);
                } else {
                    return [];
                }
            }
            $this->db->order_by('activityCode', 'DESC');
            $data = $this->db->get_where('activity', $where)->result_array();
        }

        $result = [];
        foreach ($data as $k => $v) {
            $activity = $v;
            $participant = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'deleteAt' => NULL])->result_array();
            $activity['jumlahPeserta'] = count($participant);
            $sertifikat = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'status' => '1', 'deleteAt' => NULL])->result_array();
            $activity['jumlahSertifikat'] = count($sertifikat);
            $result[] = $activity;
        }
        return $result;
    }

    public function addMember()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
        $this->form_validation->set_rules('name', 'nama', 'trim|required');
        $this->form_validation->set_rules('phone', 'no handphone', 'trim|required');
        $this->form_validation->set_rules('address', 'alamat', 'trim|required');
        $this->form_validation->set_rules('npwp', 'NPWP', 'trim|required');
        $this->form_validation->set_rules('npsn', 'NUPTK', 'trim|required');
        $this->form_validation->set_rules('agency', 'instansi', 'trim|required');
        $this->form_validation->set_rules('rank_dinas', 'jabatan dalam dinas', 'required');
        $this->form_validation->set_rules('rank', 'pangkat/golongan', 'trim|required');
        $this->form_validation->set_rules('gender', 'jenis kelamin', 'trim|required');
        $this->form_validation->set_rules('education', 'pendidikan terakhir', 'trim|required');
        $this->form_validation->set_rules('education_service', 'jenis layanan pendidikan', 'trim|required');
        $this->form_validation->set_rules('birthplace', 'tempat lahir', 'trim|required');
        $this->form_validation->set_rules('birthdate', 'tanggal lahir', 'trim|required');
        $this->form_validation->set_rules('stateCode', 'kabupaten/kota', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'name' => form_error('name'),
                'nik' => form_error('nik'),
                'phone' => form_error('phone'),
                'address' => form_error('address'),
                'npwp' => form_error('npwp'),
                'npsn' => form_error('npsn'),
                'agency' => form_error('agency'),
                'rank' => form_error('rank'),
                'rank_dinas' => form_error('rank_dinas'),
                'gender' => form_error('gender'),
                'education' => form_error('education'),
                'education_service' => form_error('education_service'),
                'birthplace' => form_error('birthplace'),
                'birthdate' => form_error('birthdate'),
                'stateCode' => form_error('stateCode'),
            );
            $data = array(
                'status'         => FALSE,
                'errors'         => $errors
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $checkDouble = $this->db->get_where('member',[
                'deleteAt' => NULL,
                'userCode' => $this->session->userdata('userCode')
            ])->row_array();
            if ($checkDouble) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => 'Data anda sudah ada sebelumnya'
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            $insert = array(
                'name' => $this->input->post('name'),
                'nik' => $this->input->post('nik'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'npwp' => $this->input->post('npwp'),
                'npsn' => $this->input->post('npsn'),
                'agency' => $this->input->post('agency'),
                'rank' => $this->input->post('rank'),
                'rank_dinas' => $this->input->post('rank_dinas'),
                'gender' => $this->input->post('gender'),
                'education' => $this->input->post('education'),
                'education_service' => $this->input->post('education_service'),
                'birthplace' => $this->input->post('birthplace'),
                'birthdate' => $this->input->post('birthdate'),
                'stateCode' => $this->input->post('stateCode'),
                'userCode' => $this->session->userdata('userCode'),
                'verify' => '1',
            );
            if (!isset($_FILES['picture'])) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => 'Foto harus diisi'
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
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
                $insert['picture'] = $uploaded_data['file_name'];
            }
            $insert = $this->db->insert('member', $insert);
            if ($insert) {
                $data['status'] = TRUE;
                $data['message'] = "Berhasil melengkapi data member";
            } else {
                $data['status'] = FALSE;
                $data['message'] = "Gagal melengkapi data member";
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function daftar($activityCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $data['status'] = TRUE;
        if ($activityCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Activity code is required"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $activity = $this->kegiatan->get_by_id($activityCode);
            if ($activity == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Kegiatan tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                if (checkRole(3)) {
                    if (checkMember() == FALSE) {
                        $data = array(
                            'status'         => FALSE,
                            'message'         => "Harap lengkapi data terlebih dahulu!"
                        );
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $member = $this->db->get_where('member', [
                            'deleteAt' => NULL,
                            'userCode' => $this->session->userdata('userCode'),
                        ])->row_array();

                        $participant = $this->db->select('participantCode')->get_where('participant', [
                            'deleteAt' => NULL,
                            'activityCode' => $activity->activityCode
                        ])->result_array();

                        $check = $this->db->select('participantCode')->get_where('participant', [
                            'deleteAt' => NULL,
                            'activityCode' => $activity->activityCode,
                            'memberCode' => $member['memberCode']
                        ])->result_array();
                        if ($check != NULL) {
                            $data = array(
                                'status'         => FALSE,
                                'message'         => "Anda telah mendaftar sebelumnya!"
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        }
                        $totalParticipant = count($participant);
                        if ($totalParticipant >= $activity->kuota) {
                            $data = array(
                                'status'         => FALSE,
                                'message'         => "Kuota habis!"
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        }
                        if ($totalParticipant == $activity->kuota) {
                            $this->db->where('activityCode', $activityCode)->update('activity', [
                                'status' => 'close'
                            ]);
                        }
                        $betweenDate = getBetweenDates($activity->startDate,$activity->endDate);
                        $temp = [];
                        foreach($betweenDate as $t => $g){
                            $temp[$g] = '0';
                        }
                        $insert = [
                            'activityCode' => $activityCode,
                            'memberCode' => $member['memberCode'],
                            'name' => $member['name'],
                            'nik' => $member['nik'],
                            'npsn' => $member['npsn'],
                            'phone' => $member['phone'],
                            'address' => $member['address'],
                            'npwp' => $member['npwp'],
                            'agency' => $member['agency'],
                            'rank' => $member['rank'],
                            'rank_dinas' => $member['rank_dinas'],
                            'gender' => $member['gender'],
                            'education' => $member['education'],
                            'education_service' => $member['education_service'],
                            'birthplace' => $member['birthplace'],
                            'birthdate' => $member['birthdate'],
                            'stateCode' => $member['stateCode'],
                            'picture' => $member['picture'],
                            'status' => '0',
                            'statusDetail' => json_encode($temp,TRUE),
                            'verify' => '2'
                        ];
                        $in = $this->db->insert('participant', $insert);
                        if ($in) {
                            $data['status'] = TRUE;
                            $data['message'] = "Berhasil mendaftar";
                        } else {
                            $data['status'] = FALSE;
                            $data['message'] = "Gagal mendaftar";
                        }
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                } else {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Silahkan logout terlebih dahulu,anda buakn di role member!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function absen(string $kegiatanCode = '')
    {
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');
        $this->load->model('ediklat/peserta_model', 'peserta');

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
                if ($result->attendance == 'close') {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Pencatatan kehadiran belum dibuka"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                if (checkRole(3)) {
                    if (checkMember() == FALSE) {
                        $data = array(
                            'status'         => FALSE,
                            'message'         => "Harap lengkapi data terlebih dahulu!"
                        );
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $member = $this->db->get_where('member', [
                            'deleteAt' => NULL,
                            'userCode' => $this->session->userdata('userCode'),
                        ])->row_array();

                        $peserta = $this->db->get_where('participant', ['deleteAt' => NULL, 'activityCode' => $result->activityCode, 'memberCode' => $member['memberCode']])->row_array();
                        if ($peserta == NULL) {
                            $data = array(
                                'status'         => FALSE,
                                'message'         => "Data tidak ditemukan!"
                            );
                            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                        }
                        $semuaAbsen = true;
                        $absenData = json_decode($peserta['statusDetail'],TRUE);
                        $newAbsen = [];
                        foreach($absenData as $t => $g){
                            if($t == date('Y-m-d')){
                                if ($g == '1') {
                                    $data = array(
                                        'status'         => FALSE,
                                        'message'         => "Anda sudah melakukan pencatatan kehadiran hari ini"
                                    );
                                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                                }else{
                                    $newAbsen[$t] = '1';
                                }
                            }else{
                                $newAbsen[$t] = $g;
                            }
                            if($g == '0'){
                                $semuaAbsen = false;
                            }
                        }
                        $params = [
                            'statusDetail' => json_encode($newAbsen,TRUE)    
                        ];
                        if($semuaAbsen == true){
                            $params['status'] = 1;
                        }else{
                            $params['status'] = 0;
                        }
                        
                        $up = $this->db->where('participantCode', $peserta['participantCode'])->update('participant', $params);
                        if ($up) {
                            $data['status'] = TRUE;
                            $data['message'] = "Berhasil melakukan pencatatan kehadiran hari ini";
                        } else {
                            $data['status'] = FALSE;
                            $data['message'] = "Gagal melakukan pencatatan kehadiran hari ini";
                        }
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                } else {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Silahkan logout terlebih dahulu,anda buakn di role member!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function detail($nameActivity = '')
    {
        if ($nameActivity == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Kegiatan tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $nameActivity = urldecode($nameActivity);
        $activity = $this->db->get_where('activity', ['deleteAt' => NULL, 'name' => $nameActivity])->row_array();
        if ($activity == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Kegiatan tidak ditemukan!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $participant = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $activity['activityCode'], 'deleteAt' => NULL])->result_array();
        $activity['jumlahPeserta'] = count($participant);
        $sertifikat = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $activity['activityCode'], 'status' => '1', 'deleteAt' => NULL])->result_array();
        $activity['jumlahSertifikat'] = count($sertifikat);
        $params['activity'] = $activity;

        if (checkRole(3)) {
            $user = $this->db->get_where('member', [
                'deleteAt' => NULL,
                'userCode' => $this->session->userdata('userCode'),
            ])->row_array();
            $telah = $this->db->select('*')
                ->get_where(
                    'participant',
                    [
                        'participant.deleteAt' => NULL,
                        'participant.memberCode' => $user['memberCode'],
                        'participant.activityCode' => $activity['activityCode']
                    ]
                )->row_array();

            if ($telah != NULL) {
                $params['participant'] = $telah;
            } else {
                $params['participant'] = [];
            }
        } else {
            $params['participant'] = [];
        }

        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "Dashboard",
                "url" => base_url('dashboard/index')
            ],
            [
                "text" => "Detail Kegiatan",
            ]
        ], 'Detail Kegiatan');
        $data = [
            'status' => TRUE,
        ];
        $data['data'] = $this->load->view($this->module . '/detail', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function peserta()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
            die();
        }
        $params = [];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/peserta', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function pesertaList(string $kegiatanCode = '')
    {
        $this->load->model('ediklat/peserta_model', 'peserta');
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');
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
                                    <p class="text-xs fw-semibold d-flex py-auto my-auto">' . $v->name . '</p>
                                    <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                                </div>
                            </div>';
                    // $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($v->status == '1' ? 'Hadir' : 'Tidak Hadir') . '</p>';
                    $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($v->verify == '1' ? 'Lulus' : ($v->verify == '2' ? 'Dicek' : 'Tidak Lulus')) . '</p>';


                    $row[] = "
                            <div class='d-flex justify-content-center'>
                                " . ($v->status == '1' && $v->verify == '1' ? "<a href='" . base_url('kegiatan/downloadSertifikat/' . base64_encode($v->participantCode . '-' . $kegiatanCode)) . "' class='text-primary'><i class='ri-download-line' role='button' title='Download'></i></a>" : "") . "
                            </div>
                            ";

                    $data[] = $row;
                }
                $output = array(
                    "draw" => @$_POST['draw'],
                    "recordsTotal" => $this->peserta->count_all($kegiatanCode),
                    "recordsFiltered" => $this->peserta->count_filtered($kegiatanCode),
                    "data" => $data,
                );
                echo json_encode($output);
            }
        }
    }

    public function materi()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
            die();
        }
        $params = [];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/materi', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function materiList(string $kegiatanCode = '')
    {
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');
        $this->load->model('ediklat/materi_model', 'materi');

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

                    $row[] = '<p class="text-xs text-bold d-flex py-auto my-auto">' . $result->name . '</p>';
                    $row[] = '<p class="text-xs text-bold d-flex py-auto my-auto">' . $result->description . '</p>';
                    $row[] = '
                        <div class="d-flex justify-content-center">
                            <a href="' . base_url('kegiatan/downloadMateri/' . base64_encode($result->file)) . '" class="text-primary"><i class="ri-download-line" role="button" title="Download"></i></a>
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
                echo json_encode($output);
            }
        }
    }

    public function tugas()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
            die();
        }
        $params = [];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view($this->module . '/tugas', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function tugasList(string $kegiatanCode = '')
    {
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');
        $this->load->model('ediklat/tugas_model', 'tugas');

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
                foreach ($list as $result) {
                    $row = array();

                    $row[] = '<p class="text-xs text-bold d-flex py-auto my-auto">' . $result->task . '</p>';
                    $row[] = '
                        <div class="d-flex justify-content-center gap-2">
                            <a href="javascript:void(0)" class="text-primary"><i class="ri-upload-line" role="button" onclick="uploadModal(\'' . $result->taskCode . '\')" title="Upload"></i></a>
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
                echo json_encode($output);
            }
        }
    }


    public function uploadModal(string $taskCode = '')
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

        if ($taskCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Activity code is required"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $result = $this->tugas->get_by_id($taskCode);
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Kegiatan tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $member = $this->db->get_where('member', [
                    'deleteAt' => NULL,
                    'userCode' => $this->session->userdata('userCode'),
                ])->row_array();
                $participant = $this->db->get_where('participant', [
                    'deleteAt' => NULL,
                    'memberCode' => $member['memberCode'],
                    'activityCode' => $result->activityCode
                ])->row_array();
                if ($participant['status'] == 0) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Anda harus absen terlebih dahulu!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                $data = [
                    'status' => TRUE,
                ];
                $params = [
                    'data' => $result,
                    'answer' => $this->db->get_where('participant_task', [
                        'participantCode' => $participant['participantCode'],
                        'taskCode' => $taskCode,
                    ])->result_array(),
                ];
                $data['data'] = modal('uploadModal', $this->load->view($this->module . '/upload_modal', $params, TRUE));
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function jawabanHTML(string $taskCode = '')
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
                $member = $this->db->get_where('member', [
                    'deleteAt' => NULL,
                    'userCode' => $this->session->userdata('userCode'),
                ])->row_array();
                $participant = $this->db->get_where('participant', [
                    'deleteAt' => NULL,
                    'memberCode' => $member['memberCode'],
                    'activityCode' => $result->activityCode
                ])->row_array();
                if ($participant['status'] == 0) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Anda harus absen terlebih dahulu!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                $data = [
                    'status' => TRUE,
                ];
                $params = [
                    'kegiatan' =>  $this->kegiatan->get_by_id($result->activityCode),
                    'data' => $result,
                    'answer' => $this->db->get_where('participant_task', [
                        'participantCode' => $participant['participantCode'],
                        'taskCode' => $taskCode,
                        'deleteAt' => NULL
                    ])->result_array(),
                ];
                $data['data'] = $this->load->view($this->module . '/jawaban', $params, TRUE);
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

    public function simpanJawaban(string $taskCode = '')
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

        if ($taskCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Activity code is required"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $result = $this->tugas->get_by_id($taskCode);
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Kegiatan tidak ditemukan!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $member = $this->db->get_where('member', [
                    'deleteAt' => NULL,
                    'userCode' => $this->session->userdata('userCode'),
                ])->row_array();
                $participant = $this->db->get_where('participant', [
                    'deleteAt' => NULL,
                    'memberCode' => $member['memberCode'],
                    'activityCode' => $result->activityCode
                ])->row_array();
                if ($participant['status'] == 0) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Anda harus absen terlebih dahulu!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                $data = [
                    'status' => TRUE,
                ];
                $this->form_validation->set_rules('answer', 'jawaban', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $errors = array(
                        'answer' => form_error('answer'),
                    );
                    $data = array(
                        'status'         => FALSE,
                        'errors'         => $errors
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $insert = [
                        'participantCode' => $participant['participantCode'],
                        'taskCode' => $taskCode,
                        'answer' => $this->input->post('answer')
                    ];
                    if (isset($_FILES['file']) && $_FILES['file']['name'] != NULL) {
                        $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                        $config['upload_path']          = FCPATH . '/assets/img/answer/';
                        $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf';
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
                    }
                    $insert = $this->db->insert('participant_task', $insert);
                    if ($insert) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil mengupload jawaban";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal mengupload jawaban";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function terima(string $participantCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $check = $this->db->get_where('participant', [
            'participantCode' => $participantCode,
            'deleteAt' => NULL
        ])->row_array();
        if ($check == NULL) {
            $data = array(
                'status'         => FALSE,
                'message'         => "Data anda tidak ditemukan"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $params = [
            'accept' => '1'
        ];
        $update = $this->db->where('participantCode', $participantCode)->update('participant', $params);
        if ($update) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menerima undangan";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menerima undangan";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function tolak(string $participantCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RDASH', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $params = [
            'accept' => '0'
        ];
        $update = $this->db->where('participantCode', $participantCode)->update('participant', $params);
        if ($update) {
            $data['status'] = TRUE;
            $data['message'] = "Berhasil menolak undangan";
        } else {
            $data['status'] = FALSE;
            $data['message'] = "Gagal menolak undangan";
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    public function baca()
    {
        foreach (getNotif() as $k => $v) {
            $this->db->where([
                'notifCode' => $v['notifCode']
            ])->update('notif', ['isRead' => 1]);
        }
        $data['status'] = TRUE;
        $data['message'] = "Berhasil menolak undangan";
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
