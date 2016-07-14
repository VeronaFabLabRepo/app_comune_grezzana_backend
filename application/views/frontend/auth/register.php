<div class="form_box">

	<div class="title">Registration</div>

<?php
echo print_form('open', 'POST', '', 'edit_form');

if ($useUsername) {

	$username = array(
		    // Input part
		  'type'        => 'text'
		, 'name'        => 'username'
		, 'id'          => 'username'
		, 'value'       => $oldValues['username']
		, 'placeholder' => 'Username'
			// Label
		, 'label'       => ''
		, 'labelFor'    => ''
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => FALSE
		, 'error'       => (isset($errors['username']) ? $errors['username'] : '')
	);
	echo print_field($username);

}


$email = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'email'
	, 'id'          => 'email'
	, 'value'       => $oldValues['email']
	, 'placeholder' => 'Email'
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['email']) ? $errors['email'] : '')
);
echo print_field($email);


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
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['password']) ? $errors['password'] : '')
);

echo print_field($password);


$confirmPassword = array(
		// Field container
	  'class'       => 'confirm_password'
	    // Input file
	, 'type'        => 'password'
	, 'name'        => 'confirm_password'
	, 'id'          => 'confirm_password'
	, 'value'       => ''
	, 'placeholder' => 'Confirm Password'
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['confirm_password']) ? $errors['confirm_password'] : '')
);

echo print_field($confirmPassword);

$firstname = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'firstname'
	, 'id'          => 'firstname'
	, 'value'       => $oldValues['firstname']
	, 'placeholder' => 'Firstname'
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['firstname']) ? $errors['firstname'] : '')
);
echo print_field($firstname);

$lastname = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'lastname'
	, 'id'          => 'lastname'
	, 'value'       => $oldValues['lastname']
	, 'placeholder' => 'Lastname'
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['lastname']) ? $errors['lastname'] : '')
);
echo print_field($lastname);

$dob = array(
	    // Input part
	  'type'        => 'date'
	, 'name'        => 'dob'
	, 'id'          => 'dob'
	, 'value'       => $oldValues['dob']
	, 'placeholder' => 'Date of Birth'
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['dob']) ? $errors['dob'] : '')
);
echo print_field($dob);


if ($captchaRegistration) {

	if ($useRecaptcha) {

		if (isset($errors['recaptcha_response_field']) && $errors['recaptcha_response_field'] != '') {

			echo '<div class="captcha_container error">';

		} else {

			echo '<div class="captcha_container">';

		}

		echo $recaptchaHtml;

		echo '</div>';

	} else {

		echo '<h2>Enter the code exactly as it appears:</h2>';

		echo $captchaHtml;

		$captcha = array(
			    // Input part
			  'type'        => 'text'
			, 'name'        => 'captcha'
			, 'id'          => 'captcha'
			, 'value'       => ''
			, 'placeholder' => 'Confirmation Code'
				// Label
			, 'label'       => ''
			, 'labelFor'    => ''
			, 'labelClass'  => ''
			, 'mandatory'   => TRUE
				// Error
			, 'showError'   => FALSE
			, 'error'       => (isset($errors['captcha']) ? $errors['captcha'] : '')
			, 'maxlength'   => 8
		);
		echo print_field($captcha);

	}

}


$submit = array(
	  'name'  => 'register'
	, 'value' => 'Register'
);
echo print_submit_buttons(array($submit));

echo print_form('close');
?>
</div>