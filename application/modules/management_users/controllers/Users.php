<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MX_Controller
{
    private $module = 'management_users';

    public function __construct()
    {
        parent::__construct();
        (isLogin() == false) ? redirect('authentication/logout') : '';
    }

    public function index()
    {
        $userPermission = getPermissionFromUser();
        (!in_array('RU', $userPermission)) ? redirect('authentication/logout') : '';
        $data['bearer'] = '';
        $data['_view'] = $this->module . '/users';
        $this->load->view('layouts/back/main', $data);
    }
}
