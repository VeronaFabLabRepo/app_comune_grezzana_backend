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
		, 'label'       => 'Username'
		, 'labelFor'    => 'username'
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['username']) ? $errors['username'] : '')
	);
	echo print_field($username);

}

$role = array(
	    // Input part
	  'type'        => 'select'
	, 'name'        => 'role'
	, 'id'          => 'role'
	, 'value'       => $oldValues['role']
	, 'options'     => $roles
		// Label
	, 'label'       => 'Role'
	, 'labelFor'    => 'role'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['role']) ? $errors['role'] : '')
);
echo print_field($role);

$email = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'email'
	, 'id'          => 'email'
	, 'value'       => $oldValues['email']
	, 'placeholder' => 'Email'
		// Label
	, 'label'       => 'Email'
	, 'labelFor'    => 'email'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
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
	, 'label'       => 'Password'
	, 'labelFor'    => 'password'
	, 'labelClass'  => 'password'
	, 'mandatory'   => $pwdMandatory
		// Error
	, 'showError'   => TRUE
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
	, 'label'       => 'Confirm Password'
	, 'labelFor'    => 'confirm_password'
	, 'labelClass'  => 'confirm_password'
	, 'mandatory'   => $pwdMandatory
		// Error
	, 'showError'   => TRUE
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
	, 'label'       => 'Firstname'
	, 'labelFor'    => 'firstname'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
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
	, 'label'       => 'Lastname'
	, 'labelFor'    => 'lastname'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
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
	, 'label'       => 'Date of Birth'
	, 'labelFor'    => 'dob'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['dob']) ? $errors['dob'] : '')
);
echo print_field($dob);

echo print_submit_buttons(array($submit));

echo print_form('close');
?>