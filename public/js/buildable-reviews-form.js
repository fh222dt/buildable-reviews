//review-form validation, submitting & other stuff
(function( $ ) {

	$( window ).load(function() {

        $('#br-review-form').validate({
            submitHandler: function(form) {

    			$.ajax({
    				url: buildableReviews.ajax_url,
    				type: 'POST',
    				data: $(form).serialize(),
    				success: function(data) {
                        //console.log(data);
                        $('#br-review-form').slideUp("slow", function() {
                          $(this).before('<p>Tack för din recension! Efter kontroll kommer vi snart att publicera den.</p>');
                         });
    				},
    				error: function(error){
    					//console.log(error);
    				}

    			});
                return false;   //preventDefault

            }
        });

    });

})( jQuery );