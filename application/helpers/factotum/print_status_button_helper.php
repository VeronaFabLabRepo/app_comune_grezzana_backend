<?php
function print_status_button($link, $status, $statusVersus)
{
	$html = '<a href="' . $link . '" class="element status" title="' . $statusVersus . '">'
		  . '<img class="' . $status . '" src="/assets/admin/img/trans.gif" alt="Status">'
		  . '</a>'
		  ;
	return $html;
}
	
		


