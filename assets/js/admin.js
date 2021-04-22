/* zorem_snackbar jquery */
(function( $ ){
$.fn.zorem_snackbar = function(msg) {
var zorem_snackbar = $("<div></div>").addClass('mfata_snackbar show_snackbar').text( msg );
$("body").append(zorem_snackbar);

setTimeout(function(){ zorem_snackbar.remove(); }, 3000);

return this;
};
})( jQuery );

/*ajax call for settings tab form save*/
jQuery(document).on("submit", "#mfata_settings_form", function(){
	'use strict';
	var spinner = jQuery(this).find(".spinner").addClass("active");
	var form = jQuery(this);
	jQuery.ajax({
		url: ajaxurl,
		data: form.serialize(),
		type: 'POST',		
		success: function(response) {
			spinner.removeClass("active");
			jQuery(document).zorem_snackbar( 'Data migrated successfully.' );	
		},
		error: function(response) {
			console.log(response);
			spinner.removeClass("active");
		}
	});
	return false;
});