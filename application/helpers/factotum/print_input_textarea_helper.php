<?php
function print_input_textarea($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'maxlength'   => (isset($input['maxlength']) ? $input['maxlength'] : '')
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
		, 'placeholder' => (isset($input['placeholder']) ? $input['placeholder'] : '')
	);

	$html = form_textarea($data, $input['value']);

	return $html;
}
	
		


