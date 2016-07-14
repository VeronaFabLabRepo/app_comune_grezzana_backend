<?php
function print_submit_buttons($buttons)
{
	$html = '<div class="submit_container clearfix">';

	foreach ($buttons as $button) {
		$html .= '<input type="submit" name="' . $button['name'] . '" value="' . $button['value'] . '"'
			   . (isset($button['class']) ? ' class="' . $button['class'] . '"' : '')
			   . (isset($button['tabindex']) ? ' tabindex="' . $button['tabindex'] . '"' : '')
			   . '>'
			   ;
	}

	$html .= '</div>';

	return $html;
}
	
		


