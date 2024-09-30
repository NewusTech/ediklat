<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH.'/libraries/PHPMailer/src/Exception.php';
require APPPATH.'/libraries/PHPMailer/src/PHPMailer.php';
require APPPATH.'/libraries/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class Forgot extends MX_Controller
{
	private $module = 'authentication';
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view($this->module . '/forgot');
	}

	public function act_forgot()
	{
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->form_validation->run() === TRUE) {
			$data = $this->db->get_where('user', ['deleteAt' => NULL, 'isActive' => 1, 'email' => $this->input->post('email')])->row_array();
			if ($data == NULL) {
				$this->session->set_flashdata('emailErr', 'email tidak ditemukan');
				redirect('authentication/forgot');
			} else {
			    $generator = "1357902468ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                $newPass = "";
                for ($i = 1; $i <= 8; $i++) {
                    $newPass .= substr($generator, (rand() % (strlen($generator))), 1);
                }
                // var_dump($this->input->post('email'));
                // die;
                $this->db->where(['deleteAt' => NULL, 'isActive' => 1, 'email' => $this->input->post('email')])->update('user',[
                    'password' => password_hash($newPass, PASSWORD_DEFAULT)
                ]);
                
                
				$mail = new PHPMailer();
                $mail->isSMTP();
                $mail->SMTPDebug = 3;
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = '587';
                $mail->SMTPAuth = TRUE;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'ediklatbgplampung@gmail.com';
                $mail->Password = 'ediklatbgp2022';
                $mail->setFrom('ediklatbgplampung@gmail.com', 'BGP LAMPUNG');
                $mail->addReplyTo('ediklatbgplampung@gmail.com', 'BGP LAMPUNG');
                $mail->addAddress($this->input->post('email'), $this->input->post('email'));
                $mail->Subject = 'Password Baru';
                $mail->isHTML(true);
                $mail->msgHTML('<p style="text-align: center;">'.$newPass.'</p>');
                if (!$mail->send()) {
                
                    $this->session->set_flashdata('emailErr', 'email gagal dikirim');
				    redirect('authentication/forgot');
                } else {
                    $this->session->set_flashdata('emailErr', 'Silahkan cek email anda');
				    redirect('authentication/forgot');
                }
			}
		} else {
			redirect('authentication/forgot');
		}
	}
}
