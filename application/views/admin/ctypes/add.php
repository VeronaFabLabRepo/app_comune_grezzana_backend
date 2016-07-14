<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Add a new content type</h1>
		<?php echo print_back_button('/admin/ctypes/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'add'
	, 'value' => 'Add'
);
$this->load->view('admin/ctypes/form', array('submit' => $submit));
?>

</div>