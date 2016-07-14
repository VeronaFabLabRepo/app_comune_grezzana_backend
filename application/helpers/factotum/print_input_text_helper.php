<?php
function print_input_text($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'maxlength'   => (isset($input['maxlength']) ? $input['maxlength'] : '255')
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
		, 'placeholder' => (isset($input['placeholder']) ? $input['placeholder'] : '')
		, 'class'       => (isset($input['class']) ? $input['class'] : '')
	);

	$html = form_input($data, $input['value']);

	return $html;
}