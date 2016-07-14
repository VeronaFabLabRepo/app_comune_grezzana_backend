<?php
function print_form($openClose, $method = 'POST', $action = '', $class = '', $enctype = 'multipart/form-data', $autocomplete = '')
{
	if ($openClose == 'open') {
		return '<form method="' . $method . '" action="' . $action . '" class="' . $class . '" enctype="' . $enctype . '" autocomplete="' . $autocomplete . '">' . "\n";
	}
	
	if ($openClose == 'close') {
		return '</form>' . "\n";
	}
	
}
