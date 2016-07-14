<?php
function print_delete_button($link, $text = 'Delete')
{
	$html = '<a class="element link_action delete" href="' . $link . '">'
		  . '<img src="/assets/admin/img/cancel.png" alt="Delete">'
		  . $text
		  . '</a>' . "\n"
		  ;
	return $html;
}
	
		


