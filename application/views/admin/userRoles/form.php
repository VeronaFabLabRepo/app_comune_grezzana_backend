<?php
echo print_form('open', 'POST', '', 'edit_form');


$role = array(
	    // Input part
	  'type'        => 'text'
	, 'name'        => 'role'
	, 'id'          => 'role'
	, 'value'       => $oldValues['role']
	, 'placeholder' => 'Role Name'
		// Label
	, 'label'       => 'Role Name'
	, 'labelFor'    => 'role'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['role']) ? $errors['role'] : '')
);
echo print_field($role);


$backendAccess = array(
	    // Input part
	  'type'        => 'radio'
	, 'name'        => 'backend_access'
	, 'id'          => 'backend_access'
	, 'value'       => $oldValues['backend_access']
	, 'options'     => array('true' => 'Yes', 'false' => 'No')
		// Label
	, 'label'       => 'Backend Access'
	, 'labelFor'    => 'backend_access'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['backend_access']) ? $errors['backend_access'] : '')
);
echo print_field($backendAccess);

$manageContentTypes = array(
	    // Input part
	  'type'        => 'radio'
	, 'name'        => 'manage_content_types'
	, 'id'          => 'manage_content_types'
	, 'value'       => $oldValues['manage_content_types']
	, 'options'     => array('true' => 'Yes', 'false' => 'No')
		// Label
	, 'label'       => 'Manage Content Types'
	, 'labelFor'    => 'manage_content_types'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['manage_content_types']) ? $errors['manage_content_types'] : '')
);
echo print_field($manageContentTypes);

$manageContentCategories = array(
	    // Input part
	  'type'        => 'radio'
	, 'name'        => 'manage_content_categories'
	, 'id'          => 'manage_content_categories'
	, 'value'       => $oldValues['manage_content_categories']
	, 'options'     => array('true' => 'Yes', 'false' => 'No')
		// Label
	, 'label'       => 'Manage Content Categories'
	, 'labelFor'    => 'manage_content_categories'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['manage_content_categories']) ? $errors['manage_content_categories'] : '')
);
echo print_field($manageContentCategories);

$manageUsers = array(
	    // Input part
	  'type'        => 'radio'
	, 'name'        => 'manage_users'
	, 'id'          => 'manage_users'
	, 'value'       => $oldValues['manage_users']
	, 'options'     => array('true' => 'Yes', 'false' => 'No')
		// Label
	, 'label'       => 'Manage Users'
	, 'labelFor'    => 'manage_users'
	, 'labelClass'  => ''
	, 'mandatory'   => TRUE
		// Error
	, 'showError'   => TRUE
	, 'error'       => (isset($errors['manage_users']) ? $errors['manage_users'] : '')
);
echo print_field($manageUsers);

if (count($oldValues['capabilities']) > 0) {

	foreach ($oldValues['capabilities'] as $idContentType => $capabilities) {

		echo '<div class="content_capability clearfix">';
		echo '<h3>' . $contentTypesList[$idContentType] . '</h3>';

		$configure = array(
			    // Input part
			  'type'        => 'checkbox'
			, 'name'        => 'capabilities[' . $idContentType . '][configure]'
			, 'id'          => $idContentType . '_configure'
			, 'options'     => array(TRUE => 'configuration')
			, 'checked'     => ($oldValues['capabilities'][$idContentType]['configure'] ? TRUE : FALSE)
				// Label
			, 'label'       => 'Configuration'
			, 'labelFor'    => $idContentType . '_configure'
			, 'labelClass'  => ''
			, 'mandatory'   => FALSE
		);

		echo print_field($configure);

		$edit = array(
			    // Input part
			  'type'        => 'checkbox'
			, 'name'        => 'capabilities[' . $idContentType . '][edit]'
			, 'id'          => $idContentType . '_edit'
			, 'options'     => array(TRUE => 'edit')
			, 'checked'     => ($oldValues['capabilities'][$idContentType]['edit'] ? TRUE : FALSE)
				// Label
			, 'label'       => 'Edit'
			, 'labelFor'    => $idContentType . '_edit'
			, 'labelClass'  => ''
			, 'mandatory'   => FALSE
		);
		echo print_field($edit);

		$publish = array(
			    // Input part
			  'type'        => 'checkbox'
			, 'name'        => 'capabilities[' . $idContentType . '][publish]'
			, 'id'          => $idContentType . '_publish'
			, 'options'     => array(TRUE => 'publish')
			, 'checked'     => ($oldValues['capabilities'][$idContentType]['publish'] ? TRUE : FALSE)
				// Label
			, 'label'       => 'Publish'
			, 'labelFor'    => $idContentType . '_publish'
			, 'labelClass'  => ''
			, 'mandatory'   => FALSE
		);
		echo print_field($publish);

		echo '</div>';
	}

}

echo print_submit_buttons(array($submit));

echo print_form('close');

?>