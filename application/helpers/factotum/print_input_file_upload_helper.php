<?php
function print_input_file_upload($input)
{
	$html = '';

	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
		, 'value'       => $input['value']
	);

	$html .= form_upload($data)
		   . form_hidden($input['name'], $input['value'])
		   ;

	return $html;
}

