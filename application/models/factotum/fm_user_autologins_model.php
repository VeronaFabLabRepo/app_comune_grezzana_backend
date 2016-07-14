<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User_Autologin
 *
 * This model represents user autologin data. It can be used
 * for user verification when user claims his autologin passport.
 *
 * @package	Factotum
 * @author	Filippo Matteo Riggio (http://www.filippomatteoriggio.it)
 * 
 * @based on	Tank_Auth by Ilya Konyukhov (http://konyukhov.com/soft/tank_auth/)
 */
class FM_User_Autologins_Model extends CI_Model
{
	private $_tableName			= '',
			$_usersTableName	= '';

	public function __construct()
	{
		parent::__construct();
		$ci =& get_instance();

		$this->_tableName      = $ci->config->item('user_autologins_tbl', 'factotum');
		$this->_usersTableName = $ci->config->item('users_tbl',           'factotum');
	}

	/**
	 * Get user data for auto-logged in user.
	 * Return NULL if given key or user ID is invalid.
	 *
	 * @param	int
	 * @param	string
	 * @return	object
	 */
	public function get($idUser, $key)
	{
		$this->db->select($this->_usersTableName . '.id, ' . $this->_usersTableName . '.username')
				 ->from($this->_usersTableName)
				 ->join($this->_tableName, $this->_tableName . '.user_id = ' . $this->_usersTableName . '.id')
				 ->where($this->_tableName . '.user_id', $idUser)
				 ->where($this->table_name.'.key_id', $key);

		$query = $this->db->get();

		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}


	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	public function set($idUser, $key)
	{
		$data = array(
			  'user_id'    => $idUser
			, 'key_id'     => $key
			, 'user_agent' => substr($this->input->user_agent(), 0, 149)
			, 'last_ip'    => $this->input->ip_address()			
		);

		return $this->db->insert($this->_tableName, $data);
	}

	/**
	 * Delete user's autologin data
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	public function delete($idUser, $key)
	{
		$this->db->where('user_id', $idUser);
		$this->db->where('key_id', $key);
		$this->db->delete($this->_tableName);
	}

	/**
	 * Delete all autologin data for given user
	 *
	 * @param	int
	 * @return	void
	 */
	public function clear($idUser)
	{
		$this->db->where('user_id', $idUser);
		$this->db->delete($this->_tableName);
	}

	/**
	 * Purge autologin data for given user and login conditions
	 *
	 * @param	int
	 * @return	void
	 */
	public function purge($idUser)
	{
		$this->db->where(array(
				  'user_id'    => $idUser
				, 'user_agent' => substr($this->input->user_agent(), 0, 149)
				, 'last_ip'    => $this->input->ip_address()
		));
		$this->db->delete($this->_tableName);
	}
}

/* End of file user_autologin.php */
/* Location: ./application/models/auth/user_autologin.php */