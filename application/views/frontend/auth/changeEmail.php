<div class="form_box">

<?php if (isset($successMessage) && $successMessage != '') { ?>

	<p><?php echo $successMessage; ?></p>

<?php } else { ?>

	<div class="title">Change Email</div>

<?php
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
		, 'label'       => ''
		, 'labelFor'    => ''
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['password']) ? $errors['password'] : '')
	);

	echo print_field($password);

	$email = array(
		    // Input part
		  'type'        => 'text'
		, 'name'        => 'email'
		, 'id'          => 'email'
		, 'value'       => $oldValues['email']
		, 'placeholder' => 'New email address'
			// Label
		, 'label'       => ''
		, 'labelFor'    => ''
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['email']) ? $errors['email'] : '')
	);
	echo print_field($email);

	$submit = array(
		  'name'  => 'change_email'
		, 'value' => 'Change Email'
	);
	echo print_submit_buttons(array($submit));

	echo print_form('close');

}
?>
</div>