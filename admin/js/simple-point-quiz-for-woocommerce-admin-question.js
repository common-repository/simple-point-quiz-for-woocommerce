(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function(){
		$('.init .add_field_button').on('click', function(){
			var count = 1,
				row = '';

			if( $('.other-answers').length > 0 ){
				count = ( $('.other-answers .row:last-child').length > 0 )? parseInt($('.other-answers .row:last-child').attr('data-id')) + 1 : count;

				row = '<div class="row" data-id="'+count+'"><a class="remove_field_button button-secondary">Remove Row</a> <input type="text" required name="answer_fields['+count+'][field]" placeholder="Answer"> <input type="number" required name="answer_fields['+count+'][point]" placeholder="Points"></div>';

				$('.other-answers').append(row);
			}
		});

		$(document).on('click', '.other-answers .remove_field_button', function(){
			$(this).parents('.row').remove();
		});
	});

})( jQuery );
