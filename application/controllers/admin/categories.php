<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends FM_AdminController
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->fm_users_management->canManageContentCategories()) {

			redirect('/admin/');

		}
	}

	public function index()
	{
		$list = $this->fm_cms->getCategories();

		if (count($list) > 0) {

			$tmp = array();
			foreach ($list as $category) {

				if (!isset($tmp[$category['id_content_type']])) {

					$tmp[$category['id_content_type']] = array(
						  'content_type' => $category['content_type']
						, 'categories'   => array()
					);

				}

				$tmp[$category['id_content_type']]['categories'][] = $category;

			}
			$list = $tmp;

		}

		$this->data['list'] = $list;
		$this->data['success']      = $this->session->flashdata('success');

		$this->data['popupDeleteTitle'] = $this->lang->line('content_category_popup_delete_title');
		$this->data['popupDeleteText']  = $this->lang->line('content_category_popup_delete_text');
	}

	public function add()
	{
		$result = $this->_saveCategory();

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Content category added correctly.');
			redirect('/admin/categories#categories_' . $oldValues['id_content_type']);

		}

		$this->data['success']        = $this->session->flashdata('success');
		$this->data['errors']         = $errors;

		$oldValues['id_content_type'] = $this->uri->segment(4);
		$this->data['oldValues']      = $oldValues;

	}

	
	public function edit()
	{
		$idContentCategory = $this->uri->segment(4);
		$contentCategory   = $this->fm_cms->getCategoryById($idContentCategory);

		$result = $this->_saveCategory($idContentCategory);

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Content category saved correctly.');
			redirect('/admin/categories/edit/' . $idContentCategory);

		}

		$this->data['success']   = $this->session->flashdata('success');
		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;

	}

	public function delete()
	{
		$this->view = FALSE;

		$idCategory = $this->uri->segment(4);

		if ($this->fm_cms->deleteContentCategoryByIdCategory($idCategory)) {

			if ($this->fm_cms->deleteCategoryById($idCategory)) {
				echo json_encode(array('type' => 'category', 'elementId' => $idCategory));
			} else {
				echo json_encode(array('type' => 'category', 'elementId' => FALSE));
			}

		} else {
			echo json_encode(array('type' => 'category', 'elementId' => FALSE));
		}


	}

	private function _saveCategory($idContentCategory = null)
	{
		$result    = array();
		$oldValues = array();
		$errors    = array();

		$result['data_saved'] = FALSE;

		if ($idContentCategory) {

			$contentCategory = $this->fm_cms->getCategoryById($idContentCategory);
			$oldValues['id_content_type'] = $contentCategory['id_content_type'];
			$oldValues['category_name']   = $contentCategory['category_name'];
			$oldValues['category_label']  = $contentCategory['category_label'];

		} else {

			$oldValues['id_content_type'] = '';
			$oldValues['category_name']   = '';
			$oldValues['category_label']  = '';

		}

		if ($this->input->post('add') || ($this->input->post('save') && $this->input->post('category_name') != $oldValues['category_name'])) {

			$this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|xss_clean|callback_checkCategoryName');
			$this->form_validation->set_message('category_name', $this->lang->line('incorrect_category_name'));

		}

		$this->form_validation->set_rules('category_label', 'Category Label', 'trim|required|xss_clean');
		$this->form_validation->set_message('category_label', $this->lang->line('incorrect_category_label'));

		if ($this->input->post('add') || $this->input->post('save')) {

			$oldValues['id_content_type'] = $this->input->post('id_content_type');
			$oldValues['category_name']   = $this->input->post('category_name');
			$oldValues['category_label']  = $this->input->post('category_label');

			if ($this->form_validation->run()) {

				if (!$idContentCategory) {

					$idContentCategory = $this->fm_cms->insertCategory($oldValues['id_content_type'], $oldValues['category_name'], $oldValues['category_label']);

				} else {

					$this->fm_cms->updateCategory($idContentCategory, $oldValues['id_content_type'], $oldValues['category_name'], $oldValues['category_label']);

				}

				$result['data_saved'] = TRUE;

			} else {

				$errors['id_content_type'] = form_error('id_content_type');
				$errors['category_name']   = form_error('category_name');
				$errors['category_label']  = form_error('category_label');

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

			foreach ($newOrder as $idContentCategory => $order) {

				$this->fm_cms->updateCategoryOrder($idContentCategory, $order * 10);

			}

		}
	}

	public function checkCategoryName($categoryName)
	{
		// Check the name with the content columns name
		if (!$this->fm_cms->isCategoryAvailable($categoryName)) {

			$this->form_validation->set_message('checkCategoryName', $this->lang->line('incorrect_category_name'));
			return FALSE;

		} else {

			return TRUE;
		}
	}
}
