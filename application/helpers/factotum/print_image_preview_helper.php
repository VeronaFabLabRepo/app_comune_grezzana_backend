<?php
function print_image_preview($attach, $relInputHidden)
{
	$html = '';

	if (count($attach) > 0 && $attach['id_content_value']) {

		$html = '<div class="preview_image" id="' . $attach['id_content_value'] . '">'
			  . '<a href="' . $attach['zoom_image'] . '" class="lightbox">'
			  . '<img src="' . $attach['thumb_image'] . '" alt="Preview image">'
			  . '</a>'
			  . '<a href="/admin/cms/deleteContentValueAttachment/' . $attach['id_content_value'] . '" class="delete_image" data-rel_input_hidden="' . $relInputHidden . '">'
			  . '<img src="/assets/admin/img/cancel.png" alt="Remove image">'
			  . '</a>'
			  . '</div>'
			  ;

	} else {

		//TODO: add an empty image

	}

	return $html;
}
	
		


