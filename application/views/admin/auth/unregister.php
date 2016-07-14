<?php
if (isset($successMessage) && $successMessage != '') {

	echo '<p>' . $successMessage . '</p>';

} else {

	echo print_form('open', 'POST', '', 'edit_form');

	$password = array(
			// Field container
		  'class'       => 'password'
		    // Input file
		, 'type'        => 'password'
		, 'name'        => 'password'
		, 'id'          => 'password'
		, 'value'       => ''
		, 'placeholder' => 'Password'
			// Label
		, 'label'       => 'Password'
		, 'labelFor'    => 'password'
		, 'labelClass'  => 'password'
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['password']) ? $errors['password'] : '')
	);

	echo print_field($password);

	$submit = array(
		  'name'  => 'delete'
		, 'value' => 'Delete account'
	);
	echo print_submit_buttons(array($submit));

	echo print_form('close');

}
?>