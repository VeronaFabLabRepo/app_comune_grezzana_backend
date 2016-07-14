<?php
$this->load->view('frontend/index/basic_content', array($page));

if (count($contentList) > 0) {
?>
	<ul class="content_list team clearfix">
<?php
	foreach ($contentList as $content) {
?>
		<li>

			<h4><?php echo $content['title']; ?></h4>
			<div class="member clearfix">
				<img src="<?php echo $uploadUrl . $content['id'] . '/thumbs/' . $content['profile']; ?>" alt="<?php echo $content['title']; ?>">
				<?php echo $content['description']; ?>
			</div>
			<p>
				<br>Gender: <strong><?php echo $content['gender']; ?></strong>
				<br>D.O.B: <strong><?php echo $content['dob']; ?></strong>
				<br>CV: <a href="<?php echo $uploadUrl . $content['id'] . '/' . $content['cv']; ?>">Download</a>
				<br>Hobbies: <strong><?php echo $content['hobbies']; ?></strong>
			</p>

		</li>
<?php
	}
?>
	</ul>
<?php
}
?>

</div>
