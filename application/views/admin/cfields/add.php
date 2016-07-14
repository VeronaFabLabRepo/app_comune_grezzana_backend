<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Add a new field</h1>
		<?php echo print_back_button('/admin/cfields/#cfields_' . $idContentType); ?>

	</div>

<?php
$submit = array(
	  'name'  => 'add'
	, 'value' => 'Add'
);
$this->load->view('admin/cfields/form', array('submit' => $submit));
?>

</div>