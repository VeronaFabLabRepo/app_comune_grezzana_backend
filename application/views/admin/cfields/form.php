<?php
echo print_form('open', 'POST', '', 'edit_form');


$fieldName = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'name'
	, 'id'          => 'name'
	, 'value'       => $oldValues['name']
	, 'placeholder' => 'Field Name'
		// Label
	, 'label'       => 'Field Name'
	, 'labelFor'    => 'name'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['name']) ? $errors['name'] : '')
);
echo print_field($fieldName);


$fieldLabel = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'label'
	, 'id'          => 'label'
	, 'value'       => $oldValues['label']
	, 'placeholder' => 'Field Label'
		// Label
	, 'label'       => 'Field Label'
	, 'labelFor'    => 'label'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['label']) ? $errors['label'] : '')
);
echo print_field($fieldLabel);


$hint = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'hint'
	, 'id'          => 'hint'
	, 'value'       => $oldValues['hint']
	, 'placeholder' => 'Hint'
		// Label
	, 'label'       => 'Hint'
	, 'labelFor'    => 'hint'
	, 'labelClass'  => ''
	, 'mandatory'   => FALSE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['hint']) ? $errors['hint'] : '')
);
echo print_field($hint);


$mandatory = array(
	    // Input part
	  'type'        => 'radio'
	, 'name'        => 'mandatory'
	, 'id'          => 'mandatory'
	, 'value'       => $oldValues['mandatory']
	, 'options'     => array('true' => 'Yes', 'false' => 'No')
		// Label
	, 'label'       => 'Mandatory'
	, 'labelFor'    => 'mandatory'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['mandatory']) ? $errors['mandatory'] : '')
);
echo print_field($mandatory);


$fieldType = array(
	    // Input part
	  'type'        => 'select'
	, 'name'        => 'type'
	, 'id'          => 'type'
	, 'value'       => $oldValues['type']
	, 'options'     => $fieldTypes
		// Label
	, 'label'       => 'Field Type'
	, 'labelFor'    => 'type'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['type']) ? $errors['type'] : '')
);
echo print_field($fieldType);
?>

<div class="options<?php echo ( in_array($oldValues['type'], array('radio', 'checkbox', 'multicheckbox', 'select', 'multiselect')) ? ' show' : ''); ?>">

	<div class="options_title clearfix">

		<h4>Options</h4>

		<a href="/admin/cfields/addOption" id="add_option"<?php echo ( in_array($oldValues['type'], array('multicheckbox', 'select', 'multiselect')) ? ' class="show"' : ''); ?>><img src="<?php echo $baseUrl; ?>assets/admin/img/add.png" alt="Add option"></a>

	</div>
	
	<ul class="sortable">

<?php
if (isset($oldValues['options']['values']) && count($oldValues['options']['values']) > 0 && 
	isset($oldValues['options']['labels']) && count($oldValues['options']['labels']) > 0) {

	foreach ($oldValues['options']['values'] as $index => $value) {

		echo '<li class="clearfix">';

		$optionValue = array(
			    // Input part
			  'type'        => 'text'
			, 'name'        => 'options[values][]'
			, 'value'       => $value
				// Label
			, 'label'       => 'Option Value'
			, 'labelClass'  => ''
			, 'mandatory'   => TRUE
				// Error
			, 'showError'   => TRUE
			, 'error'       => (isset($errors['options']['values'][$index]) ? $errors['options']['values'][$index] : '')
		);

		echo '<div class="col">';
		echo print_field($optionValue);
		echo '</div>';

		$label = $oldValues['options']['labels'][$index];
		$optionLabel = array(
			    // Input part
			  'type'        => 'text'
			, 'name'        => 'options[labels][]'
			, 'value'       => $label
				// Label
			, 'label'       => 'Option Label'
			, 'labelClass'  => ''
			, 'mandatory'   => TRUE
				// Error
			, 'showError'   => TRUE
			, 'error'       => (isset($errors['options']['labels'][$index]) ? $errors['options']['labels'][$index] : '')
		);

		echo '<div class="col">';
		echo print_field($optionLabel);
		echo '</div>';

		echo '</li>';
	}

} else {

	echo '<li class="clearfix">';

	$optionValue = array(
		    // Input part
		  'type'        => 'text'
		, 'name'        => 'options[values][]'
		, 'value'       => ''
			// Label
		, 'label'       => 'Option Value'
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['options']['values'][0]) ? $errors['options']['values'][0] : '')
	);

	echo '<div class="col">';
	echo print_field($optionValue);
	echo '</div>';

	$optionLabel = array(
		    // Input part
		  'type'        => 'text'
		, 'name'        => 'options[labels][]'
		, 'value'       => ''
			// Label
		, 'label'       => 'Option Label'
		, 'labelClass'  => ''
		, 'mandatory'   => TRUE
			// Error
		, 'showError'   => TRUE
		, 'error'       => (isset($errors['options']['labels'][0]) ? $errors['options']['labels'][0] : '')
	);

	echo '<div class="col">';
	echo print_field($optionLabel);
	echo '</div>';

}

