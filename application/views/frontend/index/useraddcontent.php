<h1><?php echo $page['title']; ?></h1>
<?php echo $page['text']; ?>

<?php
echo print_form('open', 'POST', '', 'news_form');

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
	  'type'        => 'hidden'
	, 'name'        => 'relative_path'
	, 'id'          => 'relative_path'
	, 'value'       => $oldValues['relative_path']
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => FALSE
	, 'error'       => (isset($errors['relative_path']) ? $errors['relative_path'] : '')
);

echo print_field($relativePath);


if (count($fields) > 0) {

	foreach ($fields as $field) {

		echo print_field($field);

	}
}

$submitSave = array(
	  'name'  => 'save_list'
	, 'value' => 'Save'
);

echo print_submit_buttons(array($submitSave));

echo print_form('close');

?>