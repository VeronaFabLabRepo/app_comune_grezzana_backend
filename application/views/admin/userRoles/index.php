<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>User Roles List</h1>
		<?php echo print_add_button('/admin/userRoles/add', 'Add new user role'); ?>

	</div>


<?php if ($list && count($list) > 0) { ?>

<ul class="entrylist ctype">
	<li class="header clearfix">
		<div class="element id">Id</div>
		<div class="element title">Role Name</div>
		<div class="element actions">Actions</div>		
	</li>

	<?php foreach ($list as $entry) { ?>

	<li class="clearfix" id="user<?php echo $entry['id']; ?>">
		<div class="element id">#<?php echo $entry['id']; ?></div>
		<div class="element title"><a href="/admin/userRoles/edit/<?php echo $entry['id']; ?>"><?php echo $entry['role']; ?></a></div>
		<?php echo print_delete_button('/admin/userRoles/delete/' . $entry['id']); ?>
		<?php echo print_edit_button('/admin/userRoles/edit/' . $entry['id']); ?>
	</li>

	<?php } ?>

</ul>

<?php } else { ?>

	<p class="empty_list">There isn't content types.</p>

<?php } ?>

</div>