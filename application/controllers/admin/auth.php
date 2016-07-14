<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends FM_AdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		redirect('/admin/auth/login/');
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

			redirect('/admin/');

		} elseif ($this->fm_users_management->isLoggedInButNotActive() && $this->fm_users_management->canAccessBackend()) {

			// logged in, but not activated
			redirect('/admin/auth/sendAgain/');

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

					redirect('/admin/');

				} else {

					$errors = $this->fm_users_management->getErrorMessage();

					// banned user
					if (isset($errors['banned'])) {

						$errors['banned'] = $this->lang->line('auth_message_banned') . ' ' . $errors['banned'];

					// not activated user
					} elseif (isset($errors['not_activated'])) {

						redirect('/admin/auth/sendAgain/');

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
		redirect('/admin/');
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

			redirect('/admin/auth/sendAgain/');

		} else {

			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');

			$oldValues['login'] = $this->input->post('login');

			if ($this->input->post('send_email')) {

				// validation ok
				if ($this->form_validation->run()) {
	
					$userData = $this->fm_users_management->forgotPassword($oldValues['login']);

					if (!is_null($userData)) {
	
						$userData['siteName'] = $siteName;
						$userData['baseUrl']  = 'admin/auth/resetPassword';

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

		$idUser		= $this->uri->segment(4);
		$newPassKey	= $this->uri->segment(5);

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

					$this->data['successMessage'] = $this->lang->line('auth_message_new_password_activated') . '<br>' . anchor('/admin/auth/login/', 'Login');

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