<?php

	/**
	 * Echoes the actual form
	 *
	 * @since 0.1
	 * @echo string
	 */
	function nona_verify_form() {
		echo nona_get_verify_form();
	}

	/**
	 * Returns the all-important verification form.
	 * You can filter this if you like.
	 *
	 * @since 0.1
	 * @return string
	 */
	function nona_get_verify_form() {

		$submit_button_label = apply_filters( 'nona_form_submit_label', get_option( 'nona_enter_button_text', __( 'I am old enough!', 'nona' ) ) );
		$form = '';
		$form .= '<form id="nona_verify_form" action="' . esc_url( home_url( '/' ) ) . '" method="post">';
			// Add a sweet nonce. So sweet.
		$form .= wp_nonce_field( 'verify-site', 'nona-nonce' );
		$form .= '
			<div class="agegate-wrap" id="agegate-wrap">
				<div class="agegate">
					<div id="agegate-form">
					</div>
				</div>

				<a id="nona_verify" href="#">'. esc_attr( $submit_button_label ) .'</a>
				<div id="error-too-young"><p>Sorry! You\'re under '. esc_attr( get_option('nona_site_to_restrict') ).'.</p></div>
				<small>You must be over 18 to Enter</small>

			</div>';
		$form .= '</form>';
		return apply_filters( 'nona_verify_form', $form );
	}

