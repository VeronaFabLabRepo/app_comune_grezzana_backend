<?php
function print_input_gallery($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
	);

	$html = form_upload($data)
		  . form_hidden($input['name'], $input['value'])
		  ;

	return $html;
}

