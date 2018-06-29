/* global jQuery:false */

jQuery(document).ready(function() {
	AXIOMTHEMES_GLOBALS['media_frame'] = null;
	AXIOMTHEMES_GLOBALS['media_link'] = '';
});

function axiomthemes_show_media_manager(el) {
	"use strict";

	AXIOMTHEMES_GLOBALS['media_link'] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( AXIOMTHEMES_GLOBALS['media_frame'] ) {
		AXIOMTHEMES_GLOBALS['media_frame'].open();
		return false;
	}

	// Create the media frame.
	AXIOMTHEMES_GLOBALS['media_frame'] = wp.media({
		// Set the title of the modal.
		title: AXIOMTHEMES_GLOBALS['media_link'].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: 'image'
		},
		// Multiple choise
		multiple: AXIOMTHEMES_GLOBALS['media_link'].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: AXIOMTHEMES_GLOBALS['media_link'].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	AXIOMTHEMES_GLOBALS['media_frame'].on( 'select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+AXIOMTHEMES_GLOBALS['media_link'].data('linked-field')).eq(0);
		var attachment = '';
		if (AXIOMTHEMES_GLOBALS['media_link'].data('multiple')===true) {
			AXIOMTHEMES_GLOBALS['media_frame'].state().get('selection').map( function( att ) {
				attachment += (attachment ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment = val + (val ? "\n" : '') + attachment;
		} else {
			attachment = AXIOMTHEMES_GLOBALS['media_frame'].state().get('selection').first().toJSON().url;
		}
		field.val(attachment);
		field.trigger('change');
	});

	// Finally, open the modal.
	AXIOMTHEMES_GLOBALS['media_frame'].open();
	return false;
}
