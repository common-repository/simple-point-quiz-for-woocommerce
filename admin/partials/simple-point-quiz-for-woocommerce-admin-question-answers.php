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
<?php wp_nonce_field( 'question_answer_fields', 'question_answer_fields_nonce' ); ?>
<div class="input_fields_wrap">
    <div class="init">
    	<div class="row">
    		<a class="add_field_button button-secondary">Add New Row</a> <input type="text" name="answer_fields[0][field]" placeholder="Answer" value="<?php echo isset($fields[0]['field'])? $fields[0]['field'] : ""; ?>" required> <input type="number" name="answer_fields[0][point]" placeholder="Points" value="<?php echo isset($fields[0]['point'])? $fields[0]['point'] : ""; ?>" required>
    	</div>
    </div>
    <div class="other-answers">
    	<?php if( isset($_GET['action']) && $_GET['action'] == 'edit' && count( $fields ) > 1 ){ foreach ($fields as $key => $field) { if( $key != 0 ) { ?>
    		<div class="row" data-id="<?php echo $key; ?>"><a class="remove_field_button button-secondary">Remove Row</a> 
    			<input type="text" required name="answer_fields[<?php echo $key; ?>][field]" placeholder="Answer" value="<?php echo $field['field'] ?>"> 
    			<input type="number" required name="answer_fields[<?php echo $key; ?>][point]" placeholder="Points" value="<?php echo $field['point'] ?>">
    		</div>
		<?php } } } ?>
    </div>
</div>