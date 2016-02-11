<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Nona_Site_Gate {

	/**
	 * The single instance of Nona_Site_Gate.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'nona_site_gate';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Maybe display the overlay.
		add_action( 'wp_footer', array( $this, 'verify_overlay' ), 10 );

		// Success Redirect
		// add_action( 'template_redirect', array( $this, 'success_redirect' ) );

		/**
		 * Require the necessary files.
		 */
		$this->require_files();

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new Nona_Site_Gate_Admin_API();
		}

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	} // End __construct ()

	/**
	 * Require the necessary files.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function require_files() {

		/**
		 * The helper functions.
		 */
		require( plugin_dir_path( __FILE__ ) . 'functions.php' );
	}

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		wp_register_script( $this->_token . '-mobiscroll-core', esc_url( $this->assets_url ) . 'js/mobiscroll/mobiscroll.core.min.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-mobiscroll-core' );

		wp_register_script( $this->_token . '-mobiscroll-datetime', esc_url( $this->assets_url ) . 'js/mobiscroll/mobiscroll.datetime.min.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-mobiscroll-datetime' );

		wp_register_script( $this->_token . '-mobiscroll-widget', esc_url( $this->assets_url ) . 'js/mobiscroll/mobiscroll.widget.min.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-mobiscroll-widget' );

		wp_register_script( $this->_token . '-mobiscroll-scroller', esc_url( $this->assets_url ) . 'js/mobiscroll/mobiscroll.scroller.min.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-mobiscroll-scroller' );

		wp_register_script( $this->_token . '-frontgate', esc_url( $this->assets_url ) . 'js/frontgate' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontgate' );

		wp_register_script( $this->_token . '-cookie', esc_url( $this->assets_url ) . 'js/cookie' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-cookie' );

		// WordPress Localise Script for use in JS
		$nona_localized_data = array(
		    'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
		    'age_to_restrict'		=> get_option('nona_site_to_restrict'),
		    'time_to_remember'		=> get_option('nona_time_to_remember')
		);
		wp_localize_script( $this->_token . '-frontgate', 'nonagate', $nona_localized_data );

	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'nona-site-gate', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'nona-site-gate';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Nona_Site_Gate Instance
	 *
	 * Ensures only one instance of Nona_Site_Gate is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Nona_Site_Gate()
	 * @return Main Nona_Site_Gate instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

	/**
	 * Print the actual overlay if the visitor needs verification.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function verify_overlay() {

		// Disable page caching by W3 Total Cache.
		define( 'DONOTCACHEPAGE', true ); ?>

			<div id="nona-overlay-wrap" class="nona-site-gate-hide">
				<div id="nona-overlay-inner">
					<div id="nona-overlay">

						<div class="landing-panel-wrap wrap">

							<div class="landing-panel krone">
								<a href="<?php echo home_url(); ?>/krone">
									<span class="logo"></span>
								</a>
							</div>

							<div class="landing-panel tjg">
								<a href="<?php echo home_url(); ?>/twee-jonge-gezellen">
									<span class="logo"></span>
								</a>
							</div>

						</div>

						<div class="araFooter">
						    <small>
						        <a href="http://www.ara.co.za/" target="_blank" rel="nofollow">
									Enjoy Responsibly. Not for Sale to Persons Under the Age of 18.
						        </a>
						    </small>
						</div>

							<?php nona_verify_form(); ?>
					</div>
				</div>
			</div>
		<?php
	}

}
