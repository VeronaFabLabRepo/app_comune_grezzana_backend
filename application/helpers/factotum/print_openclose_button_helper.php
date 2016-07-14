<?php
function print_openclose_button($link, $text, $dataOpen)
{
	$html = '<a href="' . $link . '" class="openclose_button" data-open="' . $dataOpen . '">'
		  . '<img src="/assets/admin/img/trans.gif" alt="Open Close">'
		  . $text
		  . '</a>'
		  ;
	return $html;
}
	
		


