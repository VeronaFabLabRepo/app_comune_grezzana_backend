<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Edit the field</h1>
		<?php echo print_back_button('/admin/cfields/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'save'
	, 'value' => 'Save'
);
$this->load->view('admin/cfields/form', array('submit' => $submit));
?>

</div>