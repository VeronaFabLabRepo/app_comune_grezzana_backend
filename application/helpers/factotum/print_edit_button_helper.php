<?php
function print_edit_button($link, $text = 'Edit')
{
	$html = '<a class="element link_action" href="' . $link . '">'
		  . '<img src="/assets/admin/img/pencil.png" alt="Edit">'
		  . $text
		  . '</a>' . "\n"
		  ;
	return $html;
}
	
		


