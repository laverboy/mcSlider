<?php
    /*
    Plugin Name: Message Creative Slider
    Plugin URI: http://messagecreative.com
    Description: A slider manager and displayer using slides plugin at http://www.woothemes.com/flexslider/
    Author: Matthew Laver
    Version: 1.4
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
    function mcSlider_admin_actions(){
        add_submenu_page('upload.php', 'MessageCreative Slider Manager', 'Slider Manager', 'manage_options', 'mc_slider_manager', 'mcSlider_admin');
    }
    add_action('admin_menu','mcSlider_admin_actions');
    


/* ----------------------------------------------------------------------------------------------------- */
/* ---------------------------------------- Display Menu Page ------------------------------------------ */
/* ----------------------------------------------------------------------------------------------------- */
    function mcSlider_admin(){

        global $wpdb;
        //check if page is loading after form submit or just normally
        if($_POST['mcSlider_hidden'] == 'Y'){  
            $image    = $_POST["mcSlider_image"]; $json = json_encode($image); update_option('mcSlider_image',$json);
            $count    = count($image);
            $imageW   = $_POST["mcSlider_imageWidth"]; update_option('mcSlider_imageWidth', $imageW);
            $imageH   = $_POST["mcSlider_imageHeight"]; update_option('mcSlider_imageHeight', $imageH);
            $captions = $_POST["captions"]; update_option('mcSlider_captions', $captions);
            $effect   = $_POST["effect"]; update_option('mcSlider_effect', $effect);
        } else {  
            $imageW   = get_option('mcSlider_imageWidth');
            $imageW   = (empty($imageW)) ? '960' : $imageW;
            $imageH   = get_option('mcSlider_imageHeight');
            $imageH   = (empty($imageH) ? '380' : $imageH);
            $json     = get_option("mcSlider_image"); $image = json_decode($json, true);
            $count    = (count($image) == 0) ? 1 : count($image);
            $captions = get_option('mcSlider_captions');
            $effect   = get_option('mcSlider_effect'); 
        } 
        if ($captions == 'true'){ $checked = "checked";}
    
        include_once dirname(__FILE__) . '/sliderAdminTemplate.php';

    }
    
/* ----------------------------------------------------------------------------------------------------- */
/* ---------------------------------------- In-Page Function ------------------------------------------- */
/* ----------------------------------------------------------------------------------------------------- */
    function mcSlider(){
        wp_enqueue_script('slides', plugins_url('slider/jquery.flexslider-min.js', __FILE__), array('jquery'));
        wp_enqueue_style('mcSlider-sliderstyle', plugins_url('slider/flexslider.css', __FILE__));   
        
        $json        = get_option('mcSlider_image'); 
        $slidesArray = json_decode($json, true);
        $width       = get_option('mcSlider_imageWidth');
        $height      = get_option('mcSlider_imageHeight');
        $captions    = get_option('mcSlider_captions');
        $effect      = get_option('mcSlider_effect');
        $slideCount  = 0;
        foreach($slidesArray as $slide){
            if($slide['image']){ $slideCount++; }
        }
        ?>
        <style>
            ul.slides li {list-style: none;margin: 0;}
        </style>
        <div class="flexslider" data-effect="<?= $effect; ?>" style="margin-bottom:18px;">
            <ul class="slides">
                <?php foreach($slidesArray as $slide): ?> 
                    <?php if ($slide['image']): $slideCount++; ?>
                        <li>
                            <?php if($slide['link']) echo("<a href='". $slide['link']. "'>"); ?>
                                <img src="<?= plugins_url('timthumb.php', __FILE__); ?>?src=<?= $slide['image']; ?>&amp;w=<?= $width; ?>&amp;h=<?= $height; ?>">
                                <?php if($captions && !empty($slide['text'])){ ?><p class="flex-caption"><?= $slide['text']; ?></p><?php } ?>
                            <?php if($slide['link']) echo("</a>"); ?>
                        </li>   
                    <?php endif; ?>
                 <?php endforeach; ?>
            </ul>
        </div>
        <script>
            jQuery(document).ready(function($) {
    
                var effect = $('.flexslider').attr('data-effect'),
                    args = {
                        directionNav: false,
                        animation: effect,
                        slideshowSpeed: 3000,
                        animationDuration: 600,
                        pauseOnHover: true
                    };
                $('.flexslider').flexslider(args);
            
            });
        </script>
        
        <?php
    }
    
