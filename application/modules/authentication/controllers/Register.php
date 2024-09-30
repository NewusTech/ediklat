<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Register extends MX_Controller
{
	private $module = 'authentication';
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view($this->module . '/register');
	}

	public function act_register()
	{
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() === TRUE) {
			$data = $this->db->get_where('user', ['deleteAt' => NULL, 'isActive' => 1, 'email' => $this->input->post('email')])->row_array();
			if ($data != NULL) {
				$this->session->set_flashdata('emailErr', 'email sudah digunakan');
				redirect('authentication/register');
			} else {
				$user = [
					'email' => $this->input->post('email'),
					'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'isActive' => '1',
					'status' => 'Public'
				];
				$insert = $this->db->insert('user', $user);
				$userCode = $this->db->insert_id();
				if ($insert) {
					$role = [
						'userCode' => $userCode,
						'roleCode' => '3'
					];
					$insert_role = $this->db->insert('role_user', $role);
					if ($insert_role) {
						$this->session->set_userdata([
							'userCode' => $userCode
						]);
						redirect('dashboard/index');
					} else {
						$delete_user = $this->db->where('userCode', $userCode)->delete('user');
						$this->session->set_flashdata('err', 'Gagal membuat akun baru');
						redirect('authentication/login');
					}
				} else {
					$this->session->set_flashdata('err', 'Gagal membuat akun baru');
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
