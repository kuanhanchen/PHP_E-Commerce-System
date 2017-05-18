$(document).ready(function(){

	// in functions.php, get_slide_thumbnails()
	$(".image_container").click(function(){
		
		var user_input;

		// when just adding a new slide and wanna delete it, session msg should not be "slide added"
		// so need to reload page to clean session msg
		location.reload();

		return user_input = confirm("Are you sure you eant to delete this file");		

	});

});