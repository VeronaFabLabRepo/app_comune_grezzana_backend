<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * FM_Auth
 *
 * Authentication library for Code Igniter.
 *
 * @package		FM_User_Management
 * @author		Filippo Matteo Riggio (http://www.filippomatteoriggio.it/)
 * @version		1.0.0
 * @based on	Tank Auth
 * 
 */
class FM_Users_Management
{
	private $error                = array(),
			$_statusActivated     = 1,
			$_statusNotActivated  = 0;


	public function __construct()
	{
		$this->ci =& get_instance();

		// Try to autologin
		$this->_autologin();
	}

	public function getErrorMessage()
	{
		return $this->error;
	}

	public function sendEmail($type, $email, $data, $subject = '')
	{
		if (!$subject) {
			$subject = sprintf($this->ci->lang->line('auth_subject_' . $type), $this->ci->config->item('website_name', 'factotum'));
		}

		$this->ci->email->from($this->ci->config->item('webmaster_email', 'factotum'), $this->ci->config->item('website_name', 'factotum'))
						->reply_to($this->ci->config->item('webmaster_email', 'factotum'), $this->ci->config->item('website_name', 'factotum'))
						->to($email)
						->subject($subject)
						->message($this->ci->load->view('emails/' . $type . '-html', $data, TRUE))
						->set_alt_message($this->ci->load->view('emails/' . $type . '-txt', $data, TRUE))
						->send();
	}




	// ==================== USER MANAGEMENT ===================== //

	// GETs
	public function getUsersList()
	{
		return $this->ci->user->getUsersList();
	}

	public function getUserById($id)
	{
		return $this->ci->user->getUserById($id);
	}

	public function getUserByLogin($login)
	{
		return $this->ci->user->getUserByLogin($login);
	}

	public function getUserByUsername($username)
	{
		return $this->ci->user->getUserByUsername($username);
	}

	public function getUserByEmail($email)
	{
		return $this->ci->user->getUserByEmail($email);
	}

	public function isUsernameAvailable($username)
	{
		return ((strlen($username) > 0) && $this->ci->user->isUsernameAvailable($username));
	}

	public function isUserActive($idUser)
	{
		return (($idUser > 0) AND $this->ci->user->isUserActive($idUser));
	}

	public function isEmailAvailable($email)
	{
		return ((strlen($email) > 0) && $this->ci->user->isEmailAvailable($email));
	}

	// INSERT
	public function insertUser($username, $email, $password, $roleId, $emailActivation)
	{
		if ((strlen($username) > 0) && !$this->isUsernameAvailable($username)) {

			$this->error = array('username' => 'auth_username_in_use');

		} elseif (!$this->isEmailAvailable($email)) {

			$this->error = array('email' => 'auth_email_in_use');

		} else {

			// Hash password
			$hashedPwd = $this->_hashPassword($password);

			$newEmailKey = ($emailActivation ? md5(rand() . microtime()) : null);

			$idUser = $this->ci->user->createUser(   $username
													, $email
													, $hashedPwd
													, $this->ci->input->ip_address()
													, $roleId
													, $newEmailKey);

			if (!is_null($idUser)) {

				$userData = $this->getUserById($idUser);

				unset($userData['last_ip']);
				$userData['password']    = $password;
				$userData['new_email_key'] = $newEmailKey;

				return $userData;

			}

		}

		return NULL;
	}

	// UPDATEs
	public function updateUser($idUser, $email, $roleId, $username = '')
	{
		$user = $this->getUserById($idUser);

		if ($user['email'] != $email || ($username != '' && $username != $user['username'])) {

			if ($user['email'] != $email && !$this->isEmailAvailable($email)) {

				$this->error = array('email' => 'auth_email_in_use');

			} else if ($username != '' && $username != $user['username'] && !$this->isUsernameAvailable($username)) {

				$this->error = array('email' => 'auth_username_in_use');

			} else {

				return $this->ci->user->updateUser($idUser, $email, $roleId, $username);

			}

			return TRUE;

		} else {

			return $this->ci->user->updateUser($idUser, $email, $roleId, $username);

		}

		return NULL;
	}

	public function activateUser($idUser, $activationKey, $activateByEmail = TRUE)
	{
		$this->ci->user->purge_na($this->ci->config->item('email_activation_expire', 'factotum'));

		if ((strlen($idUser) > 0) && (strlen($activationKey) > 0)) {

			return $this->ci->user->activateUser($idUser, $activationKey, $activateByEmail);

		}

		return FALSE;
	}

	public function changeEmail($email)
	{
		$idUser = $this->ci->session->userdata('id_user');

		$user = $this->getUserById($idUser);

		if (!is_null($user)) {

			$userData = array(
				  'id'        => $idUser
				, 'username'  => $user['username']
				, 'email'     => $email
			);

			// leave activation key as is
			if (strtolower($user['email']) == strtolower($email)) {

				$userData['new_email_key'] = $user['new_email_key'];

				return $userData;

			} elseif ($this->isEmailAvailable($email)) {

				$userData['new_email_key'] = md5(rand().microtime());

				$this->setNewEmail($idUser, $email, $userData['new_email_key'], FALSE);

				return $data;

			} else {

				$this->error = array('email' => 'auth_email_in_use');

			}

		}

		return NULL;
	}

	public function setNewEmail($idUser = null, $newEmail, $password)
	{
		if (!$idUser) {

			$idUser = $this->ci->session->userdata('id_user');

		}

		$userData = $this->getUserById($idUser);

		if (!is_null($userData)) {

			// Check if password correct
			if ($this->checkPassword($password, $userData['password'])) {

				$data = array(
					  'id'        => $userData['id']
					, 'username'  => $userData['username']
					, 'new_email' => $newEmail
				);

				if ($userData['email'] == $newEmail) {

					$this->error = array('email' => 'auth_current_email');

				} elseif ($userData['new_email'] == $newEmail) {

					// leave email key as is
					$data['new_email_key'] = $userData['new_email_key'];

					return $data;

				} elseif ($this->isEmailAvailable($newEmail)) {

					$data['new_email_key'] = md5(rand().microtime());

					$this->ci->user->setNewEmail($idUser, $newEmail, $data['new_email_key'], TRUE);

					return $data;

				} else {

					$this->error = array('email' => 'auth_email_in_use');

				}

			} else {

				$this->error = array('password' => 'auth_incorrect_password');

			}

		}

		return NULL;
	}

	public function activateNewEmail($idUser, $newEmailKey)
	{
		if ((strlen($idUser) > 0) AND (strlen($newEmailKey) > 0)) {

			return $this->ci->user->activateNewEmail($idUser, $newEmailKey);

		}

		return FALSE;
	}

	public function changePassword($oldPass, $newPass)
	{
		$idUser = $this->ci->session->userdata('id_user');

		$user = $this->getUserById($idUser);

		if (!is_null($user)) {

			// success
			if ($this->checkPassword($oldPass, $user['password'])) {

				$this->updatePassword($idUser, $newPass);

				return TRUE;

			} else {

				// fail
				$this->error = array('old_password' => 'auth_incorrect_password');

			}

		}

		return FALSE;
	}

	public function updatePassword($idUser, $password)
	{
		$hashedPwd = $this->_hashPassword($password);

		// Replace old password with new one
		$this->ci->user->changePassword($idUser, $hashedPwd);
	}

	public function resetPassword($idUser, $newPassKey, $newPassword)
	{
		if ((strlen($idUser) > 0) AND (strlen($newPassKey) > 0) AND (strlen($newPassword) > 0)) {

			$user = $this->getUserById($idUser);

			if (!is_null($user)) {

				$hashedPwd = $this->_hashPassword($newPassword);

				$result = $this->ci->user->resetPassword($idUser, $hashedPwd, $newPassKey, $this->ci->config->item('forgot_password_expire', 'factotum'));

				if ($result) {

					// Clear all user's autologins
					$this->ci->user_autologin->clear($user['id']);

					return array(
						  'id'           => $idUser
						, 'username'     => $user['username']
						, 'email'        => $user['email']
						, 'new_password' => $newPassword
					);

				}

			}

		}

		return NULL;
	}

	public function setPasswordKey($idUser, $newPassKey)
	{
		return $this->ci->user->setPasswordKey($idUser, $newPassKey);
	}


	// DELETE
	public function deleteAccount($password)
	{
		$idUser = $this->ci->session->userdata('id_user');

		$user = $this->getUserById($idUser);

		if (!is_null($user)) {

			// Check if password correct
			if ($this->checkPassword($password, $user['password'])) {

				$this->deleteUser($idUser);

				$this->logout();

				return TRUE;

			} else {

				$this->error = array('password' => 'auth_incorrect_password');

			}

		}
		return FALSE;

	}

	public function deleteUser($idUser)
	{
		return $this->ci->user->deleteUser($idUser);
	}

	// ======================================================================= //



	// ==================== USER AUTH MANAGEMENT ===================== //

	// BASIC FUNCTIONS
	private function _hashPassword($pwd)
	{
		return md5(
			$this->ci->config->item('salt',   'factotum') .
			$this->ci->config->item('pepper', 'factotum') .
			$pwd
		);
	}

	private function _updateLoginInfo($idUser)
	{
		return $this->ci->user->updateLoginInfo($idUser, $this->ci->config->item('login_record_ip', 'factotum'), $this->ci->config->item('login_record_time', 'factotum'));
	}

	public function convertCapabilities($capabilities)
	{
		$result = array();

		if ($capabilities && count($capabilities) > 0) {

			foreach ($capabilities as $capability) {

				$result[$capability['id_content_type']] = array(
					  'configure' => $capability['configure']
					, 'edit'      => $capability['edit'] 
					, 'publish'   => $capability['publish']  
				);

			}

		}

		return $result;
	}

	public function createCaptcha()
	{
		$cap = create_captcha(array(
			  'img_path'    => './' . $this->ci->config->item('captcha_path', 'factotum')
			, 'img_url'     => base_url() . $this->ci->config->item('captcha_path', 'factotum')
			, 'font_path'   => './' . $this->ci->config->item('captcha_fonts_path', 'factotum')
			, 'font_size'   => $this->ci->config->item('captcha_font_size', 'factotum')
			, 'img_width'   => $this->ci->config->item('captcha_width',     'factotum')
			, 'img_height'  => $this->ci->config->item('captcha_height',    'factotum')
			, 'show_grid'   => $this->ci->config->item('captcha_grid',      'factotum')
			, 'expiration'  => $this->ci->config->item('captcha_expire',    'factotum')
		));

		// Save captcha params in session
		$this->ci->session->set_flashdata(array(
			  'captcha_word' => $cap['word']
			, 'captcha_time' => $cap['time']
		));

		return $cap['image'];
	}

	public function createRecaptcha()
	{
		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'red' };</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->ci->config->item('recaptcha_public_key', 'factotum'));

		return $options . $html;
	}

	public function checkCaptcha($code)
	{
		$result = null;

		$time = $this->ci->session->flashdata('captcha_time');
		$word = $this->ci->session->flashdata('captcha_word');
		list($usec, $sec) = explode(" ", microtime());

		$captchaExpire        = $this->ci->config->item('captcha_expire',         'factotum');
		$captchaCaseSensitive = $this->ci->config->item('captcha_case_sensitive', 'factotum');

		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $captchaExpire) {

			$result = 'auth_captcha_expired';

		} elseif (($captchaCaseSensitive && $code != $word) || strtolower($code) != strtolower($word)) {

			$result = 'auth_incorrect_captcha';

		}

		return $result;
	}

	public function checkRecaptcha()
	{
		$result = null;

		$recaptchaPrivateKey = $this->ci->config->item('recaptcha_private_key', 'factotum');

		$resp = recaptcha_check_answer($recaptchaPrivateKey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {

			$result = 'auth_incorrect_captcha';

		}
		return $result;
	}

	// AUTH FUNCTIONS
	public function login($login, $password, $remember, $loginByUsername, $loginByEmail)
	{
		if ((strlen($login) > 0) AND (strlen($password) > 0)) {

			// Which function to use to login (based on config)
			if ($loginByUsername AND $loginByEmail) {

				$getUserFunc = 'getUserByLogin';

			} else if ($loginByUsername) {

				$getUserFunc = 'getUserByUsername';

			} else {

				$getUserFunc = 'getUserByEmail';

			}

			$user = $this->$getUserFunc($login);

			// login ok
			if (!is_null($user)) {

				// Does password match hash in database?
				if ($this->checkPassword($password, $user['password'])) {

					// password ok
					if ($user['banned'] == 1) {

						// fail - banned
						$this->error = array('banned' => $user['ban_reason']);

					} else {

						$role = $this->getRoleByRoleId($user['id_user_role']);
						$capabilities = $this->getCapabilitiesByIdUserRole($role['id']);

						$this->ci->session->set_userdata(array(
								  'id_user'	                  => $user['id']
								, 'username'                  => $user['username']
								, 'status'	                  => ($user['activated'] == 1) ? $this->_statusActivated : $this->_statusNotActivated
								, 'role'                      => $role['role']
								, 'access_backend'            => $role['backend_access']
								, 'manage_content_types'      => $role['manage_content_types']
								, 'manage_content_categories' => $role['manage_content_categories']
								, 'manage_users'              => $role['manage_users']
								, 'capabilities'              => $capabilities
						));

						if ($user['activated'] == 0) {

							// fail - not activated
							$this->error = array('not_activated' => '');

						} else {

							// success
							if ($remember) {

								$this->_createAutologin($user['id']);

							}

							$this->_clearLoginAttempts($login);

							$this->_updateLoginInfo($user['id']);

							return TRUE;
						
						}

					}

				} else {

					// fail - wrong password
					$this->_increaseLoginAttempt($login);
					$this->error = array('password' => 'auth_incorrect_password');

				}

			} else {

				// fail - wrong login
				$this->_increaseLoginAttempt($login);
				$this->error = array('login' => 'auth_incorrect_login');

			}

		}

		return FALSE;

	}

	public function logout()
	{
		$this->_deleteAutologin();

		// See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
		$this->ci->session->set_userdata(array('id_user'              => ''
											 , 'username'             => ''
											 , 'status'               => ''
											 , 'role'                 => ''
											 , 'access_backend'       => ''
											 , 'manage_content_types' => ''
											 , 'manage_users'         => ''
											 , 'capabilities'         => ''
		));
		$this->ci->session->sess_destroy();
	}

	public function forgotPassword($login)
	{
		if (strlen($login) > 0) {

			$user = $this->getUserByLogin($login);

			if (!is_null($user)) {

				$data = array(
					  'id'           => $user['id']
					, 'username'     => $user['username']
					, 'email'        => $user['email']
					, 'new_pass_key' => md5(rand().microtime())
				);

				$this->setPasswordKey($user['id'], $data['new_pass_key']);

				return $data;

			} else {
				$this->error = array('login' => 'auth_incorrect_email_or_username');
			}
		}
		return NULL;
	}


	// CHECK FUNCTIONS
	public function isLoggedIn()
	{
		return ($this->ci->session->userdata('status') === $this->_statusActivated);
	}

	public function isLoggedInButNotActive()
	{
		return ($this->ci->session->userdata('status') === $this->_statusNotActivated);
	}

	public function isMaxLoginAttemptsExceeded($login, $loginCountAttempts, $loginMaxAttempts)
	{
		if ($loginCountAttempts) {

			return $this->ci->login_attempt->getAttemptsNum($this->ci->input->ip_address(), $login) >= $loginMaxAttempts;
		}

		return FALSE;
	}

	public function canAccessBackend()
	{
		return ($this->ci->session->userdata('access_backend') == 1 ? TRUE : FALSE);
	}

	public function canManageContentTypes()
	{
		return ($this->ci->session->userdata('manage_content_types') == 1 ? TRUE : FALSE);
	}

	public function canManageContentCategories()
	{
		return ($this->ci->session->userdata('manage_content_categories') == 1 ? TRUE : FALSE);
	}

	public function canManageUsers()
	{
		return ($this->ci->session->userdata('manage_users') == 1 ? TRUE : FALSE);
	}

	public function canConfigureContentType($idContentType)
	{
		$capabilities = $this->convertCapabilities($this->getLoggedUserCapabilities());
		return (isset($capabilities[$idContentType]) && $capabilities[$idContentType]['configure'] ? TRUE : FALSE);
	}

	public function canEditContentType($idContentType)
	{
		$capabilities = $this->convertCapabilities($this->getLoggedUserCapabilities());
		return (isset($capabilities[$idContentType]) && $capabilities[$idContentType]['edit'] ? TRUE : FALSE);
	}

	public function canPublishContentType($idContentType)
	{
		$capabilities = $this->convertCapabilities($this->getLoggedUserCapabilities());
		return (isset($capabilities[$idContentType]) && $capabilities[$idContentType]['configure'] ? TRUE : FALSE);
	}

	public function checkPassword($pwdProvided, $userPwd)
	{
		$hashedPwd = $this->_hashPassword($pwdProvided);
		return ($hashedPwd == $userPwd ? TRUE : FALSE);
	}

	public function canResetPassword($idUser, $newPassKey)
	{
		if ((strlen($idUser) > 0) AND (strlen($newPassKey) > 0)) {

			return $this->ci->user->canResetPassword(
				$idUser,
				$newPassKey,
				$this->ci->config->item('forgot_password_expire', 'factotum'));

		}

		return FALSE;
	}


	// LOGGED USER FUNCTIONS
	public function getLoggedUserId()
	{
		return $this->ci->session->userdata('id_user');
	}

	public function getLoggedUsername()
	{
		return $this->ci->session->userdata('username');
	}

	public function getLoggedUserRole()
	{
		return $this->ci->session->userdata('role');
	}

	public function getLoggedUserCapabilities()
	{
		return $this->ci->session->userdata('capabilities');
	}

	// AUTOLOGIN FUNCTIONS
	private function _autologin()
	{
		// not logged in (as any user)
		if (!$this->isLoggedIn() && !$this->isLoggedInButNotActive()) {

			if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'factotum'), TRUE)) {

				$data = unserialize($cookie);

				if (isset($data['key']) && isset($data['id_user'])) {

					$user = $this->ci->user_autologin->get($data['id_user'], md5($data['key']));

					if (!is_null($user)) {

						// Login user
						$this->ci->session->set_userdata(array(
								  'id_user'	  => $user['id']
								, 'username'  => $user['username']
								, 'status'    => $this->_statusActivated
						));

						// Renew users cookie to prevent it from expiring
						set_cookie(array(
								  'name'   => $this->ci->config->item('autologin_cookie_name', 'factotum')
								, 'value'  => $cookie
								, 'expire' => $this->ci->config->item('autologin_cookie_life', 'factotum')
						));

						$this->ci->user->update_login_info(
								  $user->id
								, $this->ci->config->item('login_record_ip',   'factotum')
								, $this->ci->config->item('login_record_time', 'factotum'));

						return TRUE;

					}

				}

			}

		}

		return FALSE;
	}

	private function _createAutologin($idUser)
	{
		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

		$this->_purge($idUser);

		if ($this->ci->user_autologin->set($idUser, md5($key))) {

			set_cookie(array(
					  'name'   => $this->ci->config->item('autologin_cookie_name', 'factotum')
					, 'value'  => serialize(array(
												  'user_id' => $idUser
												, 'key'     => $key)
											)
					, 'expire' => $this->ci->config->item('autologin_cookie_life', 'factotum'),
			));

			return TRUE;

		}

		return FALSE;
	}

	private function _deleteAutologin()
	{
		$cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'factotum'), TRUE);

		if ($cookie) {

			$data = unserialize($cookie);

			$this->ci->user_autologin->delete($data['id_user'], md5($data['key']));

			delete_cookie($this->ci->config->item('autologin_cookie_name', 'factotum'));

		}
	}

	private function _increaseLoginAttempt($login)
	{
		$loginCountAttempts = $this->ci->config->item('login_count_attempts', 'factotum');
		$loginMaxAttempts   = $this->ci->config->item('login_max_attempts',   'factotum');

		if ($loginCountAttempts) {

			if (!$this->isMaxLoginAttemptsExceeded($login, $loginCountAttempts, $loginMaxAttempts)) {

				$this->_increaseAttempt($this->ci->input->ip_address(), $login);

			}
		}
	}

	private function _clearLoginAttempts($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'factotum')) {

			$this->_clearAttempts($this->ci->input->ip_address(), $login, $this->ci->config->item('login_attempt_expire', 'factotum'));

		}
	}

	private function _increaseAttempt($ipAddress, $login)
	{
		return $this->ci->login_attempt->increaseAttempt($this->ci->input->ip_address(), $login);
	}

	private function _clearAttempts($ipAddress, $login, $loginAttemptExpire)
	{
		return $this->ci->login_attempt->clearAttempts($ipAddress, $login, $loginAttemptExpire);
	}


	private function _purge($idUser)
	{
		return $this->ci->user_autologin->purge($idUser);
	}

	// ======================================================================= //



	// ==================== USER PROFILE MANAGEMENT ===================== //

	public function getUserProfileByIdUser($idUser)
	{
		return $this->ci->user_profile->getUserProfileByIdUser($idUser);
	}

	/**
	 * Insert profile infos
	 *
	 * @param	int        user id
	 * @param	array      data
	 * @return	void
	 */
	public function insertProfile($idUser, $data)
	{
		$data['id_user'] = $idUser;
		return $this->ci->user_profile->insertProfile($data);
	}

	/**
	 * Update profile infos
	 *
	 * @param	int        user id
	 * @param	array      data
	 * @return	void
	 */
	public function updateProfile($data, $idUser)
	{
		return $this->ci->user_profile->updateProfile($idUser, $data);
	}

	// ======================================================================= //




	// ==================== USER ROLES MANAGEMENT ===================== //

	public function getUserRolesList()
	{
		return $this->ci->user_role->getUserRolesList();
	}

	public function checkRoleName($role)
	{
		return ((strlen($role) > 0) && $this->ci->user_role->isRoleNameAvailable($role));
	}

	public function getRoleByRoleId($idUserRole)
	{
		return $this->ci->user_role->getRoleById($idUserRole);
	}

	public function getRoleByRoleName($roleName)
	{
		return $this->ci->user_role->getRoleByRoleName($roleName);
	}

	public function insertRole($role, $backendAccess, $manageContentTypes, $manageContentCategories, $manageUsers)
	{
		return $this->ci->user_role->insertRole($role, $backendAccess, $manageContentTypes, $manageContentCategories, $manageUsers);
	}

	public function updateRole($idRole, $role, $backendAccess, $manageContentTypes ,$manageContentCategories, $manageUsers)
	{
		return $this->ci->user_role->updateRole($idRole, $role, $backendAccess, $manageContentTypes, $manageContentCategories, $manageUsers);
	}

	public function deleteUserRole($idUserRole)
	{
		return $this->ci->user_role->deleteUserRole($idUserRole);
	}

	// ======================================================================= //



	// ==================== USER CAPABILITIES MANAGEMENT ===================== //

	public function resetLoggedCapabilities()
	{
		$role = $this->getLoggedUserRole();
		$role = $this->getRoleByRoleName($role);
		$capabilities = $this->getCapabilitiesByIdUserRole($role['id']);

		$this->ci->session->set_userdata(array('capabilities' => $capabilities));
	}

	public function getCapabilitiesByIdUserRole($idUserRole)
	{
		return $this->ci->user_capability->getCapabilitiesByIdUserRole($idUserRole);
	}

	public function insertCapabilities($idUserRole, $idContentType, $configure, $edit, $publish)
	{
		return $this->ci->user_capability->insertCapabilities($idUserRole, $idContentType, $configure, $edit, $publish);
	}

	public function updateCapabilities($idUserRole, $idContentType, $configure, $edit, $publish)
	{
		return $this->ci->user_capability->updateCapabilities($idUserRole, $idContentType, $configure, $edit, $publish);
	}

	public function existCapabilities($idUserRole, $idContentType)
	{
		return $this->ci->user_capability->existCapabilities($idUserRole, $idContentType);
	}

	public function deleteUserCapabilities($idUserRole)
	{
		return $this->ci->user_capability->deleteUserCapabilities($idUserRole);
	}

	public function deleteUserCapabilitiesByIdContentType($idContentType)
	{
		return $this->ci->user_capability->deleteUserCapabilitiesByIdContentType($idContentType);
	}
	// ======================================================================= //
}
