<div class="form_box">

<?php if (isset($successMessage) && $successMessage != '') { ?>

	<p><?php echo $successMessage; ?></p>

<?php } else { ?>

	<div class="title">Change Password</div>

<?php
	echo print_form('open', 'POST', '', 'edit_form');
	
	$oldPassword = array(
			// Field container
		  'class'       => 'old_password'
		    // Input file
		, 'type'        => 'password'
		, 'name'        => 'old_password'
		, 'id'          => 'old_password'
		, 'value'       => ''
		, 'placeholder' => 'Old Password'
			// Label
		, 'label'       => ''
		, 'labelFor'    => ''
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['old_password']) ? $errors['old_password'] : '')
	);

	echo print_field($oldPassword);
	
	$newPassword = array(
			// Field container
		  'class'       => 'new_password'
		    // Input file
		, 'type'        => 'password'
		, 'name'        => 'new_password'
		, 'id'          => 'new_password'
		, 'value'       => ''
		, 'placeholder' => 'New Password'
			// Label
		, 'label'       => ''
		, 'labelFor'    => ''
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['new_password']) ? $errors['new_password'] : '')
	);

	echo print_field($newPassword);
	
	$confirmNewPassword = array(
			// Field container
		  'class'       => 'confirm_new_password'
		    // Input file
		, 'type'        => 'password'
		, 'name'        => 'confirm_new_password'
		, 'id'          => 'confirm_new_password'
		, 'value'       => ''
		, 'placeholder' => 'Confirm New Password'
			// Label
		, 'label'       => ''
		, 'labelFor'    => ''
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['confirm_new_password']) ? $errors['confirm_new_password'] : '')
	);

	echo print_field($confirmNewPassword);

	$submit = array(
		  'name'  => 'change_password'
		, 'value' => 'Change Password'
	);
	echo print_submit_buttons(array($submit));

	echo print_form('close');

}
?>
</div>