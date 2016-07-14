<div class="overlay"></div>

<div class="overlay_content" id="login_overlay">

	<div class="title">Log into Splash Games</div>

	<form method="POST" action="">

		<div class="content">

			<a href="#" class="fb_login"><img src="/img/fb_login_button.jpg" alt="Facebook login"></a>

			<span class="or">OR</span>

<?php
// Email Address
$email = array(
	  'name'	    => 'email'
	, 'id'	        => 'email'
	, 'mandatory'   => TRUE
	, 'label'       => 'Email Address'
	, 'tabindex'    => 1
);
echo print_input_text($email, $loginOldValues, $loginErrors);

// Password
$password = array(
	  'name'	    => 'password'
	, 'id'	        => 'password'
	, 'mandatory'   => TRUE
	, 'label'       => 'Password'
	, 'tabindex'    => 5
);
echo print_input_password($password, $loginOldValues, $loginErrors);

// Remeber me
$sendOffer = array(
	  'name'	    => 'remember'
	, 'id'	        => 'remember'
	, 'mandatory'   => FALSE
	, 'label'       => 'Remember me next time'
	, 'value'       => 1
	, 'tabindex'    => 10
);
echo print_checkbox($sendOffer, $loginOldValues, $loginErrors);

?>

			<div class="field forgot_password">
				<a href="/forgot-password" class="forgot_password">Forgot password?</a>
			</div>

		</div>

		<div class="submit_container">
			<span class="arrow medium_green"><input type="submit" name="login" value="Sign In"></span>
		</div>

		<input type="hidden" name="redirect_after_login" id="redirect_after_login" value="">
	</form>

</div>
<?php
if ((isset($loginErrors['email']) && $loginErrors['email'] != '') || (isset($loginErrors['password']) && $loginErrors['password'] != '')) {
?>
<script type="text/javascript">
	var openLogin = true;
</script>
<?php
}
?>
