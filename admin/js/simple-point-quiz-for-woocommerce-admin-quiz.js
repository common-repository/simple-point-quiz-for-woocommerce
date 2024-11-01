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
		tinymce.init({
			selector: '.texteditor',
		    menubar: false,
		    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
		    noneditable_noneditable_class: "mceNonEditable",
		});
		$(".select2").select2();

		$('.add_field_button').on('click', function(){
			var count = 1,
				row = '';

			if( $('.other-answers').length > 0 ){
				count = ( $('.other-answers .row:last-child').length > 0 )? parseInt($('.other-answers .row:last-child').attr('data-id')) + 1 : count;
				
				row = awoopq_product_repeater(count);
				$('.other-answers').append(row);

				tinymce.init({
					selector: '.texteditor',
				    menubar: false,
				    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
				    noneditable_noneditable_class: "mceNonEditable",
				});
				$(".select2").select2({ placeholder: "Select Products" });
			}
		});

		$(document).on('click', '.other-answers .remove_field_button', function(){
			$(this).parents('.row').remove();
		});

		$(document).on('click', '.result-display > h4', function(){
			$(this).siblings('.result-con').slideToggle();
		});

		if( $('#addtag').length > 0 ){
			$('#addtag #submit').on('click', function(){
				if( $('#addtag input[aria-required="true"]').length > 0 ){
					$.each($('#addtag input[aria-required="true"]'), function(i,e){
						if( $(e).val() && $(e).val().replace(/\s+/g, ' ').trim() != "" ){
							$('#addtag .input_fields_wrap [name*="suggested_product"]').val('');
							$('#addtag .other-answers').html('');
							$('#addtag .select2').val('').trigger('change');
						}
					});
				}

				return false;
			});
		}
	});

})( jQuery );
