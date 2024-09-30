<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends MX_Controller
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
        (!in_array('RMEMBER', $userPermission)) ? redirect('authentication/logout') : '';
        
        $data['_view'] = $this->module . '/member';
        $this->load->view('layouts/back/main', $data);
    }
}
