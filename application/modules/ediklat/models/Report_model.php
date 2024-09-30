<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{

	var $table = 'participant';
	var $column_order = array('participant.name', 'agency', 'nik', 'npsn', 'npwp', 'education'); //set column field database for datatable orderable
	var $column_search = array('participant.name', 'agency', 'nik', 'npsn', 'npwp', 'education'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('participantCode' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->from($this->table)->select('count(participant.memberCode) as jumActivity,participant.memberCode as memberCode,participant.name as nameParticipant,participant.agency as agency,participant.birthplace as birthPlace, participant.birthdate as birthDate,participant.nik as nik,participant.npsn as npsn')
			->where('participant.deleteAt', NULL)->where('participant.memberCode !=', NULL);
		if ($this->input->post('state') != NULL) {
			$this->db->where('stateCode', $this->input->post('state'));
		}

		if ($this->input->post('education_service') != NULL) {
			$this->db->where('education_service', $this->input->post('education_service'));
		}

		if ($this->input->post('nik') != NULL) {
			$this->db->like('nik', $this->input->post('nik'));
		}

		if ($this->input->post('npsn') != NULL) {
			$this->db->like('npsn', $this->input->post('npsn'));
		}

		if ($this->input->post('name') != NULL) {
			$this->db->like('participant.name', $this->input->post('name'));
		}
		$this->db->group_by('participant.memberCode');
		$i = 0;

		foreach ($this->column_search as $item) // loop column 
		{
			if (isset($_POST['search']['value'])) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if (isset($_POST['length'])) {
			if ($_POST['length'] != -1) $this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table)->select('count(participant.memberCode) as jumActivity,participant.memberCode as memberCode,participant.name as nameParticipant,participant.education_service as educationService,participant.birthplace as birthPlace, participant.birthdate as birthDate,participant.nik as nik,participant.npsn as npsn')
			->where('participant.deleteAt', NULL)->where('participant.memberCode !=', NULL);
		if ($this->input->post('state') != NULL) {
			$this->db->where('stateCode', $this->input->post('state'));
		}

		if ($this->input->post('education_service') != NULL) {
			$this->db->where('education_service', $this->input->post('education_service'));
		}

		if ($this->input->post('nik') != NULL) {
			$this->db->like('nik', $this->input->post('nik'));
		}

		if ($this->input->post('npsn') != NULL) {
			$this->db->like('npsn', $this->input->post('npsn'));
		}

		if ($this->input->post('name') != NULL) {
			$this->db->like('participant.name', $this->input->post('name'));
		}
		$this->db->group_by('participant.memberCode');
		return $this->db->count_all_results();
	}

	public function get_all(){
		$this->db->from($this->table)->select('count(participant.memberCode) as jumActivity,participant.memberCode as memberCode,participant.name as nameParticipant,participant.education_service as educationService,participant.birthplace as birthPlace, participant.birthdate as birthDate,participant.nik as nik,participant.npsn as npsn')
			->where('participant.deleteAt', NULL)->where('participant.memberCode !=', NULL);
		if ($_GET['state'] != NULL) {
			$this->db->where('stateCode', $_GET['state']);
		}

		if ($_GET['education_service'] != NULL) {
			$this->db->where('education_service', $_GET['education_service']);
		}

		if ($_GET['nik'] != NULL) {
			$this->db->like('nik', $_GET['nik']);
		}

		if ($_GET['npsn'] != NULL) {
			$this->db->like('npsn', $_GET['npsn']);
		}

		if ($_GET['name'] != NULL) {
			$this->db->like('participant.name', $_GET['name']);
		}
		$this->db->group_by('participant.memberCode');
		return $this->db->get()->result();
	}
}
