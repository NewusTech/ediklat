<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Logout extends MX_Controller
{
	private $module = 'authentication';
	public function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		$this->session->sess_destroy();
		redirect($this->module.'/login');
	}
}