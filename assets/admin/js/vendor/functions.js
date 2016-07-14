function objsBubbleSort(arr) {
	var swapped;
	do {
		swapped = false;
		for (var i = 0; i < arr.length - 1; i++) {
			if (arr[i] > arr[i + 1]) {
				var temp   = arr[i];
				arr[i]     = arr[i + 1];
				arr[i + 1] = temp;
				swapped  = true;
			}
		}
	} while (swapped);
	return arr;
}

function objsReverseBubbleSort(arr) {
	var swapped;
	do {
		swapped = false;
		for (var i = arr.length; i > 0; i--) {
			if (arr[i] > arr[i - 1]) {
				var temp   = arr[i];
				arr[i]     = arr[i - 1];
				arr[i - 1] = temp;
				swapped  = true;
			}
		}
	} while (swapped);
	return arr;
}

function getHash() {
	var hash = window.location.hash;
	// remove #
	return hash.substring(1);
}
 
function getLinkTarget(link) {
	return link.href.substring(link.href.indexOf('#')+1);
}

//function makePermalink(str) {
//	return str.replace(/[^a-z0-9]+/gi, '-').replace(/^-*|-*$/g, '').toLowerCase();
//}
//
//function checkPermalink(permalink) {
//
//	$.ajax({
//		method: 'get',
//		url: '/admin/cms/checkPermalink/' + permalink,
//		success: function(data) {
//			
//			data = JSON.parse(data);
//
//			if (data.result == 'available') {
//
//				$('.unavailable_text').remove();
//				$('input#permalink').removeClass('unavailable').addClass('available');
//				if ($('.available_text').length == 0) {
//					$('input#permalink').after('<span class="available_text">Available</span>');
//				}
//
//			} else {
//
//				$('.available_text').remove();
//				$('input#permalink').removeClass('available').addClass('unavailable');
//				if ($('.unavailable_text').length == 0) {
//					$('input#permalink').after('<span class="unavailable_text">Unavailable</span>');
//				}
//				
//
//			}
//
//		}
//	});
//
//}
