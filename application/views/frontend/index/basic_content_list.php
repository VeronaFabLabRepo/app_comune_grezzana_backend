<?php

if (isset($content)) {
?>

	<a href="/<?php echo $page['absolute_path']; ?>">go back</a>
	<h1><?php echo $content['title']; ?></h1>

<?php
	if (isset($content[$contentType . '_text'])) {

		echo $content[$contentType . '_text'];

	}
?>

<?php
} else if ($contentList && count($contentList) > 0) {
?>
	<ul class="content_list">
<?php
	foreach ($contentList as $content) {
?>
		<li>
			<a href="<?php echo $content['relative_path']; ?>" class="clearfix" id="<?php echo $content['id']; ?>">
				<?php echo $content['title']; ?>
			</a>
		</li>
<?php
	}
?>
	</ul>
<?php
	if ($pagination) {
		echo $this->pagination->create_links();
	}

}
