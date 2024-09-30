<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaduan extends MX_Controller
{
    private $module = 'ediklat';

    private $validation_for = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model($this->module . '/pengaduan_model', 'pengaduan');
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
        if (!in_array('RPENGADUAN', $userPermission)) {
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
                "url" => base_url('ediklat/pengaduan')
            ],
            [
                "text" => "Pengaduan",
            ]
        ], 'Data Pengaduan');
        $data['data'] = $this->load->view($this->module . '/pengaduan/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function list()
    {
        $userPermission = getPermissionFromUser();
        $list = $this->pengaduan->get_datatables();
        $data = array();
        foreach ($list as $v) {
            $row = array();
            $row[] = '
                <div class="d-flex px-2 py-1 gap-2">
                    <div class="d-flex flex-column justify-content-center">
                        <p class="text-xs text-bold d-flex py-auto my-auto">' . getOneValue('member', ['deleteAt' => NULL, 'memberCode' => $v->memberCode], 'name') . '</p>
                        <p class="text-xs d-flex py-auto my-auto">' . getOneValue('member', ['deleteAt' => NULL, 'memberCode' => $v->memberCode], 'agency') . '</p>
                        <p class="text-xs d-flex py-auto my-auto">' . tgl_hari($v->createAt) . '</p>
                    </div>
                </div>';

            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . word_wrap($v->pengaduan, 50) . '</p>';
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->pengaduan->count_all(),
            "recordsFiltered" => $this->pengaduan->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function add()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CPENGADUAN', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'add';
            $data = array();
            $data['status'] = TRUE;

            $this->form_validation->set_rules('pengaduan', 'pesan pengaduan', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'pengaduan' => form_error('pengaduan'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $member = $this->db->get_where('member', [
                    'deleteAt' => NULL,
                    'userCode' => $this->session->userdata('userCode'),
                ])->row_array();
                $insert = array(
                    'pengaduan' => $this->input->post('pengaduan'),
                    'memberCode' => $member['memberCode']
                );

                $insert = $this->pengaduan->save($insert);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil menambah pengaduan";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal menambah pengaduan";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }
}
