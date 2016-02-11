jQuery(document).ready( function ($) {


	function initialize_site_gate() {
		$("body").addClass('overlay-active');

		// Makes the overlay visible.
		$("#nona-overlay-wrap").fadeIn(100);

	}

	jQuery(document).bind('gform_confirmation_loaded', function(event, formId){
        if(formId == 1) {
            $("#nona-overlay-wrap").fadeOut(500);
            $("body").removeClass('overlay-active');
			// cookie.set( 'ftds', 'verified', {
			//    expires: 180,
			//    domain: document.location.hostname,
			//    path: '/',
			//    secure: false
			// });
        }
    });

	// INIT AGE GATE
	if( ! cookie.get('ftds') ) {
		initialize_site_gate();
	}

});




