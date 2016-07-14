<?php
function print_input_radio($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'checked'     => (isset($input['checked']) ? $input['checked'] : FALSE)
		, 'value'       => $input['value']
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
	);

	$html = '<span class="radio_field clearfix">'
		  . '<label ' . (isset($input['id']) ? 'for="' . $input['id'] . '"' : '' ) . '>'
		  . form_radio($data)
		  . $input['label']
		  . '</label>'
		  . '</span>';

	return $html;
}
	
		


