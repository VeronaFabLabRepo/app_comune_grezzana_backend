<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Edit Content Type</h1>
		<?php echo print_back_button('/admin/ctypes/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'save'
	, 'value' => 'Save'
);
$this->load->view('admin/ctypes/form', array('submit' => $submit));
?>

</div>