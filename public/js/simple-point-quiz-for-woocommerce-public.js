(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
		var awoopq_con = $('#awoopq-quiz-container'),
			last_quizcon = $('#awoopq-quiz-container .awoopq-quizcon:last-of-type').attr('id'),
			last_result = {};

		$('.awoopq-btn-next').on('click', function(){
			let current = $(this).parents('.awoopq-quiz-question'),
				quizcon = current.parents('.awoopq-quizcon');
				quizcon.find('.awoopq-alert').text('').hide();

			if( current.find('[name]:checked').length < 1 ){
				quizcon.find('.awoopq-alert').addClass('has-danger').text('Please Choose an Answer.').show();
				return false;
			}

			current.hide();
			current.next().show();
		});

		$('.awoopq-btn-prev').on('click', function(){
			let current = $(this).parents('.awoopq-quiz-question'),
				quizcon = current.parents('.awoopq-quizcon'),
				multiple = quizcon.find('[name="multiple"]').val(),
				current_question_first = current.hasClass('first'),
				quizcon_first_quiz = quizcon.hasClass('first');
				quizcon.find('.awoopq-alert').text('').hide();

			if( multiple == 1 && current_question_first == true && quizcon_first_quiz == false ){
				quizcon.hide();
				quizcon.prev().show();
			}else{
				current.hide();
				current.prev().show();
			}
		});

		$('.awoopq-btn-submit').on('click', function(){
			let current = $(this).parents('.awoopq-quiz-question'),
				quizcon = current.parents('.awoopq-quizcon'),
				other_con = quizcon.find('.awoopq-quiz-details'),
				loader = quizcon.find('.loader');
				loader.hide();

				other_con.removeAttr('style');
				quizcon.find('.awoopq-alert').text('').hide();

			if( current.find('[name]:checked').length < 1 ){
				quizcon.find('.awoopq-alert').addClass('has-danger').text('Please Choose an Answer.').show();
				return false;
			}else{
				other_con.css({ 'visibility':'hidden' });
				loader.show();

				let form = quizcon.find('form').serialize();
				$.ajax({
					type : "POST",
					dataType : "json",
					url : alf_woocommerce_point_quiz_awoopq_ajax.ajax_url,
					data : form,
					success: function(response) {
						if(!response.error) {
							if(response.redirect != null && response.multiple == false){
								window.location = response.redirect;
							}else{
								
								if(response.multiple == true){
									
									if( response.redirect_link != null ){
										last_result[quizcon.attr('id')] = [ quizcon.find('.awoopq-quiz-init').html(), response.redirect_link ];
									}else{
										last_result[quizcon.attr('id')] = [ quizcon.find('.awoopq-quiz-init').html(), response.suggested_products ];
									}
									
									if( last_quizcon == quizcon.attr('id') ){
										build_multiple_result(last_result);
										other_con.removeAttr('style');
										loader.hide();
									}else{
										quizcon.hide().next('.awoopq-quizcon').show();
										other_con.removeAttr('style');
										loader.hide();
									}
								}else{
									quizcon.find('form').html(response.suggested_products);
									other_con.removeAttr('style');
									loader.hide();
								}
							}
						} else {
							quizcon.find('form').remove();
							quizcon.find('.awoopq-alert').addClass('has-danger').text('Unknown Error Occured. Please contact Administrator.').show();
							loader.hide();
						}
					},
					error: function(){
						quizcon.find('form').remove();
						quizcon.find('.awoopq-alert').addClass('has-danger').text('Unknown Error Occured. Please contact Administrator.').show();
						loader.hide();
					}
				});

			}

			return false;
		});

		function build_multiple_result(last_result){
			let _return = ''; 
			$.each(last_result, function(i,e){
				_return += '<div id="'+i+'" class="awoopq-quizcon" style="display:block;"><div class="awoopq-quiz-details">';
				_return += '<div class="awoopq-quiz-init">'+e[0]+'</div><div class="awoopq-quiz-form">'+e[1]+'</div>';
				_return += '</div></div>';
			});

			awoopq_con.html(_return);
		}
	}); 

})( jQuery );
