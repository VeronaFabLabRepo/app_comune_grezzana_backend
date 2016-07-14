<?php if ($logged) { ?>

<nav class="mainnav clearfix">

<?php
if (in_array($idContentTypePages, array_keys($capabilities))) { ?>

	<a href="/admin/contents/index/pages"<?php echo ($currentPage == 'pages' ? ' class="active"' : ''); ?>>Pages</a>

<?php
}

if (count($contentTypes) > 0) {

	foreach ($contentTypes as $cType) {

		echo  '<a href="/admin/contents/index/' . $cType['content_type'] . '" '
			. 'id="content_type_' . $cType['id'] . '"'
			. ($currentPage == $cType['content_type'] ? ' class="active"' : '') . '>'
			. $cType['content_type']
			. '</a>'
			;

	}

}
?>

</nav>
<?php } ?>