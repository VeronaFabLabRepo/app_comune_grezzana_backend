<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Add a new user role</h1>
		<?php echo print_back_button('/admin/userRoles/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'add'
	, 'value' => 'Add'
);
$this->load->view('admin/userRoles/form', array('submit' => $submit));
?>

</div>