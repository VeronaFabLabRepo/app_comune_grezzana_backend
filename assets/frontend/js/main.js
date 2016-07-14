$(document).ready(function() {

	$('input, textarea').placeholder();
	$('.datepicker').datepicker({dateFormat: 'dd/mm/yy'});

//	if ($('#permalink').length > 0) {
//
//		// Permalink management
//		$('input#title').blur(function() {
//
//			var val = $(this).val();
//			var permalink = makePermalink(val);
//			$('#permalink').val(permalink);
//
//		});
//
//		$('input#permalink').blur(function() {
//
//			var permalink = $(this).val();
//
//		});
//
//	}

	if ($('textarea.xhtml').length > 0) {

		tinyMCE.init({
			// General options
			editor_selector : "xhtml",
			mode : "textareas",
			theme : "advanced",
			width: 680,
			height: 300,
			relative_urls : false,
			convert_urls : true,

			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
	
			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,formatselect,|,code",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote",
			theme_advanced_buttons3 : "link,unlink,forecolor,backcolor,|,tablecontrols,|,hr,visualaid,|,sub,sup,|,media,jbimages",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			theme_advanced_disable: "image,advimage",
	
			// Style formats
			style_formats : [
				{title : 'Bold text', inline : 'b'},
				{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
				{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
				{title : 'Example 1', inline : 'span', classes : 'example1'},
				{title : 'Example 2', inline : 'span', classes : 'example2'},
				{title : 'Table styles'},
				{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
			]
		});

	}
});


	