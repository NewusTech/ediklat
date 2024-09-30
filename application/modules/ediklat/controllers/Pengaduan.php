<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaduan extends MX_Controller
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
        (!in_array('RPENGADUAN', $userPermission)) ? redirect('authentication/logout') : '';
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
                    "url" => base_url('ediklat/pengaduan')
                ],
                [
                    "text" => "Pengaduan",
                ]
            ], 'Form Pengaduan');
            $data['_view'] = $this->module . '/pengaduan/member/index';
        } else {
            $data['_view'] = $this->module . '/pengaduan';
        }
        $this->load->view('layouts/back/main', $data);
    }
}
