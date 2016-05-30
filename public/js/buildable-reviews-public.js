(function( $ ) {
	'use strict';

	$( window ).load(function() {

		$('#br-review-button').click(function(e) {
			e.preventDefault();
			$('#review-form').toggle('slow', function() {

			});
		});

		$('#br-view-all-button').click(function(e) {
			e.preventDefault();
			$('#list-all-reviews').toggle('slow', function() {

			});
		});

		$('#br-summary-button').click(function(e) {
			e.preventDefault();
			$('#review-summary').toggle('slow', function() {

			});
		});

	});

})( jQuery );