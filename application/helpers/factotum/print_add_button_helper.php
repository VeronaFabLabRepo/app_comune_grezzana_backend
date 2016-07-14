<?php
function print_add_button($link, $text)
{
	$html = '<a href="' . $link . '" class="add_button">'
		  . '<img src="/assets/admin/img/add.png" alt="Add">'
		  . $text
		  . '</a>' . "\n"
		  ;
	return $html;
}
	
		


