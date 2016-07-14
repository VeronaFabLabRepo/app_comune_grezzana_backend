<?php
if (isset($successMessage) && $successMessage != '') {

	echo '<p>' . $successMessage . '</p>';

} else {

	echo print_form('open', 'POST', '', 'edit_form');

	$email = array(
		    // Input part
		  'type'        => 'text'
		, 'name'        => 'email'
		, 'id'          => 'email'
		, 'value'       => $oldValues['email']
		, 'placeholder' => 'Email Address'
			// Label
		, 'label'       => 'Email Address'
		, 'labelFor'    => 'email'
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['email']) ? $errors['email'] : '')
	);
	echo print_field($email);

	$submit = array(
		  'name'  => 'send_again'
		, 'value' => 'Send'
	);

	echo print_submit_buttons(array($submit));

	echo print_form('close');

}
?>