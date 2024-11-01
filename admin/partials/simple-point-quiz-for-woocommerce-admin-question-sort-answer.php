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
<?php wp_nonce_field( 'question_answer_field_sort', 'question_answer_field_sort_nonce' ); ?>
<div class="field">
    <label for="field_sorting">Sort Index</label>
    <input type="number" name="field_sorting" placeholder="" value="<?php echo $field_sorting; ?>">
</div>