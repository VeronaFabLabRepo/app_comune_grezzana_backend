<?php
function print_field($field)
{
	// Container
	$html = "\t" . '<div class="field clearfix' 
		   . (isset($field['class']) ? ' ' . $field['class'] : ' ' . $field['name'])
		   . (isset($field['error']) && $field['error'] != '' ? ' error' : '')
		   . '">' . "\n";

	$labelHTML = '';
	$inputHTML = '';
	$errorHTML = '';

	// Label
	if (isset($field['label']) && $field['label'] != '') {

		$label = array();
		$label['label'] = $field['label'];

		if (isset($field['labelFor'])) {

			$label['labelFor'] = $field['labelFor'];

		} else if (isset($field['id'])) {

			$label['labelFor'] = $field['id'];
		}

		$label['labelClass'] = $field['type'] . '_label';

		if (isset($field['labelClass'])) {

			$label['labelClass'] .= ' ' . $field['labelClass'];

		}	
	
		if (isset($field['hint'])) {

			$label['hint'] = $field['hint'];

		}	
	
		if (isset($field['mandatory'])) {

			$label['mandatory'] = $field['mandatory'];

		}

		$labelHTML .= print_label($label);
	}

	if (isset($field['mandatory']) && $field['mandatory']) {

		if (isset($field['placeholder'])) {

			$field['placeholder'] .= ' *';

		}

		if (isset($field['label'])) {

			$field['label'] .= ' *';

		}
	}

	// Input element
	switch ($field['type']) {

		case 'hidden':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
			);
			$inputHTML = print_input_hidden($input);

		break;

		case 'text':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'placeholder' => (isset($field['placeholder']) ? $field['placeholder'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
			);
			$inputHTML = print_input_text($input);

		break;

		case 'textarea':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'placeholder' => (isset($field['placeholder']) ? $field['placeholder'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
			);
			$inputHTML = print_input_textarea($input);

		break;

		case 'xhtml_textarea':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'placeholder' => (isset($field['placeholder']) ? $field['placeholder'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
			);
			$inputHTML = print_input_xhtml_textarea($input);

		break;

		case 'select':

			if (!$field['mandatory']) {

				$field['options'] = array('' => 'Select from the list') + $field['options'];

			}

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'label'       => $label
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				, 'options'     => $field['options']
			);
			$inputHTML = print_input_select($input);

		break;

		case 'multiselect':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'label'       => $label
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				, 'options'     => $field['options']
			);
			$inputHTML = print_input_multiselect($input);

		break;

		case 'checkbox':

			foreach ($field['options'] as $option => $label) {

				$input = array(
					  'name'        => $field['name']
					, 'id'          => (isset($field['id']) ? $field['id'] : '')
					, 'label'       => $label
					, 'checked'     => (isset($field['checked']) && $field['checked'] ? TRUE : FALSE)
					, 'value'       => $option
					, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				);
				$inputHTML = print_input_checkbox($input);

			}

		break;

		case 'multicheckbox':

			foreach ($field['options'] as $option => $label) {

				$input = array(
					  'name'        => $field['name'] . '[]'
					, 'id'          => (isset($field['id']) ? $field['id'] : '') . '_' . $option
					, 'label'       => $label
					, 'checked'     => (isset($field['value']) && $field['value'] != '' && in_array($option, $field['value'])  ? TRUE : FALSE)
					, 'value'       => $option
					, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				);
				$inputHTML .= print_input_multicheckbox($input);

			}

		break;

		case 'radio':

			foreach ($field['options'] as $option => $label) {

				$input = array(
					  'name'        => $field['name']
					, 'id'          => (isset($field['id']) ? $field['id'] : '') . '_' . $option
					, 'label'       => $label
					, 'checked'     => (isset($field['value']) && $field['value'] == $option ? TRUE : FALSE)
					, 'value'       => $option
					, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				);
				$inputHTML .= print_input_radio($input);
			}


		break;

		case 'image_upload':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
			);

			$inputHTML = print_input_image_upload($input);

		break;

		case 'file_upload':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
			);

			$inputHTML = print_input_file_upload($input);

		break;

		case 'gallery':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				, 'attachs'     => $field['attachs']
			);

			$inputHTML = print_input_gallery($input);

		break;

		case 'password':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'placeholder' => (isset($field['placeholder']) ? $field['placeholder'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
			);
			$inputHTML = print_input_password($input);

		break;

		case 'linked_content':

			if (!$field['mandatory']) {

				$field['options'] = array('' => 'Select from the list') + $field['options'];

			}

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'label'       => $label
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				, 'options'     => $field['options']
			);
			$inputHTML = print_input_select($input);

		break;


		case 'multiple_linked_content':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'label'       => $label
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				, 'options'     => $field['options']
			);
			$inputHTML = print_input_multiselect($input);

		break;

		case 'date':
		case 'datetime':

			$input = array(
				  'name'        => $field['name']
				, 'id'          => (isset($field['id']) ? $field['id'] : '')
				, 'value'       => (isset($field['value']) ? $field['value'] : '')
				, 'placeholder' => (isset($field['placeholder']) ? $field['placeholder'] : '')
				, 'tabindex'    => (isset($field['tabindex']) ? $field['tabindex'] : '')
				, 'class'       => (isset($field['class']) ? $field['class'] : '') . ' ' . $field['type'] . 'picker'
			);
			$inputHTML = print_input_text($input);

		break;
	}
	
	// Error
	if (isset($field['showError']) && $field['showError'] && isset($field['error']) && $field['error']) {

		$errorHTML = print_error($field['error']);

	}

	switch ($field['type']) {

		case 'text':
		case 'textarea':
		case 'xhtml_textarea':
		case 'select':
		case 'multiselect':
		case 'radio':
		case 'password':
		case 'date':
		case 'datetime':
		case 'multicheckbox':
		case 'linked_content':
		case 'multiple_linked_content':

			$html .= "\t\t" . $labelHTML . "\n\t\t" . $inputHTML . "\n\t" . $errorHTML;

		break;

		case 'checkbox':

			$html .= "\t\t" . $inputHTML . "\n\t\t"  . $labelHTML . "\n\t" . $errorHTML;

		break;

		case 'file_upload':

			$html .= '<div class="file_upload">'
				   . $labelHTML
				   . $inputHTML
				   . $errorHTML
				   . '</div>'
				   . print_file_preview($field['attach'], $field['name'])
				   ;

		break;

		case 'image_upload':

			$html .= '<div class="image_upload">'
				   . $labelHTML
				   . $inputHTML
				   . $errorHTML
				   . '</div>'
				   . print_image_preview($field['attach'], $field['name'])
				   ;

		break;

		case 'gallery':

			$html .= '<div class="clearfix">'
				   . '<div class="image_upload gallery">'
				   . $labelHTML
				   . $inputHTML
				   . $errorHTML
				   . '</div>'
				   . print_gallery_preview($field['attachs'], $field['name'])
				   . '</div>'
				   ;

		break;

		case 'hidden':

			$html .= "\t\t" . $inputHTML . "\n\t";

		break;
	}

	$html .= '</div>' . "\n";

	return $html;	
}
