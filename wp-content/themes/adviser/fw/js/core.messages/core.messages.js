// Popup messages
//-----------------------------------------------------------------
jQuery(document).ready(function(){
	"use strict";

	AXIOMTHEMES_GLOBALS['message_callback'] = null;
	AXIOMTHEMES_GLOBALS['message_timeout'] = 5000;

	jQuery('body').on('click', '#axiomthemes_modal_bg,.axiomthemes_message .axiomthemes_message_close', function (e) {
		"use strict";
		axiomthemes_message_destroy();
		if (AXIOMTHEMES_GLOBALS['message_callback']) {
			AXIOMTHEMES_GLOBALS['message_callback'](0);
			AXIOMTHEMES_GLOBALS['message_callback'] = null;
		}
		e.preventDefault();
		return false;
	});
});


// Warning
function axiomthemes_message_warning(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var icon = arguments[2] ? arguments[2] : 'cancel-1';
	var delay = arguments[3] ? arguments[3] : AXIOMTHEMES_GLOBALS['message_timeout'];
	return axiomthemes_message({
		msg: msg,
		hdr: hdr,
		icon: icon,
		type: 'warning',
		delay: delay,
		buttons: [],
		callback: null
	});
}

// Success
function axiomthemes_message_success(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var icon = arguments[2] ? arguments[2] : 'check-1';
	var delay = arguments[3] ? arguments[3] : AXIOMTHEMES_GLOBALS['message_timeout'];
	return axiomthemes_message({
		msg: msg,
		hdr: hdr,
		icon: icon,
		type: 'success',
		delay: delay,
		buttons: [],
		callback: null
	});
}

// Info
function axiomthemes_message_info(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var icon = arguments[2] ? arguments[2] : 'info-1';
	var delay = arguments[3] ? arguments[3] : AXIOMTHEMES_GLOBALS['message_timeout'];
	return axiomthemes_message({
		msg: msg,
		hdr: hdr,
		icon: icon,
		type: 'info',
		delay: delay,
		buttons: [],
		callback: null
	});
}

// Regular
function axiomthemes_message_regular(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var icon = arguments[2] ? arguments[2] : 'quote-1';
	var delay = arguments[3] ? arguments[3] : AXIOMTHEMES_GLOBALS['message_timeout'];
	return axiomthemes_message({
		msg: msg,
		hdr: hdr,
		icon: icon,
		type: 'regular',
		delay: delay,
		buttons: [],
		callback: null
	});
}

// Confirm dialog
function axiomthemes_message_confirm(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var callback = arguments[2] ? arguments[2] : null;
	return axiomthemes_message({
		msg: msg,
		hdr: hdr,
		icon: 'help-1',
		type: 'regular',
		delay: 0,
		buttons: ['Yes', 'No'],
		callback: callback
	});
}

// Modal dialog
function axiomthemes_message_dialog(content) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var init = arguments[2] ? arguments[2] : null;
	var callback = arguments[3] ? arguments[3] : null;
	return axiomthemes_message({
		msg: content,
		hdr: hdr,
		icon: '',
		type: 'regular',
		delay: 0,
		buttons: ['Apply', 'Cancel'],
		init: init,
		callback: callback
	});
}

// General message window
function axiomthemes_message(opt) {
	"use strict";
	var msg = opt.msg != undefined ? opt.msg : '';
	var hdr  = opt.hdr != undefined ? opt.hdr : '';
	var icon = opt.icon != undefined ? opt.icon : '';
	var type = opt.type != undefined ? opt.type : 'regular';
	var delay = opt.delay != undefined ? opt.delay : AXIOMTHEMES_GLOBALS['message_timeout'];
	var buttons = opt.buttons != undefined ? opt.buttons : [];
	var init = opt.init != undefined ? opt.init : null;
	var callback = opt.callback != undefined ? opt.callback : null;
	// Modal bg
	jQuery('#axiomthemes_modal_bg').remove();
	jQuery('body').append('<div id="axiomthemes_modal_bg"></div>');
	jQuery('#axiomthemes_modal_bg').fadeIn();
	// Popup window
	jQuery('.axiomthemes_message').remove();
	var html = '<div class="axiomthemes_message axiomthemes_message_' + type + (buttons.length > 0 ? ' axiomthemes_message_dialog' : '') + '">'
		+ '<span class="axiomthemes_message_close iconadmin-cancel icon-cancel-1"></span>'
		+ (icon ? '<span class="axiomthemes_message_icon iconadmin-'+icon+' icon-'+icon+'"></span>' : '')
		+ (hdr ? '<h2 class="axiomthemes_message_header">'+hdr+'</h2>' : '');
	html += '<div class="axiomthemes_message_body">' + msg + '</div>';
	if (buttons.length > 0) {
		html += '<div class="axiomthemes_message_buttons">';
		for (var i=0; i<buttons.length; i++) {
			html += '<span class="axiomthemes_message_button">'+buttons[i]+'</span>';
		}
		html += '</div>';
	}
	html += '</div>';
	// Add popup to body
	jQuery('body').append(html);
	var popup = jQuery('body .axiomthemes_message').eq(0);
	// Prepare callback on buttons click
	if (callback != null) {
		AXIOMTHEMES_GLOBALS['message_callback'] = callback;
		jQuery('.axiomthemes_message_button').click(function(e) {
			"use strict";
			var btn = jQuery(this).index();
			callback(btn+1, popup);
			AXIOMTHEMES_GLOBALS['message_callback'] = null;
			axiomthemes_message_destroy();
		});
	}
	// Call init function
	if (init != null) init(popup);
	// Show (animate) popup
	var top = jQuery(window).scrollTop();
	jQuery('body .axiomthemes_message').animate({top: top+Math.round((jQuery(window).height()-jQuery('.axiomthemes_message').height())/2), opacity: 1}, {complete: function () {
		// Call init function
		//if (init != null) init(popup);
	}});
	// Delayed destroy (if need)
	if (delay > 0) {
		setTimeout(function() { axiomthemes_message_destroy(); }, delay);
	}
	return popup;
}

// Destroy message window
function axiomthemes_message_destroy() {
	"use strict";
	var top = jQuery(window).scrollTop();
	jQuery('#axiomthemes_modal_bg').fadeOut();
	jQuery('.axiomthemes_message').animate({top: top-jQuery('.axiomthemes_message').height(), opacity: 0});
	setTimeout(function() { jQuery('#axiomthemes_modal_bg').remove(); jQuery('.axiomthemes_message').remove(); }, 500);
}
