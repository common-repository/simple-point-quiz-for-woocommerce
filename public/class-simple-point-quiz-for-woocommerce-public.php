<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://bitbucket.org/allouise/simple-point-quiz-for-woocommerce
 * @since      1.0.0
 *
 * @package    AWoo_PQ
 * @subpackage AWoo_PQ/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    AWoo_PQ
 * @subpackage AWoo_PQ/public
 * @author     Allyson Flores <elixirlouise@gmail.com>
 */
class AWoo_PQ_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register Custom Post Type & Taxonomy
	 *
	 * @since    1.0.0
	 */
	public function register_inits() {
		add_shortcode('awoopq_quiz', array($this, 'awoopq_quiz_shortcode_handler'));
	}

	/**
	 * Shortcode for Quiz Display
	 *
	 * @since    1.0.0
	 */
	public function awoopq_quiz_shortcode_handler( $atts, $content = NULL ){

		$a = shortcode_atts([
			'id' => '',
			'ids' => ''
		], $atts);
		$multiple = 0;
		$ids = ( isset($a['ids']) && $a['ids'] != "" )? explode(',', $a['ids']) : null;

		if( isset($ids) ){
			$multiple = 1;
			$return_forms = "";
			$total = count($ids) - 1;
			foreach ($ids as $i => $id) {
				$slug = get_term_by('id', $id, 'awoopq-quizzes');
				$slug = isset($slug->slug)? $slug->slug : "";
				
				$questions = $this->get_questions($slug);
				$return_forms .= $this->build_questions($questions, $slug, ( $i == 0 && $total > 0 ? true : false ), ( $i == $total && $total > 0 ? true : false ), true);
			}
			// wp_die("<pre>".print_r($ids, true)."</pre>");
			$return = $return_forms;
		}else{
			$questions = $this->get_questions($a['id']);
			$return = $this->build_questions($questions, $a['id']);
		}

		$return = "<div id='awoopq-quiz-container' class='multiple-$multiple'>$return</div>";
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-point-quiz-for-woocommerce-public.css', array(), $this->version, 'all' );
		wp_enqueue_script( 'simple-point-quiz-for-woocommerce-awoopq-ajax', plugin_dir_url( __FILE__ ) . 'js/simple-point-quiz-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'simple-point-quiz-for-woocommerce-awoopq-ajax', 'alf_woocommerce_point_quiz_awoopq_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

		return $return;
	}

	/**
	 * Get Questions
	 *
	 * @since    1.0.0
	 */
	public function get_questions( $slug ){
		$return = null;
		$slug = sanitize_text_field($slug);
		
		if( isset($slug) && $slug != "" ){
			$args = array(
			    'post_type' => 'awoopq-questions',
			    'post_status' => 'publish',
			    'tax_query' => array(
		            array(
		                'taxonomy' => 'awoopq-quizzes',
		                'field' => 'slug',
		                'terms' => $slug
		            ),
		        ),
		        'orderby'  => array( 'meta_value_num' => 'DESC', 'field_sorting' => 'ASC' ),
    			'posts_per_page' => -1
			);
			$return = new WP_Query( $args );
		}

		return $return;
	}

	/**
	 * Get Answers
	 *
	 * @since    1.0.0
	 */
	public function get_answers( $id ){
		$return = array();
		$id = (int) $id;
		$fields = get_post_meta( $id, "awoopq_fields", true );

		return ( isset($fields) )? $fields : $return;
	}

	/**
	 * Build Questions
	 *
	 * @since    1.0.0
	 */
	public function build_questions( $questions, $term_slug, $first = false, $last = false, $multiple = false ){
		$multiple = ( $multiple === true )? 1 : 0;
		$return = "";
		$term = get_term_by('slug', sanitize_text_field($term_slug), 'awoopq-quizzes');

		if ( isset($questions->post_count) && $questions->have_posts() ) {
		    $return .= '<div id="awoopq-quizcon-'.base64_encode(get_the_ID()).'" class="awoopq-quizcon'.( $first == true? " first" : "" ).'"><div class="awoopq-quiz-details"><div class="awoopq-quiz-init"><h2>'.$term->name.'</h2><p>'.$term->description.'</p></div>
		    <form id="awoopq-quiz-'.base64_encode(get_the_ID()).'" class="awoopq-quiz-form" method="POST" action="">
		    			<input type="hidden" name="awoopq_get_result_ajax_nonce" value="'.wp_create_nonce("awoopq_get_result_ajax_nonce").'" />
		    			<input type="hidden" name="action" value="htws_results_ajax"/>
		    			<input type="hidden" name="multiple" value="'.$multiple.'"/>
		    			<input type="hidden" name="term" value="'.$term_slug.'"/>
		    			<ul class="awoopq-quiz-question-list">';
		    $next = ( $questions->post_count > 1 )? "<button type='button' class='awoopq-btn-next'>".__( 'Next', $this->plugin_name )."</button>" : "";
		    $prev = ( $questions->post_count > 1 )? "<button type='button' class='awoopq-btn-prev'>".__( 'Prev', $this->plugin_name )."</button>" : "";
		    
		    $count = 1;
		    while ( $questions->have_posts() ) {
		        $questions->the_post();
		        $answers = $this->get_answers(get_the_ID());
		        $fields = "";
		        $next = ( $count == $questions->post_count )? "<button type='submit' class='awoopq-btn-submit'>". ( ( $multiple == 1 && $last == true )? __( 'Submit', $this->plugin_name ) : __( 'Next', $this->plugin_name ) )."</button>" : $next;

		        if( isset($answers) && count( $answers ) > 0 ){
		        	$fields .= "<span class='awoopq-question-answers'>";
		        	foreach ($answers as $key => $answer) {
		        		$fields .= "<label class='awoopq-field'>
		        			<input type='radio' class='awoopq-input' name='awoopq-question[".get_the_ID()."]' value='$key' /> <span class='awoopq-label'>{$answer['field']}</span>
		        		</label>";
		        	}
		        	$fields .= "</span>";
		        }

		        if( $first == false && $multiple == 1 || $count > 1 &&  $multiple == 0 ){
		        	$prev = "<button type='button' class='awoopq-btn-prev'>".__( 'Prev', $this->plugin_name )."</button>";
		        }elseif ( $count == 1 && $multiple == 0 ) {
		        	$prev = "";
		        }

		        $return .= "<li class='awoopq-quiz-question question-$count ".( $count == 1? "first" : "" )."' ".( $count == 1? "style='display:block;'" : "" ).">
		        	<strong class='hq-title'>".get_the_title()."</strong><br/>
		        	$fields
		        	$prev
		        	$next
		        	<br class='clear'/>
		        </li>";
		        $count++;
		    }
		    $return .= "</ul></form><div class='awoopq-alert'></div></div><span class='loader' style='display:none;'></span></div>";
		}else{
			$return = ( $multiple == true )? "" : "<div class='awoopq-alert has-danger' style='display:block;'>".__( 'Quiz has no question', $this->plugin_name )."</div>";
		}
		
		return $return;
	}

	/**
	 * Show Results
	 *
	 * @since    1.0.0
	 */
	public function show_result() {
		$return = array(
			"error" => true,
			"messages" => ""
		);

		if ( 
			wp_verify_nonce( $_POST['awoopq_get_result_ajax_nonce'], "awoopq_get_result_ajax_nonce" ) !== false && 
			isset($_POST['awoopq-question']) && is_array($_POST['awoopq-question']) && count($_POST['awoopq-question']) > 0 &&
			isset($_POST['term'])
		) {
			$awoopq_questions = (array) $_POST['awoopq-question'];
			$term = get_term_by('slug', sanitize_text_field($_POST['term']), 'awoopq-quizzes');
			$suggested_products = ( $term !== false )? get_term_meta( $term->term_id, 'suggested_products', true ) : null;
			if ( $term !== false && isset($suggested_products) && count($suggested_products) > 0 ) {
				$error = false;
				$points = 0;
				$multiple = ( isset($_POST['multiple']) && $_POST['multiple'] == 1 )? true : false;

				foreach ($awoopq_questions as $qid => $ans) {
					$qid = (int) $qid;
					$ans = sanitize_text_field($ans);
					$terms = wp_get_post_terms($qid, "awoopq-quizzes", array('fields' => 'slugs'));
					$metas = get_post_meta( $qid, "awoopq_fields", true );
					if( !in_array($term->slug, $terms) || !in_array($ans, array_keys($metas)) ){
						$error = true;
						break;
					}
					#$answers[] = $metas[$ans];
					$points += $metas[$ans]['point'];
				}
			
			    if( $error === false ){

			    	$products = array();
			    	$redirect = null;
			    	#Find Suggested Products
			    	foreach ($suggested_products as $sp) {
			    		if( $points <= $sp['Maxpoint'] && $points >= $sp['Minpoint'] ){
			    			$products[] = array(
			    				"content" => $sp['content'],
			    				"products" => $sp['product'],
			    				"redirect" => $sp['redirect']
			    			);

			    			if( isset($sp['redirect']) ){
			    				$redirect = $sp['redirect'];
			    				break;
			    			}
			    		}
			    	}
			    	$result = ( $redirect != null )? null : $this->result_display($products, $multiple);

			    	$return["error"] = false;
			    	$return["points"] = $points;
			    	$return["suggested_products"] = $result;
			    	$return["products"] = $products;
			    	$return["redirect"] = $redirect;
			    	$return["multiple"] = $multiple;

			    	if( $multiple == true && isset($redirect) && $redirect != "" ){
			    		$return["redirect_link"] = "<a href='$redirect' target='_blank'>".__( "Click Here", $this->plugin_name )."</a> ". __( "to check our", $this->plugin_name )." <a href='$redirect' target='_blank'>".__( "Suggested Products", $this->plugin_name )."</a>";
			    	}
			    }
			}
		}
		echo json_encode($return);
		wp_die();
	}

	/**
	 * Create Product Results
	 *
	 * @since    1.0.0
	 */
	public function result_display($products, $multiple = false) {

		if( count($products) > 0 ){
			$return = "<h3 class='awoopq-result-title'>".__( "Hello! We suggest these products for you", $this->plugin_name ).":</h3>";

			foreach ($products as $product) {
				$return .= "<div class='htws-description'>{$product['content']}</div>"; 
				$prods = "";
				$args = array(
				    'post_type' => 'product',
				    'post__in' => $product['products'],
				    'post_status' => 'publish'
				);
				$wprod = new WP_Query( $args );
				while ( $wprod->have_posts() ) { 
					$wprod->the_post();
				    $prods .= "<div class='htws-products'><a href='".get_permalink(get_the_ID())."'>
				    	<strong class='awoopq-title'>".get_the_title()."</strong>
				    	<span class='awoopq-image'>".( has_post_thumbnail( get_the_ID() )? get_the_post_thumbnail( $_post->ID, 'thumbnail' ) : "" )."</span>
				    	<span class='awoopq-price'>$".get_post_meta( get_the_ID(), '_price', true )."</span>
				    </a></div>";
				}
				$return .= "<div class='htws-suggested-products'>$prods</div>";
			}

		}else{
			$return = ( ( $multiple == true )? "" : "<h3 class='awoopq-result-title'>".__( "We're sorry,", $this->plugin_name )."</h3>" )."<p>".__( "We didn't find products suited for your needs. Please try browsing our other Products/Services.", $this->plugin_name )."</p>";
		}

		return $return;
	}

}
