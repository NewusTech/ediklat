<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Broadcast extends MX_Controller
{
    private $module = 'ediklat';

    private $validation_for = '';

    public function __construct()
    {
        parent::__construct();
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
        if (!in_array('RBROADCAST', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission,
            'broadcast' => $this->db
                ->distinct('broadcastCode')
                ->select('broadcastCode,text')
                ->where(['broadcastCode !=' => NULL, 'type' => 'pengumuman'])
                ->get('notif')
                ->result_array()
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/broadcast')
            ],
            [
                "text" => "Broadcast",
            ]
        ], 'Data Broadcast');
        $data['data'] = $this->load->view($this->module . '/broadcast/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}
