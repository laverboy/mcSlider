<?php
	/*
	Plugin Name: Message Creative Slider
	Plugin URI: http://messagecreative.com
	Description: A slider manager and displayer using slides plugin at http://slidesjs.com/
	Author: Message:Creative Team
	Version: 1.3
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
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('mcslider', plugins_url('mcSlider.js', __FILE__), array('jquery'), '', true);
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


/* ----------------------------------------------------------------------------------------------------- */
/* ---------------------------------------- Display Menu Page ------------------------------------------ */
/* ----------------------------------------------------------------------------------------------------- */
	function mcSlider_admin(){

		global $wpdb;
		//check if page is loading after form submit or just normally
        if($_POST['mcSlider_hidden'] == 'Y'){  
	        $count = $_POST["count"]; update_option('mcSlider_count', $count);
	        $image = $_POST["mcSlider_image"]; $json = json_encode($image); update_option('mcSlider_image',$json);
	        $imageW = $_POST["mcSlider_imageWidth"]; update_option('mcSlider_imageWidth', $imageW);
	        $imageH = $_POST["mcSlider_imageHeight"]; update_option('mcSlider_imageHeight', $imageH);
	        $captions = $_POST["captions"]; update_option('mcSlider_captions', $captions);
	        $effect = $_POST["effect"]; update_option('mcSlider_effect', $effect);
	    } else {  
	        $count = get_option("mcSlider_count");
	        $imageW = get_option('mcSlider_imageWidth');
	        $imageW = (empty($imageW)) ? '600' : $imageW;
	        $imageH = get_option('mcSlider_imageHeight');
	        $json = get_option("mcSlider_image"); $image = json_decode($json, true);
	        $captions = get_option('mcSlider_captions');
	        $effect = get_option('mcSlider_effect'); 
	    } 
	    if ($captions == 'true'){ $checked = "checked";}
	    ?>
			<script>
				jQuery(document).ready(function($){
					
					<?php //if no settings have been added yet show options
					if(!$count){ ?>
						$('#mcOptionsMenu').parent().show();
						$('.showMenu').text('Hide Settings');
					<?php } ?>
					
				});
			</script>
			<style>
				.wrap {width: <?= $imageW; ?>px;}
				a.showMenu { margin-left: 20px;font-size: 12px;}
				.sliderHeader {
    				background-color: #EEE;
    				padding: 10px 5px;
    				margin: 0;
				}
				.sliderHeader:hover {
    				background-color: #E0E0E0;
				}
				.caption, .add_link {width: <?= $imageW; ?>px;}
				#submit { text-align:right; }
				<?php if($captions != 'true'){?>
				.captions {
					display: none;
				}
				<?php } ?>				
			</style>
		
			<div class="wrap">
				<h2><span style="color:#A7CD54;">Message:<strong>Creative</strong></span> Slider Manager <a href="#" onclick="return false" class="showMenu">Show Settings</a></h2>
				<form name="mcSlider_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
					<div>	
						<table id="mcOptionsMenu" class="form-table" style="margin-bottom:20px;">
							<input type="hidden" name="mcSlider_hidden" value="Y"> 
							<tr>
								<th scope="row"><label for="count">How many slides would you like:</label></th>
								<td><input style="width:40px;" type="text" name="count" value="<?php echo $count ?>"> <em>Press Return after</em></p></td>
							</tr>
							<tr>
								<th><label>What size would you like your images:</label> </th>
								<td>
									<input style="width:40px;" type="text" name="mcSlider_imageWidth" value="<?php echo $imageW ?>">px
									&nbsp;x&nbsp; 
									<input style="width:40px;" type="text" name="mcSlider_imageHeight" value="<?php echo $imageH ?>">px 
									&nbsp;<em>Press Return after</em><br />
									<em>Don't forget to crop your images to what ever size you choose here!</em> 
								</td>
							</tr>
							<tr>
								<th><label for="captions">Do you want captions:</label></th>
								<td><input type="checkbox" name="captions" value="true" <?= $checked; ?>></td>
							</tr>
							<tr>
								<th><label for="effect">Which effect would you like:</label></th>
								<td>
									<select name="effect">
										<option value="slide" <?php if($effect == "slide") echo "selected='selected'" ?>>Slide</option>
										<option value="fade"<?php if($effect == "fade") echo "selected='selected'" ?>>Fade</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<input class="button-primary" type="submit" name="Submit" value="Update Options" style="margin-top:10px;" />
								</td>
							</tr>
						</table>
					</div><!-- end mcOptionsMenus -->
						
					<?php sort($image); ?>
					
					<ul class="ui-sortable">
					<?php for ($i=0; $i <= $count-1; $i++ ) { ?>
						<li>
							<input class="order" type="hidden" name="mcSlider_image[<?= $i; ?>][order]" value="<?= $image[$i]['order'] ?: $i + 1; ?>">
							<h4 class="sliderHeader">Slider Image <?= $i+1; ?></h4>
							<div class="slider">
								<p>
									<input class="upload_image" type="text" name="mcSlider_image[<?= $i; ?>][image]" value="<?php echo $image[$i]['image']; ?>" style="width:<?= $imageW - 100 ?>px;">
									<input class="upload_image_button" type="button" value="Upload Image"><br />
									Enter a URL or upload an image
								</p>
								<p class="sliderPreview" style="background:#aeaeae;width:<?= $imageW ?>px;height:<?= $imageH ?>px">
									<img src="<?= plugins_url('timthumb.php', __FILE__); ?>?src=<?php echo $image[$i]['image']; ?>&amp;w=<?= $imageW ?>&amp;h=<?= $imageH ?>" width="<?= $imageW ?>" height="<?= $imageH ?>">
								</p>
								<p class="captions">
								    <strong>Caption</strong><br />
								    <textarea class="caption" name="mcSlider_image[<?= $i; ?>][text]" rows="1" cols="30" style=""><?php echo $image[$i]['text']; ?></textarea>
								</p>
								<p>
								    <strong>Link</strong><br />
									<input class="add_link" type="text" name="mcSlider_image[<?= $i; ?>][link]" value="<?php echo $image[$i]['link']; ?>"><br />
									Add a Link for this image (<em>Needs full URL including http://</em>)
								</p>
							</div>
						</li>
					<?php } ?>
					</ul>
						<p class="help" style="float:left;">(Drag to re-arrange)</p>
						<p id="submit" class="submit" >
	
							<input class="button-primary" type="submit" name="Submit" value="Update Options" style="margin-top:10px;" />
						</p>
					
				</form>
			</div><!-- end wrap -->
	<?php } //end function mcSlider_admin()
	
/* ----------------------------------------------------------------------------------------------------- */
/* ---------------------------------------- In-Page Function ------------------------------------------- */
/* ----------------------------------------------------------------------------------------------------- */
	function mcSlider(){
    	wp_enqueue_script('slides', plugins_url('slider/jquery.flexslider-min.js', __FILE__), array('jquery'), '', true);
    	wp_enqueue_style('mcSlider-sliderstyle', plugins_url('slider/flexslider.css', __FILE__));	
    	
		$json = get_option('mcSlider_image'); $slidesArray = json_decode($json, true);
		$width = get_option('mcSlider_imageWidth');
        $height = get_option('mcSlider_imageHeight');
        $captions = get_option('mcSlider_captions');
        $effect = get_option('mcSlider_effect');
        $slideCount = 0;
        foreach($slidesArray as $slide){
        	if($slide['image']){$slideCount++;}
        }
        ?>
        <style>
        	ul.slides li {list-style: none;margin: 0;}
        </style>
        <div class="flexslider" style="margin-bottom:18px;"><!-- a bit of unnecessary markup for use by the slides plugin unfortunately -->
	        <ul class="slides">
	        	<?php foreach($slidesArray as $slide){ 
	        		if ($slide['image']){ $slideCount++;?>
	        			<li>
	        				<?php if($slide['link']) echo("<a href='". $slide['link']. "'>"); ?>
					        	<img src="<?= plugins_url('timthumb.php', __FILE__); ?>?src=<?= $slide['image']; ?>&amp;w=<?= $width; ?>&amp;h=<?= $height; ?>">
					        	<?php if($captions && !empty($slide['text'])){ ?><p class="flex-caption"><?= $slide['text']; ?></p><?php } ?>
					        <?php if($slide['link']) echo("</a>"); ?>
				        </li>   
				    <?php }//end if
				 }//end foreach ?>
		    </ul>
		</div>
		<script>
			jQuery(document).ready(function($) {
	
				var args = {
				    directionNav: false,
				    animation: "slide",
				    slideshowSpeed: 3000,
				    animationDuration: 600,
				    pauseOnHover: true
				};
				<?php if($effect == 'fade') echo "args.animation = 'fade';"; ?>
				$('.flexslider').flexslider(args);
			
			});
		</script>
	    
	    <?php
	}//end function mcSlider()
	
