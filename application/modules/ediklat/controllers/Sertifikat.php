<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sertifikat extends MX_Controller
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
        (!in_array('RCERTIFICATE', $userPermission)) ? redirect('authentication/logout') : '';
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
            $data['_view'] = $this->module . '/sertifikat_member';
        } else {
            $data['_view'] = $this->module . '/sertifikat';
        }
        $this->load->view('layouts/back/main', $data);
    }
}
