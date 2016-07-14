<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Add <?php echo ($currentPage == 'pages' ? 'a new page' : 'the content'); ?></h1>
		<?php echo print_back_button('/admin/contents/index/' . $currentPage); ?>

	</div>

<?php
$submitSave = array(
	  'name'  => 'save_list'
	, 'value' => 'Save'
);
$this->load->view('admin/contents/form', array('submits' => array($submitSave)));
?>

</div>