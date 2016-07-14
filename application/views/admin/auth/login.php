<?php
if ($loginByUsername AND $loginByEmail) {

	$loginLabel = 'Email or username';

} else if ($loginByUsername) {

	$loginLabel = 'Login';

} else {

	$loginLabel = 'Email';

}

?>
<div class="login_logo clearfix">
	<img src="<?php echo $baseUrl; ?>assets/admin/img/logo.png" alt="Comune Grezzana">
	<h1>Comune Grezzana</h1>
</div>

<div class="form_box<?php echo ($showCaptcha ? ' with_captcha' : ''); ?>">

	<div class="title">Log In</div>

<?php

echo print_form('open', 'POST', '/' . $this->uri->uri_string());

$login = array(
		// Field container
	  'class'       => 'login'
	    // Input file
	, 'type'        => 'text'
	, 'name'        => 'login'
	, 'id'          => 'login'
	, 'value'       => $oldValues['login']
	, 'placeholder' => 'Login'
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['login']) ? $errors['login'] : '')
);

echo print_field($login);

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

$remember = array(
	    // Input part
	  'type'        => 'checkbox'
	, 'name'        => 'remember'
	, 'id'          => 'remember'
	, 'options'     => array(TRUE => 'Remember me')
	, 'checked'     => ($oldValues['remember'] ? TRUE : FALSE)
		// Label
	, 'label'       => 'Remember me'
	, 'labelFor'    => 'remember'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['remember']) ? $errors['remember'] : '')
);
echo print_field($remember);

if ($showCaptcha) {

	if ($useRecaptcha) {

		if (isset($errors['recaptcha_response_field']) && $errors['recaptcha_response_field'] != '') {

			echo '<div class="captcha_container error">';

		} else {

			echo '<div class="captcha_container">';

		}

		echo $recaptchaHtml;

		echo '</div>';

	} else {

		echo '<div class="title small">Enter the code exactly as it appears:</div>';
		echo '<div class="captcha_container">' . $captchaHtml . '</div>';

		$captcha = array(
			    // Input part
			  'type'        => 'text'
			, 'name'        => 'captcha'
			, 'id'          => 'captcha'
			, 'value'       => ''
			, 'placeholder' => 'Confirmation Code'
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
	  'name'  => 'login_submit'
	, 'value' => 'Log In'
);
echo print_submit_buttons(array($submit));

echo print_form('close');
?>

<div class="link_container clearfix">
<?php echo anchor('/admin/auth/forgotPassword/', 'Forgot password'); ?>
</div>

</div>