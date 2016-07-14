<?php
class FM_User_Capabilities_Model extends CI_Model {

	private $_tableName = '';

    public function __construct()
	{
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName = $ci->config->item('user_capabilities_tbl', 'factotum');
    }

    public function insertCapabilities($idUserRole, $idContentType, $configure, $edit, $publish)
    {
		$data = array(
			  'id_user_role'    => $idUserRole
			, 'id_content_type' => $idContentType
			, 'configure'       => $configure
			, 'edit'            => $edit
			, 'publish'         => $publish
		);
        $this->db->insert($this->_tableName, $data);
		return $this->db->insert_id();
    }

    public function updateCapabilities($idUserRole, $idContentType, $configure, $edit, $publish)
    {
		$data = array(
			  'configure'       => $configure
			, 'edit'            => $edit
			, 'publish'         => $publish
		);
		$this->db->where('id_user_role', $idUserRole);
		$this->db->where('id_content_type', $idContentType);
        return $this->db->update($this->_tableName, $data);
    }

	public function getCapabilitiesByIdUserRole($idUserRole)
	{
		$query = $this->db->get_where($this->_tableName, array('id_user_role' => $idUserRole));
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function existCapabilities($idUserRole, $idContentType)
	{
		$query = $this->db->get_where($this->_tableName, array('id_user_role' => $idUserRole, 'id_content_type' => $idContentType), 1);
		return ($query->num_rows() == 1 ? TRUE : FALSE);
	}

	public function deleteUserCapabilities($idUserRole)
	{
		return $this->db->delete($this->_tableName, array('id_user_role' => $idUserRole));
	}

	public function deleteUserCapabilitiesByIdContentType($idContentType)
	{
		return $this->db->delete($this->_tableName, array('id_content_type' => $idContentType));
	}
}
