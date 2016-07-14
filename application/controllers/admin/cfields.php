<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cfields extends FM_AdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$contentTypeList = $this->fm_cms->getContentTypes();

		if (!$this->data['manageContentFields']) {

			redirect('/admin');

		}

        foreach ($contentTypeList as $index => $cType) {

                // Get content field for each content type
                $cFields = $this->fm_cms->getContentFieldsByIdContentType($cType['id']);
                $contentTypeList[$index]['cfields'] = $cFields;

        }

		$this->data['success'] = $this->session->flashdata('success');
		$this->data['list']    = $contentTypeList;

		$this->data['popupDeleteTitle'] = $this->lang->line('content_field_popup_delete_title');
		$this->data['popupDeleteText']  = $this->lang->line('content_field_popup_delete_text');
	}


	public function add()
	{
		$idContentType = $this->uri->segment(4);

		if (!$this->fm_users_management->canConfigureContentType($idContentType)) {

			redirect('/admin');

		}

		$result = $this->_saveContentField($idContentType);

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Field added correctly.');
			redirect('/admin/cfields#cfields_' . $idContentType);

		}

		$this->data['errors']    = $errors;
		$this->data['oldValues'] = $oldValues;
		$this->data['idContentType'] = $idContentType;
	}

	public function edit()
	{

		$idContentField = $this->uri->segment(4);
		$contentField   = $this->fm_cms->getContentFieldById($idContentField);

		if (!$this->fm_users_management->canConfigureContentType($contentField['id_content_type'])) {

			redirect('/admin');

		}

		$result = $this->_saveContentField(null, $idContentField);

		$oldValues = $result['oldValues'];
		$errors    = $result['errors'];
		$dataSaved = $result['data_saved'];

		if ($dataSaved) {

			$this->session->set_flashdata('success', 'Field saved correctly.');
			redirect('/admin/cfields#cfields_' . $contentField['id_content_type']);

		}

		$this->data['errors']        = $errors;
		$this->data['oldValues']     = $oldValues;
		$this->data['idContentType'] = $contentField['id_content_type'];

	}

	public function delete()
	{
		$this->view = FALSE;

		$idContentField = $this->uri->segment(4);
		$contentField   = $this->fm_cms->getContentFieldById($idContentField);

		if (!$this->fm_users_management->canConfigureContentType($contentField['id_content_type'])) {

			redirect('/admin');

		}

		if ($this->fm_cms->deleteContentFieldById($idContentField)) {

			echo json_encode(array('type'      => 'cfield'
								 , 'elementId' => $idContentField));

		} else {

			echo json_encode(array('type'      => 'cfield'
								 , 'elementId' => FALSE));

		}

	}

	/**
	 * AJAX function to get a row with the fields for add an option
	 */
	public function addOption()
	{
		$this->layout = FALSE;
		$this->view = 'admin/cfields/add_option';
	}

	private function _saveContentField($idContentType = null, $idContentField = null)
	{
		$result    = array();
		$oldValues = array();
		$errors    = array();

		$result['data_saved'] = FALSE;

		if ($idContentField) {

			$contentField   = $this->fm_cms->getContentFieldById($idContentField);
			$oldValues['name']                   = $contentField['name'];
			$oldValues['label']                  = $contentField['label'];
			$oldValues['hint']                   = $contentField['hint'];
			$oldValues['mandatory']              = ($contentField['mandatory'] ? 'true' : 'false');
			$oldValues['type']                   = $contentField['type'];
			$oldValues['max_file_size']          = $contentField['max_file_size'];
			$oldValues['allowed_types']          = $contentField['allowed_types'];
			$oldValues['max_image_size']         = $contentField['max_image_size'];
			$oldValues['thumb_size']             = $contentField['thumb_size'];
			$oldValues['image_operation']        = $contentField['image_operation'];
			$oldValues['image_bw']               = $contentField['image_bw'];
			$oldValues['linked_id_content_type'] = $contentField['linked_id_content_type'];
			$oldValues['options']['values']      = array();
			$oldValues['options']['labels']      = array();

			if (in_array($oldValues['type'], array('radio', 'select', 'multiselect', 'checkbox', 'multicheckbox'))) {

				$options = fm_Utility::convertOptionsTextToArray($contentField['options']);
				$oldValues['options']['values'] = array_keys($options);
				$oldValues['options']['labels'] = array_values($options);

			}
			
		} else {

			$oldValues['name']                   = '';
			$oldValues['label']                  = '';
			$oldValues['hint']                   = '';
			$oldValues['mandatory']              = '';
			$oldValues['type']                   = '';
			$oldValues['max_file_size']          = '';
			$oldValues['allowed_types']          = '';
			$oldValues['max_image_size']         = '';
			$oldValues['thumb_size']             = '';
			$oldValues['image_operation']        = '';
			$oldValues['image_bw']               = '';
			$oldValues['linked_id_content_type'] = '';

		}

		if ($this->input->post('add') || ($this->input->post('save') && $this->input->post('name') != $oldValues['name'])) {
			$this->form_validation->set_rules('name', 'Field Name', 'trim|required|xss_clean|alpha_dash|callback_checkFieldName');
			$this->form_validation->set_message('name', $this->lang->line('incorrect_content_field_name'));
		} 
		

		$this->form_validation->set_rules('label', 'Field Label', 'trim|required|xss_clean');
		$this->form_validation->set_message('label', $this->lang->line('incorrect_content_field_label'));

		$this->form_validation->set_rules('mandatory', 'Mandatory', 'trim|required|xss_clean');
		$this->form_validation->set_message('mandatory', $this->lang->line('incorrect_content_field_mandatory'));

		$this->form_validation->set_rules('type', 'Field Type', 'trim|required|xss_clean');
		$this->form_validation->set_message('type', $this->lang->line('incorrect_content_field_type'));

		if ($this->input->post('add') || $this->input->post('save')) {

			$oldValues['name']      = $this->input->post('name');
			$oldValues['type']      = $this->input->post('type');
			$oldValues['label']     = $this->input->post('label');
			$oldValues['hint']      = $this->input->post('hint');
			$oldValues['mandatory'] = $this->input->post('mandatory');

			if ($this->input->post('type') == 'file_upload' || 
				$this->input->post('type') == 'image_upload' ||
				$this->input->post('type') == 'gallery') {

				$oldValues['max_file_size'] = $this->input->post('max_file_size');
				$oldValues['allowed_types'] = $this->input->post('allowed_types');

				$this->form_validation->set_rules('max_file_size', 'Max File Size', 'trim|required|xss_clean|integer');
				$this->form_validation->set_message('max_file_size', $this->lang->line('incorrect_max_file_size'));

				$this->form_validation->set_rules('allowed_types', 'Allowed Types', 'trim|required|xss_clean');
				$this->form_validation->set_message('allowed_types', $this->lang->line('incorrect_allowed_types'));

				if ($this->input->post('type') == 'image_upload' || $this->input->post('type') == 'gallery') {

					$oldValues['max_image_size']  = $this->input->post('max_image_size');
					$oldValues['thumb_size']      = $this->input->post('thumb_size');
					$oldValues['image_operation'] = $this->input->post('image_operation');
					$oldValues['image_bw']        = $this->input->post('image_bw');

					$this->form_validation->set_rules('max_image_size', 'Max Image Size', 'trim|required|xss_clean');
					$this->form_validation->set_message('max_image_size', $this->lang->line('incorrect_max_image_size'));

					$this->form_validation->set_rules('thumb_size', 'Thumb Size', 'trim|required|xss_clean');
					$this->form_validation->set_message('thumb_size', $this->lang->line('incorrect_thumb_size'));

					$this->form_validation->set_rules('image_operation', 'Image Operation', 'trim|required|xss_clean');
					$this->form_validation->set_message('image_operation', $this->lang->line('incorrect_image_operation'));

				}

			} else {

				$oldValues['max_file_size'] = '';
				$oldValues['allowed_types'] = '';

			}
	
			if (in_array($this->input->post('type'), array('linked_content', 'multiple_linked_content'))) {

				$oldValues['linked_id_content_type'] = $this->input->post('linked_id_content_type');
				$this->form_validation->set_rules('linked_id_content_type', 'Linked Content Type', 'trim|required|xss_clean');
				$this->form_validation->set_message('linked_id_content_type', $this->lang->line('incorrect_linked_id_content_type'));

			} else {

				$oldValues['linked_id_content_type'] = '';

			}

			if ($this->input->post('type') == 'radio' || 
				$this->input->post('type') == 'select' ||
				$this->input->post('type') == 'multiselect' ||
				$this->input->post('type') == 'checkbox' ||
				$this->input->post('type') == 'multicheckbox') {

				$options = $this->input->post('options');
				if ($options) {

					$oldValues['options']['values'] = $options['values'];
					$oldValues['options']['labels'] = $options['labels'];

					if (isset($oldValues['options']['values']) && count($oldValues['options']['values']) == 0) {

						$errors['options']['values'][0]  = $this->lang->line('incorrect_options_values');

					}

					if (isset($oldValues['options']['labels']) && count($oldValues['options']['labels']) == 0) {

						$errors['options']['labels'][0]  = $this->lang->line('incorrect_options_labels');

					}
				}

			}

			if ($this->form_validation->run()) {

				$extra = array();

				$extra['hint'] = $oldValues['hint'];

				if (in_array($oldValues['type'], array('select', 'multiselect', 'radio', 'checkbox', 'multicheckbox'))) {
	
					$options = array();

					foreach ($oldValues['options']['values'] as $index => $value) {

						if ($value != '') {

							$options[] = FM_Utility::cleanString($value) . ':' . $oldValues['options']['labels'][$index];

						}

					}
	
					$extra['options'] = join("\n", $options);
				}
	
				if (in_array($oldValues['type'], array('file_upload', 'image_upload', 'gallery'))) {
	
					$extra['max_file_size'] = $oldValues['max_file_size'];
					$extra['allowed_types'] = $oldValues['allowed_types'];

				}

				if (in_array($oldValues['type'], array('image_upload', 'gallery'))) {
	
					$extra['max_image_size']  = $oldValues['max_image_size'];
					$extra['thumb_size']      = $oldValues['thumb_size'];
					$extra['image_operation'] = $oldValues['image_operation'];
					$extra['image_bw']        = $oldValues['image_bw'];

				}

				if (in_array($oldValues['type'], array('linked_content', 'multiple_linked_content'))) {
	
					$extra['linked_id_content_type'] = $oldValues['linked_id_content_type'];

				}

				$oldValues['mandatory'] = ($oldValues['mandatory'] == 'true' ? TRUE : FALSE);

				if (!$idContentField) {

					$this->fm_cms->insertContentField($idContentType, $oldValues['name'], $oldValues['type'], $oldValues['label'], $oldValues['mandatory'], '', $extra);

				} else {

					$this->fm_cms->updateContentField($idContentField, $oldValues['name'], $oldValues['type'], $oldValues['label'], $oldValues['mandatory'], '', $extra);

				}

				$result['data_saved'] = TRUE;

			} else {

				$errors['name']               = form_error('name');
				$errors['label']              = form_error('label');
				$errors['mandatory']          = form_error('mandatory');
				$errors['type']               = form_error('type');
				$errors['options']['values']  = form_error('options[values]');
				$errors['options']['labels']  = form_error('options[labels]');
				$errors['max_file_size']      = form_error('max_file_size');
				$errors['allowed_types']      = form_error('allowed_types');
				$errors['max_image_size']     = form_error('max_image_size');
				$errors['thumb_size']         = form_error('thumb_size');
				$errors['image_operation']    = form_error('image_operation');

			}

		}

		$result['errors']    = $errors;
		$result['oldValues'] = $oldValues;
		return $result;
	}

	public function checkFieldName($name)
	{
		// Check the name with the content columns name
		if (!$this->fm_cms->checkContentFieldName($name) || !$this->fm_cms->isContentFieldNameAvailable($name)) {

			$this->form_validation->set_message('checkFieldName', $this->lang->line('existing_column_name'));
			return FALSE;

		} else {

			return TRUE;
		}
	}

	public function order()
	{
		$this->view = FALSE;

		$newOrder = (array)json_decode($this->input->post('new_order'));

		if (count($newOrder) > 0) {

			foreach ($newOrder as $idContentField => $order) {

				$this->fm_cms->updateContentFieldOrder($idContentField, $order * 10);

			}

		}
	}
}
