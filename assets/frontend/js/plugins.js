// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

jQuery.browser = {};
jQuery.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
jQuery.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());

function setOverlay(selector) {
	
	var dW = $(document).width(),
		dH = $(document).height(),
		wW = $(window).width(),
		wH = $(window).height(),
		cW = $(selector).width(),
		cH = $(selector).height(),
		scrollbarWidth = 0;

	if (jQuery.browser.msie) {
		if ($('html').hasClass('lt-ie9')) {
			dW = dW - 22;
		} else {
			dW = dW - 17;
		}
	}

	$('.overlay').css({
		width: dW,
		height: dH,
		opacity: .8
	});

	$(selector).css({
		left: Math.round((wW - cW) / 2),
		top: Math.round((wH - cH) / 2)
	}).show();

}

function resetOverlay() {
	$('.overlay').css({
		width: 0,
		height: 0,
		opacity: 0
	});
	$('.overlay_content').hide();
}