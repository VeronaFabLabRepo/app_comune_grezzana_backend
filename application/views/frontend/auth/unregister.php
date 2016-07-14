<div class="form_box">

<?php if (isset($successMessage) && $successMessage != '') { ?>

	<p><?php echo $successMessage; ?></p>

<?php } else { ?>

	<div class="title">Unregister</div>

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

	$submit = array(
		  'name'  => 'delete'
		, 'value' => 'Delete account'
	);
	echo print_submit_buttons(array($submit));

	echo print_form('close');

}
?>
</div>