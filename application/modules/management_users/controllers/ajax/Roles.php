<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Roles extends MX_Controller
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
                "url" => base_url('management_users/roles')
            ],
            [
                "text" => "Roles",
            ]
        ], 'Data Roles');
        $data['data'] = $this->load->view($this->module . '/roles/index', $params, TRUE);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function list()
    {
        $userPermission = getPermissionFromUser();
        $list = $this->roles->get_datatables();
        $data = array();
        foreach ($list as $role) {
            $row = array();
            $row[] = '<p class="text-sm d-flex py-auto my-auto">' . $role->role . '</p>';

            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('UR', $userPermission)) ? '<i class="ri-edit-2-line ri-lg text-warning m-1" role="button" title="Update" onclick="editData(' . $role->roleCode . ')"></i>' : '') . "
                " . ((in_array('DR', $userPermission) && $role->tipe == 'Dynamic') ? '<i class="ri-delete-bin-line ri-lg text-danger m-1" role="button" title="Delete" onclick="deleteData(' . $role->roleCode . ')"></i>' : '') . "
                " . ((count(array_intersect($userPermission, ['RRP', 'CRP', 'DRP'])) > 0) ? '<i class="ri-information-line ri-lg text-primary m-1" role="button" title="Info" onclick="infoData(' . $role->roleCode . ')"></i>' : '') . "
                </div>
                ";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->roles->count_all(),
            "recordsFiltered" => $this->roles->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    public function addHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CR', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            $params = [
                'title' => 'Add Data',
                'roleCode' => NULL,
                'role' => NULL,
            ];
            $data['breadcrumb'] = breadcrumb([
                [
                    "text" => "Management Users",
                    "url" => base_url('management_users/roles')
                ],
                [
                    "text" => "Roles",
                    "action" => "back()"
                ],
                [
                    "text" => "Add Roles"
                ]
            ], 'Add Roles');
            $data['data'] = $this->load->view($this->module . '/roles/form', $params, TRUE);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function editHTML(string $roleCode = '')
    {

        $userPermission = getPermissionFromUser();
        if (!in_array('UR', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($roleCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID role is required"
                );
            } else {
                $role = $this->roles->get_by_id($roleCode);
                if ($role == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Role not found!"
                    );
                } else {
                    $params = [
                        'title' => 'Edit Data',
                        'roleCode' => $role->roleCode,
                        'role' => $role->role,
                    ];
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "Management Users",
                            "url" => base_url('management_users/roles')
                        ],
                        [
                            "text" => "Roles",
                            "action" => "back()"
                        ],
                        [
                            "text" => "Edit Roles"
                        ]
                    ], 'Edit Roles');
                    $data['data'] = $this->load->view($this->module . '/roles/form', $params, TRUE);
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function add()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CR', $userPermission)) {
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
                    'role' => form_error('role')
                );
                $data = array(
                    'status'         => FALSE,
                    'errors'         => $errors
                );
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $insert = array(
                    'role' => $this->input->post('role'),
                    'status' => 'Public',
                    'tipe' => 'Dynamic',
                );
                $insert = $this->roles->save($insert);
                if ($insert) {
                    $data['status'] = TRUE;
                    $data['message'] = "Success to add role";
                } else {
                    $data['status'] = FALSE;
                    $data['message'] = "Failed to add role";
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function update()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('UR', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $this->validation_for = 'update';
            $data = array();
            $data['status'] = TRUE;
            if ($this->input->post('roleCode') == '' || $this->input->post('roleCode') == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID role is required"
                );
            } else {
                $role = $this->roles->get_by_id($this->input->post('roleCode'));
                if ($role == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Role not found!"
                    );
                } else {
                    $this->_validate();

                    if ($this->form_validation->run() == FALSE) {
                        $errors = array(
                            'role'     => form_error('role')
                        );
                        $data = array(
                            'status'         => FALSE,
                            'errors'         => $errors
                        );
                        $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    } else {
                        $update = array(
                            'role' => $this->input->post('role'),
                        );
                        $up = $this->roles->update(array('roleCode' => $this->input->post('roleCode')), $update);
                        if ($up) {
                            $data['status'] = TRUE;
                            $data['message'] = "Success to update role";
                        } else {
                            $data['status'] = FALSE;
                            $data['message'] = "Failed to update role";
                        }
                        $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                }
            }
        }
    }

    public function delete($roleCode)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('DR', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($roleCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID role is required"
                );
            } else {
                $role = $this->roles->get_by_id($roleCode);
                if ($role == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Role not found!"
                    );
                } else {
                    $del = $this->roles->delete_by_id($roleCode);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['message'] = "Success to delete role";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Failed to delete role";
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    private function _validate()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('role', 'Role', 'trim|required');
    }

    public function detailHTML($roleCode)
    {
        $userPermission = getPermissionFromUser();
        if (count(array_intersect($userPermission, ['RRP', 'CRP', 'DRP'])) > 0) {
            $data = array();
            $data['status'] = TRUE;
            if ($roleCode == '' || $roleCode == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID role is required"
                );
            } else {
                $role = $this->roles->get_by_id($roleCode);
                if ($role == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Role not found!"
                    );
                } else {
                    $getModule = $this->db->get_where('module', ['deleteAt' => NULL])->result_array();
                    $permission = [];
                    foreach ($getModule as $g => $f) {
                        $getPermission = $this->db->join('permission', 'permission.permissionCode=role_permission.permissionCode')
                            ->get_where('role_permission', ['permission.moduleCode' => $f['moduleCode'], 'role_permission.deleteAt' => NULL, 'role_permission.roleCode' => $roleCode])
                            ->result_array();
                        foreach ($getPermission as $k => $v) {
                            $permission[$f['module']][$v['rpCode']] = $v['description'];
                        }
                    }

                    $permissionHTML = '';
                    if (in_array('RRP', $userPermission)) {
                        $permissionHTML .= '<table>';
                        foreach ($permission as $r => $k) {
                            if ($k != NULL) {
                                $permissionHTML .= '<tr><th class="d-flex"><p class="fs-6 my-auto fw-bold">' . $r . '</p></th></tr>';
                                foreach ($k as $v => $s) {
                                    $permissionHTML .= '<tr><td class="d-flex"><p class="fs-6 my-auto">  - ' . $s . '</p> ' . ((in_array('DRP', $userPermission)) ? '<i class="ri-delete-bin-line ri-md text-danger my-auto" role="button" title="Delete Permission" onclick="deletePermission(' . $v . ')"></i>' : '') . '</td></tr>';
                                }
                            }
                        }
                        $permissionHTML .= '</table>';
                    } else {
                        $permissionHTML .= '<p>You don\'t have access to see role</p>';
                    }

                    $params = [
                        'userPermission' => $userPermission,
                        'roleCode' => $role->roleCode,
                        'role' => $role->role,
                        'permissionHTML' => $permissionHTML,
                    ];
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "Management Users",
                            "url" => base_url('management_users/roles')
                        ],
                        [
                            "text" => "Roles",
                            "action" => "back()"
                        ],
                        [
                            "text" => "Detail Roles"
                        ]
                    ], 'Detail Roles');
                    $data['data'] = $this->load->view($this->module . '/roles/detail/index', $params, TRUE);
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

    public function deletePermission($rpCode)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('DRP', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($rpCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID role permission is required"
                );
            } else {
                $up = $this->roles->get_role_permission_id($rpCode);
                if ($up == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Role permission not found!"
                    );
                } else {
                    $del = $this->roles->delete_role_permission_id($rpCode);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['roleCode'] = $up->roleCode;
                        $data['message'] = "Success to delete permission from role";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Failed to delete permission from role";
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }


    private function permissionHtml(string $title = '', string $roleCode = '')
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
            'roleCode' => $roleCode,
            'permission' => $permission,
        ];

        return $this->load->view($this->module . '/roles/detail/permission/form', $params, TRUE);
    }

    public function addPermissionHTML($roleCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CRP', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($roleCode == '' || $roleCode == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID role is required"
                );
            } else {
                $role = $this->roles->get_by_id($roleCode);
                if ($role == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Role not found!"
                    );
                } else {
                    $data['status'] = TRUE;
                    $data['breadcrumb'] = breadcrumb([
                        [
                            "text" => "Management Users",
                            "url" => base_url('management_users/roles')
                        ],
                        [
                            "text" => "Roles",
                            "action" => "back()"
                        ],
                        [
                            "text" => "Detail Roles",
                            "action" => "backDetail(".$roleCode.")"
                        ],
                        [
                            "text" => "Add Permission"
                        ]
                    ], 'Add Permission');
                    $data['data'] = $this->permissionHtml('Add Permission', $role->roleCode);
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function addPermission($roleCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('CUP', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($roleCode == '' || $roleCode == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "ID role is required"
                );
            } else {
                $data = array();
                $data['status'] = TRUE;
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('permissionCode', 'Permission', 'trim|required');
                $this->form_validation->set_rules('roleCode', 'Role', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $errors = array(
                        'permissionCode' => form_error('permissionCode'),
                        'roleCode' => form_error('roleCode')
                    );
                    $data = array(
                        'status'         => FALSE,
                        'errors'         => $errors
                    );
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $insert = array(
                        'permissionCode' => $this->input->post('permissionCode'),
                        'roleCode' => $this->input->post('roleCode'),
                    );
                    $where = $insert;
                    $where['deleteAt'] = NULL;
                    $check = $this->db->get_where('role_permission', $where)->row_array();
                    if ($check == NULL) {
                        $insert = $this->db->insert('role_permission', $insert);
                        if ($insert) {
                            $data['status'] = TRUE;
                            $data['roleCode'] = $roleCode;
                            $data['message'] = "Success add permission to role";
                        } else {
                            $data['status'] = FALSE;
                            $data['message'] = "Failed add permission to role";
                        }
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Failed add permission to role, permission already exists";
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }
}
