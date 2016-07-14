<?php
function print_input_checkbox($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
		, 'value'       => $input['value']
		, 'checked'     => (isset($input['checked']) ? $input['checked'] : FALSE)
		, 'tabindex'    => (isset($input['tabindex'])  ? $input['tabindex']  : '')
	);
	$html = '<span class="checkbox_field clearfix">' . form_checkbox($data) . '</span>';

	return $html;
}
