<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Categories List</h1>

	</div>

<?php if ($contentTypes && count($contentTypes) > 0) { ?>

<ul class="entrylist categories sortable">

	<li class="header clearfix">
		<div class="element id">Id</div>
		<div class="element title">Content Type</div>
		<div class="element actions">Actions</div>
	</li>

<?php
    foreach ($contentTypes as $cType) {
        $contentTypeId = $cType['id'];
?>

	<li class="clearfix" id="<?php echo $contentTypeId; ?>">

		<div class="clearfix">
			<div class="element id"><strong>#<?php echo $contentTypeId; ?></strong></div>
			<div class="element title"><strong><?php echo $cType['content_type']; ?></strong></div>
			<?php echo print_add_button('/admin/categories/add/' . $contentTypeId, 'Add category'); ?>

			<?php if (isset($list[$contentTypeId]) && count($list[$contentTypeId]['categories']) > 0) { ?>

				<?php echo print_openclose_button('#', 'watch categories', 'categories_' . $contentTypeId); ?>

			<?php } ?>

		</div>

<?php
		if (isset($list[$contentTypeId]) && count($list[$contentTypeId]['categories']) > 0) {

			if (isset($capabilities[$contentTypeId]) && $capabilities[$contentTypeId]['configure']) {

				if (count($list[$contentTypeId]['categories']) > 0) {
?>

					<ul class="entrylist categories_block sortable" id="categories_<?php echo $contentTypeId; ?>">

						<li class="subheader clearfix">
							<div class="element id">Id</div>
							<div class="element title">Content Categories</div>
							<div class="element actions">Actions</div>
						</li>

						<?php foreach ($list[$contentTypeId]['categories'] as $contentCategory) { ?>

						<li class="clearfix" id="category<?php echo $contentCategory['id']; ?>">

							<div class="element id">#<?php echo $contentCategory['id']; ?></div>
							<div class="element title"><a href="/admin/categories/edit/<?php echo $contentCategory['id']; ?>"><?php echo $contentCategory['category_label']; ?></a></div>
							<?php echo print_delete_button('/admin/categories/delete/' . $contentCategory['id']); ?>
							<?php echo print_edit_button('/admin/categories/edit/' . $contentCategory['id'], 'edit'); ?>

						</li>

						<?php } ?>

					</ul>

<?php
				}
			}
		}
?>
	</li>
<?php
}
?>

</ul>

<?php } else { ?>

	<p class="empty_list">There isn't content categories.</p>

<?php } ?>

</div>