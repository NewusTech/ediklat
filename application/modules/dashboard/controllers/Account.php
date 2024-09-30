<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends MX_Controller
{
    private $module = 'dashboard';
    public function __construct()
    {
        parent::__construct();
        (isLogin() == false) ? redirect('authentication/logout') : '';
        $this->load->model('management_users/users_model', 'users');
    }

    public function index()
    {
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "Dashboard",
                "url" => base_url('dashboard/account')
            ],
            [
                "text" => "Account",
            ]
        ], 'Account');
        $data['account'] = $this->db->get_where('user', [
            'deleteAt' => NULL,
            'userCode' => $this->session->userdata('userCode')
        ])->row_array();
        if (checkRole(3)) {
            if (checkMember() == FALSE) {
                redirect('dashboard/index');
            } else {
                $member = $this->db->get_where('member', [
                    'deleteAt' => NULL,
                    'userCode' => $this->session->userdata('userCode'),
                ])->row_array();
                if($member == NULL){
                    redirect('dashboard/index');
                }
                if ($member['verify'] == '0') {
                    redirect('dashboard/index');
                } else {
                    $dataState = $this->db->get_where('state', ['deleteAt' => NULL])->result();
                    $state = [];
                    foreach ($dataState as $k => $v) {
                        $state[$v->stateCode] = $v->state;
                    }
                    $data['state'] = $state;
                    $data['member'] = $member;
                    $data['_view'] = $this->module . '/account';
                }
            }
        } elseif (checkRole(2)) {
            $data['_view'] = $this->module . '/account';
        } else {
            $data['_view'] = $this->module . '/account';
        }
        $this->load->view('layouts/back/main', $data);
    }
    public function updateAccount()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACCOUNT', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'update';
            $data = array();
            $data['status'] = TRUE;

            $user = $this->users->get_by_id($this->session->userdata('userCode'));
            if ($user == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "User not found!"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $this->form_validation->set_error_delimiters('', '');

                $email_unique = '';
                $getData = $this->users->get_by_id($this->session->userdata('userCode'));
                if ($this->input->post('email') != $getData->email) {
                    $email_unique = '|is_unique[user.email]';
                }
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email' . $email_unique);

                if ($this->form_validation->run() == FALSE) {
                    $errors = array(
                        'email' => form_error('email'),
                        'password' => form_error('password'),
                    );
                    $data = array(
                        'status'         => FALSE,
                        'errors'         => $errors
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $getData = $this->users->get_by_id($this->session->userdata('userCode'));
                    if ($this->input->post('password') == NULL) {
                        $password = $getData->password;
                    } else {
                        $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                    }
                    $update = array(
                        'email' => $this->input->post('email'),
                        'password' => $password,
                    );
                    $up = $this->users->update(array('userCode' => $this->session->userdata('userCode')), $update);
                    if ($up) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil mengubah data akun";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal mengubah data akun";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function updateMember()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RACCOUNT', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data = array();
            $data['status'] = TRUE;

            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
            $this->form_validation->set_rules('npsn', 'NUPTK', 'trim|required');
            $this->form_validation->set_rules('name', 'nama', 'trim|required');
            $this->form_validation->set_rules('phone', 'no handphone', 'trim|required');
            $this->form_validation->set_rules('address', 'alamat', 'trim|required');
            $this->form_validation->set_rules('npwp', 'NPWP', 'trim|required');
            $this->form_validation->set_rules('agency', 'instansi', 'trim|required');
            $this->form_validation->set_rules('rank_dinas', 'jabatan dalam dinas', 'required');
            $this->form_validation->set_rules('rank', 'pangkat/golongan', 'trim|required');
            $this->form_validation->set_rules('gender', 'jenis kelamin', 'trim|required');
            $this->form_validation->set_rules('birthplace', 'tempat lahir', 'trim|required');
            $this->form_validation->set_rules('birthdate', 'tanggal lahir', 'trim|required');
            $this->form_validation->set_rules('education', 'pendidikan terakhir', 'trim|required');
            $this->form_validation->set_rules('education_service', 'layanan pendidikan', 'trim|required');
            $this->form_validation->set_rules('stateCode', 'stateCode', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'name' => form_error('name'),
                    'npsn' => form_error('npsn'),
                    'nik' => form_error('nik'),
                    'phone' => form_error('phone'),
                    'address' => form_error('address'),
                    'npwp' => form_error('npwp'),
                    'agency' => form_error('agency'),
                    'rank' => form_error('rank'),
                    'rank_dinas' => form_error('rank_dinas'),
                    'gender' => form_error('gender'),
                    'birthplace' => form_error('birthplace'),
                    'birthdate' => form_error('birthdate'),
                    'education' => form_error('education'),
                    'education_service' => form_error('education_service'),
                    'stateCode' => form_error('stateCode'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $update = array(
                    'name' => $this->input->post('name'),
                    'nik' => $this->input->post('nik'),
                    'npsn' => $this->input->post('npsn'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'npwp' => $this->input->post('npwp'),
                    'agency' => $this->input->post('agency'),
                    'rank' => $this->input->post('rank'),
                    'rank_dinas' => $this->input->post('rank_dinas'),
                    'gender' => $this->input->post('gender'),
                    'education' => $this->input->post('education'),
                    'education_service' => $this->input->post('education_service'),
                    'birthplace' => $this->input->post('birthplace'),
                    'birthdate' => $this->input->post('birthdate'),
                    'stateCode' => $this->input->post('stateCode'),
                );
                if (isset($_FILES['picture']) && $_FILES['picture']['name'] != NULL) {

                    $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
                    $config['upload_path']          = FCPATH . '/assets/img/participant/';
                    $config['allowed_types']        = 'gif|jpg|jpeg|png';
                    $config['file_name']            = $file_name;
                    $config['overwrite']            = true;
                    $config['max_size']             = 10240;

                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('picture')) {
                        $data = array(
                            'status'         => FALSE,
                            'message'         => 'Foto gagal di upload'
                        );
                        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $uploaded_data = $this->upload->data();
                        $update['picture'] = $uploaded_data['file_name'];
                    }
                }
                $update = $this->db->where(['userCode' => $this->session->userdata('userCode')])->update('member', $update);
                if ($update) {
                    $data['status'] = TRUE;
                    $data['message'] = "Berhasil mengubah data member";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Gagal mengubah data member";
                }
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }
}
