<?php
class FM_AdminController extends FM_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->layout = 'admin/layouts/layout.php';

		if (!$this->fm_users_management->isLoggedIn() || !$this->fm_users_management->canAccessBackend()) {

			$this->data['logged']   = FALSE;

			if (in_array($this->uri->segment(3), array('forgotPassword', 'resetPassword'))) {

				// limit access to forgotPassword

			} else if ($this->uri->segment(3) != 'login') {

				redirect('/admin/auth/login');
				return;

			}

		} else {

			$this->data['logged']                  = TRUE;
			$this->data['username']	               = $this->fm_users_management->getLoggedUsername();
			$this->data['role']                    = $this->fm_users_management->getLoggedUserRole();
			$this->data['manageContentTypes']      = $this->fm_users_management->canManageContentTypes();
			$this->data['manageContentCategories'] = $this->fm_users_management->canManageContentCategories();
			$this->data['manageUsers']             = $this->fm_users_management->canManageUsers();
			$this->data['capabilities']            = $this->fm_users_management->convertCapabilities($this->fm_users_management->getLoggedUserCapabilities());

			$allContentTypes = $this->fm_cms->getContentTypes();

			$this->data['manageContentFields'] = FALSE;

			foreach ($allContentTypes as $index => $cType) {

				if ($this->fm_users_management->canConfigureContentType($cType['id'])) {

					$this->data['manageContentFields'] = TRUE;

				}

			}

			$contentTypes = $this->fm_cms->getContentTypes('pages');

			if (count($contentTypes) > 0) {

				$tmp = array();
				foreach ($contentTypes as $cType) {

					if (isset($this->data['capabilities'][$cType['id']])) {
						
						if (!(!$this->data['capabilities'][$cType['id']]['configure'] && 
							  !$this->data['capabilities'][$cType['id']]['edit'] && 
							  !$this->data['capabilities'][$cType['id']]['publish'])) {
	
							$tmp[] = $cType;

						}

					}

				}
				$contentTypes = $tmp;

			}

			$this->data['contentTypes'] = $contentTypes;

			if ($this->uri->segment(2) == '' || $this->uri->segment(2) == '/') {

				$this->data['currentPage'] = 'index';

			} else {

				$this->data['currentPage'] = $this->uri->segment(2);

			}

			$contentTypePage = $this->fm_cms->getContentTypeByName('pages');
			$this->data['idContentTypePages']   = $contentTypePage['id'];
			$this->data['contentStatusLive']    = $this->fm_cms->getContentStatusLive();
			$this->data['contentStatusOffline'] = $this->fm_cms->getContentStatusOffline();
			$this->data['fieldTypes']           = $this->config->item('field_types', 'factotum');
			$this->data['imageOperations']      = $this->config->item('image_operations', 'factotum');

			$this->load->helper(array(
				  'factotum/print_add_button'
				, 'factotum/print_back_button'
				, 'factotum/print_edit_button'
				, 'factotum/print_delete_button'
				, 'factotum/print_status_button'
				, 'factotum/print_openclose_button'
			));

			// Load the language for the backend area
			$this->lang->load('FM_Cms');

		}

		// Load css
		$this->data['css'] = array(
			  '/assets/admin/css/normalize.css'
			, '/assets/admin/css/main.css'
			, '/assets/admin/css/jquery-ui-1.10.3.custom.min.css'
		);
        
		// Load header js
		$this->data['headerJS'] = array(
			  '/assets/admin/js/vendor/modernizr-2.6.2.min.js'
			, '/assets/admin/js/vendor/tiny_mce/tiny_mce.js'
		);

		// Load footer js
		$this->data['footerJS'] = array(
			  '/assets/admin/js/vendor/jquery-1.10.2.min.js'
			, '/assets/admin/js/vendor/jquery-ui-1.10.3.custom.min.js'
			, '/assets/admin/js/vendor/jquery-ui-timepicker-addon.js'
			, '/assets/admin/js/vendor/jquery.placeholder.js'
			, '/assets/admin/js/vendor/classie.js'
			, '/assets/admin/js/vendor/cbpFWTabs.js'
			, '/assets/admin/js/vendor/functions.js'
			, '/assets/admin/js/plugins.js'
			, '/assets/admin/js/main.js'
		);

	}
}
