<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MX_Controller
{
    private $module = 'management_users';
    private $validation_for = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model($this->module . '/users_model', 'users');
        $this->load->model($this->module . '/roles_model', 'roles');
        if (isLogin() == false) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You must login first!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
            die();
        }
    }

    public function data()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "Management Users",
                "url" => base_url('management_users/users')
            ],
            [
                "text" => "Users",
            ]
        ], 'Data Users');
        $data['data'] = $this->load->view($this->module . '/users/index', $params, TRUE);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function list()
    {
        $userPermission = getPermissionFromUser();
        $list = $this->users->get_datatables();
        $data = array();
        foreach ($list as $user) {
            $row = array();
            $row[] = '<p class="text-sm d-flex py-auto my-auto">' . $user->email . '</p>';
            $row[] = '<p class="text-sm d-flex py-auto my-auto">' . ($user->isActive == '1' ? 'active' : 'not active') . '</p>';

            //add html for action
            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('UU', $userPermission)) ? '<i class="ri-edit-2-line ri-lg text-warning m-1" role="button" title="Update" onclick="editData(' . $user->userCode . ')"></i>' : '') . "
                " . ((in_array('DU', $userPermission)) ? '<i class="ri-delete-bin-line ri-lg text-danger m-1" role="button" title="Delete" onclick="deleteData(' . $user->userCode . ')"></i>' : '') . "
                " . ((count(array_intersect($userPermission, ['RRU', 'RUP'])) > 0) ? '<i class="ri-information-line ri-lg text-primary m-1" role="button" title="Info" onclick="infoData(' . $user->userCode . ')"></i>' : '') . "
                </div>
                ";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->users->count_all(),
            "recordsFiltered" => $this->users->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            $params = [
                'title' => 'Add Data',
                'userCode' => NULL,
                'email' => NULL,
                'isActive' => '',
            ];
            $data['breadcrumb'] = breadcrumb([
                [
                    "text" => "Management Users",
                    "url" => base_url('management_users/users')
                ],
                [
                    "text" => "Users",
                    "action" => "back()"
                ],
                [
                    "text" => "Add Users",
                ]
            ], 'Add Users');
            $data['data'] = $this->load->view($this->module . '/users/form', $params, TRUE);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function editHTML(string $userCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($userCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID user is required"
                );
            } else {
                $user = $this->users->get_by_id($userCode);
                if ($user == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "User not found!"
                    );
                } else {
                    $params = [
                        'title' => 'Edit Data',
                        'userCode' => $user->userCode,
                        'email' => $user->email,
                        'isActive' => $user->isActive,
                    ];
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "Management Users",
                            "url" => base_url('management_users/users')
                        ],
                        [
                            "text" => "Users",
                            "action" => "back()"
                        ],
                        [
                            "text" => "Edit Users",
                        ]
                    ], 'Edit Users');
                    $data['data'] = $this->load->view($this->module . '/users/form', $params, TRUE);
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function add()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'add';
            $data = array();
            $data['status'] = TRUE;

            $this->_validate();

            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'email' => form_error('email'),
                    'password' => form_error('password'),
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $insert = array(
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'status' => 'Public',
                    'isActive' => $this->input->post('isActive')
                );
                $insert = $this->users->save($insert);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Success to add user";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Failed to add user";
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function update()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'update';
            $data = array();
            $data['status'] = TRUE;
            if ($this->input->post('userCode') == '' || $this->input->post('userCode') == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID user is required"
                );
            } else {
                $user = $this->users->get_by_id($this->input->post('userCode'));
                if ($user == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "User not found!"
                    );
                } else {
                    $this->_validate();

                    if ($this->form_validation->run() == FALSE) {
                        $errors = array(
                            'email' => form_error('email'),
                            'password' => form_error('password'),
                        );
                        $data = array(
                            'status'         => FALSE,
                            'errors'         => $errors
                        );
                        $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $getData = $this->users->get_by_id($this->input->post('userCode'));
                        if ($this->input->post('password') == NULL) {
                            $password = $getData->password;
                        } else {
                            $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                        }
                        $update = array(
                            'email' => $this->input->post('email'),
                            'password' => $password,
                            'isActive' => $this->input->post('isActive')
                        );
                        $up = $this->users->update(array('userCode' => $this->input->post('userCode')), $update);
                        if ($up) {
                            $data['status'] = TRUE;
                            $data['message'] = "Success to update user";
                        } else {
                            $data['status'] = FALSE;
                            $data['message'] = "Failed to update user";
                        }
                        $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                }
            }
        }
    }

    public function delete($userCode)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('DU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($userCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID user is required"
                );
            } else {
                $user = $this->users->get_by_id($userCode);
                if ($user == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "User not found!"
                    );
                } else {
                    $del = $this->users->delete_by_id($userCode);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['message'] = "Success to delete user";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Failed to delete user";
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    private function _validate()
    {
        $this->form_validation->set_error_delimiters('', '');

        $email_unique = '';
        $passwordAdd = '';

        if ($this->validation_for == 'add') {
            $passwordAdd = '|required|min_length[3]';
            $email_unique = '|is_unique[user.email]';
        } else if ($this->validation_for == 'update') {
            $getData = $this->users->get_by_id($this->input->post('userCode'));
            if ($this->input->post('email') != $getData->email) {
                $email_unique = '|is_unique[user.email]';
            }
        }
        $this->form_validation->set_rules('isActive', 'Status', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim' . $passwordAdd);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email' . $email_unique);
    }

    public function detailHTML($userCode)
    {
        $userPermission = getPermissionFromUser();
        if (count(array_intersect($userPermission, ['RRU', 'RUP'])) > 0) {
            $data = array();
            $data['status'] = TRUE;
            if ($userCode == '' || $userCode == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID user is required"
                );
            } else {
                $user = $this->users->get_by_id($userCode);
                if ($user == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "User not found!"
                    );
                } else {
                    $roleHTML = '';
                    if (in_array('RRU', $userPermission)) {
                        $role = $this->users->get_role_id($userCode);
                        $roleHTML .= '<table>';
                        foreach ($role as $r) {
                            $roleHTML .= '<tr><td class="d-flex"><p class="fs-6 my-auto">' . $r['role'] . '</p> ' . ((in_array('DRU', $userPermission)) ? '<i class="ri-delete-bin-line ri-md text-danger my-auto" role="button" title="Delete Role" onclick="deleteRole(' . $r['ruCode'] . ')"></i>' : '') . '</td></tr>';
                        }
                        $roleHTML .= '</table>';
                    } else {
                        $roleHTML .= '<p>You don\'t have access to see role</p>';
                    }

                    $specialPermissionHTML = '';
                    if (in_array('RUP', $userPermission)) {
                        $specialPermission = $this->users->get_special_permission_id($userCode);
                        $specialPermissionHTML .= '<table>';
                        foreach ($specialPermission as $r) {
                            $specialPermissionHTML .= '<tr><td class="d-flex"><p class="fs-6 my-auto">' . $r['description'] . '</p> ' . ((in_array('DUP', $userPermission)) ? '<i class="ri-delete-bin-line ri-md text-danger my-auto" role="button" title="Delete Special Permission" onclick="deletePermission(' . $r['upCode'] . ')"></i>' : '') . '</td></tr>';
                        }
                        $specialPermissionHTML .= '</table>';
                    } else {
                        $specialPermissionHTML .= '<p>You don\'t have access to see special permission</p>';
                    }
                    $params = [
                        'userPermission' => $userPermission,
                        'userCode' => $user->userCode,
                        'email' => $user->email,
                        'roleHTML' => $roleHTML,
                        'specialPermissionHTML' => $specialPermissionHTML,
                    ];
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "Management Users",
                            "url" => base_url('management_users/users')
                        ],
                        [
                            "text" => "Users",
                            "url" => base_url('management_users/users')
                        ],
                        [
                            "text" => "Detail Users",
                        ]
                    ], 'Detail Users');
                    $data['data'] = $this->load->view($this->module . '/users/detail/index', $params, TRUE);
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        } else {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function deleteRole($ruCode)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('DRU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($ruCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID role user is required"
                );
            } else {
                $ru = $this->users->get_role_user_id($ruCode);
                if ($ru == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Role user not found!"
                    );
                } else {
                    $del = $this->users->delete_role_user_id($ruCode);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['userCode'] = $ru->userCode;
                        $data['message'] = "Success to delete role from user";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Failed to delete role from user";
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function deletePermission($upCode)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('DU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($upCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID special permission user is required"
                );
            } else {
                $up = $this->users->get_user_permission_id($upCode);
                if ($up == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Special permission user not found!"
                    );
                } else {
                    $del = $this->users->delete_user_permission_id($upCode);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['userCode'] = $up->userCode;
                        $data['message'] = "Success to delete special permission from user";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Failed to delete special permission from user";
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    private function roleHtml(string $title = '', string $userCode = '', string $roleCode = '')
    {
        $getRole = $this->roles->get_all();
        $roles = [];
        foreach ($getRole as $k => $v) {
            $roles[$v['roleCode']] = $v['role'];
        }
        $params = [
            'title' => $title,
            'roles' => $roles,
            'roleCode' => $roleCode,
            'userCode' => $userCode,
        ];
        return $this->load->view($this->module . '/users/detail/role/form', $params, TRUE);
    }

    public function addRoleHTML($userCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CRU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($userCode == '' || $userCode == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID user is required"
                );
            } else {
                $user = $this->users->get_by_id($userCode);
                if ($user == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "User not found!"
                    );
                } else {
                    $data['status'] = TRUE;
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "Management Users",
                            "url" => base_url('management_users/users')
                        ],
                        [
                            "text" => "Users",
                            "url" => base_url('management_users/users')
                        ],
                        [
                            "text" => "Detail Users",
                            "action" => "backDetail(" . $userCode . ")"
                        ],
                        [
                            "text" => "Add Role",
                        ],
                    ], 'Add Role');
                    $data['data'] = $this->roleHtml('Add Role', $user->userCode);
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function addRole($userCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CRU', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($userCode == '' || $userCode == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID user is required"
                );
            } else {
                $data = array();
                $data['status'] = TRUE;
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('roleCode', 'Role', 'trim|required');
                $this->form_validation->set_rules('userCode', 'User', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $errors = array(
                        'roleCode' => form_error('roleCode'),
                        'userCode' => form_error('userCode')
                    );
                    $data = array(
                        'status'         => FALSE,
                        'errors'         => $errors
                    );
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $insert = array(
                        'roleCode' => $this->input->post('roleCode'),
                        'userCode' => $this->input->post('userCode'),
                    );
                    $where = $insert;
                    $where['deleteAt'] = NULL;
                    $check = $this->db->get_where('role_user', $where)->row_array();
                    if ($check == NULL) {
                        $insert = $this->db->insert('role_user', $insert);
                        if ($insert) {
                            $data['status'] = TRUE;
                            $data['userCode'] = $userCode;
                            $data['message'] = "Success add role to user";
                        } else {
                            $data['status'] = FALSE;
                            $data['message'] = "Failed add role to user";
                        }
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Failed add role to user, role already exists";
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    private function permissionHtml(string $title = '', string $userCode = '', string $permissionCode = '')
    {
        $getModule = $this->db->get_where('module', ['deleteAt' => NULL])->result_array();
        $permission = '';
        foreach ($getModule as $g => $f) {
            $getPermission = $this->db->get_where('permission', ['moduleCode' => $f['moduleCode'], 'deleteAt' => NULL])->result_array();
            $permission .= '<optgroup label="' . $f['module'] . '">';
            foreach ($getPermission as $k => $v) {
                $permission .= '<option value="' . $v['permissionCode'] . '">' . $v['description'] . '</option>';
            }
            $permission .= '</optgroup>';
        }
        $params = [
            'title' => $title,
            'permission' => $permission,
            'permissionCode' => $permissionCode,
            'userCode' => $userCode,
        ];

        return $this->load->view($this->module . '/users/detail/permission/form', $params, TRUE);
    }

    public function addPermissionHTML($userCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CUP', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($userCode == '' || $userCode == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID user is required"
                );
            } else {
                $user = $this->users->get_by_id($userCode);
                if ($user == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "User not found!"
                    );
                } else {
                    $data['status'] = TRUE;
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "Management Users",
                            "url" => base_url('management_users/users')
                        ],
                        [
                            "text" => "Users",
                            "url" => base_url('management_users/users')
                        ],
                        [
                            "text" => "Detail Users",
                            "action" => "backDetail(" . $userCode . ")"
                        ],
                        [
                            "text" => "Add Special Permission",
                        ],
                    ], 'Special Permission');
                    $data['data'] = $this->permissionHtml('Add Permission', $user->userCode);
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function addPermission($userCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CUP', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($userCode == '' || $userCode == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID user is required"
                );
            } else {
                $data = array();
                $data['status'] = TRUE;
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('permissionCode', 'Permission', 'trim|required');
                $this->form_validation->set_rules('userCode', 'User', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $errors = array(
                        'permissionCode' => form_error('permissionCode'),
                        'userCode' => form_error('userCode')
                    );
                    $data = array(
                        'status'         => FALSE,
                        'errors'         => $errors
                    );
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $insert = array(
                        'permissionCode' => $this->input->post('permissionCode'),
                        'userCode' => $this->input->post('userCode'),
                    );
                    $where = $insert;
                    $where['deleteAt'] = NULL;
                    $check = $this->db->get_where('user_permission', $where)->row_array();
                    if ($check == NULL) {
                        $userPermissionIN = getPermissionFromUser($userCode);
                        $permissionOne = $this->db->get_where('permission', ['permissionCode' => $insert['permissionCode']])->row_array();
                        if (in_array($permissionOne['permission'], $userPermissionIN)) {
                            $data['status'] = FALSE;
                            $data['message'] = "Failed add permission to user, permission already exists on role";
                        } else {
                            $insert = $this->db->insert('user_permission', $insert);
                            if ($insert) {
                                $data['status'] = TRUE;
                                $data['userCode'] = $userCode;
                                $data['message'] = "Success add permission to user";
                            } else {
                                $data['status'] = FALSE;
                                $data['message'] = "Failed add permission to user";
                            }
                        }
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Failed add permission to user, permission already exists";
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }
}
