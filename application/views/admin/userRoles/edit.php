<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Edit the user role</h1>
		<?php echo print_back_button('/admin/userRoles/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'save'
	, 'value' => 'Save'
);
$this->load->view('admin/userRoles/form', array('submit' => $submit));
?>

</div>