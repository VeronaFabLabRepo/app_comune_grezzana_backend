<?php
class FM_User_Profiles_Model extends CI_Model {

	private $_tableName = '';

    public function __construct()
	{
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName = $ci->config->item('user_profiles_tbl', 'factotum');
    }


	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	public function getUserProfileByIdUser($idUser)
	{
		$query = $this->db->get_where($this->_tableName, array('id_user' => $idUser), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}


	public function insertProfile($data)
	{
		return $this->db->insert($this->_tableName, $data);
	}


	public function updateProfile($data, $idUser)
	{
		return $this->db->update($this->_tableName, $data, array('id_user' => $idUser));
	}


	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	public function deleteProfile($idUser)
	{
		$this->db->delete($this->_tableName, array('id_user' => $idUser));
	}

}
