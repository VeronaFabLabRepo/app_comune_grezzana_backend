$(document).ready(function() {

	var hash = getHash();

	if ($('.success_box').length > 0) {
		setTimeout(function() { $('.success_box').fadeOut('slow'); }, 1000);
	}

	if ($('.entrylist').length > 0) {

		// Function for publish/unpublish content
		$('.status').click(function(event) {
			event.preventDefault();

			var id = $(this).parent().attr('id'),
				element = $(this);

			$.ajax({
				url: $(this).attr('href'),
				success: function(data) {
					var data = JSON.parse(data);
					element.find('img').removeClass().addClass(data.result);
				}
			});

		});

		// Common cancellation function for the rows
		$('.delete').click(function(event) {
			event.preventDefault();

			var urlToCall = $(this).attr('href');

			$( "#dialog-confirm" ).dialog({
				resizable: false,
				width: 350,
				height: 250,
				modal: true,
				buttons: {
					"Yes, delete!": function() {

						$(this).dialog("close");

						$.ajax({
							url: urlToCall,
							async: false,
							success: function(data) {
								data = JSON.parse(data);

								$('#' + data.type + data.elementId).remove();
							}
						});

					},
					Cancel: function() {
						$(this).dialog( "close" );
					}
				}
			});

		});


		// Common function for the sublist
		$('.openclose_button').click(function(event) {
			event.preventDefault();

			var open = $(this).data('open');
			if ($(this).hasClass('open')) {

				$('#' + open).slideUp('fast');
				$(this).removeClass('open');

			} else {

				$('#' + open).slideDown('fast');
				$(this).addClass('open');

			}

		});

		if (($('body').hasClass('cfields') && $('body').hasClass('index') && hash != '' && $('#' + hash).length > 0) ||
			($('body').hasClass('categories') && $('body').hasClass('index') && hash != '' && $('#' + hash).length > 0)) {

			$('a.openclose_button[data-open=' + hash + ']').trigger('click');
		}

	}

	if ($('.sortable').length > 0) {

		function getOrderItems($list) {

			var orderItems = {};

			$list.find('li').not('[class*=header]').each(function(index, item) {

				orderItems[$(item).data('id_item')] = index;

			});

			return orderItems;
		}

		$('.sortable').sortable({
			items: "li:not([class*=header])",
			update: function(event, ui) {
				var newOrder = getOrderItems(ui.item.parent());

				$.ajax({
					url: (typeof orderURL !== "undefined" ? orderURL : location.href.replace('#', '') + 'order'),
					method: 'post',
					data: 'new_order=' + JSON.stringify(newOrder)
				});


			}
		});

	}

	if ($('.edit_form').length > 0) {

		$('input, textarea').placeholder();

		$('.delete_image, .delete_file').click(function(event) {
			event.preventDefault();

			var urlToCall = $(this).attr('href'),
				elToEmpty = $('input[type=hidden][name="' + $(this).data('rel_input_hidden') + '"]');

			$("#dialog-confirm").dialog({
				resizable: false,
				width: 350,
				height: 250,
				modal: true,
				buttons: {
					"Yes, delete!": function() {
						$(this).dialog("close");

						$.ajax({
							url: urlToCall,
							async: false,
							success: function(data) {
								data = JSON.parse(data);
								$('#' + data.result).remove();
							}
						});

						elToEmpty.val('');
					},
					Cancel: function() {
						$(this).dialog( "close" );
					}
				}
			});

		});

//		// Permalink management
//		$('input#title').blur(function() {
//
//			var val = $(this).val();
//			var permalink = makePermalink(val);
//			checkPermalink(permalink);
//			$('#permalink').val(permalink);
//
//		});
//
//		$('input#permalink').blur(function() {
//
//			var permalink = $(this).val();
//			checkPermalink(permalink);
//
//		});


		tinyMCE.init({
			// General options
			editor_selector : "xhtml",
			mode : "textareas",
			theme : "advanced",
			width: 680,
			height: 300,
			relative_urls : false,
			convert_urls : true,

			plugins : "jbimages,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

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


		$('select[name="operation"]').change(function(event) {

			var op = $(this).val();

			if (op == 'content' || op == 'content_list') {

				$('.content_types_list').show();

				if (op == 'content_list') {

					$('.content_list').hide();
					$('.content_list_categories').show();
					$('.content_list_pagination').show();
					$('.content_list_limit').show();
					$('.content_list_order').show();
					$('.content_list_num_per_page').show();

				} else {

					$('.content_list').show();
					$('.content_list_categories').hide();
					$('.content_list_pagination').hide();
					$('.content_list_limit').hide();
					$('.content_list_order').hide();
					$('.content_list_num_per_page').hide();

				}

				if (op == 'content_list') {

					$('select[name="content_list"]')
						.attr('multiple', 'multiple')
						.addClass('multiselect')
						.find('option[value=""]').remove();

				} else {

					$('select[name="content_list"]')
						.removeAttr('multiple')
						.removeClass('multiselect');

				}

			} else {

				$('.content_types_list').hide();
				$('.content_list_categories').hide();
				$('.content_list').hide();
				$('.content_list_pagination').hide();
				$('.content_list_limit').hide();
				$('.content_list_order').hide();
				$('.content_list_num_per_page').hide();

			}

			if (op == 'link') {

				$('.link').show();
				$('.link_title').show();
				$('.link_open_in').show();

			} else {

				$('.link').hide();
				$('.link_title').hide();
				$('.link_open_in').hide();

			}

			if (op == 'action') {

				$('.action').show();

			} else {

				$('.action').hide();

			}

		});

		$('select[name="content_types_list"]').change(function(event) {

			var val = $(this).val(),
				$contentList = $('select[name="content_list"]'),
				selectedContents,
				$contentCategories = $('select[name="content_list_categories[]"]'),
				selectedCategories;

			$contentList.data('selected', (selectedContents = [].concat($contentList.val() || $contentList.data('selected'))));
			$contentList.empty();

			if (val) {
				// Populate the content list with the options
				$.ajax({
					method: 'get',
					async: false,
					url: '/admin/contents/getOptionsContentList/' + val,
					success: function(data) {
						var options = JSON.parse(data),
							opts = '',
							selected = '';

						for (var i = 0; i < options.length; i++) {
							selected = (!!~$.inArray('' + options[i].value, selectedContents) ? ' selected="selected"' : '');
							opts = opts + '<option value="' + options[i].value + '"' + selected + '>' + options[i].label + '</option>';
						}

						$contentList.append(opts);
					}
				});
			}


			$contentCategories.data('selected', (selectedCategories = [].concat($contentCategories.val() || $contentCategories.data('selected'))));
			$contentCategories.empty();

			if (val) {
				$.ajax({
					method: 'get',
					async: true,
					url: '/admin/contents/getCategories/' + val,
					success: function(data) {
						var options = JSON.parse(data),
							opts = '',
							selected = '';

						for (var i = 0; i < options.length; i++) {
							selected = (!!~$.inArray('' + options[i].value, selectedCategories) ? ' selected="selected"' : '');
							opts += '<option value="' + options[i].value + '"' + selected + '>' + options[i].label + '</option>';
						}

						$contentCategories.append(opts);
					}
				});
			}
		}).change();

		if ($('select[name="operation"]').length > 0) {

			$('select[name="operation"]').trigger('change');

		}


		// Dates widgets
		$('.datepicker').datepicker({dateFormat: 'dd/mm/yy'});
		$('.datetimepicker').datetimepicker({dateFormat: 'dd/mm/yy'});



		// Creating field Add Option
		$('#add_option').click(function(event) {
			event.preventDefault();

			$.ajax({
				method: 'get',
				url: $(this).attr('href'),
				success: function(data) {
					$('.options .sortable').append(data);
				}
			});

		});


		// Creating field Show More Options
		$('#type').change(function() {

			var val = $(this).val();

			if (val == 'linked_content' || val == 'multiple_linked_content') {

				$('.linked_content_features').addClass('show');
				$('.options').removeClass('show');
				$('#add_option').removeClass('show');
				$('.file_upload_features').removeClass('show');
				$('.image_upload_features').removeClass('show');

			} else if (val == 'file_upload' || val == 'image_upload' || val == 'gallery') {

				$('.file_upload_features').addClass('show');

				if (val == 'image_upload' || val == 'gallery') {

					$('.image_upload_features').addClass('show');

				} else {

					$('.image_upload_features').removeClass('show');

				}

				$('.options').removeClass('show');
				$('#add_option').removeClass('show');
				$('.linked_content_features').removeClass('show');

			} else if (val == 'select' || val == 'multiselect' || val == 'multicheckbox' || val == 'radio' || val == 'checkbox') {

				$('.options').addClass('show');
				$('.linked_content_features').removeClass('show');
				$('.file_upload_features').removeClass('show');
				$('.image_upload_features').removeClass('show');

				if (val == 'select' || val == 'radio' || val == 'multiselect' || val == 'multicheckbox') {

					$('#add_option').addClass('show');

				} else {

					$('#add_option').removeClass('show');

				}

			} else {

				$('.options').removeClass('show');
				$('#add_option').removeClass('show');
				$('.linked_content_features').removeClass('show');
				$('.file_upload_features').removeClass('show');
				$('.image_upload_features').removeClass('show');

			}

		});

		if ($('fieldset.openable').length > 0) {

			$('fieldset.openable legend').click(function(event){
				event.preventDefault();

				if ($(this).parent().hasClass('open')) {
					$(this).parent().removeClass('open');
				} else {
					$(this).parent().addClass('open');
				}
			});

		}

		if ($('.tabs').length > 0) {
			new CBPFWTabs( document.getElementById( 'tabs' ) );
		}

	}

});
