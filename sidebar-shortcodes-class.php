<?php
/**
 * Class Sidebar_Shortcodes
 *
 * @package TorroForms
 * @subpackage Components
 * @version 1.0.0
 * @since 1.0.0
 */
class Sidebar_Shortcodes {
	/**
	 * instance
	 *
	 * @var Singleton
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Registered shortcodes
	 *
	 * @var array
	 * @since 1.0.0
	 */
	var $_shortcodes = array();

	/**
	 * Instance
	 *
	 * @return Sidebar_Shortcodes
	 * @since 1.0.0
	 */
	public static function instance()
	{
		if (null === self::$_instance)
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * HIN_Sidebar_Shortcodes constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_action( 'widgets_init', array( $this, 'register_sidebar' ) );
	}

	/**
	 * Creating a sidebar shortcode
	 *
	 * @param string $shortcode_name
	 *
	 * @since 1.0.0
	 */
	public function create( $shortcode_name ) {
		if( array_key_exists( $shortcode_name, $this->_shortcodes ) ) {
			return false;
		}

		$this->_shortcodes[] = sanitize_title( $shortcode_name );

		add_shortcode( $shortcode_name, array( $this, 'ssc_' . sanitize_title( $shortcode_name ) ) );

		return true;
	}

	/**
	 * Registering Sidebars
	 *
	 * @param string $name
	 *
	 * @since 1.0.0
	 */
	public function register_sidebar( $name ) {
		if( count( $this->_shortcodes ) === 0 ) {
			return;
		}

		foreach( $this->_shortcodes AS $shortcode ) {
			$name = sprintf( __( '[%s] (Shortcode)', 'sidebar-shortcodes' ), $shortcode );
			$id = $this->get_sidebar_id( $shortcode );
			$description = sprintf( __( 'This is a sidebar for the shortcode [%s], generated by the Sidebar Shortcodes Plugin.', 'sidebar-shortcodes' ), $shortcode );

			$before_widget = apply_filters( 'ssc_before_widget', '<div id="%1$s" class="widget %2$s">', $shortcode );
			$after_widget = apply_filters( 'ssc_after_widget', '</div>', $shortcode );
			$before_title = apply_filters( 'ssc_before_title', '<h2 class="widgettitle">', $shortcode );
			$after_title = apply_filters( 'ssc_after_title', '</h2>', $shortcode );

			$args = array(
				'name' => $name,
				'id' => $id,
				'description' => $description,
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			);

			register_sidebar( $args );
		}
	}

	/**
	 * Getting a sidebar id
	 *
	 * @param string $shortcode
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function get_sidebar_id( $shortcode ) {
		return 'ssc_' . $shortcode;
	}

	/**
	 * Showing shortcode
	 *
	 * @param string $name
	 *
	 * @since 1.0.0
	 */
	private function shortcode( $shortcode ) {
		dynamic_sidebar( $this->get_sidebar_id( $shortcode ) );
	}

	/**
	 * Magic shortcode function
	 *
	 * @param string $name
	 * @param array $arguments
	 *
	 * @since 1.0.0
	 */
	public function __call( $name, $arguments ) {
		if( 'ssc_' !== substr( $name, 0, 4 ) ) {
			return;
		}

		$shortcode = substr( $name, 4, strlen( $name ) - 4 );

		if( ! in_array( $shortcode, $this->_shortcodes ) ) {
			return;
		}

		$this->shortcode( $shortcode );
	}
}

/**
 * Functionality to add sidebar shortcode
 *
 * @param string $shortcode
 * @return int|boolean True if sidebar have been added, 2 if shortcode exists, 3 if widgets_init hook already fired
 *
 * @since 1.0.0
 */
function add_sidebar_shortcode( $shortcode ){
	if( shortcode_exists( $shortcode ) ) {
		return 2;
	}

	if( did_action( 'widgets_init' ) > 0 ) {
		return 3;
	}

	$ssc = Sidebar_Shortcodes::instance();
	if( ! $ssc->create( $shortcode ) ) {
		return 2;
	}

	return true;
}