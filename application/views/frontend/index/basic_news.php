<?php

if (isset($content)) {
	if (isset($page) && $page['operation'] === 'content_list') {
?>
	<a href="/<?php echo $page['absolute_path']; ?>">go back</a>
<?php
	}
?>

	<h1><?php echo $content['title']; ?></h1>
	<?php echo $content['news_article']; ?>

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
