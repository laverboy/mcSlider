jQuery(document).ready(function($){
	$('#slides').slides({
		play: 5000,
		pause: 2500,
		slideSpeed: 800,
		hoverPause: true,
		animationStart: function(current){
			console.log(current);
			$('.caption').animate({
				opacity: 0
			}, 500);		
		},
		animationComplete: function(current){
			$('.caption').animate({
				opacity: 1
			}, 700);
		}
	});
});