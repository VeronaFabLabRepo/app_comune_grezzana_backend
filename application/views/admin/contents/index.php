<div class="content clearfix">

	<div class="page_title clearfix">

		<h1><?php echo $contentType; ?> List</h1>
		<?php echo print_add_button('/admin/contents/add/' . $contentType, 'Add ' . ($currentPage == 'pages' ? 'Page' : 'Content')); ?>

	</div>

<?php if ($list && count($list) > 0) { ?>

<ul class="entrylist sortable">

	<li class="header clearfix">

		<div class="element id">Id</div>
		<div class="element title">Title</div>

<?php if ($capabilities[$idContentType]['publish']) { ?>

		<div class="element status">Status</div>

<?php } ?>

<?php if ($capabilities[$idContentType]['edit']) { ?>

		<div class="element actions">Actions</div>		

<?php } ?>

	</li>

<?php foreach ($list as $entry) { ?>

	<li class="clearfix" id="content<?php echo $entry['id']; ?>" data-id_item="<?php echo $entry['id']; ?>">
		<div class="element id">#<?php echo $entry['id']; ?></div>
		<div class="element title"><a href="/admin/contents/edit/<?php echo $entry['id']; ?>"><?php echo $entry['title']; ?></a></div>

<?php
if ($capabilities[$idContentType]['publish']) {

	echo print_status_button('/admin/contents/changeContentStatus/' . $entry['id'], $entry['status'], 'Put ' . ($entry['status'] == $contentStatusLive ? $contentStatusOffline : $contentStatusLive));

}

if ($capabilities[$idContentType]['edit']) {

	echo print_delete_button('/admin/contents/delete/' . $entry['id']);

	echo print_edit_button('/admin/contents/edit/' . $entry['id']);

}
?>

	</li>
<?php } ?>

</ul>

<?php } else { ?>

	<p class="empty_list">There are not contents of this type.</p>

<?php } ?>

</div>

<script type="text/javascript">
	var orderURL = '/admin/contents/order';
</script>