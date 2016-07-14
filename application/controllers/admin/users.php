<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends FM_AdminController
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->fm_users_management->canManageUsers()) {

			redirect('/admin/');

		}
	}

	public function index()
	{
		$this->data['list']    = $this->fm_users_management->getUsersList();
		$this->data['success'] = $this->session->flashdata('success');

		$this->data['popupDeleteTitle'] = $this->lang->line('user_popup_delete_title');
		$this->data['popupDeleteText']  = $this->lang->line('user_popup_delete_text');
	}

	public function add()
	{
		$result = $this->_saveUser();

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'User saved correctly.');
			redirect($result['redirectURL']);

		}

		$this->data['success']   = $this->session->flashdata('success');
		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;
	}

	public function edit()
	{
		$idUser = $this->uri->segment(4);

		$result = $this->_saveUser($idUser);

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'User saved correctly.');
			redirect($result['redirectURL']);

		}

		$this->data['success']   = $this->data['success']   = $this->session->flashdata('success');
		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;

	}

	private function _saveUser($idUser = null)
	{
		$result    = array();
		$oldValues = array();
		$errors    = array();

		$redirectURL = '/admin/users/';

		$useUsername         = $this->config->item('use_username',            'factotum');
		$usernameMinLength   = $this->config->item('username_min_length',     'factotum');
		$usernameMaxLength   = $this->config->item('username_max_length',     'factotum');
		$passwordMinLength   = $this->config->item('password_min_length',     'factotum');
		$passwordMaxLength   = $this->config->item('password_max_length',     'factotum');
		$emailActivation     = $this->config->item('email_activation',        'factotum');
		$activationPeriod    = $this->config->item('email_activation_expire', 'factotum');
		$emailAccountDetails = $this->config->item('email_account_details',   'factotum');
		$siteName            = $this->config->item('website_name',            'factotum');

		$this->data['useUsername']          = $useUsername;

		$result['data_saved'] = FALSE;

		$roles = $this->fm_users_management->getUserRolesList();
		$tmp = array();
		foreach ($roles as $role) {

			$tmp[$role['id']] = $role['role'];

		}
		$roles = $tmp;
		$this->data['roles'] = $roles;

		if ($idUser) {

			$user        = $this->fm_users_management->getUserById($idUser);
			$userProfile = $this->fm_users_management->getUserProfileByIdUser($idUser);

			$oldValues['username'] = $user['username'];
			$oldValues['email']    = $user['email'];
			$oldValues['role']     = $user['id_user_role'];

			// Profile Fields
			$oldValues['firstname'] = $userProfile['firstname'];
			$oldValues['lastname']  = $userProfile['lastname'];
			$oldValues['dob']       = FM_Utility::convertIsoDateToHuman($userProfile['dob']);

		} else {

			$oldValues['username'] = '';
			$oldValues['email']    = '';
			$oldValues['role']     = '';

			// Profile Fields
			$oldValues['firstname'] = '';
			$oldValues['lastname']  = '';
			$oldValues['dob']       = '';
		}

		if ($useUsername) {

			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length[' . $usernameMinLength . ']|max_length[' . $usernameMaxLength . ']|alpha_dash');
			$this->form_validation->set_message('username', $this->lang->line('auth_incorrect_username'));

		}

		$this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean');

		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_message('email', $this->lang->line('auth_incorrect_email'));

		// Profile Fields
		$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required|xss_clean');
		$this->form_validation->set_message('firstname', $this->lang->line('auth_incorrect_firstname'));

		$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required|xss_clean');
		$this->form_validation->set_message('lastname', $this->lang->line('auth_incorrect_lastname'));

		$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|callback_checkDob');
		$this->form_validation->set_message('dob', $this->lang->line('auth_incorrect_dob'));



		if ($this->input->post('add') || ($this->input->post('save') && $this->input->post('password') != '')) {
			
			$this->form_validation->set_rules('password',         'Password',         'trim|required|xss_clean|min_length[' . $passwordMinLength . ']|max_length[' . $passwordMaxLength . ']|alpha_dash');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');

		}

		if ($this->input->post('add') || $this->input->post('save')) {

			$redirectURL .= ($this->input->post('save') ? 'edit/' . $idUser : 'index/');

			$oldValues['username'] = ($useUsername ? $this->input->post('username') : '');
			$oldValues['email']    = $this->input->post('email');
			$oldValues['role']     = $this->input->post('role');

			// Profile Fields
			$oldValues['firstname'] = $this->input->post('firstname');
			$oldValues['lastname']  = $this->input->post('lastname');
			$oldValues['dob']       = $this->input->post('dob');

			// validation ok
			if ($this->form_validation->run()) {
	
				$password = $this->form_validation->set_value('password');

				$profileData = array(
					  'firstname' => $oldValues['firstname']
					, 'lastname'  => $oldValues['lastname']
					, 'dob'       => FM_Utility::convertHumanDateToIso($oldValues['dob'])
				);

				if ($idUser) {

					$userData = $this->fm_users_management->updateUser($idUser, $oldValues['email'], $oldValues['role'], ($useUsername ? $oldValues['username'] : ''));

					if ($password) {

						$this->fm_users_management->updatePassword($idUser, $password);

					}

					// Update the profile for the user
					$this->fm_users_management->updateProfile($idUser, $profileData);

				} else {

					$userData = $this->fm_users_management->insertUser($oldValues['username'], $oldValues['email'], $password, $oldValues['role'], $emailActivation);

					// Insert the profile for the user
					$this->fm_users_management->insertProfile($userData['id'], $profileData);

				}

				// success
				if (!is_null($userData)) {

					if (!$idUser) {

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
	
					}

					$result['redirectURL'] = $redirectURL;
					$result['data_saved']  = TRUE;

				} else {
	
					$authErrors = $this->fm_users_management->getErrorMessage();

					foreach ($authErrors as $index => $errMsg) {
	
						$errors[$index] = $this->lang->line($errMsg);

					}
	
				}
	
			} else {

				$result['data_saved']  = FALSE;
				$result['redirectURL'] = '';
				
				if ($useUsername) {

					$errors['username'] = form_error('username');

				}

				$errors['role']                      = form_error('role');
				$errors['email']                     = form_error('email');
				$errors['password']                  = form_error('password');
				$errors['confirm_password']          = form_error('confirm_password');

				// Profiles Fields Errors
				$errors['firstname']                 = form_error('firstname');
				$errors['lastname']                  = form_error('lastname');
				$errors['dob']                       = form_error('dob');

			}

		}


		$result['errors']    = $errors;
		$result['oldValues'] = $oldValues;

		return $result;
	}


	public function delete()
	{
		$this->view = FALSE;

		$idUser = $this->uri->segment(4);

		$result         = array();
		$result['type'] = 'user';

		if ($idUser) {

			$this->fm_users_management->deleteUser($idUser);

			$result['elementId'] = $idUser;

		} else {

			$result['elementId'] = FALSE;

		}

		echo json_encode($result);
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
