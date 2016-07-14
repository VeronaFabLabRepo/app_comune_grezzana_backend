<?php
class FM_User_Roles_Model extends CI_Model {

	private $_tableName = '';

    public function __construct()
	{
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName = $ci->config->item('user_roles_tbl', 'factotum');
    }

	public function getUserRolesList()
	{
		$this->db->order_by('role', 'ASC'); 
		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

    public function getRoleByRoleName($roleName)
    {
    	$query = $this->db->get_where($this->_tableName, array('role' => $roleName), 1);
        return ($query->num_rows() == 1 ? $query->row_array() : NULL);
    }

    public function getRoleById($id)
    {
    	$query = $this->db->get_where($this->_tableName, array('id' => $id), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
    }

	public function isRoleNameAvailable($role)
	{
		$query = $this->db->get_where($this->_tableName, array('LOWER(role)' => $role), 1);
		return ($query->num_rows() == 0 ? TRUE : FALSE);
	}

    public function insertRole($role, $backendAccess, $manageContentTypes, $manageContentCategories, $manageUsers)
    {
		$data = array(
			  'role'                      => $role
			, 'backend_access'            => $backendAccess
			, 'manage_content_types'      => $manageContentTypes
			, 'manage_content_categories' => $manageContentCategories
			, 'manage_users'              => $manageUsers
		);
        $this->db->insert($this->_tableName, $data);
		return $this->db->insert_id();
    }

    public function updateRole($idRole, $role, $backendAccess, $manageContentTypes, $manageContentCategories, $manageUsers)
    {
		$data = array(
			  'role'                      => $role
			, 'backend_access'            => $backendAccess
			, 'manage_content_types'      => $manageContentTypes
			, 'manage_content_categories' => $manageContentCategories
			, 'manage_users'              => $manageUsers
		);
		$this->db->where('id', $idRole);
        return $this->db->update($this->_tableName, $data);
    }

	public function deleteUserRole($idUserRole)
	{
		return $this->db->delete($this->_tableName, array('id' => $idUserRole));
	}
}
