<div class="login_logo clearfix">
	<img src="<?php echo $baseUrl; ?>assets/admin/img/logo.png" alt="Comune Grezzana">
	<h1>Comune Grezzana</h1>
</div>

<div class="form_box">

<?php if (isset($successMessage) && $successMessage != '') { ?>

	<p><?php echo $successMessage; ?></p>

<?php } elseif (isset($errorMessage) && $errorMessage != '') { ?>
	
	<p class="error"><?php echo $errorMessage; ?></p>

<?php } else { ?>

	<div class="title">Reset Password</div>

<?php

	echo print_form('open', 'POST', '/' . $this->uri->uri_string());

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
		, 'value' => 'Save'
	);
	echo print_submit_buttons(array($submit));

	echo print_form('close');

	}

?>
</div>