<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Add a new content category</h1>
		<?php echo print_back_button('/admin/categories/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'add'
	, 'value' => 'Add'
);
$this->load->view('admin/categories/form', array('submit' => $submit));
?>

</div>