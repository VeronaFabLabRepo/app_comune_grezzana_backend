<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Edit the user</h1>
		<?php echo print_back_button('/admin/users/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'save'
	, 'value' => 'Save'
);
$this->load->view('admin/users/form', array('submit' => $submit, 'pwdMandatory' => FALSE));
?>

</div>