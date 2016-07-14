<div class="map">
	<iframe width="900" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.it/maps?sll=41.44272441609302,12.373451542754426&amp;sspn=16.076562843035493,37.66658735519404&amp;t=m&amp;q=Chroma+Agency,+Clerkenwell+Close,+Londra,+Regno+Unito&amp;dg=opt&amp;ie=UTF8&amp;hq=Chroma+Agency,&amp;hnear=Clerkenwell+Close,+London,+Regno+Unito&amp;ll=51.525861,-0.105979&amp;spn=0.00534,0.01929&amp;z=16&amp;iwloc=A&amp;output=embed"></iframe>
</div>

<h2>Contact us</h2>
<div class="clearfix">

	<div class="contact_column">
<?php
echo print_form('open', 'POST', '', 'contact_form');

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

$comment = array(
	    // Input part
	  'type'        => 'textarea'
	, 'name'        => 'comment'
	, 'id'          => 'comment'
	, 'value'       => $oldValues['comment']
	, 'placeholder' => 'Comment'
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['comment']) ? $errors['comment'] : '')
);
echo print_field($comment);

$submit = array(
	  'name'  => 'send'
	, 'value' => 'Send'
);
echo print_submit_buttons(array($submit));

echo print_form('close');
?>
	</div>

	<div class="contact_column">
		<h3>Chroma Agency</h3>
		<p>
			31a Clerkenwell Close<br>
			London EC1R 0AT<br>
			Phone: <strong> +44 (0)20 7608 2748</strong><br>
			Email: <a href="mailto:info@chromaagency.com">info@chromaagency.com</a>
		</p>
	</div>
</div>