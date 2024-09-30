<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Broadcast extends MX_Controller
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
        (!in_array('RBROADCAST', $userPermission)) ? redirect('authentication/logout') : '';

        $data['_view'] = $this->module . '/broadcast';
        $this->load->view('layouts/back/main', $data);
    }

    public function add()
    {
        $userPermission = getPermissionFromUser();
        (!in_array('CBROADCAST', $userPermission)) ? redirect('authentication/logout') : '';
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/broadcast')
            ],
            [
                "text" => "Broadcast",
                "url" => base_url('ediklat/broadcast')
            ],
            [
                "text" => "Tambah",
            ]
        ], 'Tambah');
        $data['title'] = 'Tambah broadcast';
        $dataActivity = $this->db->get_where('activity', ['deleteAt' => NULL])->result();
        $activity = [];
        foreach ($dataActivity as $k => $v) {
            $activity[$v->activityCode] = $v->name;
        }
        $data['activity'] = $activity;
        $this->form_validation->set_rules('text', 'text', 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['_view'] = $this->module . '/broadcast/add';
            $this->load->view('layouts/back/main', $data);
        } else {
            $participant = $this->db->get_where('participant', ['deleteAt' => NULL, 'memberCode != ' => NULL, 'activityCode' => $this->input->post('activityCode')])->result_array();
            $unik = uniqid();
            $params = [
                'type' => 'pengumuman',
                'broadcastCode' => $unik,
                'text' => $this->input->post('text')
            ];
            //var_dump($participant);
            //die;
            foreach ($participant as $k => $v) {
                $params['memberCode'] = $v['memberCode'];
                $this->db->insert('notif', $params);
            }
            redirect('ediklat/broadcast');
        }
    }

    public function delete(string $broadcastCode = '')
    {
        $this->db->delete('notif', ['broadcastCode' => $broadcastCode]);
        redirect('ediklat/broadcast');
    }
}
