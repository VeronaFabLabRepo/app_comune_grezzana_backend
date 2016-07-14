<li class="clearfix">

	<div class="col">
<?php
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
	, 'showError'   => FALSE
	, 'error'       => ''
);
echo print_field($optionValue);
?>
		</div>

		<div class="col">
<?php
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
	, 'showError'   => FALSE
	, 'error'       => ''
);
echo print_field($optionLabel);
?>
	</div>

</li>
