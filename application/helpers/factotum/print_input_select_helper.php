<?php
function print_input_select($input)
{
	$extra = '';

	if (isset($input['id']) && $input['id'] != '') {
		$extra .= 'id="' . $input['id'] . '" ';
	}

	if (isset($input['tabindex']) && $input['tabindex'] != '') {
		$extra .= 'tabindex="' . $input['tabindex'] . '" ';
	}

	return form_dropdown($input['name'], $input['options'], $input['value'], $extra);

}
	
