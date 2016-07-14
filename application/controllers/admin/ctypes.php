<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ctypes extends FM_AdminController
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->fm_users_management->canManageContentTypes()) {

			redirect('/admin/');

		}
	}

	public function index()
	{
		$this->data['list']    = $this->fm_cms->getContentTypes('pages');
		$this->data['success'] = $this->session->flashdata('success');

		$this->data['popupDeleteTitle'] = $this->lang->line('content_type_popup_delete_title');
		$this->data['popupDeleteText']  = $this->lang->line('content_type_popup_delete_text');
	}

	public function add()
	{
		$result = $this->_saveContentType();

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Content type added correctly.');
			redirect('/admin/ctypes/');

		}

		$this->data['success']   = $this->session->flashdata('success');
		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;

	}

	public function edit()
	{
		$idContentType = $this->uri->segment(4);
		$contentType = $this->fm_cms->getContentTypeById($idContentType);

		$result = $this->_saveContentType($idContentType);

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Content type saved correctly.');
			redirect('/admin/ctypes/edit/' . $idContentType);

		}

		$this->data['success']   = $this->session->flashdata('success');
		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;

	}

	public function delete()
	{
		$this->view = FALSE;

		// Retrieve the content if the idContent is setted
		$idContentType = $this->uri->segment(4);

		if ($this->fm_cms->deleteContentTypeById($idContentType)) {

			echo json_encode(array('type'      => 'ctype'
								 , 'elementId' => $idContentType));

		} else {

			echo json_encode(array('type'      => 'ctype'
								 , 'elementId' => FALSE));

		}

	}

	private function _saveContentType($idContentType = null)
	{
		$result    = array();
		$oldValues = array();
		$errors    = array();

		$result['data_saved'] = FALSE;

		if ($idContentType) {

			$contentType = $this->fm_cms->getContentTypeById($idContentType);
			$oldValues['content_type'] = $contentType['content_type'];

		} else {

			$oldValues['content_type'] = '';

		}

		if ($this->input->post('add') || ($this->input->post('save') && $this->input->post('content_type') != $oldValues['content_type'])) {

			$this->form_validation->set_rules('content_type', 'Content Type', 'trim|required|xss_clean|alpha_dash|callback_checkContentType');
			$this->form_validation->set_message('content_type', $this->lang->line('incorrect_content_type'));

		} 

		if ($this->input->post('add') || $this->input->post('save')) {

			$oldValues['content_type'] = $this->input->post('content_type');

			if ($this->form_validation->run()) {

				if (!$idContentType) {

					$idContentType = $this->fm_cms->insertContentType($oldValues['content_type']);

				} else {

					$this->fm_cms->updateContentType($oldValues['content_type'], $idContentType);

				}

				$result['data_saved'] = TRUE;

			} else {

				$errors['content_type'] = form_error('content_type');

			}

		}

		$result['errors']    = $errors;
		$result['oldValues'] = $oldValues;
		return $result;
	}

	public function order()
	{
		$this->view = FALSE;

		$newOrder = (array)json_decode($this->input->post('new_order'));

		if (count($newOrder) > 0) {

			foreach ($newOrder as $idContentType => $order) {

				$this->fm_cms->updateContentTypeOrder($idContentType, $order * 10);

			}

		}
	}

	public function checkContentType($contentType)
	{
		// Check the name with the content columns name
		if (!$this->fm_cms->isContentTypeAvailable($contentType)) {

			$this->form_validation->set_message('checkContentType', $this->lang->line('incorrect_content_type'));
			return FALSE;

		} else {

			return TRUE;
		}
	}
}
