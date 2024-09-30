<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(FCPATH . '/vendor/setasign/fpdf/fpdf.php');
require_once(FCPATH . '/vendor/setasign/fpdi/src/autoload.php');
require_once APPPATH . "libraries/phpqrcode/qrlib.php";

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;


class Report extends MX_Controller
{
    private $module = 'ediklat';

    private $validation_for = '';

    public function __construct()
    {
        parent::__construct();
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
        $this->load->model($this->module . '/report_model', 'report');
    }

    public function data()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RREPORT', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $state = [];
        $dataState = $this->db->get_where('state', ['deleteAt' => NULL])->result_array();
        foreach ($dataState as $k => $v) {
            $state[$v['stateCode']] = $v['state'];
        }
        $params = [
            'userPermission' => $userPermission,
            'state' => $state,
        ];
        $data['status'] = TRUE;
        $data['breadcrumb'] = breadcrumb([
            [
                "text" => "E-Diklat",
                "url" => base_url('ediklat/report')
            ],
            [
                "text" => "Report",
            ]
        ], 'Data Report');
        $data['data'] = $this->load->view($this->module . '/report/index', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function dataHTML()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RREPORT', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $state = [];
        $dataState = $this->db->get_where('state', ['deleteAt' => NULL])->result_array();
        foreach ($dataState as $k => $v) {
            $state[$v['stateCode']] = $v['state'];
        }
        $params = [
            'userPermission' => $userPermission,
            'link' => 'ediklat/report/download?state=' . (!isset($_POST['state']) ? '' : $_POST['state']) . '&education_service=' . (!isset($_POST['education_service']) ? '' : $_POST['education_service']) . '&nik=' . (!isset($_POST['nik']) ? '' : $_POST['nik']) . '&npsn=' . (!isset($_POST['npsn']) ? '' : $_POST['npsn']) . '&name=' . (!isset($_POST['name']) ? '' : $_POST['name'])
        ];
        $data['status'] = TRUE;

        $data['data'] = $this->load->view($this->module . '/report/data_list', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function list()
    {
        $userPermission = getPermissionFromUser();
        $list = $this->report->get_datatables();
        $data = array();
        foreach ($list as $v) {
            $row = array();
            $row[] = '
                <div class="d-flex px-2 py-1 gap-2">
                    <div class="d-flex flex-column justify-content-center">
                        <p class="text-xs text-bold d-flex py-auto my-auto">' . $v->nameParticipant . '</p>
                        <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                    </div>
                </div>';

            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->birthPlace .  ($v->birthDate != NULL ? '/' . tanggal_indo($v->birthDate) : '') . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->nik . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->npsn . '</p>';
            $row[] = '<p class="text-xs d-flex py-auto my-auto">' . $v->jumActivity . ' Kegiatan</p>';
            $row[] = "
                <div class='d-flex justify-content-center'>
                " . ((in_array('RREPORT', $userPermission)) ? '<i class="ri-information-line ri-lg text-primary m-1" role="button" title="Ubah" onclick="detailData(' . $v->memberCode . ')"></i>' : '') . "
               </div>
                ";
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->report->count_all(),
            "recordsFiltered" => $this->report->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    public function detailData()
    {
        $userPermission = getPermissionFromUser();
        if (!in_array('RREPORT', $userPermission)) {
            $data = array(
                'status'         => FALSE,
                'message'         => "You don't have access!"
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        $activity = $this->db->select('*,activity.name as nameActivity')->join('activity','activity.activityCode=participant.activityCode')->get_where('participant',['memberCode' => $this->input->post('memberCode'),'participant.deleteAt' => NULL,'activity.deleteAt' => NULL])->result_array();
        $params = [
            'userPermission' => $userPermission,
            'activity' => $activity
        ];
        $data['status'] = TRUE;

        $data['data'] = $this->load->view($this->module . '/report/detail', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
