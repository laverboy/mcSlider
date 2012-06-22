jQuery(document).ready(function($){
	//wrap the slide sections of the admin menu in a div.slider
	$('div.slider').not(':first').slideUp();
	
	//make the div.sliders into a clickable accordian
	$('h4.sliderHeader').css('cursor', 'pointer').click(function(){
		$(this).next('div.slider').slideToggle().siblings('div.slider:visible').slideUp();
		return false;
	});
	
	$('#mcOptionsMenu').parent().hide();
	$('.showMenu').click(function(){
		$('#mcOptionsMenu').parent().slideToggle();
		$('.showMenu').text($('.showMenu').text() == 'Show Settings' ? 'Hide Settings' : 'Show Settings');		
	});
	
	
	//intercept wordpress 'image uploader' and return the clicked image into our url field
	var imageField;
	$('.upload_image_button').click(function() {
	 formfield = $(this).prev('input').attr('name');
	 tb_show('Add Image to Slider', 'media-upload.php?type=image&amp;TB_iframe=true');
	 imageField = $(this).prev('input');
	 return false;
	});
	window.send_to_editor = function(html) {
	 imgurl = $(html).attr('href');
	 $(imageField).val(imgurl);
	 tb_remove();
	}
	
	$('ul.ui-sortable').sortable({
		update: function (event, ui) {
			$('ul.ui-sortable li').each(function (index) {
				$(this).find('.order').val(index+1);
			});
		}
	});
});
