<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_essay_model extends CI_Model
{

	var $table = 'essay_member';
	var $column_order = array('essay.judul'); //set column field database for datatable orderable
	var $column_search = array('essay.judul'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('emCode' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($memberCode = '')
	{

		$this->db->from($this->table)
		->join('member' , 'member.memberCode=essay_member.memberCode')
		->join('essay' , 'essay.essayCode=essay_member.essayCode')
		->where('member.memberCode', $memberCode)->where('essay_member.deleteAt', NULL);

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
		$this->db->from($this->table);
		$this->db->where('deleteAt', NULL);
		$this->db->where('memberCode', $memberCode);
		return $this->db->count_all_results();
	}

	public function get_all($memberCode)
	{
		$this->db->from($this->table);
		$this->db->where('memberCode', $memberCode);
		$this->db->where('deleteAt', NULL);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('emCode', $id);
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
		$this->db->update($this->table, $data, ['emCode' => $id]);
		return $this->db->affected_rows();
	}
}
