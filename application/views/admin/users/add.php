<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Add a new user</h1>
		<?php echo print_back_button('/admin/users/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'add'
	, 'value' => 'Add'
);
$this->load->view('admin/users/form', array('submit' => $submit, 'pwdMandatory' => TRUE));
?>

</div>