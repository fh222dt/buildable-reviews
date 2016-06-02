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

		//Pagination tutorial here: http://www.bilalakil.me/simplepagination/ (paginates list of all review results)
		// Grab whatever we need to paginate
	    var pageParts = $(".br-review");

	    // How many parts do we have?
	    var numPages = pageParts.length;
	    // How many parts do we want per page?
	    var perPage = 5;

	    // When the document loads we're on page 1
	    // So to start with... hide everything else
	    pageParts.slice(perPage).hide();
	    // Apply simplePagination to our placeholder
	    $("#results-pagination").pagination({
	        items: numPages,
	        itemsOnPage: perPage,
	        cssStyle: "light-theme",
	        // We implement the actual pagination
	        //   in this next function. It runs on
	        //   the event that a user changes page
	        onPageClick: function(pageNum) {
	            // Which page parts do we show?
	            var start = perPage * (pageNum - 1);
	            var end = start + perPage;

	            // First hide all page parts
	            // Then show those just for our page
	            pageParts.hide()
	                     .slice(start, end).show();
	        }
	    });




	});

})( jQuery );