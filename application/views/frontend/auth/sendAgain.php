<div class="form_box">

<?php if (isset($successMessage) && $successMessage != '') { ?>

	<p><?php echo $successMessage; ?></p>

<?php } else { ?>

	<div class="title">Send Again</div>

<?php
	echo print_form('open', 'POST', '', 'edit_form');

	$email = array(
		    // Input part
		  'type'        => 'text'
		, 'name'        => 'email'
		, 'id'          => 'email'
		, 'value'       => $oldValues['email']
		, 'placeholder' => 'Email Address'
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
		  'name'  => 'send_again'
		, 'value' => 'Send Again'
	);

	echo print_submit_buttons(array($submit));

	echo print_form('close');

}
?>
</div>