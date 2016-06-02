(function( $ ) {
	'use strict';

	$( window ).load(function() {
		//toggle buttons for review, results, summary
		$('#br-review-button').click(function(e) {
			e.preventDefault();
			$('#review-summary').hide('slow');
			$('#list-all-reviews').hide('slow');

			$('#review-form').toggle('slow', function() {


			});
		});

		$('#br-view-all-button').click(function(e) {
			e.preventDefault();
			$('#review-form').hide('slow');
			$('#review-summary').hide('slow');

			$('#list-all-reviews').toggle('slow', function() {

			});
		});

		$('#br-summary-button').click(function(e) {
			e.preventDefault();
			$('#review-form').hide('slow');
			$('#list-all-reviews').hide('slow');

			$('#review-summary').toggle('slow', function() {

			});
		});

		//modal reporting a review with bad content
		var $id, $review;

		$('.br-report-review').click(function(e) {
				e.preventDefault();
				$('#br-report-review-modal').modal();
				$review = $(e.target).closest('.br-review');
				$id = parseInt($review.data('review'));
		});

		//confirm modal reporting bad content
		$('#br-confirm-report-review').click(function(e) {
			var $this = $(this);

			$.ajax({
				url: buildableReviews.ajax_url,
				type: 'POST',
				data: {
					'action': 'report_content',
					'report_content': $id
				},
				success: function(data) {
					console.log(data);
					$review.hide();
					$('#br-report-review-modal').modal('hide');
				},
				error: function(error){
					console.log(error);
				}

			});
		});




	});

})( jQuery );