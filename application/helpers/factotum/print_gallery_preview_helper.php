<?php
function print_gallery_preview($attachs, $relInputHidden)
{
	$html = '';

	if (count($attachs) > 0) {

		$html .= '<ul class="gallery sortable">';

		foreach ($attachs as $att) {

			$html .= '<li class="preview_image" id="' . $att['id_content_attachment'] . '">'
				   . '<a href="' . $att['zoom_image'] . '" class="lightbox">'
				   . '<img src="' . $att['thumb_image'] . '" alt="Preview image">'
				   . '</a>'
				   . '<a href="/admin/cms/deleteAttachment/' . $att['id_content_attachment'] . '" class="delete_image" data-rel_input_hidden="' . $relInputHidden . '">'
				   . '<img src="/assets/admin/img/cancel.png" alt="Remove attachment">'
				   . '</a>'
				   . '</li>'
				   ;

		}

		$html .= '</ul>';

	} else {

		//TODO: add an empty image

	}

	return $html;
}
	
		


