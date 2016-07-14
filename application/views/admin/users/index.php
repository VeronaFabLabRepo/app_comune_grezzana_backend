<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Users List</h1>
		<?php echo print_add_button('/admin/users/add', 'Add new user'); ?>

	</div>


<?php if ($list && count($list) > 0) { ?>

<ul class="entrylist ctype">
	<li class="header clearfix">
		<div class="element id">Id</div>
		<div class="element title">Username/Email</div>
		<div class="element actions">Actions</div>		
	</li>

	<?php foreach ($list as $entry) { ?>

	<li class="clearfix" id="user<?php echo $entry['id']; ?>">
		<div class="element id">#<?php echo $entry['id']; ?></div>
		<div class="element title"><a href="/admin/users/edit/<?php echo $entry['id']; ?>"><?php echo $entry['username']; ?> - <?php echo $entry['email']; ?></a></div>
		<?php echo print_delete_button('/admin/users/delete/' . $entry['id']); ?>
		<?php echo print_edit_button('/admin/users/edit/' . $entry['id']); ?>
	</li>

	<?php } ?>

</ul>

<?php } else { ?>

	<p class="empty_list">There isn't content types.</p>

<?php } ?>

</div>