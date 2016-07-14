<?php
function print_input_multicheckbox($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'checked'     => (isset($input['checked']) ? $input['checked'] : FALSE)
		, 'value'       => $input['value']
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
	);

	$html = '<span class="multicheckbox_field clearfix">' . "\n"
		  . "\t\t\t" . '<label ' . (isset($input['id']) ? 'for="' . $input['id'] . '"' : '' ) . '>'
		  . form_checkbox($data) . "\n\t\t"
		  . $input['label']
		  . '</label>' . "\n\t\t"
		  . '</span>' . "\n\t\t";

	return $html;
}
	
		


