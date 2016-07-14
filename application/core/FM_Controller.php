<?php
/**
 * A base controller for CodeIgniter with view autoloading, layout support,
 * model loading, helper loading, asides/partials and per-controller 404
 */

class FM_Controller extends CI_Controller
{

	/* --------------------------------------------------------------
	 * VARIABLES
	 * ------------------------------------------------------------ */

	/**
	 * The current request's view. Automatically guessed
	 * from the name of the controller and action
	 */
	protected $view = '';

	/**
	 * An array of variables to be passed through to the
	 * view, layout and any asides
	 */
	protected $data = array();

	/**
	 * The name of the layout to wrap around the view.
	 */
	protected $layout;

	/**
	 * An arbitrary list of asides/partials to be loaded into
	 * the layout. The key is the declared name, the value the file
	 */
	protected $asides = array();

	/**
	 * A list of models to be autoloaded
	 */
	protected $models = array();

	/**
	 * A formatting string for the model autoloading feature.
	 * The percent symbol (%) will be replaced with the model name.
	 */
	protected $model_string = '%_model';

	/**
	 * A list of helpers to be autoloaded
	 */
	protected $helpers = array();

	/**
	 * Paths for the uploads folders
	 */
	protected $_uploadPath    = '',
			  $_tmpUploadPath = '',
			  $_imagesURL     = '';


	/* --------------------------------------------------------------
	 * GENERIC METHODS
	 * ------------------------------------------------------------ */

	/**
	 * Initialise the controller, tie into the CodeIgniter superobject
	 * and try to autoload the models and helpers
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->config('factotum', TRUE);

		$baseUrl = $this->config->item('base_url');

		$this->load->helper(array(  'url'
								  , 'form'
								  , 'cookie'
								  , 'file'

								  , 'text'

								  , 'factotum/directory'
								  , 'factotum/captcha'
								  , 'factotum/recaptcha'
								  , 'factotum/print_form'
								  , 'factotum/print_field'
								  , 'factotum/print_submit_buttons'
								  , 'factotum/print_label'
								  , 'factotum/print_error'
								  , 'factotum/print_input_hidden'
								  , 'factotum/print_input_text'
								  , 'factotum/print_input_textarea'
								  , 'factotum/print_input_xhtml_textarea'
								  , 'factotum/print_input_select'
								  , 'factotum/print_input_multiselect'
								  , 'factotum/print_input_radio'
								  , 'factotum/print_input_checkbox'
								  , 'factotum/print_input_multicheckbox'
								  , 'factotum/print_input_password'
								  , 'factotum/print_input_image_upload'
								  , 'factotum/print_input_file_upload'
								  , 'factotum/print_input_gallery'
								  , 'factotum/print_image_preview'
								  , 'factotum/print_gallery_preview'
								  , 'factotum/print_file_preview'

								  , 'factotum/menu'

							  ));

		$this->load->model('factotum/fm_users_model',               'user');
		$this->load->model('factotum/fm_user_profiles_model',       'user_profile');
		$this->load->model('factotum/fm_user_roles_model',          'user_role');
		$this->load->model('factotum/fm_user_capabilities_model',   'user_capability');
		$this->load->model('factotum/fm_user_autologins_model',     'user_autologin');
		$this->load->model('factotum/fm_login_attempts_model',      'login_attempt');

		$this->load->model('factotum/fm_content_types_model',       'content_type');
		$this->load->model('factotum/fm_contents_model',            'content');
		$this->load->model('factotum/fm_content_fields_model',      'content_field');
		$this->load->model('factotum/fm_content_values_model',      'content_value');
		$this->load->model('factotum/fm_content_attachments_model', 'content_attachment');
		$this->load->model('factotum/fm_categories_model',          'category');
		$this->load->model('factotum/fm_content_categories_model',  'content_category');

		$this->load->library('email');
		$this->load->library('session');
		$this->load->library('security');
		$this->load->library('form_validation');
		$this->load->library('pagination');

		$this->load->library('FM_Utility');
		$this->load->library('FM_Cms');
		$this->load->library('FM_ContentSearch');
		$this->load->library('FM_Users_Management');

		// Load the languages
		$this->lang->load('FM_Users_Management');
		$this->lang->load('FM_Frontend');


		$this->_load_models();
		$this->_load_helpers();


		// Setting upload paths and URL
		$this->_uploadPath    = $this->fm_cms->getUploadPath();
		$this->_tmpUploadPath = $this->fm_cms->getTmpUploadPath();
		$this->_imagesURL     = $this->fm_cms->getImagesURL();

		$this->data['uploadUrl']  = $this->_imagesURL;
		$this->data['baseUrl']    = $baseUrl;

		// Validation stuff
		$this->form_validation->set_error_delimiters('', '');

		// Debugging stuff
		$this->output->enable_profiler($this->config->item('profiler', 'factotum'));

		// View and data stuff
		$this->layout = 'frontend/layouts/layout.php';

		$this->data['websiteName']     = $this->config->item('website_name', 'factotum');
		$this->data['factotumVersion'] = $this->config->item('version',      'factotum');

		// On the admin controller, this part is overwritten
		if (!$this->fm_users_management->isLoggedIn()) {

			$this->data['logged']   = FALSE;

		} else {

			$this->data['logged']   = TRUE;
			$this->data['username']	= $this->fm_users_management->getLoggedUsername();
			$this->data['role']     = $this->fm_users_management->getLoggedUserRole();

		}

		// if ($this->uri->segment(2) == '' || $this->uri->segment(2) == '/') {

		// 	$this->data['currentPage'] = 'index';

		// } else {

		// 	$this->data['currentPage'] = $this->uri->segment(2);

		// }

		// Load css
		$this->data['css'] = array(
			  $baseUrl . 'assets/frontend/css/normalize.css'
			, $baseUrl . 'assets/frontend/css/main.css'
			, $baseUrl . 'assets/frontend/css/jquery-ui-1.10.3.custom.min.css'
			, $baseUrl . 'assets/frontend/css/lightbox.css'
		);

		// Load header js
		$this->data['headerJS'] = array(
			  $baseUrl . 'assets/frontend/js/vendor/modernizr-2.6.2.min.js'
			, $baseUrl . 'assets/frontend/js/vendor/tiny_mce/tiny_mce.js'
		);

		// Load footer js
		$this->data['footerJS'] = array(
			  $baseUrl . 'assets/frontend/js/vendor/jquery-1.10.2.min.js'
			, $baseUrl . 'assets/frontend/js/vendor/jquery-ui-1.10.3.custom.min.js'
			, $baseUrl . 'assets/frontend/js/vendor/jquery.placeholder.js'
			, $baseUrl . 'assets/frontend/js/vendor/lightbox-2.6.min.js'
			, $baseUrl . 'assets/frontend/js/vendor/functions.js'
			, $baseUrl . 'assets/frontend/js/plugins.js'
			, $baseUrl . 'assets/frontend/js/main.js'
		);


 		$this->data['menu'] = $this->fm_cms->getMenu($this->data['logged']);
	}

	/* --------------------------------------------------------------
	 * VIEW RENDERING
	 * ------------------------------------------------------------ */

