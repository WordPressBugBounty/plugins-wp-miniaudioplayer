/* global tinymce */
tinymce.PluginManager.add('wpmbmap', function( editor ) {

	setTimeout(function(){
		if(!tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden() ){
			jQuery("#add-map").css("opacity",.5);
		} else {
			jQuery("#add-map").css("opacity",1);
		}
	},400);

	editor.on( 'change', function( event ) {});

	editor.on( 'click', function( event ) {
		node = event.target;
		if(jQuery(node).is("a[href *= '.mp3']")) {
			jQuery("#add-map").removeClass("disabled");
		} else {
			jQuery("#add-map").addClass("disabled");
		}
	});
});
