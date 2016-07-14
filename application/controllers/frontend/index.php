<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends FM_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$requestedPage = $_SERVER['REQUEST_URI'];

		if (!preg_match('/\/(.*\/)?([^\/\?]+)?(?:\?.*)?$/', $_SERVER['REQUEST_URI'], $match)) {
			show_404($requestedPage, true);
		}

		$pagePath    = isset($match[1]) ? $match[1] : '';   // absolute path identifies a page
		$contentPath = isset($match[2]) ? $match[2] : null; // relative path identifies a content

		// var_dump($pagePath, $contentPath);
		// exit;

		if ($pagePath === '') {
			$this->home();
			return;
		}

		$pageSearch = new FM_ContentSearch();
		$pageSearch->initialize('pages')
				   ->onlyLiveContent(!$this->data['logged'])
				   ->addWhereCondition('absolute_path', '=', $pagePath)
				   ->fullInfo(true)
				   ->withAllFieldsAndValues(true);

		$page = $pageSearch->getContent();

		if (!$page || (isset($page['only_logged_user']) && $page['only_logged_user'] && !$this->data['logged'])) {
			show_404($requestedPage, TRUE);
		}

		$this->data['page'] = $page;
		$this->data['menu'] = $this->fm_cms->getMenu($this->data['logged'], $page['id']);

		$operation = $page['operation'];

		switch ($operation) {

			case 'content':

				// Retrieve the content and pass it to the view
				$contentSearch = new FM_ContentSearch();
				$contentSearch->initialize($page['content_types_list'])
							  ->onlyLiveContent(!$this->data['logged'])
							  ->withAllFieldsAndValues(TRUE)
							  ->withAllCategories(TRUE)
							  ->addWhereCondition('id', '=', $page['content_list'])
							  ->order(FM_Utility::getSQLOrderFromOption($page['content_list_order']))
							  ;

				$content = $contentSearch->getContent();

				$contentCType = $this->fm_cms->getContentTypeById($content['id_content_type']);
				$contentCType = $contentCType['content_type'];

				$this->data['content'] = $content;

				if (file_exists(APPPATH . 'views/frontend/index/' . strtolower($page['template']) . '.php')) {
					$this->view = 'frontend/index/' . strtolower($page['template']);
				} else {
					$this->view = 'frontend/index/basic_content';
				}

				break;

			case 'content_list':

				// Retrieve the content list and pass it to the view
				$contentSearch = new FM_ContentSearch();
				$contentSearch->initialize($page['content_types_list'])
							  ->onlyLiveContent(!$this->data['logged'])
							  ->withAllFieldsAndValues(TRUE)
							  ->order(FM_Utility::getSQLOrderFromOption($page['content_list_order']))
							  ;
				if (array_key_exists('content_list_categories', $page) && $page['content_list_categories']) {
					$contentSearch->byCategories(is_array($page['content_list_categories']) ? $page['content_list_categories'] : explode(',', $page['content_list_categories']));
				}

				if ($contentPath) {
					$contentSearch->addWhereCondition('relative_path', '=', $contentPath);

					$content = $contentSearch->getContent();

					$contentType = $this->fm_cms->getContentTypeById($content['id_content_type']);
					$contentType = $contentType['content_type'];
					$this->data['contentType'] = $contentType;


					$this->data['content'] = $content;

				} else {

					if ($this->input->get('per_page')) {
						// Start the offset from 1 (issue in the CodeIgniter Pagination Class)
						$contentSearch->offset($this->input->get('per_page') + 1);
					}

					if ($page['content_list_num_per_page']) {
						$contentSearch->limit($page['content_list_num_per_page']);
					}

					$contentList = $contentSearch->getContentList();

					// TODO: complete
					$this->data['pagination'] = ($page['content_list_pagination'] == 'yes' ? TRUE : FALSE);
					if ($this->data['pagination']) {
						$this->pagination(site_url() . "/{$page['absolute_path']}?", $contentSearch->getContentListCount(), $page['content_list_num_per_page']);
					}

					$this->data['contentList'] = $contentList;


				}

				if (file_exists(APPPATH . 'views/frontend/index/' . strtolower($page['template']) . '.php')) {
					$this->view = 'frontend/index/' . strtolower($page['template']);
				} else {
					$this->view = 'frontend/index/basic_content_list';
				}

			break;

			case 'action':

				$action = $page['action'];
				$this->{$action}();

				if (file_exists(APPPATH . 'views/frontend/index/' . strtolower($action) . '.php')) {
					$this->view = 'frontend/index/' . strtolower($action);
				} else {
					$this->view = 'frontend/index/' . $page['template'];
				}

			break;

			case 'text':
				$this->view = 'frontend/index/basic_content';
			break;

			case 'link':

				$this->output->set_status_header(303, 'See Other');
				header('Location: ' . $page['link']);
				exit;

			break;
		}

		// Special template for ajax Calls
		if ($page['template'] == 'ajax') {

			$this->layout = FALSE;
			$this->view   = FALSE;

		}

// 		} else {
//
// 			if (file_exists(APPPATH . 'views/frontend/index/basic_' . strtolower($contentType) . '.php')) {
//
// 				$this->view = 'frontend/index/basic_' . strtolower($contentType);
//
// 			} else {
//
// 				$this->view = 'frontend/index/basic_content';
//
// 			}
//
// 		}


	}

	public function home()
	{
		//
	}


	/*
	 * Give to the user the chance of adding content
	 */
	public function userAddContent()
	{
		$contentType = $this->fm_cms->getContentTypeByName('news');

		if (!$this->fm_users_management->canEditContentType($contentType['id'])) {

			redirect('/');

		}

		$result = $this->fm_cms->saveContent($contentType['content_type']);

		$fields    = $result['fields'];
		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Content added correctly.');
			redirect('/user-add-content');

		}

		$this->data['fields']    = $fields;
		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;

	}

	public function calendarAction()
	{
		if (isset($_GET['day'])) {

			// Get specific day
			list($day, $month, $year) = explode('-', $_GET['day']);
			$condition =  $day . '/' . $month . '/' . $year;

		} else if (isset($_GET['month_year'])) {

			// Get specific month
			list($month, $year) = explode('-', $_GET['month_year']);
			$condition = '%/' . $month . '/' . $year;

		} else {

			// Get current month
			$condition = '%/' . date('m/Y');

		}


		$contentSearch = new FM_ContentSearch();
		$contentSearch->initialize('calendar')
					  ->onlyLiveContent(true)
					  ->withAllFieldsAndValues(TRUE)
					  ->addWhereCondition('day', 'LIKE', $condition);
		$contentList = $contentSearch->getContentList();

		$currentMonth = array();

		if (count($contentList) > 0) {

			foreach ($contentList as $item) {

				list($day, $month, $year) = explode('/', $item['day']);
				$timestamp = mktime (0, 0, 0, $month , $day , $year);

				$data = array('items' => explode(',', $item['items']));
				if (isset($item['ecomobile_text']) && $item['ecomobile_text'] != '') {
					$data['ecomobile_text'] = $item['ecomobile_text'];
				}
				$currentMonth[$timestamp] = $data;

			}

			ksort($currentMonth);

			echo json_encode(array('result' => 'ok', 'data' => $currentMonth));

		} else {
			echo json_encode(array('result' => 'ko', 'data' => 'No results for the query'));
		}

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */