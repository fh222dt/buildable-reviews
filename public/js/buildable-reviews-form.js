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


		//slider for 1-5 radio button q:s
		// $('#1').slider({
		//   orientation: 'horizontal',
		// //   range:    false,
		//   min:		0,
		//   max:		5,
		//   value:	3
		// });

		$(".br-answer-options-slider").each(function() {
		    var radios = $(this).find(":radio").hide();
		    $('<div class="flat-slider"></div>').slider({
			      min: parseInt(radios.first().val(), 10),
			      max: parseInt(radios.last().val(), 10),
				  value: 3,
			      slide: function(event, ui) {
			        radios.filter("[value=" + ui.value + "]").click();
			      }
		    }).appendTo(this);
		});














    });

})( jQuery );