<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tugas_model extends CI_Model
{

	var $table = 'task';
	var $column_order = array('task'); //set column field database for datatable orderable
	var $column_search = array('task'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('taskCode' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($activityCode = '')
	{

		$this->db->from($this->table)->where('activityCode', $activityCode)->where('deleteAt', NULL);

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

	function get_datatables($activityCode = '')
	{
		$this->_get_datatables_query($activityCode);
		if (isset($_POST['length'])) {
			if ($_POST['length'] != -1) $this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($activityCode = '')
	{
		$this->_get_datatables_query($activityCode);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($activityCode)
	{
		$this->db->from($this->table);
		$this->db->where('deleteAt', NULL);
		$this->db->where('activityCode', $activityCode);
		return $this->db->count_all_results();
	}

	public function get_all($activityCode)
	{
		$this->db->from($this->table);
		$this->db->where('activityCode', $activityCode);
		$this->db->where('deleteAt', NULL);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('taskCode', $id);
		$this->db->where('deleteAt', NULL);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$data['updateAt'] = date('Y-m-d H:i:s');
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$data['deleteAt'] = date('Y-m-d H:i:s');
		$this->db->update($this->table, $data, ['taskCode' => $id]);
		return $this->db->affected_rows();
	}
}
