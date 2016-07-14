<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserRoles extends FM_AdminController
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
		$this->data['list']    = $this->fm_users_management->getUserRolesList();
		$this->data['success'] = $this->session->flashdata('success');

		$this->data['popupDeleteTitle'] = $this->lang->line('role_popup_delete_title');
		$this->data['popupDeleteText']  = $this->lang->line('role_popup_delete_text');
	}

	public function add()
	{
		$result = $this->_saveRoleAndCapabilities();

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'User role saved correctly.');
			redirect($result['redirectURL']);

		}

		$this->data['contentTypesList'] = $result['contentTypesList'];
		$this->data['success']          = $this->session->flashdata('success');
		$this->data['errors']           = $errors;
		$this->data['oldValues']        = $oldValues;

	}

	public function edit()
	{
		$idUserRole = $this->uri->segment(4);

		$result = $this->_saveRoleAndCapabilities($idUserRole);

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'User role saved correctly.');
			redirect($result['redirectURL']);

		}

		$this->data['contentTypesList'] = $result['contentTypesList'];
		$this->data['success']          = $this->session->flashdata('success');
		$this->data['errors']           = $errors;
		$this->data['oldValues']        = $oldValues;

	}

	private function _saveRoleAndCapabilities($idUserRole = null)
	{
		$result    = array();
		$oldValues = array();
		$errors    = array();

		$result['data_saved'] = FALSE;

		$redirectURL = '/admin/userRoles/';

		$contentTypes = $this->fm_cms->getContentTypes();

		if (count($contentTypes) > 0) {

			$tmp = array();

			foreach ($contentTypes as $cType) {

				$tmp[$cType['id']] = $cType['content_type'];

			}

			$contentTypes = $tmp;
		}

		if ($idUserRole) {

			$role         = $this->fm_users_management->getRoleByRoleId($idUserRole);
			$capabilities = $this->fm_users_management->convertCapabilities($this->fm_users_management->getCapabilitiesByIdUserRole($idUserRole));

			$oldValues['role']                      = $role['role'];
			$oldValues['backend_access']            = ($role['backend_access'] ?            'true' : 'false');
			$oldValues['manage_content_types']      = ($role['manage_content_types'] ?      'true' : 'false');
			$oldValues['manage_content_categories'] = ($role['manage_content_categories'] ? 'true' : 'false');
			$oldValues['manage_users']              = ($role['manage_users'] ?              'true' : 'false');
			$oldValues['capabilities']              = array();


			if (count($contentTypes) > 0) {

				$tmp = array();

				foreach ($contentTypes as $idContentType => $cType) {

					if (isset($capabilities[$idContentType])) {
						
						$tmp[$idContentType]['configure'] = $capabilities[$idContentType]['configure'];
						$tmp[$idContentType]['edit']      = $capabilities[$idContentType]['edit'];
						$tmp[$idContentType]['publish']   = $capabilities[$idContentType]['publish'];

					} else {

						$tmp[$idContentType]['configure'] = '';
						$tmp[$idContentType]['edit']      = '';
						$tmp[$idContentType]['publish']   = '';

					}

				}

				$oldValues['capabilities'] = $tmp;

			}

		} else {

			$oldValues['role']                      = '';
			$oldValues['backend_access']            = '';
			$oldValues['manage_content_types']      = '';
			$oldValues['manage_content_categories'] = '';
			$oldValues['manage_users']              = '';

			// Capabilities loading
			$oldValues['capabilities'] = array();

			if (count($contentTypes) > 0) {
	
				foreach ($contentTypes as $idContentType => $cType) {
	
					$oldValues['capabilities'][$idContentType]['configure'] = '';
					$oldValues['capabilities'][$idContentType]['edit']      = '';
					$oldValues['capabilities'][$idContentType]['publish']   = '';

				}
	
			}

		}

		if ($this->input->post('add') || ($this->input->post('save') && $this->input->post('role') != $oldValues['role'])) {

			$this->form_validation->set_rules('role', 'Role Name', 'trim|required|xss_clean|alpha_dash|callback_checkRoleName');
			$this->form_validation->set_message('role', $this->lang->line('incorrect_role_name'));

		} 
		
		$this->form_validation->set_rules('backend_access', 'Backend Access', 'trim|required|xss_clean');
		$this->form_validation->set_message('backend_access', $this->lang->line('incorrect_backend_access'));

		$this->form_validation->set_rules('manage_content_types', 'Manage Content Types', 'trim|required|xss_clean');
		$this->form_validation->set_message('manage_content_types', $this->lang->line('incorrect_manage_content_types'));

		$this->form_validation->set_rules('manage_content_categories', 'Manage Content Categories', 'trim|required|xss_clean');
		$this->form_validation->set_message('manage_content_categories', $this->lang->line('incorrect_manage_content_categories'));

		$this->form_validation->set_rules('manage_users', 'Manage Users', 'trim|required|xss_clean');
		$this->form_validation->set_message('manage_users', $this->lang->line('incorrect_manage_users'));

		if ($this->input->post('add') || $this->input->post('save')) {

			$redirectURL .= ($this->input->post('save') ? 'edit/' . $idUserRole : '');
			
			$oldValues['role']                      = $this->input->post('role');
			$oldValues['backend_access']            = ($this->input->post('backend_access') == 'true' ? TRUE : FALSE);
			$oldValues['manage_content_types']      = ($this->input->post('manage_content_types') == 'true' ? TRUE : FALSE);
			$oldValues['manage_content_categories'] = ($this->input->post('manage_content_categories') == 'true' ? TRUE : FALSE);
			$oldValues['manage_users']              = ($this->input->post('manage_users') == 'true' ? TRUE : FALSE);
			$oldValues['capabilities']              = $this->input->post('capabilities');

			if (count($contentTypes) > 0) {

				foreach ($contentTypes as $idContentType => $cType) {

					if (isset($oldValues['capabilities'][$idContentType])) {

						$cTypeCap = $oldValues['capabilities'][$idContentType];

						$cTypeCap['configure'] = (isset($cTypeCap['configure']) ? TRUE : FALSE);
						$cTypeCap['edit']      = (isset($cTypeCap['edit'])      ? TRUE : FALSE);
						$cTypeCap['publish']   = (isset($cTypeCap['publish'])   ? TRUE : FALSE);

						$oldValues['capabilities'][$idContentType] = $cTypeCap;

					}

				}

			}


			if ($this->form_validation->run()) {

				if (!$idUserRole) {
  
					$idUserRole = $this->fm_users_management->insertRole( $oldValues['role']
																		, $oldValues['backend_access']
																		, $oldValues['manage_content_types']
																		, $oldValues['manage_content_categories']
																		, $oldValues['manage_users']
																		);


				} else {

					$this->fm_users_management->updateRole( $idUserRole
														  , $oldValues['role']
														  , $oldValues['backend_access']
														  , $oldValues['manage_content_types']
														  , $oldValues['manage_content_categories']
														  , $oldValues['manage_users']
														  );

				}

				if (count($oldValues['capabilities']) > 0) {

					foreach ($contentTypes as $idContentType => $cType) {

						if (!in_array($idContentType, array_keys($oldValues['capabilities']))) {

							$oldValues['capabilities'][$idContentType] = array(
								  'configure' => null
								, 'edit'      => null
								, 'publish'   => null
							);

						}

					}

					foreach ($oldValues['capabilities'] as $idContentType => $capabilities) {

						$alreadyExist = $this->fm_users_management->existCapabilities($idUserRole, $idContentType);

						if ($alreadyExist) {

							$this->fm_users_management->updateCapabilities($idUserRole
																		 , $idContentType
																		 , $capabilities['configure']
																		 , $capabilities['edit']
																		 , $capabilities['publish']
																		 );

						} else {

							$this->fm_users_management->insertCapabilities($idUserRole
																		 , $idContentType
																		 , (isset($capabilities['configure']) ? TRUE : FALSE)
																		 , (isset($capabilities['edit'])      ? TRUE : FALSE)
																		 , (isset($capabilities['publish'])   ? TRUE : FALSE)
																		 );

						}

					}

					$this->fm_users_management->resetLoggedCapabilities();
				}

				$result['redirectURL'] = $redirectURL;
				$result['data_saved']  = TRUE;

			} else {

				$errors['role']                      = form_error('role');
				$errors['backend_access']            = form_error('backend_access');
				$errors['manage_content_types']      = form_error('manage_content_types');
				$errors['manage_content_categories'] = form_error('manage_content_categories');
				$errors['manage_users']              = form_error('manage_users');

				$result['redirectURL'] = '';
				$result['data_saved']  = FALSE;

			}

		}

		$result['contentTypesList'] = $contentTypes;
		$result['errors']           = $errors;
		$result['oldValues']        = $oldValues;

		return $result;
	}


	public function delete()
	{
		$this->view = FALSE;

		$idUserRole = $this->uri->segment(4);

		$result         = array();
		$result['type'] = 'user';

		if ($idUserRole) {

			$this->fm_users_management->deleteUserCapabilities($idUserRole);
			$this->fm_users_management->deleteUserRole($idUserRole);

			$result['elementId'] = $idUserRole;

		} else {

			$result['elementId'] = FALSE;

		}

		echo json_encode($result);
	}


	public function checkRoleName($role)
	{
		// Check the name with the content columns name
		if (!$this->fm_users_management->checkRoleName($role)) {

			$this->form_validation->set_message('checkRoleName', $this->lang->line('existing_role_name'));
			return FALSE;

		} else {

			return TRUE;
		}
	}
}
