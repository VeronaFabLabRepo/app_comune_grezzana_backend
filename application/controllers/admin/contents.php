<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contents extends FM_AdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$contentType = $this->uri->segment(4);
		$contentType = $this->fm_cms->getContentTypeByName($contentType);

		if (!$this->fm_users_management->canEditContentType($contentType['id']) && !$this->fm_users_management->canPublishContentType($contentType['id'])) {

			redirect('/admin');

		}

		$this->data['popupDeleteTitle'] = $this->lang->line('content_popup_delete_title');
		$this->data['popupDeleteText']  = $this->lang->line('content_popup_delete_text');

		$this->data['success']       = $this->session->flashdata('success');
		$this->data['currentPage']   = $contentType['content_type'];
		$this->data['contentType']   = $contentType['content_type'];
		$this->data['idContentType'] = $contentType['id'];

		$contentSearch = new FM_ContentSearch();
		$contentSearch->initialize($contentType['content_type'])
					  ->fullInfo(TRUE)
					  ;

		$this->data['list'] = $contentSearch->getContentList();
	}

	public function add()
	{
		$contentType = $this->uri->segment(4);
		$contentType = $this->fm_cms->getContentTypeByName($contentType);

		if (!$this->fm_users_management->canEditContentType($contentType['id'])) {

			redirect('/admin/');

		}

		$this->data['currentPage'] = $contentType['content_type'];

		$result = $this->fm_cms->saveContent($contentType['content_type']);

		$fields    = $result['fields'];
		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Content added correctly.');
			redirect($result['redirectURL']);

		}

		$this->data['categories'] = $this->_getCategories($contentType['id']);
		$this->data['fields']     = $fields;
		$this->data['errors']     = $errors;
		$this->data['oldValues']  = $oldValues;

	}

	public function edit()
	{
		$idContent = $this->uri->segment(4);

		$content     = $this->fm_cms->getContentById($idContent);
		$contentType = $this->fm_cms->getContentTypeById($content['id_content_type']);

		if (!$this->fm_users_management->canEditContentType($content['id_content_type'])) {

			redirect('/admin/');

		}

		$this->data['currentPage'] = $contentType['content_type'];

		$result = $this->fm_cms->saveContent($contentType['content_type'], $content);

		$fields    = $result['fields'];
		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Content saved correctly.');
			redirect($result['redirectURL']);

		}

		$this->data['popupDeleteTitle'] = $this->lang->line('content_attach_popup_delete_title');
		$this->data['popupDeleteText']  = $this->lang->line('content_attach_popup_delete_text');

		$this->data['success']    = $this->session->flashdata('success');
		$this->data['categories'] = $this->_getCategories($contentType['id']);
		$this->data['fields']     = $fields;
		$this->data['errors']     = $errors;
		$this->data['oldValues']  = $oldValues;

	}

	public function changeContentStatus()
	{
		$this->view   = FALSE;
		$this->layout = FALSE;

		// Retrieve the content if the idContent is setted
		$idContent   = $this->uri->segment(4);
		$content     = $this->fm_cms->getContentById($idContent);
		$contentType = $this->fm_cms->getContentTypeById($content['id_content_type']);


		if (!$this->fm_users_management->canPublishContentType($content['id_content_type'])) {

			redirect('/admin/');

		}

		$result = array();

		if ($idContent) {

			$currentStatus = $this->fm_cms->updateReverseContentStatus($idContent);
			echo json_encode(array('result' => $currentStatus));

		} else {

			echo json_encode(array('result' => 'Nothing to show.'));

		}

	}


	public function delete()
	{
		$this->view   = FALSE;
		$this->layout = FALSE;

		$idContent = $this->uri->segment(4);
		$content   = $this->fm_cms->getContentById($idContent);

		if (!$this->fm_users_management->canEditContentType($content['id_content_type'])) {

			redirect('/admin');

		}

		if ($this->fm_cms->deleteContentById($idContent)) {

			echo json_encode(array('type'      => 'content'
								 , 'elementId' => $idContent));

		} else {

			echo json_encode(array('type'      => 'content'
								 , 'elementId' => $idContent));

		}

	}


	public function deleteAttachment()
	{
		$this->view   = FALSE;
		$this->layout = FALSE;

		$idContentAttachment = $this->uri->segment(4);
		$contentAttach       = $this->fm_cms->getContentAttachmentById($idContentAttachment);
		$content             = $this->fm_cms->getContentById($contentAttach['id_content']);

		if (!$this->fm_users_management->canEditContentType($content['id_content_type'])) {

			redirect('/admin/');

		}

		if ($idContentAttachment) {

			$this->fm_cms->deleteContentAttachmentById($idContentAttachment);
			echo json_encode(array('result' => $idContentAttachment));

		}

	}


	public function deleteContentValueAttachment()
	{
		$this->view   = FALSE;
		$this->layout = FALSE;

		$idContentValue = $this->uri->segment(4);
		$this->fm_cms->deleteContentValueAttachmentById($idContentValue);

		echo json_encode(array('result' => $idContentValue));
	}


// 	public function checkPermalink()
// 	{
// 		$this->view   = FALSE;
// 		$this->layout = FALSE;

// 		$result = array();

// 		$permalink    = $this->uri->segment(4);
// 		$alreadyExist = $this->fm_cms->getContentByPermalink($permalink);

// 		if ($alreadyExist) {

