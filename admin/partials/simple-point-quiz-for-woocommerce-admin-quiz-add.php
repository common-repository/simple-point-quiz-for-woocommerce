<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://bitbucket.org/allouise/simple-point-quiz-for-woocommerce
 * @since      1.0.0
 *
 * @package    AWoo_PQ
 * @subpackage AWoo_PQ/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php wp_nonce_field( 'quiz_custom_fields', 'quiz_custom_fields_nonce' ); ?>
<hr/>
<div class="col-wrap"><h2 style="text-align: center;"><?php _e( 'Set Suggested Products Results are in Edit Quiz Page', $this->plugin_name ); ?></h2></div>
<?php /*
    <div class="init">
    	<div class="row">
    		<input type="number" min="0" name="suggested_product[0][Minpoint]" placeholder="Min Point" value="" required> <input type="number" min="0" name="suggested_product[0][Maxpoint]" placeholder="Max Point" value="" required> 
            <div class='result-display'>
                <h4><i>+</i> Show Result via Custom Page</h4>
                <div class="result-con">
                    <input type="url" name="suggested_product[0][redirect]" placeholder="Page URL" value=""/>
                </div>
            </div>
            <div class='result-display'>
                <h4><i>+</i> Show Result by Choosing Product Result</h4>
                <div class="result-con">
                    <select class="select2" name="suggested_product[0][product][]" data-placeholder="Select Products" multiple>
                        <?php while ( $products->have_posts() ) { $products->the_post(); ?>
                            <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>
                        <?php } wp_reset_postdata(); ?>
                    </select>
                    <textarea class="texteditor" id="suggested_product[0][content]" name="suggested_product[0][content]"></textarea>
                </div>
            </div>
            <p style="font-weight: bold; text-align: center;"><em>You can add more Suggested Products in Edit Quiz Page</em></p>
    	</div>
    </div>
    <br/>
</div>
<script type="text/javascript">
    function awoopq_product_repeater( _count = 0 ){
        return '<div class="row" data-id="'+_count+'"><a class="remove_field_button button-secondary">-</a> <input type="number" min="0" name="suggested_product['+_count+'][Minpoint]" placeholder="Min Point" value="" required> <input type="number" min="0" name="suggested_product['+_count+'][Maxpoint]" placeholder="Max Point" value="" required> <div class="result-display"><h4><i>+</i> Show Result via Custom Page</h4><div class="result-con"><input type="url" name="suggested_product['+_count+'][redirect]" placeholder="Page URL" value=""/></div></div><div class="result-display"><h4><i>+</i> Show Result by Choosing Product Result</h4><div class="result-con"><select class="select2" name="suggested_product['+_count+'][product][]" data-placeholder="Select Products" multiple><?php while ( $products->have_posts() ) { $products->the_post(); ?><option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option><?php } wp_reset_postdata(); ?></select><textarea class="texteditor" id="suggested_product['+_count+'][content]" name="suggested_product['+_count+'][content]"></textarea></div></div></div>';
    }
</script> */