	/**
	 * Override CodeIgniter's despatch mechanism and route the request
	 * through to the appropriate action. Support custom 404 methods and
	 * autoload the view into the layout.
	 */
	public function _remap($method)
	{
		if (method_exists($this, $method)) {

			call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));

		} else {

			if (method_exists($this, '_404')) {

				call_user_func_array(array($this, '_404'), array($method));

			} else {

				show_404(strtolower(get_class($this)).'/'.$method);

			}

		}

		$this->_load_view();

	}

	/**
	 * Automatically load the view, allowing the developer to override if
	 * he or she wishes, otherwise being conventional.
	 */
	protected function _load_view()
	{

		// If $this->view == FALSE, we don't want to load anything
		if ($this->view !== FALSE) {

			// If $this->view isn't empty, load it. If it isn't, try and guess based on the controller and action name
			$view = (!empty($this->view)) ? $this->view : $this->router->directory . $this->router->class . '/' . $this->router->method;

			// Load the view into $yield
			$data['contentBlock'] = $this->load->view($view, $this->data, TRUE);

			// Do we have any asides? Load them.
			if (!empty($this->asides)) {

				foreach ($this->asides as $name => $file) {

					$data['yield_' . $name] = $this->load->view($file, $this->data, TRUE);

				}

			}

			// Load in our existing data with the asides and view
			$data = array_merge($this->data, $data);
			$layout = FALSE;

			// If we didn't specify the layout, try to guess it
			if (!isset($this->layout)) {

				if (file_exists(APPPATH . 'views/layouts/' . $this->router->class . '.php')) {

					$layout = 'layouts/' . $this->router->class;

				} else {

					$layout = 'layouts/application';

				}

			} else if ($this->layout !== FALSE) {

				// If we did, use it
				$layout = $this->layout;

			}

			// If $layout is FALSE, we're not interested in loading a layout, so output the view directly
			if ($layout == FALSE) {

				$this->output->set_output($data['contentBlock']);

			} else {

				// Otherwise? Load away :)
				$this->load->view($layout, $data);

			}

		}

	}

	/* --------------------------------------------------------------
	 * MODEL LOADING
	 * ------------------------------------------------------------ */

	/**
	 * Load models based on the $this->models array
	 */
	private function _load_models()
	{

		foreach ($this->models as $model) {

			$this->load->model($this->_model_name($model), $model);

		}

	}

	/**
	 * Returns the loadable model name based on
	 * the model formatting string
	 */
	protected function _model_name($model)
	{

		return str_replace('%', $model, $this->model_string);

	}


	/* --------------------------------------------------------------
	 * HELPER LOADING
	 * ------------------------------------------------------------ */

	/**
	 * Load helpers based on the $this->helpers array
	 */
	private function _load_helpers()
	{

		foreach ($this->helpers as $helper) {

			$this->load->helper($helper);

		}

	}

	public function getUploadPath()
	{
		return $this->_uploadPath;
	}

	public function pagination($baseUrl, $total, $numPerPage)
	{
		// Pagination part
		$config['base_url']          = $baseUrl;
		$config['num_links']         = 3;
		$config['total_rows']        = $total;
		$config['per_page']          = $numPerPage;
		$config['page_query_string'] = TRUE;
		$config['full_tag_open']     = '<ul class="pagination clearfix">';
		$config['full_tag_close']    = '</ul>';
		$config['prev_tag_open']     = '<li class="prev">';
		$config['prev_link']         = '&lt;';
		$config['prev_tag_close']    = '</li>';
		$config['num_tag_open']      = '<li>';
		$config['num_tag_close']     = '</li>';
		$config['cur_tag_open']      = '<li class="active">';
		$config['cur_tag_close']     = '</li>';
		$config['next_tag_open']     = '<li class="next">';
		$config['next_link']         = '&gt;';
		$config['next_tag_close']    = '</li>';

		$this->pagination->initialize($config);
	}

}

?>