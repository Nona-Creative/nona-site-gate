jQuery(document).ready( function ($) {

	var time_to_remember = parseInt(nonagate.time_to_remember);
	function initialize_site_gate() {
		//$(".container").addClass('nona-site-gate');


		$('#sitegate-form').mobiscroll().date({
			theme: 'default',
			display: 'inline',
			startYear: 1900,
			endYear: 2020,
			dateOrder: "ddMyy",
			defaultValue: new Date("1991/02/08"),
			rows: 5,
			mode: "mixed",
			height: "30"
		});

		$("#sitegate-wrap").fadeIn(500);
		$("#nona-overlay-wrap").fadeIn(500);

		$("#nona_verify").on("click", function(e) {
		e.preventDefault();

			var old_enough = false;

			if ( is_visitor_old_enough() ) {
				old_enough = true;
			}

			if (old_enough) {
			   cookie.set( 'nona-site-verified', 'verified', {
				   expires: time_to_remember,
				   domain: document.location.hostname,
				   path: '/',
				   secure: false
				});

				$("#nona-overlay-wrap").fadeOut("slow");

			} else {
				$("#error-too-young").slideDown().delay(3000).slideUp("slow");
			}

		});

	}

	function is_visitor_old_enough() {

			var ageData, values, day, month, year, today, birthDate, age, m;

			ageData = $('#sitegate-form').mobiscroll('getInst');
			values = ageData.getValue();

			day   = values[0]; // day
			month = parseInt( values[1] ) +1; // caters for months (starting at Jan as index 0)
			year  = values[2]; // year

			today = new Date(); // existing JS function
			birthDate = new Date(year + "/" + month + "/" + day);
			age = today.getFullYear() - birthDate.getFullYear();
			m = today.getMonth() - birthDate.getMonth();

			if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
				age--;
			}

			return (age >= nonagate.age_to_restrict); //returns boolean
	}

		// INIT AGE GATE
	if( ! cookie.get('nona-site-verified') ) {
		initialize_site_gate();
	}

});




