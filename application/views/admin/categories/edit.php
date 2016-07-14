<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Edit Content Category</h1>
		<?php echo print_back_button('/admin/categories/'); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'save'
	, 'value' => 'Save'
);
$this->load->view('admin/categories/form', array('submit' => $submit));
?>

</div>