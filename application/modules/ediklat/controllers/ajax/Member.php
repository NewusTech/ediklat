<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(FCPATH . '/vendor/setasign/fpdf/fpdf.php');
require_once(FCPATH . '/vendor/setasign/fpdi/src/autoload.php');
require_once APPPATH . "libraries/phpqrcode/qrlib.php";

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;


class Member extends MX_Controller
{
    private $module = 'ediklat';

    private $validation_for = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model($this->module . '/member_model', 'member');
        if (isLogin() == false) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You must login first!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
            die();
        }
    }

    public function data()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RMEMBER', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        $params = [
            'userPermission' => $userPermission
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/member')
            ],
            [
                "text" => "Member",
            ]
        ], 'Data Member');
        $data['data'] = $this->load->view($this->module . '/member/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function list()
    {
        $userPermission = getPermissionFromUser();
        $list = $this->member->get_datatables();
        $data = array();
        foreach ($list as $v) {
            $row = array();

            if (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $v->picture))) {
                $urlImage = base_url('assets/img/participant/' . $v->picture);
            } else {
                $urlImage = base_url('assets/img/participant/default.png');
            }

            $row[] = '
                <div class="d-flex px-2 py-1 gap-2">
                    <div>
                        <img src="' . $urlImage . '" class="avatar avatar-sm me-3" alt="user2" role="button" onclick="viewImageMemberDetail(' . $v->memberCode . ')">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <p class="text-xs text-bold d-flex py-auto my-auto">' . $v->name . '</p>
                        <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                    </div>
                </div>';

            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->nik . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->npsn . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . getOneValue('state', ['deleteAt' => NULL, 'stateCode' => $v->stateCode], 'state') . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->education_service . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($v->verify == '1' ? 'aktif' : 'tidak aktif') . '</p>';
            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('RMEMBER', $userPermission)) ? '<span class="btn btn-sm btn-' . ($v->verify == "1" ? 'danger' : 'success') . ' text-xs my-auto py-1 mx-1" role="button" title="Detail" onclick="' . ($v->verify == '1' ? 'statusData(' . $v->memberCode . ',\'0\')' : 'statusData(' . $v->memberCode . ',\'1\')') . '">' . ($v->verify == "1" ? 'Non Aktifkan' : 'Aktifkan') . '</span>' : '') . "
                " . ((in_array('RMEMBER', $userPermission)) ? '<span class="btn btn-sm btn-primary text-xs my-auto py-1 mx-1" role="button" title="Detail" onclick="detailData(' . $v->memberCode . ')">Detail</span>' : '') . "
                " . ((in_array('RMEMBER', $userPermission)) ? '<span class="btn btn-sm btn-danger text-xs my-auto py-1 mx-1" role="button" title="Hapus" onclick="deleteData(' . $v->memberCode . ')">Hapus</span>' : '') . "
                </div>
                ";
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->member->count_all(),
            "recordsFiltered" => $this->member->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function detailHTML(string $memberCode = '')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RMEMBER', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($memberCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->member->get_by_id($memberCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Member tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'title' => 'Detail Data Member',
                        'name' => $result->name,
                        'npsn' => $result->npsn,
                        'nik' => $result->nik,
                        'birthplace' => $result->birthplace,
                        'birthdate' => $result->birthdate,
                        'phone' => $result->phone,
                        'agency' => $result->agency,
                        'gender' => $result->gender,
                        'rank' => $result->rank,
                        'rank_dinas' => $result->rank_dinas,
                        'education_service' => $result->education_service,
                        'education' => $result->education,
                        'state' => getOneValue('state', ['deleteAt' => NULL, 'stateCode' => $result->stateCode], 'state'),
                        'npwp' => $result->npwp,
                        'address' => $result->address,
                        'picture' => $result->picture,
                    ];
                    $data['data'] = $this->load->view($this->module . '/member/detail', $params, TRUE);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function status(string $memberCode = '', string $status = '0')
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RMEMBER', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $data['status'] = TRUE;
            if ($memberCode == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Activity code is required"
                );
            } else {
                $result = $this->member->get_by_id($memberCode);
                if ($result == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Member tidak ditemukan!"
                    );
                } else {
                    $params = [
                        'verify' => $status
                    ];
                    $up = $this->member->update(['memberCode' => $memberCode],$params);
                    if ($up) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil memngubah status member";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal memngubah status member";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                
                }
            }
        }
    }
    
    public function delete($id)
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RMEMBER', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($id == '') {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Member code is required"
                );
                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $sertifikat = $this->member->get_by_id($id);
                if ($sertifikat == NULL) {
                    $data = array(
                        'status'         => FALSE,
                        'message'         => "Member tidak ditemukan!"
                    );
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $del = $this->member->delete_by_id($id);
                    if ($del) {
                        $data['status'] = TRUE;
                        $data['message'] = "Berhasil menghapus member";
                    } else {
                        $data['status'] = FALSE;
                        $data['message'] = "Gagal menghapus member";
                    }
                    return $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }
}
