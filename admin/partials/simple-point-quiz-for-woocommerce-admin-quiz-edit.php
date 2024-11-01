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
<tr class="form-field">
    <th><label for="minPoint"><?php _e( 'Set Suggested Products Result', $this->plugin_name ); ?></label></th>
    <td class='input_fields_wrap'>     
        <span class="init">
            <span class="row">
                <a class="add_field_button button-secondary">+</a> <input type="number" min="0" name="suggested_product[0][Minpoint]" placeholder="Min Point" value="<?php echo isset( $suggested_products[0]['Minpoint'] )? $suggested_products[0]['Minpoint'] : "" ?>" required/> <input type="number" min="0" name="suggested_product[0][Maxpoint]" placeholder="Max Point" value="<?php echo isset( $suggested_products[0]['Maxpoint'] )? $suggested_products[0]['Maxpoint'] : "" ?>" required/> 

                <span class='result-display'>
                    <h4><i>+</i> Show Result via Custom Page</h4>
                    <span class="result-con">
                        <input type="url" name="suggested_product[0][redirect]" placeholder="Page URL" value="<?php echo isset( $suggested_products[0]['redirect'] )? $suggested_products[0]['redirect'] : "" ?>"/>
                    </span>
                </span>
                <span class='result-display'>
                    <h4><i>+</i> Show Result by Choosing Product Result</h4>
                    <span class="result-con">
                        <select class="select2" name="suggested_product[0][product][]" data-placeholder="Select Products" multiple>
                            <?php while ( $products->have_posts() ) { $products->the_post(); ?>
                                <option value="<?php echo get_the_ID(); ?>" <?php echo ( isset( $suggested_products[0]['product'] ) && count( $suggested_products[0]['product'] ) > 0 && in_array(get_the_ID(), $suggested_products[0]['product']) )? "selected" : "" ?>><?php echo get_the_title(); ?></option>
                            <?php } ?>
                        </select>
                        <textarea class="texteditor" id="suggested_product[0][content]" name="suggested_product[0][content]"><?php echo isset( $suggested_products[0]['content'] )? $suggested_products[0]['content'] : "" ?></textarea>
                    </span>
                </span>
            </span>
        </span>
        <span class="other-answers">
            <?php if( isset($suggested_products) && is_array($suggested_products) && count( $suggested_products ) > 1 ){ foreach ($suggested_products as $key => $suggested_product) { if( $key != 0 ) { ?>
                <span class="row" data-id="<?php echo $key; ?>">
                    <a class="remove_field_button button-secondary">-</a> <input type="number" min="0" name="suggested_product[<?php echo $key; ?>][Minpoint]" placeholder="Min Point" value="<?php echo isset( $suggested_product['Minpoint'] )? $suggested_product['Minpoint'] : "" ?>" required> <input type="number" min="0" name="suggested_product[<?php echo $key; ?>][Maxpoint]" placeholder="Max Point" value="<?php echo isset( $suggested_product['Maxpoint'] )? $suggested_product['Maxpoint'] : "" ?>" required> 

                    <span class='result-display'>
                        <h4><i>+</i> Show Result via Custom Page</h4>
                        <span class="result-con">
                            <input type="url" name="suggested_product[<?php echo $key; ?>][redirect]" placeholder="Page URL" value="<?php echo isset( $suggested_product['redirect'] )? $suggested_product['redirect'] : "" ?>"/>
                        </span>
                    </span>
                    <span class='result-display'>
                        <h4><i>+</i> Show Result by Choosing Product Result</h4>
                        <span class="result-con">
                            <select class="select2" name="suggested_product[<?php echo $key; ?>][product][]"  multiple>
                                <?php while ( $products->have_posts() ) { $products->the_post(); ?>
                                    <option value="<?php echo get_the_ID(); ?>" <?php echo ( isset( $suggested_product['product'] ) && count( $suggested_product['product'] ) > 0 && in_array(get_the_ID(), $suggested_product['product']) )? "selected" : "" ?>><?php echo get_the_title(); ?></option>
                                <?php } ?>
                            </select>
                            <textarea class="texteditor" id="suggested_product[<?php echo $key; ?>][content]" name="suggested_product[<?php echo $key; ?>][content]"><?php echo isset( $suggested_product['content'] )? $suggested_product['content'] : "" ?></textarea>
                        </span>
                    </span>
                </span>
            <?php } } } ?>
        </span>
        <a class="add_field_button button-secondary">+</a>
        <script type="text/javascript">
            function awoopq_product_repeater( _count = 0 ){
                return '<div class="row" data-id="'+_count+'"><a class="remove_field_button button-secondary">-</a> <input type="number" min="0" name="suggested_product['+_count+'][Minpoint]" placeholder="Min Point" value="" required> <input type="number" min="0" name="suggested_product['+_count+'][Maxpoint]" placeholder="Max Point" value="" required> <div class="result-display"><h4><i>+</i> Show Result via Custom Page</h4><div class="result-con"><input type="url" name="suggested_product['+_count+'][redirect]" placeholder="Page URL" value=""/></div></div><div class="result-display"><h4><i>+</i> Show Result by Choosing Product Result</h4><div class="result-con"><select class="select2" name="suggested_product['+_count+'][product][]" data-placeholder="Select Products" multiple><?php while ( $products->have_posts() ) { $products->the_post(); ?><option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option><?php } wp_reset_postdata(); ?></select><textarea class="texteditor" id="suggested_product['+_count+'][content]" name="suggested_product['+_count+'][content]"></textarea></div></div></div>';
            }
        </script>
    </td>
</tr>