<?php
function print_back_button($link, $text = 'Go back')
{
	$html = '<a href="' . $link . '" class="back_button">'
		  . '<img src="/assets/admin/img/left.png" alt="Back">'
		  . $text
		  . '</a>' . "\n"
		  ;
	return $html;
}
	
		


