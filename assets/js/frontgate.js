jQuery(document).ready( function ($) {

	function initialize_site_gate() {
		// Adds body class when overlay active to stop scrolling.
		// $("body").addClass('overlay-active');

		// Makes the overlay visible.
		$("#nona-overlay-wrap").fadeIn();

	}

	jQuery(document).bind('gform_confirmation_loaded', function(event, formId){
			// Only work if its a specific form, formId = 1
	    if(formId == 1) {
					// This makes the overlay disappear with a delay and fade.
	        // $("#nona-overlay-wrap").delay(5000).fadeOut(300);

					// This removes the body class that prevents site scrolling.
	        // $("body").removeClass('overlay-active');

					// This sets the cookie so the form is not shown again.
					cookie.set( 'ftds', 'verified', {
					   expires: 180,
					   domain: document.location.hostname,
					   path: '/',
					   secure: false
					});
	    }
	  });

	// Checks if the cookie exists, displays form if it doesn't.
	if( ! cookie.get('ftds') ) {
		initialize_site_gate();
	}

});
