<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sertifikat_member_model extends CI_Model
{

	var $table = 'participant';
	var $column_order = array('activity.name','participant.participantCode', 'participant.createAt'); //set column field database for datatable orderable
	var $column_search = array('activity.name','participant.participantCode', 'participant.createAt'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('memberCode' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($memberCode = '')
	{

		$this->db->select('participant.participantCode, activity.activityCode as activityCodeActivity ,activity.name as activityName,participant.createAt as participantCreateAt')->from($this->table)->join('activity', 'activity.activityCode=participant.activityCode')->where([
			'participant.memberCode' => $memberCode,
			'participant.deleteAt' => NULL,
			'activity.deleteAt' => NULL,
			'participant.verify' => '1',
			'participant.status' => '1'
		]);
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

	function get_datatables($memberCode = '')
	{
		$this->_get_datatables_query($memberCode);
		if (isset($_POST['length'])) {
			if ($_POST['length'] != -1) $this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($memberCode = '')
	{
		$this->_get_datatables_query($memberCode);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($memberCode)
	{
		$this->db->select('activity.activityCode as activityCodeActivity ,activity.name as activityName,participant.createAt as participantCreateAt');
		$this->db->from($this->table);
		$this->db->where([
			'memberCode' => $memberCode,
			'deleteAt' => NULL,
			'verify' => '1',
			'status' => '1'
		]);
		return $this->db->count_all_results();
	}
}
