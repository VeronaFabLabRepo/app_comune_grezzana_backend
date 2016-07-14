<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Edit <?php echo ($currentPage == 'pages' ? 'the page' : 'the content'); ?></h1>
		<?php echo print_back_button('/admin/contents/index/' . $currentPage); ?>

	</div>

<?php
$submitSave = array(
	  'name'  => 'save'
	, 'value' => 'Save'
);
$submitSaveList = array(
	  'name'  => 'save_list'
	, 'value' => 'Save & List'
);

$this->load->view('admin/contents/form', array('submits' => array($submitSave, $submitSaveList)));
?>

</div>

<script type="text/javascript">
	var orderURL = '/admin/contents/orderAttachment/';
</script>