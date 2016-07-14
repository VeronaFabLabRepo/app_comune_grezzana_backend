<div class="content clearfix">

	<div class="page_title clearfix">

		<div class="clearfix">

			<h1>Content Fields List</h1>

		</div>

<?php if ($capabilities[$idContentTypePages]['configure']) { ?>

		<h3 class="error">Pay attention when you modify the Pages fields.</h3>

<?php } ?>

		<h3 class="error">Pay attention when you modify the fields with options.</h3>

	</div>


<?php if ($list && count($list) > 0) { ?>

<ul class="entrylist cfields">

	<li class="header clearfix">
		<div class="element id">Id</div>
		<div class="element title">Content Type</div>
		<div class="element actions">Actions</div>		
	</li>

	<?php foreach ($list as $entry) { ?>

		<?php if (isset($capabilities[$entry['id']]) && $capabilities[$entry['id']]['configure']) { ?>

			<li class="clearfix" id="<?php echo $entry['id']; ?>">
		
				<div class="clearfix">
					<div class="element id"><strong>#<?php echo $entry['id']; ?></strong></div>
					<div class="element title"><strong><?php echo $entry['content_type']; ?></strong></div>
					<?php echo print_add_button('/admin/cfields/add/' . $entry['id'], 'Add field'); ?>

					<?php if (count($entry['cfields']) > 0) { ?>

						<?php echo print_openclose_button('#', 'watch fields', 'cfields_' . $entry['id']); ?>

					<?php } ?>

				</div>

				<?php if (count($entry['cfields']) > 0) { ?>

					<ul class="entrylist cfields_block sortable" id="cfields_<?php echo $entry['id']; ?>">
			
						<li class="subheader clearfix">
							<div class="element id">Id</div>
							<div class="element title">Content Fields</div>
							<div class="element actions">Actions</div>		
						</li>
	
						<?php foreach ($entry['cfields'] as $contentField) { ?>
			
						<li class="clearfix" id="cfield<?php echo $contentField['id']; ?>" data-id_item="<?php echo $contentField['id']; ?>">
			
							<div class="element id">#<?php echo $contentField['id']; ?></div>
							<div class="element icon"><img src="<?php echo $baseUrl; ?>assets/admin/img/<?php echo $contentField['type']; ?>.png" alt="<?php echo $contentField['type']; ?>"></div>
							<div class="element title"><a href="/admin/cfields/edit/<?php echo $contentField['id']; ?>"><?php echo $contentField['label']; ?></a></div>
							<?php echo print_delete_button('/admin/cfields/delete/' . $contentField['id']); ?>
							<?php echo print_edit_button('/admin/cfields/edit/' . $contentField['id'], 'edit'); ?>
			
						</li>
			
						<?php } ?>
			
					</ul>

				<?php } ?>
		
			</li>

		<?php } ?>

	<?php } ?>

</ul>

<?php } else { ?>

	<p class="empty_list">There isn't content types.</p>

<?php } ?>

</div>
<script type="text/javascript">
	var orderURL = '/admin/cfields/order';
</script>