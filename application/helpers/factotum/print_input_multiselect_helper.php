<?php
function print_input_multiselect($input)
{
	$extra = 'class="multiselect" ';

	if (isset($input['id']) && $input['id'] != '') {
		$extra .= 'id="' . $input['id'] . '" ';
	}

	if (isset($input['tabindex']) && $input['tabindex'] != '') {
		$extra .= 'tabindex="' . $input['tabindex'] . '" ';
	}

	$html = form_multiselect($input['name'] . '[]', $input['options'], $input['value'], $extra);

	return $html;

}
	
		


