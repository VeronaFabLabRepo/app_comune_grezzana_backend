<div class="form_box">

<?php if (isset($successMessage) && $successMessage != '') { ?>

	<p><?php echo $successMessage; ?></p>

<?php } else { ?>

	<div class="title">Forgot Password</div>

<?php

	echo print_form('open', 'POST', '/' . $this->uri->uri_string());

	$login = array(
		    // Input part
		  'type'        => 'text'
		, 'name'        => 'login'
		, 'id'          => 'login'
		, 'value'       => $oldValues['login']
		, 'placeholder' => ($useUsername ? 'Email or login' : 'Email')
			// Label
		, 'label'       => ''
		, 'labelFor'    => ''
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['login']) ? $errors['login'] : '')
	);
	echo print_field($login);

	$submit = array(
		  'name'  => 'send_email'
		, 'value' => 'Send Email'
	);
	echo print_submit_buttons(array($submit));

	echo print_form('close');

	}

?>
</div>