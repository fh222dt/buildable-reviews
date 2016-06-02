//review-form validation, submitting & other stuff
(function( $ ) {
	'use strict';

	$( window ).load(function() {

        $('#br-review-form').validate({
            submitHandler: function(form) {
                form.submit();
                $('#br-review-form').slideUp("fast", function() {
                  $(this).before('<p>Tack för din recension! Efter kontroll kommer vi snart att publicera den.</p>');
                 });
            }
        });

    });

})( jQuery );