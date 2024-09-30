<?php

class Home extends CI_Controller
{
    public function index()
    {
        $data['_view'] = 'home';
        $this->load->view('layouts/front/main', $data);
    }

    public function listActivity()
    {
        $section = ($this->input->post('section') == NULL ? 1 : $this->input->post('section'));
        $page = ($this->input->post('page') == NULL ? 1 : $this->input->post('page'));
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
            'total' => count($this->semua()),
            'section' => $section
        ];
        $data['data'] = $this->load->view('/kegiatan', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function semua($limit = '', $offset = '')
    {
        $where = [
            'deleteAt' => NULL,
            'certificateCode !=' => NULL,
        ];
        if ($this->input->post('section') != NULL) {
            if ($this->input->post('section') != '-') {
                $where['category'] = $this->input->post('section');
            }
        }

        if ($limit != '') {
            $this->db->order_by('activityCode', 'DESC')->limit($limit, $offset);
            $data = $this->db->get_where('activity', $where)->result_array();
        } else {
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

    public function cek()
    {
        $data = [
            'status' => TRUE,
        ];
        $code = $this->input->post('code');
        $code = explode('-', $code);
        if (count($code) == 3) {
            $participant = $this->db->get_where('participant', [
                'deleteAt' => NULL,
                'participantCode' => $code[0],
                'activityCode' => $code[1],
            ])->row_array();
            if ($participant == NULL) {
                $params = [
                    'status' => FALSE,
                    'message' => 'Sertifikat tidak ditemukan'
                ];
            } else {
                if (substr($participant['createAt'], 0, 4) == $code[2]) {
                    $activity = $this->db->get_where('activity', [
                        'activityCode' => $code[1],
                    ])->row_array();
                    $params = [
                        'status' => TRUE,
                        'message' => 'Sertifikat ditemukan',
                        'participant' => $participant,
                        'activity' => $activity,
                        'type' => 'KEGIATAN'
                    ];
                } else {
                    $params = [
                        'status' => FALSE,
                        'message' => 'Sertifikat tidak ditemukan'
                    ];
                }
            }
        } elseif (count($code) == 4) {
            $essay = $this->db->get_where('essay', [
                'deleteAt' => NULL,
                'essayCode' => $code[2]
            ])->row_array();
            if ($essay == NULL) {
                $params = [
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ];
            }
            $member = $this->db->get_where('member', [
                'deleteAt' => NULL,
                'memberCode' => $code[1]
            ])->row_array();
            if ($member == NULL) {
                $params = [
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ];
            }
            $essay_member = $this->db->get_where('essay_member', [
                'deleteAt' => NULL,
                'essayCode' => $code[2],
                'memberCode' => $code[1]
            ])->row_array();
            if ($essay_member == NULL) {
                $params = [
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ];
            }
            if (substr($essay['createAt'], 0, 4) == $code[3]) {
                $params = [
                    'status' => TRUE,
                    'message' => 'Sertifikat ditemukan',
                    'peserta' => $member,
                    'essay' => $essay,
                    'type' => 'ESSAY'
                ];
            } else {
                $params = [
                    'status' => FALSE,
                    'message' => 'Sertifikat tidak ditemukan'
                ];
            }
        } else {
            $params = [
                'status' => FALSE,
                'message' => 'Format kode salah'
            ];
        }
        $data['data'] = $this->load->view('/cek', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
