<?php
function print_input_password($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'value'       => $input['value']
		, 'maxlength'   => (isset($input['maxlength']) ? $input['maxlength'] : '255')
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
		, 'placeholder' => (isset($input['placeholder']) ? $input['placeholder'] : '')
	);

	$html = form_password($data);

	return $html;
}