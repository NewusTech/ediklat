<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Login extends MX_Controller
{
	private $module = 'authentication';
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view($this->module . '/login');
	}

	public function act_login()
	{
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() === TRUE) {
			$data = $this->db->get_where('user', ['deleteAt' => NULL, 'isActive' => 1, 'email' => $this->input->post('email')])->row_array();
			if ($data == NULL) {
				$this->session->set_flashdata('emailErr', 'email tidak ditemukan');
				redirect('authentication/login');
			} else {
				if (password_verify($this->input->post('password'), $data['password'])) {
					$this->session->set_userdata([
						'userCode' => $data['userCode']
					]);
					redirect('dashboard/index');
				} else {
					$this->session->set_flashdata('passwordErr', 'password salah');
					redirect('authentication/login');
				}
			}
		} else {
			redirect('authentication/login');
		}
	}

	// function inPermission()
	// {
	// 	$params = [];
	// 	$permission = $this->db->get_where('permission', ['deleteAt' =>  NULL])->result_array();
	// 	foreach ($permission as $k => $v) {
	// 		$params[] = [
	// 			'permissionCode' => $v['permissionCode'],
	// 			'roleCode' => '1'
	// 		];
	// 	}
	// 	$this->db->insert_batch('role_permission', $params);
	// }
}
