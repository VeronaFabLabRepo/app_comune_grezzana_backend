<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * FM_User_Model
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data
 * 
 *
 * @package	Factotum
 * @author	Filippo Matteo Riggio (http://www.filippomatteoriggio.it)
 */
class FM_Users_Model extends CI_Model
{
	private $_tableName	= '';


	public function __construct()
	{
		parent::__construct();
		$ci =& get_instance();

		$this->_tableName = $ci->config->item('users_tbl', 'factotum');
	}

	/**
	 * Get the user list
	 */
	public function getUsersList()
	{
		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}


	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @return	array
	 */
	public function getUserById($idUser)
	{
		$query = $this->db->get_where($this->_tableName, array('id' => $idUser), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}


	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */
	public function getUserByLogin($login)
	{
		$this->db->where('LOWER(username)=', strtolower($login))
				 ->or_where('LOWER(email)=', strtolower($login));

		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	public function getUserByUsername($username)
	{
		$query = $this->db->get_where($this->_tableName, array('LOWER(username)' => strtolower($username)), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	public function getUserByEmail($email)
	{
		$query = $this->db->get_where($this->_tableName, array('LOWER(email)' => strtolower($email)), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	public function isUsernameAvailable($username)
	{
		$query = $this->db->get_where($this->_tableName, array('LOWER(username)' => $username), 1);
		return ($query->num_rows() == 0 ? TRUE : FALSE);
	}


	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	public function isEmailAvailable($email)
	{
		$this->db->where('LOWER(email)', $email)
				 ->or_where('LOWER(new_email)', $email);

		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() == 0 ? TRUE : FALSE);
	}

	/**
	 * Check if user is active or not
	 *
	 * @param	string
	 * @return	bool
	 */
	public function isUserActive($idUser)
	{
		$this->db->select('activated')->where(array('id' => $idUser));
		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	public function createUser($username, $email, $hashedPwd, $lastIp, $roleId, $newEmailKey = null)
	{

		$data = array(
			  'created'       => date('Y-m-d H:i:s')
			, 'activated'     => (!$newEmailKey ? TRUE : FALSE)
			, 'username'      => $username
			, 'password'      => $hashedPwd
			, 'email'         => $email
			, 'last_ip'       => $lastIp
			, 'id_user_role'  => $roleId
		);

		if ($newEmailKey) {

			$data['new_email_key'] = $newEmailKey;

		}

		$this->db->insert($this->_tableName, $data); 
		return $this->db->insert_id();

	}

	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	public function activateUser($idUser, $activationKey, $activateByEmail)
	{
		$this->db->select('1', FALSE)
				 ->where('id', $idUser);

		if ($activateByEmail) {

			$this->db->where('new_email_key', $activationKey);

		} else {

			$this->db->where('new_password_key', $activationKey);

		}

		$this->db->where('activated', 0);

		$query = $this->db->get($this->_tableName);

		if ($query->num_rows() == 1) {

			$data = array(
				  'activated' => 1
				, 'new_email_key' => NULL
			);

			$this->db->where('id', $idUser);

			return $this->db->update($this->_tableName, $data);

		}
		return FALSE;
	}


	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	public function purge_na($expirePeriod = 172800)
	{
		$this->db->where('activated', 0);
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expirePeriod);
		$this->db->delete($this->_tableName);
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	public function deleteUser($idUser)
	{
		return $this->db->delete($this->_tableName, array('id' => $idUser));
	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	public function setPasswordKey($idUser, $newPassKey)
	{
		$data = array(
			  'new_password_key'       => $newPassKey
			, 'new_password_requested' => date('Y-m-d H:i:s')
		);
		$this->db->where('id', $idUser);
		return $this->db->update($this->_tableName, $data);
	}


	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	public function canResetPassword($idUser, $newPassKey, $expirePeriod = 900)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $idUser);
		$this->db->where('new_password_key', $newPassKey);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expirePeriod);

		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() == 1 ? TRUE : FALSE);
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	public function resetPassword($idUser, $newPass, $newPassKey, $expirePeriod = 900)
	{
		$data = array(
			  'password'               => $newPass
			, 'new_password_key'       => NULL
			, 'new_password_requested' => NULL
		);
		$this->db->where('id', $idUser);
		$this->db->where('new_password_key', $newPassKey);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expirePeriod);

		return $this->db->update($this->_tableName, $data);
	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	public function changePassword($idUser, $newPass)
	{
		$data = array('password' => $newPass);
		$this->db->where('id', $idUser);

		return $this->db->update($this->_tableName, $data);
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	public function setNewEmail($idUser, $newEmail, $newEmailKey, $activated)
	{
		$data = array(
			  'new_email_key' => $newEmailKey
		);

		if ($activated) {

			$data['new_email'] = $newEmail;

		} else {

			$data['email'] = $newEmail;

		}

		$this->db->where('id', $idUser);
		$this->db->where('activated', $activated ? 1 : 0);

		return $this->db->update($this->_tableName, $data);
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	public function activateNewEmail($idUser, $newEmailKey)
	{
		$user = $this->getUserById($idUser);

		if ($user['new_email_key'] == $newEmailKey) {

			$data = array(
				    'email'         => $user['new_email']
				  , 'new_email'     => NULL
				  , 'new_email_key' => NULL
			);

			$this->db->where('id', $idUser);
			$this->db->where('new_email_key', $newEmailKey);
			return $this->db->update($this->_tableName, $data);

		}
	}


	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	public function updateLoginInfo($idUser, $recordIp, $recordTime)
	{
		$data = array(
			  'new_password_key'       => NULL
			, 'new_password_requested' => NULL
		);

		if ($recordIp) {

			$data['last_ip'] = $this->input->ip_address();

		}

		if ($recordTime) {

			$data['last_login'] = date('Y-m-d H:i:s');

		}

		$this->db->where('id', $idUser);
		$this->db->update($this->_tableName, $data);
	}


	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	public function banUser($idUser, $reason = NULL)
	{
		$data = array(
			  'banned'     => 1
			, 'ban_reason' => $reason
		);
		$this->db->where('id', $idUser);
		$this->db->update($this->_tableName, $data);
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	public function unbanUser($idUser)
	{
		$data = array(
			  'banned'     => 0
			, 'ban_reason' => NULL
		);
		$this->db->where('id', $idUser);
		$this->db->update($this->_tableName, $data);
	}


	/**
	 * Update user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	public function updateUser($idUser, $email, $idRole, $username = '')
	{
		$data = array(
			  'email'        => $email
			, 'id_user_role' => $idRole
		);

		if ($username != '') {

			$data['username'] = $username;

		}

		$this->db->where('id', $idUser);
		return $this->db->update($this->_tableName, $data);
	}

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */