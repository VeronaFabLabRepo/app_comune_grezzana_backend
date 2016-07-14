<?php
function print_input_hidden($input)
{
	$data = array(
		  'name'        => $input['name']
		, 'id'          => (isset($input['id']) ? $input['id'] : $input['name'])
	);

	$html = '<input type="hidden" id="' . $data['id'] . '" name="' . $data['name'] . '" value="' . form_prep($input['value']) . '" />';

	return $html;
}