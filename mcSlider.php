<?php
	/*
	Plugin Name: Message Creative Slider
	Plugin URI: http://messagecreative.com
	Description: A slider manager and displayer
	Author: Message:Creative Team
	Version: 1.0
	*/
	
	// Make sure we don't expose any info if called directly
	if ( ! function_exists( 'add_action' ) ) {
		_e( "Hi there! I'm just a plugin, not much I can do when called directly." );
		exit;
	}
	
	//load Admin scripts and styles
	function mcSlider_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery');
    }
	function mcSlider_styles(){
		wp_enqueue_style('thickbox');
	}	
	if (isset($_GET['page']) && $_GET['page'] == 'mc_slider_manager'){
		add_action('admin_print_scripts', 'mcSlider_scripts');
		add_action('admin_print_styles', 'mcSlider_styles');
	}
	
	//add plugin to menu
	add_action('admin_menu','mcSlider_admin_actions');
	function mcSlider_admin_actions(){
		add_submenu_page('upload.php', 'MessageCreative Slider Manager', 'Slider Manager', 'manage_options', 'mc_slider_manager', 'mcSlider_admin');
	}
	
	//display menu page
	function mcSlider_admin(){
		//check if page is loading after form submit or just normally
        if($_POST['mcSlider_hidden'] == 'Y'){  
	        $count = $_POST["count"]; update_option('mcSlider_count', $count);
	        $image = $_POST["mcSlider_image"]; update_option('mcSlider_image', serialize($image));
	    } else {  
	        $count = get_option("mcSlider_count");
	        $image = unserialize(get_option("mcSlider_image"));
	    } ?>
			<script>
				jQuery(document).ready(function(){
					console.log('<?php echo(__FILE__); ?>');
					jQuery('h4.sliderHeader').css('margin','0').each(function(){
						jQuery(this).nextUntil('h4.sliderHeader, #submit').wrapAll('<div class="slider" />')
					});					
					jQuery('div.slider').not(':first').slideUp();
					jQuery('h4.sliderHeader').css('cursor', 'pointer').click(function(){
						jQuery(this).next('div.slider').slideToggle().siblings('div.slider:visible').slideUp();
						return false;
					});
					var imageField;
					jQuery('.upload_image_button').click(function() {
					 formfield = jQuery(this).prev('input').attr('name');
					 tb_show('Add Image to Slider', 'media-upload.php?type=image&amp;TB_iframe=true');
					 imageField = jQuery(this).prev('input');
					 console.log(imageField);
					 return false;
					});
					window.send_to_editor = function(html) {
					 imgurl = jQuery('img',html).attr('src');
					 jQuery(imageField).val(imgurl);
					 tb_remove();
					}
				});
			</script>
			
			<style>
				.sliderHeader:hover {
    				background-color: #E0E0E0;
				}
				.sliderHeader {
    				width: 791px;
    				background-color: #EEE;
    				padding: 10px 5px;
				}
			</style>
		
			<div class="wrap">
				<h2>Message:Creative Slider Manager</h2>
				<p><em>Please remember to crop your photos to 940x320 pixels before you upload them and to select full size in the upload screen.</em></p>
				<form name="mcSlider_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
					<input type="hidden" name="mcSlider_hidden" value="Y"> 
					<p><label for="count">How many slides would you like:</label> <input type="text" name="count" value="<?php echo $count ?>"> <em>Press Return after</em> </p>
					<?php for ($i=1; $i <= $count; $i++ ) {?>
						<h4 class="sliderHeader">Slider Image <?= $i; ?></h4>
						<p><input class="upload_image" type="text" name="mcSlider_image[<?= $i; ?>][image]" value="<?php echo $image[$i]['image']; ?>" style="width:700px;">
						<input class="upload_image_button" type="button" value="Upload Image"><br />Enter an URL or upload an image</p>
						<textarea name="mcSlider_image[<?= $i; ?>][text]" rows="11" cols="30" style="float:left;width:190px;"><?php echo $image[$i]['text']; ?></textarea>
						<p style="margin-left:200px;background:#aeaeae;width:600px;height:205px">
							<img src="<?php echo $image[$i]['image']; ?>" width="600" height="205">
						</p>
					<?php } ?>
				
					<p id="submit" style="width:801px;text-align:right;"><input type="submit" name="Submit" value="Update Options" style="margin-top:10px;" /></p>
				</form>
			</div>
	<?php } 
	
	//load Page scripts and styles for display function
	add_action('wp_print_scripts', 'mcSlider_script_load');
	function mcSlider_script_load(){
		if (!is_admin()){
			wp_enqueue_script('slides', plugins_url('slides.min.jquery.js', __FILE__), array('jquery'), '', true);
			wp_enqueue_script('mcSliderjs', plugins_url('mcSlider.js', __FILE__), array('jquery'), '', true);	
		}
	}
	
	//function to print out slides where you want them in your theme
	function mcSlider($width, $height){
		
		$slidesArray = unserialize(get_option('mcSlider_image'));
        ?>
        <style>
        	.slides_container {
				width: <?= $width ?>px;
				height: <?= $height ?>px;
			}
			.slides_container div {
				width: <?= $width ?>px;
				height: <?= $height ?>px;
				display: block;
			}
			.slides_container div span {
				position: absolute;
				top: 0;
				left: 0;
				background: rgba(174, 174, 174, 0.7);
				height: 300px;
				width: 215px;
				padding: 10px;
				border-top-left-radius: 5px;
			}
			ul.pagination {
				margin: 0;
				padding: 0;
				text-align: center;
				position: absolute;
				z-index: 50;
				left: 48%;
				margin-top: 9px;
			}
			ul.pagination li {
				float: left;
				margin-right: 3px;
			}
			.pagination li a {
				display:block;
				width:12px;
				height:0;
				padding-top:12px;
				background-image:url(<?= plugins_url('pagination.png', __FILE__) ?>);
				background-position:0 0;
				float:left;
				overflow:hidden;
			}
			.pagination li.current a {
				background-position:0 -12px;
			}
        </style>
        <div id="slides"><!-- a bit of unnecessary markup for use by the slides plugin unfortunately -->
	        <div class="slides_container">
	        	<?php foreach($slidesArray as $slide){ 
	        		if ($slide['image']){ ?>
						<div>
				        	<img src="<?= $slide['image']; ?>">
				        	<span class="caption"><?= $slide['text']; ?></span>
				        </div>   
				    <?php }//end if
				 }//end foreach ?>
		    </div>
		</div>
	    
	    <?php
	}
	