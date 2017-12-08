;(function($){

jQuery(document).ready(function(){

	var t = jQuery('.secedit.editbutton_section');
	t.toArray().forEach(function(el){
		var v = jQuery(el);
		var p = v.prev().prev();
		v.detach().insertBefore(p);
	});
	
});

})(jQuery);
