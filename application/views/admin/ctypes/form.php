<?php
echo print_form('open', 'POST', '', 'edit_form');

$contentType = array(
	    // Input file
	  'type'        => 'text'
	, 'name'        => 'content_type'
	, 'id'          => 'content_type'
	, 'value'       => $oldValues['content_type']
	, 'placeholder' => 'Content Type'
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['content_type']) ? $errors['content_type'] : '')
);

echo print_field($contentType);

echo print_submit_buttons(array($submit));

echo print_form('close');
?>