<?php
function print_input_xhtml_textarea($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'maxlength'   => (isset($input['maxlength']) ? $input['maxlength'] : '255')
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
		, 'placeholder' => (isset($input['placeholder']) ? $input['placeholder'] : '')
		, 'class'       => 'xhtml'
	);

	$html = form_textarea($data, $input['value']);

	return $html;
}
	
		


