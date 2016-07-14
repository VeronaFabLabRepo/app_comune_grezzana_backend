<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends FM_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function login()
	{
		$errors    = array();
		$oldValues = array();

		$loginCountAttempts = $this->config->item('login_count_attempts', 'factotum');
		$loginMaxAttempts   = $this->config->item('login_max_attempts',   'factotum');

		// Loading basic data into the view
		$this->data['loginByUsername']   = $this->config->item('login_by_username',  'factotum') && $this->config->item('use_username', 'factotum');
		$this->data['loginByEmail']      = $this->config->item('login_by_email',     'factotum');
		$this->data['useRecaptcha']      = $this->config->item('use_recaptcha',      'factotum');
		$this->data['allowRegistration'] = $this->config->item('allow_registration', 'factotum');

		$oldValues['login']    = '';
		$oldValues['remember'] = '';

		// logged in
		if ($this->fm_users_management->isLoggedIn() && $this->fm_users_management->canAccessBackend()) {

			redirect('/');

		} elseif ($this->fm_users_management->isLoggedInButNotActive() && $this->fm_users_management->canAccessBackend()) {

			// logged in, but not activated
			redirect('/send-again');

		} else {

			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_message('login', $this->lang->line('auth_incorrect_login'));

			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_message('password', $this->lang->line('auth_incorrect_password'));

			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($loginCountAttempts && ($login = $this->input->post('login'))) {

				$login = $this->security->xss_clean($login);

			} else {

				$login = '';

			}

			if ($this->fm_users_management->isMaxLoginAttemptsExceeded($login, $loginCountAttempts, $loginMaxAttempts)) {

				if ($this->data['useRecaptcha']) {

					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback_checkRecaptcha');

				} else {

					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback_checkCaptcha');

				}

			}

			$oldValues['login']    = $this->input->post('login');
			$oldValues['password'] = $this->input->post('password');
			$oldValues['remember'] = $this->input->post('remember');

			// validation ok
			if ($this->form_validation->run()) {

				// success
				if ($this->fm_users_management->login($oldValues['login'], $oldValues['password'], $oldValues['remember'], $this->data['loginByUsername'], $this->data['loginByEmail'])) {

					redirect('/');

				} else {

					$errors = $this->fm_users_management->getErrorMessage();

					// banned user
					if (isset($errors['banned'])) {

						$errors['banned'] = $this->lang->line('auth_message_banned') . ' ' . $errors['banned'];

					// not activated user
					} elseif (isset($errors['not_activated'])) {

						redirect('/send-again/');

					} else {
						
						// fail
						foreach ($errors as $k => $v) {

							$errors[$k] = $this->lang->line($v);

						}

					}

				}

			} else {

				$errors['login']    = form_error('login');
				$errors['password'] = form_error('password');
				$errors['captcha']  = form_error('captcha');
				$errors['recaptcha_response_field']  = form_error('recaptcha_response_field');

			}

			$this->data['showCaptcha'] = FALSE;

			if ($this->fm_users_management->isMaxLoginAttemptsExceeded($login, $loginCountAttempts, $loginMaxAttempts)) {

				$this->data['showCaptcha'] = TRUE;

				if ($this->data['useRecaptcha']) {

					$this->data['recaptchaHtml'] = $this->fm_users_management->createRecaptcha();

				} else {

					$this->data['captchaHtml']   = $this->fm_users_management->createCaptcha();

				}

			}

		}

		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;

	}


	/**
	 * Logout user
	 *
	 * @return void
	 */
	public function logout()
	{
		$this->view = FALSE;
		$this->fm_users_management->logout();
		redirect('/');
	}


	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	public function register()
	{
		$oldValues  = array();
		$errors     = array();

		$allowRegistration   = $this->config->item('allow_registration',      'factotum');
		$useUsername         = $this->config->item('use_username',            'factotum');
		$usernameMinLength   = $this->config->item('username_min_length',     'factotum');
		$usernameMaxLength   = $this->config->item('username_max_length',     'factotum');
		$passwordMinLength   = $this->config->item('password_min_length',     'factotum');
		$passwordMaxLength   = $this->config->item('password_max_length',     'factotum');
		$emailActivation     = $this->config->item('email_activation',        'factotum');
		$activationPeriod    = $this->config->item('email_activation_expire', 'factotum');
		$emailAccountDetails = $this->config->item('email_account_details',   'factotum');
		$siteName            = $this->config->item('website_name',            'factotum');
		$captchaRegistration = $this->config->item('captcha_registration',    'factotum');
		$useRecaptcha        = $this->config->item('use_recaptcha',           'factotum');

		$role = $this->fm_users_management->getRoleByRoleName('user');

		$this->data['useUsername']          = $useUsername;
		$this->data['captchaRegistration']  = $captchaRegistration;
		$this->data['useRecaptcha']         = $useRecaptcha;
			
		// logged in
		if ($this->fm_users_management->isLoggedIn()) {

			redirect('/');

		} elseif ($this->fm_users_management->isLoggedInButNotActive()) {

			// logged in, not activated
			redirect('/send-again/');

		} elseif (!$allowRegistration) {

			// registration is off
			$errors['generic'] = $this->lang->line('auth_message_registration_disabled');

		} else {

			if ($useUsername) {

				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length[' . $usernameMinLength . ']|max_length[' . $usernameMaxLength . ']|alpha_dash');

			}

			if ($captchaRegistration) {

				if ($useRecaptcha) {

					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback_checkRecaptcha');
					$this->data['recaptchaHtml'] = $this->fm_users_management->createRecaptcha();

				} else {

					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback_checkCaptcha');
					$this->data['captchaHtml'] = $this->fm_users_management->createCaptcha();

				}

			}


			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_message('email', $this->lang->line('auth_incorrect_email'));

			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length[' . $passwordMinLength . ']|max_length[' . $passwordMaxLength . ']|alpha_dash');
			$this->form_validation->set_message('password', $this->lang->line('auth_incorrect_password'));

			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
			$this->form_validation->set_message('confirm_password', $this->lang->line('auth_incorrect_confirm_password'));

			// Profile Fields
			$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required|xss_clean');
			$this->form_validation->set_message('firstname', $this->lang->line('auth_incorrect_firstname'));
	
			$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required|xss_clean');
			$this->form_validation->set_message('lastname', $this->lang->line('auth_incorrect_lastname'));
	
			$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|callback_checkDob');
			$this->form_validation->set_message('dob', $this->lang->line('auth_incorrect_dob'));

			$oldValues['username']  = '';
			$oldValues['email']     = '';

			// Profile Fields
			$oldValues['firstname'] = '';
			$oldValues['lastname']  = '';
			$oldValues['dob']       = '';

			if ($this->input->post('register')) {

				$oldValues['username'] = ($useUsername ? $this->input->post('username') : '');
				$oldValues['email']    = $this->input->post('email');

				// Profile Fields
				$oldValues['firstname'] = $this->input->post('firstname');
				$oldValues['lastname']  = $this->input->post('lastname');
				$oldValues['dob']       = $this->input->post('dob');

				// validation ok
				if ($this->form_validation->run()) {

					$password = $this->form_validation->set_value('password');

					$userData = $this->fm_users_management->insertUser($oldValues['username'], $oldValues['email'], $password, $role['id'], $emailActivation);

					$profileData = array(
						  'firstname' => $oldValues['firstname']
						, 'lastname'  => $oldValues['lastname']
						, 'dob'       => FM_Utility::convertHumanDateToIso($oldValues['dob'])
					);

					// success
					if (!is_null($userData)) {

						// Insert the profile for the user
						$this->fm_users_management->insertProfile($userData['id'], $profileData);

						$userData['siteName']         = $siteName;
						$userData['activationPeriod'] = $activationPeriod / 3600;

						if ($emailActivation) {
	
							// send "activate" email
							$this->fm_users_management->sendEmail('activate', $userData['email'], $userData);
	
							// Clear password (just for any case)
							unset($userData['password']);

						} else {
	
							// send "welcome" email
							if ($emailAccountDetails) {

								$this->fm_users_management->sendEmail('welcome', $userData['email'], $userData);

							}
	
							// Clear password (just for any case)
							unset($userData['password']);

						}

						redirect('/registration-complete');

					} else {
	
						$authErrors = $this->fm_users_management->getErrorMessage();

						foreach ($authErrors as $index => $errMsg) {
	
							$errors[$index] = $this->lang->line($errMsg);

						}
	
					}
	
				} else {
	
					if ($useUsername) {
	
						$errors['username'] = form_error('username');

					}
	
					$errors['email']                     = form_error('email');
					$errors['password']                  = form_error('password');
					$errors['confirm_password']          = form_error('confirm_password');
					$errors['captcha']                   = form_error('captcha');
					$errors['recaptcha_response_field']  = form_error('recaptcha_response_field');

					// Profiles Fields Errors
					$errors['firstname']                 = form_error('firstname');
					$errors['lastname']                  = form_error('lastname');
					$errors['dob']                       = form_error('dob');

				}

			}

		}

		$this->data['errors']     = $errors;
		$this->data['oldValues']  = $oldValues;

	}

	/**
	 * Registration complete page
	 * 
	 */
	public function registrationComplete()
	{
		$emailActivation = $this->config->item('email_activation', 'factotum');

		if ($emailActivation) {

			$this->data['welcomeMessage'] = $this->lang->line('auth_message_registration_completed_1');

		} else {

			$this->data['welcomeMessage'] = $this->lang->line('auth_message_registration_completed_2') . ' ' . anchor('/auth/login/', 'Login');

		}
	}


	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	public function sendAgain()
	{
		$errors    = array();
		$oldValues = array();

		$activationPeriod    = $this->config->item('email_activation_expire', 'factotum');
		$siteName            = $this->config->item('website_name',            'factotum');

		// not logged in or activated
		if (!$this->fm_users_management->isLoggedInButNotActive()) {

			redirect('/login');

		} else {

			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$oldValues['email'] = '';

			if ($this->input->post('send_again')) {

				$oldValues['email'] = $this->input->post('email');
				
				// validation ok
				if ($this->form_validation->run()) {

					$userData = $this->fm_users_management->changeEmail($oldValues['email']);

					// success
					if (!is_null($userData)) {

						$userData['siteName']         = $siteName;
						$userData['activationPeriod'] = $activationPeriod / 3600;

						$this->fm_users_management->sendEmail('activate', $userData['email'], $userData);

						$this->data['successMessage'] = sprintf($this->lang->line('auth_message_activation_email_sent'), $userData['email']);

					} else {

						$authErrors = $this->fm_users_management->getErrorMessage();

						foreach ($authErrors as $index => $errMsg) {
	
							$errors[$index] = $this->lang->line($errMsg);

						}

					}

				} else {

					$errors['email'] = form_error('email');

				}

			}

		}

		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;
	}


	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	public function activateUser()
	{
		$idUser      = $this->uri->segment(2);
		$newEmailKey = $this->uri->segment(3);

		// Activate user
		if ($this->fm_users_management->activateUser($idUser, $newEmailKey)) {

			$this->fm_users_management->logout();
			$this->data['message'] = $this->lang->line('auth_message_activation_completed') . ' ' . anchor('/login', 'Login');

		} else {

			$this->data['error'] = $this->lang->line('auth_message_activation_failed');

		}
	}



	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	public function forgotPassword()
	{
		$errors = array();

		$siteName    = $this->config->item('website_name', 'factotum');
		$useUsername = $this->config->item('use_username', 'factotum');

		$this->data['useUsername'] = $useUsername;

		// logged in
		if ($this->fm_users_management->isLoggedIn()) {

			redirect('');

		// logged in, not activated
		} elseif ($this->fm_users_management->isLoggedInButNotActive()) {

			redirect('/send-again');

		} else {

			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');

			$oldValues['login'] = $this->input->post('login');

			if ($this->input->post('send_email')) {

				// validation ok
				if ($this->form_validation->run()) {
	
					$userData = $this->fm_users_management->forgotPassword($oldValues['login']);

					if (!is_null($userData)) {
	
						$userData['siteName'] = $siteName;
						$userData['baseUrl']  = 'reset-password';

						// Send email with password activation link
						$this->fm_users_management->sendEmail('forgot_password', $userData['email'], $userData);

						$this->data['successMessage'] = $this->lang->line('auth_message_new_password_sent');

					} else {
	
						$errors = $this->fm_users_management->getErrorMessage();

						$authErrors = $this->fm_users_management->getErrorMessage();

						foreach ($authErrors as $index => $errMsg) {
	
							$errors[$index] = $this->lang->line($errMsg);

						}

					}

				} else {

					$errors['login'] = form_error('login');

				}

			}

		}

		$this->data['errors']     = $errors;
		$this->data['oldValues']  = $oldValues;
	}


	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	public function resetPassword()
	{
		$errors    = array();
		$oldValues = array();

		$idUser		= $this->uri->segment(2);
		$newPassKey	= $this->uri->segment(3);

		$passwordMinLength   = $this->config->item('password_min_length', 'factotum');
		$passwordMaxLength   = $this->config->item('password_max_length', 'factotum');
		$emailActivation     = $this->config->item('email_activation',    'factotum');
		$siteName            = $this->config->item('website_name',        'factotum');

		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length[' . $passwordMinLength . ']|max_length[' . $passwordMaxLength . ']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

		if ($this->input->post('change_password')) {

			$oldValues['new_password'] = $this->input->post('new_password');

			if ($this->form_validation->run()) {

				$userData = $this->fm_users_management->resetPassword($idUser, $newPassKey, $oldValues['new_password']);

				if (!is_null($userData)) {

					$userData['siteName'] = $siteName;

					// Send email with new password
					$this->fm_users_management->sendEmail('reset_password', $userData['email'], $userData);

					$this->data['successMessage'] = $this->lang->line('auth_message_new_password_activated') . '<br>' . anchor('/login/', 'Login');

				} else {

					$this->data['errorMessage'] = $this->lang->line('auth_message_new_password_failed');

				}

			} else {

				$errors['new_password']         = form_error('new_password');
				$errors['confirm_new_password'] = form_error('confirm_new_password');

				// Try to activate user by password key (if not activated yet)
				if ($emailActivation) {

					$this->fm_users_management->activateUser($idUser, $newPassKey, FALSE);

				}

				if (!$this->fm_users_management->canResetPassword($idUser, $newPassKey)) {

					$this->data['errorMessage'] = $this->lang->line('auth_message_new_password_failed');

				}

			}

		}

		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;
	}


	/**
	 * Change user password
	 *
	 * @return void
	 */
	public function changePassword()
	{
		$errors    = array();
		$oldValues = array();

		$passwordMinLength   = $this->config->item('password_min_length',     'factotum');
		$passwordMaxLength   = $this->config->item('password_max_length',     'factotum');

		if (!$this->fm_users_management->isLoggedIn()) {

			redirect('/login');

		} else {

			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length[' . $passwordMinLength . ']|max_length[' . $passwordMaxLength . ']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

			if ($this->input->post('change_password')) {

				$oldValues['old_password'] = $this->input->post('old_password');
				$oldValues['new_password'] = $this->input->post('new_password');

				if ($this->form_validation->run()) {

					if ($this->fm_users_management->changePassword($oldValues['old_password'], $oldValues['new_password'])) {

						$this->data['successMessage'] = $this->lang->line('auth_message_password_changed');

					} else {

						$authErrors = $this->fm_users_management->getErrorMessage();

						foreach ($authErrors as $index => $errMsg) {
	
							$errors[$index] = $this->lang->line($errMsg);

						}

					}

				} else {
	
					$errors['old_password']         = form_error('old_password');
					$errors['new_password']         = form_error('new_password');
					$errors['confirm_new_password'] = form_error('confirm_new_password');
	
				}

			}

		}

		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;
	}

	/**
	 * Change user email
	 *
	 * @return void
	 */
	public function changeEmail()
	{
		$errors    = array();
		$oldValues = array();

		$siteName = $this->config->item('website_name', 'factotum');

		// not logged in or not activated
		if (!$this->fm_users_management->isLoggedIn()) {

			redirect('/login');

		} else {

			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$oldValues['email']    = '';
			$oldValues['password'] = '';

			if ($this->input->post('change_email')) {

				$oldValues['email']    = $this->input->post('email');
				$oldValues['password'] = $this->input->post('password');
				
				// validation ok
				if ($this->form_validation->run()) {

					$userData = $this->fm_users_management->setNewEmail(null, $oldValues['email'], $oldValues['password']);

					// success
					if (!is_null($userData)) {

						$userData['siteName'] = $siteName;

						// Send email with new email address and its activation link
						$this->fm_users_management->sendEmail('change_email', $userData['new_email'], $userData);

						$this->data['successMessage'] = sprintf($this->lang->line('auth_message_new_email_sent'), $userData['new_email']);

					} else {

						$authErrors = $this->fm_users_management->getErrorMessage();

						foreach ($authErrors as $index => $errMsg) {
	
							$errors[$index] = $this->lang->line($errMsg);

						}

					}

				} else {
	
					$errors['email']    = form_error('email');
					$errors['password'] = form_error('password');
	
				}

			}

		}

		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;
	}

	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	public function resetEmail()
	{
		$idUser      = $this->uri->segment(2);
		$newEmailKey = $this->uri->segment(3);

		// Reset email
		if ($this->fm_users_management->activateNewEmail($idUser, $newEmailKey)) {

			$this->fm_users_management->logout();
			$this->data['message'] = $this->lang->line('auth_message_new_email_activated') . ' ' . anchor('/login', 'Login');

		} else {

			$this->data['error'] = $this->lang->line('auth_message_new_email_failed');

		}
	}


	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	public function unregister()
	{
		$errors    = array();
		$oldValues = array();

		if (!$this->fm_users_management->isLoggedIn()) {

			redirect('/login');

		} else {

			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			if ($this->input->post('delete')) {

				$oldValues['password'] = $this->input->post('password');

				if ($this->form_validation->run()) {

					$userDeleted = $this->fm_users_management->deleteAccount($oldValues['password']);

					if ($userDeleted) {

						$this->data['successMessage'] = $this->lang->line('auth_message_unregistered');

					} else {

						$authErrors = $this->fm_users_management->getErrorMessage();

						foreach ($authErrors as $index => $errMsg) {
	
							$errors[$index] = $this->lang->line($errMsg);

						}

					}

				} else {

					$errors['password'] = form_error('password');

				}

			}

		}

		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;
	}



	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	public function checkCaptcha($code)
	{
		$check = $this->fm_users_management->checkCaptcha($code);
		if ($check) {

			$this->form_validation->set_message('checkCaptcha', $this->lang->line($check));
			return FALSE;

		}

		return TRUE;
	}



	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	public function checkRecaptcha()
	{
		$check = $this->fm_users_management->checkRecaptcha();
		if ($check) {

			$this->form_validation->set_message('checkRecaptcha', $this->lang->line($check));
			return FALSE;
		}

		return TRUE;
	}

	public function checkDob($value)
	{
		list($day, $month, $year) = explode('/', $value);

		if (checkdate($month, $day, $year) == false) {

			$this->form_validation->set_message('checkDob', $this->lang->line('auth_incorrect_dob'));
			return FALSE;

		} else {

			return TRUE;
		}
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */