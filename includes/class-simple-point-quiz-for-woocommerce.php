<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bitbucket.org/allouise/simple-point-quiz-for-woocommerce
 * @since      1.0.0
 *
 * @package    AWoo_PQ
 * @subpackage AWoo_PQ/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    AWoo_PQ
 * @subpackage AWoo_PQ/includes
 * @author     Allyson Flores <elixirlouise@gmail.com>
 */
class AWoo_PQ {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      AWoo_PQ_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'AWOOPQ_SPQ_VERSION' ) ) {
			$this->version = AWOOPQ_SPQ_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'simple-point-quiz-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - AWoo_PQ_Loader. Orchestrates the hooks of the plugin.
	 * - AWoo_PQ_i18n. Defines internationalization functionality.
	 * - AWoo_PQ_Admin. Defines all hooks for the admin area.
	 * - AWoo_PQ_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-point-quiz-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-point-quiz-for-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-point-quiz-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-simple-point-quiz-for-woocommerce-public.php';

		$this->loader = new AWoo_PQ_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the AWoo_PQ_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new AWoo_PQ_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new AWoo_PQ_Admin( $this->get_plugin_name(), $this->get_version() );
		$post_type = 'awoopq-questions';
		$tax_allowed = 'awoopq-quizzes';

		if( isset($_GET['page']) && $_GET['page'] == 'awoopq-quiz-linking'  ){
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_quizlinking_scripts' );
		}elseif( isset($_GET['taxonomy']) && $_GET['taxonomy'] == $tax_allowed ){
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_tax_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_tax_scripts' );
		}elseif( isset($_GET['post_type']) && $_GET['post_type'] == $post_type || 
			isset($_GET['post']) && get_post_type($_GET['post']) == $post_type
		){
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_posttype_scripts' );
		}

		$this->loader->add_action( 'init', $plugin_admin, 'register_inits' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_question_answer_fields' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_question_answer_fields' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_question_answer_field_sort' );
		$this->loader->add_action( 'awoopq-quizzes_add_form_fields', $plugin_admin, 'add_quiz_custom_field_display', 10, 2 );
		$this->loader->add_action( 'awoopq-quizzes_edit_form_fields', $plugin_admin, 'edit_quiz_custom_field_display', 10 );
		$this->loader->add_action( 'edited_awoopq-quizzes', $plugin_admin, 'awoopq_quizzes_save_field' );  
		$this->loader->add_action( 'create_awoopq-quizzes', $plugin_admin, 'awoopq_quizzes_save_field' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_menu' );

		$this->loader->add_filter( 'manage_edit-awoopq-quizzes_columns', $plugin_admin, 'awoopq_quizzes_taxonomy_columns' );
		$this->loader->add_filter( 'manage_awoopq-quizzes_custom_column', $plugin_admin, 'awoopq_quizzes_taxonomy_columns_content', 10, 3 );

		$this->loader->add_filter( 'manage_awoopq-questions_posts_columns', $plugin_admin, 'awoopq_questions_columns' );
		$this->loader->add_filter( 'manage_awoopq-questions_posts_custom_column', $plugin_admin, 'awoopq_questions_columns_content', 10, 3 );
		$this->loader->add_filter( 'manage_edit-awoopq-questions_sortable_columns', $plugin_admin, 'awoopq_questions_columns_sortable' );

		$this->loader->add_filter( 'plugin_action_links_'.$this->get_plugin_name().'/'.$this->get_plugin_name().'.php', $plugin_admin, 'add_settings_link_plugin', 10, 4 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new AWoo_PQ_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'register_inits' );
		$this->loader->add_action( 'wp_ajax_nopriv_htws_results_ajax', $plugin_public, 'show_result' );
		$this->loader->add_action( 'wp_ajax_htws_results_ajax', $plugin_public, 'show_result' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    AWoo_PQ_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
