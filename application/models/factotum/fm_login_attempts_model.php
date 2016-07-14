<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login_attempt
 *
 * This model serves to watch on all attempts to login on the site
 * (to protect the site from brute-force attack to user database)
 *
 * @package	Factotum
 * @author	Filippo Matteo Riggio (http://www.filippomatteoriggio.it)
 * 
 * @based on	Tank_Auth by Ilya Konyukhov (http://konyukhov.com/soft/tank_auth/)
 * 
 */
class FM_Login_Attempts_Model extends CI_Model
{
	private $_tableName = '';

	public function __construct()
	{
		parent::__construct();
		$ci =& get_instance();

		$this->_tableName = $ci->config->item('login_attempts_tbl', 'factotum');
	}


	/**
	 * Get number of attempts to login occured from given IP-address or login
	 *
	 * @param	string
	 * @param	string
	 * @return	int
	 */
	public function getAttemptsNum($ip_address, $login)
	{
		$this->db->select('1', FALSE)
				 ->where('ip_address', $ip_address);

		if (strlen($login) > 0) {

			$this->db->or_where('login', $login);

		}

		return $this->db->get($this->_tableName)->num_rows();
	}


	/**
	 * Increase number of attempts for given IP-address and login
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	public function increaseAttempt($ipAddress, $login)
	{
		return $this->db->insert($this->_tableName, array('ip_address' => $ipAddress
														, 'login'      => $login));
	}


	/**
	 * Clear all attempt records for given IP-address and login.
	 * Also purge obsolete login attempts (to keep DB clear).
	 *
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	public function clearAttempts($ipAddress, $login, $expirePeriod = 86400)
	{
		$this->db->where(array('ip_address' => $ipAddress, 'login' => $login));

		// Purge obsolete login attempts
		$this->db->or_where('UNIX_TIMESTAMP(time) <', time() - $expirePeriod);

		$this->db->delete($this->_tableName);
	}
}

/* End of file login_attempts.php */
/* Location: ./application/models/auth/login_attempts.php */