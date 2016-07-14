<?php echo print_form('open', 'POST', '', 'edit_form'); ?>

<div id="tabs" class="tabs">
	<nav>
		<ul>
			<li><a href="#section-1" class="icon-pencil"><span>Edit Content</span></a></li>
            <li><a href="#section-2" class="icon-tags"><span>Categories</span></a></li>
            <li><a href="#section-3" class="icon-settings"><span>Other Options</span></a></li>
		</ul>
	</nav>

	<div class="form_content">

		<section id="section-1">
<?php
$title = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'title'
	, 'id'          => 'title'
	, 'value'       => $oldValues['title']
	, 'placeholder' => 'Title'
		// Label
	, 'label'       => 'Title'
	, 'labelFor'    => 'title'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['title']) ? $errors['title'] : '')
);

echo print_field($title);

$relativePath = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'relative_path'
	, 'id'          => 'relative_path'
	, 'value'       => $oldValues['relative_path']
	, 'placeholder' => 'Path'
		// Label
	, 'label'       => 'Path'
	, 'labelFor'    => 'relative_path'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['relative_path']) ? $errors['relative_path'] : '')
);

echo print_field($relativePath);

$lang = array(
    'type' => 'select',
    'name' => 'lang',
    'id'   => 'lang',
    'options' => $this->config->item('langs', 'factotum'),
    'value' => isset($oldValues['lang']) ? $oldValues['lang'] : $this->config->item('default_lang', 'factotum'),
    'label' => 'Language',
    'labelFor' => 'lang',
    'mandatory' => true,
    'showError' => true,
    'error' => isset($errors['lang']) ? $errors['lang'] : ''
);

echo print_field($lang);

if (count($fields) > 0) {

	foreach ($fields as $field) {

		echo print_field($field);

	}
}
?>
	</section>
	<section id="section-2">
<?php
if (count($categories) > 0) {
	$categoriesField = array(
		    // Input part
		  'type'        => 'multicheckbox'
		, 'name'        => 'categories'
		, 'id'          => 'categories'
		, 'value'       => $oldValues['categories']
		, 'options'     => $categories
			// Label
		, 'label'       => 'Content Categories'
		, 'labelFor'    => 'categories'
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['categories']) ? $errors['categories'] : '')
	);
	echo print_field($categoriesField);
} else {
?>
	<h4>No categories</h4>
<?php
}
?>
	</section>

	<section id="section-3">
		pages and other stuff
	</section>
</div>
<?php
echo print_submit_buttons($submits);

echo print_form('close');
?>