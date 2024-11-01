<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://bitbucket.org/allouise/simple-point-quiz-for-woocommerce
 * @since      1.0.0
 *
 * @package    Hww_Beautrip
 * @subpackage Hww_Beautrip/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2 class="text-center"><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <?php
    if( count( $terms ) <= 1 ){ ?>
        <div class="notice notice-warning">
            <p><strong><?php echo __( 'Notice', $this->plugin_name ) ?>:</strong> <?php echo __( 'Please create atleast 2 Quizzes to allow linking.', $this->plugin_name ) ?> <a href="<?php echo admin_url('edit-tags.php?taxonomy=awoopq-quizzes&post_type=awoopq-questions'); ?>"><?php echo __( 'Click to View Quizzes', $this->plugin_name ) ?></a></p>
        </div>
    <?php }else{ settings_errors(); ?>
        <div id="awoopq-quiz-linking">
            <div id="awoopq-build-link">
                <ul id="sortable">

                </ul>
                <button type="button" style="display: none;" id="awoopqgetShortcode">Generate Shortcode</button>
                <label id="awoopq_shortcode" style="display: none;">Copy Shortcode here <input type="text" readonly value="" style="width: 370px; max-width: 100%;" /></label>
            </div>
            <div id="awoopq-choose-quizzes">
                <h3>Choose Quizzes to Link</h3>
                <?php foreach ($terms as $quiz) { ?>
                    <label>
                        <input type="checkbox" name="quizzes[]" value="<?php echo $quiz->term_id; ?>"> <span><?php echo $quiz->name; ?></span>
                    </label>
                <?php } ?>
            </div>
        </div>
        <?php /*<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>*/ ?>
        <script type="text/javascript">
            jQuery( function($){
                $( "#sortable" ).sortable({
                    update: function( ) {
                        create_shortcode();
                    }
                });
                $( "#sortable" ).disableSelection();

                $('#awoopq-choose-quizzes [name="quizzes[]"]').on('change', function(){
                    if( $(this).prop('checked') ){
                        $('#awoopq-build-link #sortable').prepend('<li id="quiz-'+ $(this).val() +'" data-id="'+ $(this).val() +'" class="ui-state-default"><span class="icon">&#8597;</span>'+ $(this).siblings('span').text() +'<input type="checkbox" checked name="quizzes-sort[]" value="'+ $(this).val() +'"></li>');

                        $('#awoopq-build-link #awoopqgetShortcode').removeAttr('style');
                        create_shortcode();
                    }else{
                        $('#awoopq-build-link #sortable').find('#quiz-'+ $(this).val()).remove();
                    }
                });


                function create_shortcode(){
                    let sorted = $('[name="quizzes-sort[]"]').map(function(){
                        return $(this).val();
                    }).get();
                    if( sorted.length > 0 ){
                        $('#awoopq_shortcode input').val('[awoopq_quiz ids="'+ sorted.join() +'"]').parents('label').show();
                    }else{
                        $('#awoopq_shortcode input').val('').parents('label').hide();
                    }
                }
            });
        </script>

    <?php } ?>
</div>