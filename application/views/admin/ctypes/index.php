<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Content Types List</h1>
		<?php echo print_add_button('/admin/ctypes/add', 'Add content type'); ?>

	</div>


<?php if ($list && count($list) > 0) { ?>

<ul class="entrylist ctype sortable">
	<li class="header clearfix">
		<div class="element id">Id</div>
		<div class="element title">Content Type</div>
		<div class="element actions">Actions</div>		
	</li>

	<?php foreach ($list as $entry) { ?>

	<li class="clearfix" id="ctype<?php echo $entry['id']; ?>">
		<div class="element id">#<?php echo $entry['id']; ?></div>
		<div class="element title"><a href="/admin/ctypes/edit/<?php echo $entry['id']; ?>"><?php echo $entry['content_type']; ?></a></div>
		<?php echo print_delete_button('/admin/ctypes/delete/' . $entry['id']); ?>
		<?php echo print_edit_button('/admin/ctypes/edit/' . $entry['id']); ?>
	</li>

	<?php } ?>

</ul>

<?php } else { ?>

	<p class="empty_list">There are not content types.</p>

<?php } ?>

</div>