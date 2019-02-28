	jQuery(document).ready(function(){
		jQuery.metadata.setType("attr","validate");
	});
	jQuery(document).ready(function(){   
		jQuery(".formvalidate").validate({
	        errorElement: "span",
	        errorClass: "errormsg",
	        success:"valid"
	    });   
	});