// 			$result['result'] = 'unavailable';

// 		} else {

// 			$result['result'] = 'available';

// 		}

// 		echo json_encode($result);
// 	}


	public function getOptionsContentList()
	{
		$this->view   = FALSE;
		$this->layout = FALSE;

		$cType       = $this->uri->segment(4);

		if ($cType) {

			$contentSearch = new FM_ContentSearch();
			$contentSearch->initialize($cType)
						  ->fullInfo(TRUE)
						  ;

			$contentList = $contentSearch->getContentList();

			$options = array();

			if (count($contentList) > 0) {

				foreach ($contentList as $content) {
					$tmp = array(
						  'value' => $content['id']
						, 'label' => $content['title']
					);
					$options[] = $tmp;
				}

			}

			echo json_encode($options);

		}
	}


	public function getCategories()
	{
		$this->view   = FALSE;
		$this->layout = FALSE;

		$cType       = $this->uri->segment(4);

	    $options = array();

		if ($cType && ($contentType = $this->fm_cms->getContentTypeByName($cType))) {
		    $categories = $this->_getCategories($contentType['id']);

			if (count($categories) > 0) {

				foreach ($categories as $id => $category) {
					$options[] = array(
					    'value' => $id,
					    'label' => $category
					);
				}

			}

		}

		echo json_encode($options);
	}


	public function order()
	{
		$this->view   = FALSE;
		$this->layout = FALSE;

		$newOrder = (array)json_decode($this->input->post('new_order'));

		if (count($newOrder) > 0) {

			foreach ($newOrder as $idContent => $order) {

				$this->fm_cms->updateContentOrder($idContent, $order * 10);

			}

		}
	}


	public function orderAttachment()
	{
		$this->view   = FALSE;
		$this->layout = FALSE;

		$newOrder = (array)json_decode($this->input->post('new_order'));

		if (count($newOrder) > 0) {

			foreach ($newOrder as $idContentAttachment => $order) {

				$this->fm_cms->updateContentAttachmentOrder($idContentAttachment, $order * 10);

			}

		}
	}


	/**
	 * AJAX Image Upload for the XHTML textarea plugin
	 * This function saves the iamges into the TMP folder, like the normal image upload
	 */
	public function uploader()
	{
		$this->layout = FALSE;

		$tmpFolder = $this->fm_cms->getTmpFolder();

		$config['upload_path']		= $this->_tmpUploadPath . '/' . $tmpFolder;

		if (file_exists($config['upload_path']) == FALSE) {

			mkdir($config['upload_path'], 0777);

		}

		$conf['img_path']			= $this->config->item('txt_upl_img_path',	    'factotum') . '/' . $tmpFolder;
		$conf['allow_resize']		= $this->config->item('txt_upl_allow_resize',	'factotum');
		$config['allowed_types']	= $this->config->item('txt_upl_allowed_types',	'factotum');
		$config['max_size']			= $this->config->item('txt_upl_max_size',		'factotum');
		$config['encrypt_name']		= $this->config->item('txt_upl_encrypt_name',	'factotum');
		$config['overwrite']		= $this->config->item('txt_upl_overwrite',		'factotum');


		if (!$conf['allow_resize']) {

			$config['max_width']	= $this->config->item('txt_upl_max_width',		'factotum');
			$config['max_height']	= $this->config->item('txt_upl_max_height',		'factotum');

		} else {

			$conf['max_width']		= $this->config->item('txt_upl_max_width',		'factotum');
			$conf['max_height']		= $this->config->item('txt_upl_max_height',		'factotum');

			if ($conf['max_width'] == 0 and $conf['max_height'] == 0) {

				$conf['allow_resize'] = FALSE;

			}

		}

		// Load uploader
		$this->load->library('upload', $config);

		if ($this->upload->do_upload()) {

			// General result data
			$result = $this->upload->data();

			// Shall we resize an image?
			if ($conf['allow_resize'] && $conf['max_width'] > 0 && $conf['max_height'] > 0 &&
				(($result['image_width'] > $conf['max_width']) || ($result['image_height'] > $conf['max_height']))) {

				// Resizing parameters
				$resizeParams = array(
					  'source_image'    => $result['full_path']
					, 'new_image'       => $result['full_path']
					, 'width'           => $conf['max_width']
					, 'height'          => $conf['max_height']
				);

				// Load resize library
				$this->load->library('image_lib', $resizeParams);

				// Do resize
				$this->image_lib->resize();

			}

			// Add our stuff
			$this->data['result']		= "file_uploaded";
			$this->data['resultcode']	= 'ok';
			$this->data['file_name']	= $conf['img_path'] . '/' . $result['file_name'];

		} else {

			// Compile data for output
			$this->data['result']		= $this->upload->display_errors(' ', ' ');
			$this->data['resultcode']	= 'failed';

		}
	}


	public function blank()
	{
		$this->layout = FALSE;
		$this->data['txt_upl_blankpage_message'] = $this->lang->line('txt_upl_blankpage_message');
	}


	public function version()
	{
		// Do nothing, show only the CMS version
	}

	private function _getCategories($idContentType)
	{
		$categories = $this->fm_cms->getCategoriesByContentType($idContentType);

		$tmp = array();
		if (count($categories) > 0) {

			foreach ($categories as $cat) {

				$tmp[$cat['id']] = $cat['category_label'];

			}

		}

		return $tmp;
	}
}