?>

</ul>

</div>

<div class="file_upload_features<?php echo ( in_array($oldValues['type'], array('file_upload', 'image_upload', 'gallery')) ? ' show' : ''); ?>">

<?php
$maxFileSize = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'max_file_size'
	, 'id'          => 'max_file_size'
	, 'value'       => $oldValues['max_file_size']
	, 'placeholder' => 'Max File Size'
		// Label
	, 'label'       => 'Max File Size'
	, 'labelFor'    => 'max_file_size'
	, 'labelClass'  => ''
	, 'hint'        => 'File size in KB'
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['max_file_size']) ? $errors['max_file_size'] : '')
);
echo print_field($maxFileSize);


$allowedTypes = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'allowed_types'
	, 'id'          => 'allowed_types'
	, 'value'       => $oldValues['allowed_types']
	, 'placeholder' => 'Allowed Types'
		// Label
	, 'label'       => 'Allowed Types'
	, 'labelFor'    => 'allowed_types'
	, 'labelClass'  => ''
	, 'hint'        => 'Comma separated list (i.e. jpg, png, gif OR *)'
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['allowed_types']) ? $errors['allowed_types'] : '')
);
echo print_field($allowedTypes);

?>
</div>

<div class="image_upload_features<?php echo ( in_array($oldValues['type'], array('image_upload', 'gallery')) ? ' show' : ''); ?>">
<?php

$maxImageSize = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'max_image_size'
	, 'id'          => 'max_image_size'
	, 'value'       => $oldValues['max_image_size']
	, 'placeholder' => 'Max Image Size'
		// Label
	, 'label'       => 'Max Image Size'
	, 'labelFor'    => 'max_image_size'
	, 'labelClass'  => ''
	, 'hint'        => 'WxH (i.e. 1024x768)'
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['max_image_size']) ? $errors['max_image_size'] : '')
);
echo print_field($maxImageSize);

$thumbSize = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'thumb_size'
	, 'id'          => 'thumb_size'
	, 'value'       => $oldValues['thumb_size']
	, 'placeholder' => 'Thumb Size'
		// Label
	, 'label'       => 'Thumb Size'
	, 'labelFor'    => 'thumb_size'
	, 'labelClass'  => ''
	, 'hint'        => 'WxH (i.e. 320x200)'
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['thumb_size']) ? $errors['thumb_size'] : '')
);
echo print_field($thumbSize);

$imageOperation = array(
	    // Input part
	  'type'        => 'select'
	, 'name'        => 'image_operation'
	, 'id'          => 'image_operation'
	, 'value'       => $oldValues['image_operation']
	, 'options'     => $imageOperations
		// Label
	, 'label'       => 'Image Operation'
	, 'labelFor'    => 'image_operation'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['image_operation']) ? $errors['image_operation'] : '')
);
echo print_field($imageOperation);

$imageBW = array(
	    // Input part
	  'type'        => 'checkbox'
	, 'name'        => 'image_bw'
	, 'id'          => 'image_bw'
	, 'options'     => array(TRUE => 'Image B/W')
	, 'checked'     => ($oldValues['image_bw'] ? TRUE : FALSE)
		// Label
	, 'label'       => 'Image B/W'
	, 'labelFor'    => 'image_bw'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['image_bw']) ? $errors['image_bw'] : '')
);
echo print_field($imageBW);
?>
</div>

<div class="linked_content_features<?php echo (in_array($oldValues['type'], array('linked_content', 'multiple_linked_content'))  ? ' show' : ''); ?>">
<?php
$contentTypeOptions = array();
foreach ($contentTypes as $cType) {

	if ($cType['id'] != $idContentType) {

		$contentTypeOptions[$cType['id']] = $cType['content_type'];

	}

}

$linkedContent = array(
	    // Input part
	  'type'        => 'select'
	, 'name'        => 'linked_id_content_type'
	, 'id'          => 'linked_id_content_type'
	, 'value'       => $oldValues['linked_id_content_type']
	, 'options'     => $contentTypeOptions
		// Label
	, 'label'       => 'Linked Content'
	, 'labelFor'    => 'linked_id_content_type'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['linked_id_content_type']) ? $errors['linked_id_content_type'] : '')
);
echo print_field($linkedContent);
?>
</div>

<?php

echo print_submit_buttons(array($submit));

echo print_form('close');

?>