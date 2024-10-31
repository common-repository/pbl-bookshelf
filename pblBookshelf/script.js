jQuery(document).ready(function(){
	easing = 'easeInOutSine';
	jQuery('#pblBookshelf-daddy').hover(
	function () {
		jQuery('#pblBookshelf-thumb').stop();
		jQuery('#pblBookshelf-desc').stop();
		jQuery('#pblBookshelf-thumb').animate({'left':'-54px'},500,easing);
		jQuery('#pblBookshelf-desc').animate({'left':'115px','opacity':1},500,easing);
	},
	function () { 
		jQuery('#pblBookshelf-thumb').stop();
		jQuery('#pblBookshelf-desc').stop();
		jQuery('#pblBookshelf-thumb').animate({'left':'0px'},500,easing);
		jQuery('#pblBookshelf-desc').animate({'left':'55px','opacity':0},500,easing);
	}
	);
	
});