<?php
echo print_form('open', 'POST', '', 'edit_form');

$categoryContentType = array(
	    // Input part
	  'type'        => 'hidden'
	, 'name'        => 'id_content_type'
	, 'id'          => 'id_content_type'
	, 'value'       => $oldValues['id_content_type']
		// Label
	, 'label'       => ''
	, 'labelFor'    => ''
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['id_content_type']) ? $errors['id_content_type'] : '')
);
echo print_field($categoryContentType);

$categoryName = array(
	    // Input file
	  'type'        => 'text'
	, 'name'        => 'category_name'
	, 'id'          => 'category_name'
	, 'value'       => $oldValues['category_name']
	, 'placeholder' => 'Category Name'
		// Label
	, 'label'       => 'Category Name'
	, 'labelFor'    => 'category_name'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['category_name']) ? $errors['category_name'] : '')
);
echo print_field($categoryName);

$categoryLabel = array(
	    // Input file
	  'type'        => 'text'
	, 'name'        => 'category_label'
	, 'id'          => 'category_label'
	, 'value'       => $oldValues['category_label']
	, 'placeholder' => 'Category Label'
		// Label
	, 'label'       => 'Category Label'
	, 'labelFor'    => 'category_label'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['category_label']) ? $errors['category_label'] : '')
);
echo print_field($categoryLabel);

echo print_submit_buttons(array($submit));

echo print_form('close');
?>