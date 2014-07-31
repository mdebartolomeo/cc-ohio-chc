jQuery(document).ready(function($){
	/* When a toggle is clicked, show the toggle-content */
	$('.toggle-link').click(function(){
		// Traverse for some items
		var $toggleable = $( this ).parents( '.toggleable' );

		if ( $toggleable.hasClass( 'toggle-open' ) ) {
			$toggleable.removeClass( 'toggle-open' ).addClass( 'toggle-closed' );
		} else {
			$toggleable.removeClass( 'toggle-closed' ).addClass( 'toggle-open' );
		}

		return false;
	});

	// Generalized click relation
	// Trigger input should have this markup: class="has-follow-up" and data-relatedQuestion="id_of_follow_up_q" (if a radio button, all items should have the class)
	// Target question should be wrapped in a div with the class "follow-up-question" and  with the attribute: data-relatedTarget="2.2.2.2"

	// On page load, refresh element visibility.
	// Begin by creating an array of target_ids that we'll use again and again.
	// We have to treat the inputs in groups by data attribute
	// First get all the "relatedquestion" possibilities on the page
	var follow_up_questions = [];
	var target_id = '';

	$( '.has-follow-up' ).each( function() {
      	if ( target_id = $( this ).data( 'relatedquestion' ) ) {
	      	if ( $.inArray( target_id, follow_up_questions ) == -1 ) {
	        	follow_up_questions.push( target_id );
	      	}
		}
	});

	refresh_follow_up_question_visibility();

	// When a watched input changes, refresh visbility.
	$('.has-follow-up').on( 'change', function() {
		refresh_follow_up_question_visibility();
	});

	function refresh_follow_up_question_visibility(){

		// Next we iterate through each group of inputs that targets the same follow-up, if any is checked, show the follow-up question
		$.each( follow_up_questions, function( index, target ) {
			var show_target = false;

			$( '.has-follow-up[data-relatedquestion="' + target + '"]' ).each( function() {
				if ( $( this ).prop( "checked" ) ) {
					show_target = true;
				} 
			});

			if ( show_target ) {
				$('.follow-up-question[data-relatedtarget="' + target + '"]').addClass('enabled');
			} else {
				$('.follow-up-question[data-relatedtarget="' + target + '"]').removeClass('enabled');
			}
		});
	}

	// Prompt user for confirmation if she leaves a form page without saving
	$('.aha-survey input:not(:submit), .aha-survey textarea, .aha-survey select').change( function() {
		var aha_should_confirm = true;

		$('.aha-survey input:submit').on( 'click', function() {
			aha_should_confirm = false;
		});

		window.onbeforeunload = function(e) {
			if ( aha_should_confirm ) {
				return 'If you leave this page without saving, your changes will be lost.';
			}
		};
	});

},(jQuery));