<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{

	var $table = 'user';
	var $column_order = array('email'); //set column field database for datatable orderable
	var $column_search = array('email'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('userCode' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->from($this->table)->where('status','Public')->where('deleteAt',NULL);

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
		$this->db->where('status','Public')->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_all()
	{
		$this->db->from($this->table);
		$this->db->where('status','Public')->where('deleteAt',NULL);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('status','Public')->where('userCode', $id)->where('deleteAt',NULL);
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
		$this->db->update($this->table, $data, ['userCode' => $id]);
		return $this->db->affected_rows();
	}

	public function get_role_id($id)
	{
		$this->db->select('ru.ruCode,r.roleCode,r.role');
		$this->db->join('role r','r.roleCode=ru.roleCode');
		$this->db->from('role_user ru');
		$this->db->where('ru.userCode', $id);
		$this->db->where('ru.deleteAt', NULL);
		$this->db->where('r.deleteAt', NULL);
		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_role_user_id($id)
	{
		$this->db->from('role_user');
		$this->db->where('ruCode', $id)->where('deleteAt',NULL);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete_role_user_id($id)
	{
		$data['deleteAt'] = date('Y-m-d H:i:s');
		$this->db->update('role_user', $data, ['ruCode' => $id]);
		return $this->db->affected_rows();
	}

	public function get_special_permission_id($id)
	{
		$this->db->select('up.upCode,r.permissionCode,r.permission,r.description');
		$this->db->join('permission r','r.permissionCode=up.permissionCode');
		$this->db->from('user_permission up');
		$this->db->where('up.userCode', $id);
		$this->db->where('up.deleteAt', NULL);
		$this->db->where('r.deleteAt', NULL);
		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_user_permission_id($id)
	{
		$this->db->from('user_permission');
		$this->db->where('upCode', $id)->where('deleteAt',NULL);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete_user_permission_id($id)
	{
		$data['deleteAt'] = date('Y-m-d H:i:s');
		$this->db->update('user_permission', $data, ['upCode' => $id]);
		return $this->db->affected_rows();
	}

}