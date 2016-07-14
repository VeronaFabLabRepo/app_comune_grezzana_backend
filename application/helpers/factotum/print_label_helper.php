<?php
function print_label($label)
{
	$html = (isset($label['hint']) && $label['hint'] != '' ? '<a href="#" class="hint"><span>' . $label['hint'] . '</span></a>' : '')
		  . $label['label']
		  . (isset($label['mandatory']) && $label['mandatory'] ? ' <span class="mandatory">*</span>' : '') 
		  ;

	$attributes = array();

	$attributes['class'] = 'clearfix';

	if (isset($label['labelClass'])) {

		$attributes['class'] .= ' ' . $label['labelClass'];

	}
	return form_label( $html , (isset($label['labelFor']) ? $label['labelFor'] : ''), $attributes);
}
	
		


