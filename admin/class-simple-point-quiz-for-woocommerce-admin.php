<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bitbucket.org/allouise/simple-point-quiz-for-woocommerce
 * @since      1.0.0
 *
 * @package    AWoo_PQ
 * @subpackage AWoo_PQ/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AWoo_PQ
 * @subpackage AWoo_PQ/admin
 * @author     Allyson Flores <elixirlouise@gmail.com>
 */
class AWoo_PQ_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-point-quiz-for-woocommerce-admin.css', array(), $this->version, 'all' );
	}
	public function enqueue_tax_styles() {
		wp_enqueue_style( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_posttype_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-point-quiz-for-woocommerce-admin-question.js', array( 'jquery' ), $this->version, false );
	}
	public function enqueue_tax_scripts() {
		wp_enqueue_script( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-point-quiz-for-woocommerce-admin-quiz.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_editor();
	}
	public function enqueue_quizlinking_scripts() {
		/*wp_enqueue_script( $this->plugin_name . '-jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array( 'jquery' ), $this->version, true );*/
		wp_enqueue_script('jquery-ui-sortable');
	}

	/**
	 * Register Custom Post Type & Taxonomy
	 *
	 * @since    1.0.0
	 */
	public function register_inits() {
		register_post_type( 'awoopq-questions', $this->register_quizzes_cpt() );
		register_taxonomy( 'awoopq-quizzes', array('awoopq-questions'), $this->register_quizzes_tax() );
	}

	/**
	 * Add Settings Links in Plugin list page
	 *
	 * @since    1.0.0
	 */
	public function add_settings_link_plugin( $links ) {
		$links[] = '<a href="' . admin_url( 'edit-tags.php?taxonomy=awoopq-quizzes&post_type=awoopq-questions' ) . '">' . __('Quizzes') . '</a>';
		$links[] = '<a href="' . admin_url( 'edit.php?post_type=awoopq-questions' ) . '">' . __('Questions') . '</a>';
		return $links;
	}

	/**
	 * Question Posttype Settings
	 *
	 * @since    1.0.0
	 */
	public function register_quizzes_cpt() {
		$labels = array(
				'name'               => __( 'Woocommerce Quiz Questionnaire', 'post type general name', $this->plugin_name ),
				'singular_name'      => __( 'Question', 'post type singular name', $this->plugin_name ),
				'menu_name'          => __( 'Woocommerce Quiz Questionnaire', 'admin menu', $this->plugin_name ),
				'name_admin_bar'     => __( 'Woocommerce Quiz Questionnaire', 'add new on admin bar', $this->plugin_name ),
				'add_new'            => __( 'Add New Question', 'question', $this->plugin_name ),
				'add_new_item'       => __( 'Add New Question', $this->plugin_name ),
				'new_item'           => __( 'New Question', $this->plugin_name ),
				'edit_item'          => __( 'Edit Question', $this->plugin_name ),
				'view_item'          => __( 'View Question', $this->plugin_name ),
				'all_items'          => __( 'All Questions', $this->plugin_name ),
				'search_items'       => __( 'Search Questions', $this->plugin_name ),
				'parent_item_colon'  => __( 'Parent Questions:', $this->plugin_name ),
				'not_found'          => __( 'No questions found.', $this->plugin_name ),
				'not_found_in_trash' => __( 'No questions found in Trash.', $this->plugin_name )
			);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Quizzes', $this->plugin_name ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'question' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'			 => plugins_url( $this->plugin_name . '/images/icon-sm.png' ),
			'supports'           => array( 'title', 'thumbnail' )
		);

		return $args;
	}

	/**
	 * Quiz Tax Settings
	 *
	 * @since    1.0.0
	 */
	public function register_quizzes_tax() {
		 
		$labels = array(
		    'name' => __( 'Quizzes', 'taxonomy general name', $this->plugin_name ),
		    'singular_name' => __( 'Quiz', 'taxonomy singular name', $this->plugin_name ),
		    'search_items' =>  __( 'Search Quizzes', $this->plugin_name ),
		    'all_items' => __( 'All Quizzes', $this->plugin_name ),
		    'parent_item' => __( 'Parent Quiz', $this->plugin_name ),
		    'parent_item_colon' => __( 'Parent Quiz:', $this->plugin_name ),
		    'edit_item' => __( 'Edit Quiz', $this->plugin_name ), 
		    'update_item' => __( 'Update Quiz', $this->plugin_name ),
		    'add_new_item' => __( 'Add New Quiz', $this->plugin_name ),
		    'new_item_name' => __( 'New Quiz Name', $this->plugin_name ),
		    'menu_name' => __( 'Quizzes', $this->plugin_name ),
		);    
		 
		return array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'awoopq-quizzes' ),
  		);
	}

	/**
	 * Modify Quiz Taxonomy Columns
	 *
	 * @since    1.0.0
	 */
	public function awoopq_quizzes_taxonomy_columns( $columns ) {
	    unset($columns['slug']);
	    $columns['awoopq_quizes_shortcode'] = __('Quiz Shortcode', $this->plugin_name);
	    return $columns;
	}

	/**
	 * Modify Quiz Taxonomy Columns Content
	 *
	 * @since    1.0.0
	 */
	public function awoopq_quizzes_taxonomy_columns_content( $content, $column_name, $term_id ) {
	    $term = get_term( $term_id, 'awoopq-quizzes' );
	    if ( 'awoopq_quizes_shortcode' == $column_name ) {
	        $shortcode = '<input type="text" readonly="readonly" value="[awoopq_quiz id='.$term->slug.']" />';
	        $content = $shortcode;
	    }
	    return $content;
	}

	/**
	 * Modify Question Posttype Columns
	 *
	 * @since    1.0.0
	 */
	public function awoopq_questions_columns( $columns ) {
	    $columns['awoopq_sort_index'] = __('Sort Index', $this->plugin_name);
	    return $columns;
	}

	/**
	 * Modify Question Posttype Columns Content
	 *
	 * @since    1.0.0
	 */
	public function awoopq_questions_columns_content( $column, $post_id ) {
	    $sort = (int) get_post_meta( $post_id, "awoopq_field_sorting", true );
	    if ( 'awoopq_sort_index' == $column ) {
	        echo $sort;
	    }
	}

	/**
	 * Question Posttype Columns Sortable
	 *
	 * @since    1.0.0
	 */
	public function awoopq_questions_columns_sortable( $columns ) {
		$columns['taxonomy-awoopq-quizzes'] = 'taxonomy-awoopq-quizzes';
	    return $columns;
	}

	/**
	 * Question Fields
	 *
	 * @since    1.0.0
	 */
	public function add_question_answer_fields() {

	    add_meta_box(
	        "quiz_question_fields",
	        "Answers",
	        array( $this, "question_display_callback" ),
	        "awoopq-questions",
	        "normal"/*,
	        "low"*/
	    );

	    add_meta_box(
	        "quiz_question_field_sort",
	        "Sort Index",
	        array( $this, "question_sort_display_callback" ),
	        "awoopq-questions",
	        "side",
	        "high"
	    );
	}

	/**
	 * Question Custom Field Display
	 *
	 * @since    1.0.0
	 */
	public function question_display_callback() {
		global $post;
		if( isset($_GET['action']) && $_GET['action'] == 'edit' ){
			$fields = get_post_meta( $post->ID, "awoopq_fields", true );
			$fields = isset( $fields )? array_values($fields) : $fields;
		}
		
		require_once plugin_dir_path( __FILE__ ). 'partials/simple-point-quiz-for-woocommerce-admin-question-answers.php';
	}

	/**
	 * Question Custom Field Display
	 *
	 * @since    1.0.0
	 */
	public function question_sort_display_callback() {
		global $post;
		$field_sorting = (int) get_post_meta( $post->ID, "awoopq_field_sorting", true );
		require_once plugin_dir_path( __FILE__ ). 'partials/simple-point-quiz-for-woocommerce-admin-question-sort-answer.php';
	}

	/**
	 * Question Fields Save
	 *
	 * @since    1.0.0
	 */
	public function save_question_answer_fields(){
	    global $post;

        if ( ! isset( $_POST['question_answer_fields_nonce'] ) ) {
            return;
        }
        $nonce = $_POST['question_answer_fields_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'question_answer_fields' ) ) {
            return;
        }
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return;
	    }
	    if ( get_post_status( $post->ID ) === 'auto-draft' ) {
	        return;
	    }

	    $answer_fields = sanitize_text_field($_POST["answer_fields"]);
	    $input = array();

	    if( count( $answer_fields ) > 0 ){
	    	foreach ($answer_fields as $field) {
	    		$field['field'] = preg_replace('/\s+/', ' ', $field['field']);
	    		if( isset($field['field']) && $field['field'] != " " && $field['field'] != "" ){
		    		$input[uniqid($post->ID)] = array(
		    			"field" => sanitize_text_field( $field['field'] ),
						"point" => ( isset($field['point']) && $field['point'] > 0 )? (int) $field['point'] : 0
		    		);
	    		}
	    	}
	    }
	    if( count( $input ) > 0 ){
	    	update_post_meta( $post->ID, "awoopq_fields", $input );
	    }
	}

	/**
	 * Question Fields Sort Save
	 *
	 * @since    1.0.0
	 */
	public function save_question_answer_field_sort(){
	    global $post;

        if ( ! isset( $_POST['question_answer_field_sort_nonce'] ) ) {
            return;
        }
        $nonce = $_POST['question_answer_field_sort_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'question_answer_field_sort' ) ) {
            return;
        }
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return;
	    }
	    if ( get_post_status( $post->ID ) === 'auto-draft' ) {
	        return;
	    }

	    $field_sorting = (int)$_POST["field_sorting"];
	    update_post_meta( $post->ID, "awoopq_field_sorting", $field_sorting );
	}

	/**
	 * Add Quiz Custom Field Display
	 *
	 * @since    1.0.0
	 */
	public function add_quiz_custom_field_display( $term ) {
		$args = array(
		    'post_type' => 'product',
		    'posts_per_page' => -1
		);
		$products = new WP_Query( $args );
	    require_once plugin_dir_path( __FILE__ ). 'partials/simple-point-quiz-for-woocommerce-admin-quiz-add.php';
	}

	/**
	 * Edit Quiz Custom Field Display
	 *
	 * @since    1.0.0
	 */
	public function edit_quiz_custom_field_display( $term ) {
		$args = array(
		    'post_type' => 'product',
		    'posts_per_page' => -1
		);
		$products = new WP_Query( $args );
	    $suggested_products = get_term_meta( $term->term_id, 'suggested_products', true );
	    require_once plugin_dir_path( __FILE__ ). 'partials/simple-point-quiz-for-woocommerce-admin-quiz-edit.php';
	}
	
	/**
	 * Save Quiz Custom Field Display
	 *
	 * @since    1.0.0
	 */
	public function awoopq_quizzes_save_field( $term_id ) {
		
        if ( ! isset( $_POST['quiz_custom_fields_nonce'] ) ) {
            return;
        }
        $nonce = $_POST['quiz_custom_fields_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'quiz_custom_fields' ) ) {
            return;
        }

        if( isset($_POST['suggested_product']) && count($_POST['suggested_product']) > 0 ){
        	$suggested_products = sanitize_text_field($_POST['suggested_product']);
        	$input = array();

        	foreach ($suggested_products as $suggested_product) {
        		if( 
        			isset( $suggested_product['Minpoint'] ) && (int)$suggested_product['Minpoint'] >= 0 &&  
        			isset( $suggested_product['Maxpoint'] ) && (int)$suggested_product['Maxpoint'] >= 0 &&
        			( isset( $suggested_product['product'] ) && count( $suggested_product['product'] ) > 0 || 
        				isset( $suggested_product['redirect'] ) && $suggested_product['redirect'] != "" )
        		){

        			$result = array(
        				"Minpoint" => ( (int)$suggested_product['Minpoint'] <= (int)$suggested_product['Maxpoint'] )? (int)$suggested_product['Minpoint'] : 0,
        				"Maxpoint" => ( (int)$suggested_product['Maxpoint'] >= (int)$suggested_product['Minpoint'] )? (int)$suggested_product['Maxpoint'] : (int)$suggested_product['Minpoint'] + 1,
        				"content" => isset($suggested_product['content'])? wp_kses_post($suggested_product['content']) : ""
        			);

        			if( isset( $suggested_product['product'] ) && count( $suggested_product['product'] ) > 0 ){
        				$args = array(
        				    'post_type' => 'product',
        				    'post__in' => $suggested_product['product'],
        				    'post_status' => 'publish'
        				);
        				$products = new WP_Query( $args );

        				if( $products->post_count > 0 ){
        					$result["product"] = $suggested_product['product'];
        				}
        			}
        			if( isset( $suggested_product['redirect'] ) && $suggested_product['redirect'] != "" ){
        				$result["redirect"] = $suggested_product['redirect'];
        			}
        			$input[] = $result;
        		
        		}
        	}
        	
        	if( count( $input ) > 0 ){
        		update_term_meta( $term_id, 'suggested_products', $input );
        	}

        }
	}  

	/**
	 * Add Plugin Menu
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_menu() {
		
		add_submenu_page( 'edit.php?post_type=awoopq-questions', __( 'Build Linked Quizzes', $this->plugin_name ), __( 'Link Quizzes', $this->plugin_name ), 'manage_options', 'awoopq-quiz-linking', array($this, 'awoopq_quiz_linking_display') ); 
	}  

	/**
	 * Quiz Linking Page Display
	 *
	 * @since    1.0.0
	 */
	public function awoopq_quiz_linking_display() {
		$terms = get_terms(array(
		    'taxonomy' => 'awoopq-quizzes',
		    'hide_empty' => false
		));
		/*echo "<pre>".print_r($terms, true)."</pre>";*/
		require_once plugin_dir_path( __FILE__ ). 'partials/simple-point-quiz-for-woocommerce-admin-quiz-linking.php';
	}
}
