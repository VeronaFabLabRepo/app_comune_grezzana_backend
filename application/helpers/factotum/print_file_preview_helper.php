<?php
function print_file_preview($attach, $relInputHidden)
{
	$html = '';

	if (count($attach) > 0 && $attach['id_content_value']) {

		$html = '<div class="preview_file" id="' . $attach['id_content_value'] . '">'
			  . '<a href="' . $attach['file'] . '" target="_blank">'
			  . '<img src="/assets/admin/img/file_upload.png" alt="Preview file">'
			  . '</a>'
			  . '<a href="/admin/cms/deleteContentValueAttachment/' . $attach['id_content_value'] . '" class="delete_file" data-rel_input_hidden="' . $relInputHidden . '">'
			  . '<img src="/assets/admin/img/cancel.png" alt="Remove image">'
			  . '</a>'
			  . '</div>'
			  ;

	} else {

		//TODO: add an empty image

	}

	return $html;
}
	
		


