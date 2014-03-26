<script type="text/template" id="slideTemplate">
    <li>
        <input class="order" type="hidden" name="mcSlider_image[%id%][order]" value="%id1%">
        <a href="#" class="minus" alt="remove this slide">-</a>
        <h4 class="sliderHeader" style="cursor:pointer;">Slider Image <span class="order">%id1%</span></h4>
        <div class="slider">
            <p>
                <input class="upload_image" type="text" name="mcSlider_image[%id%][image]" value="">
                <input class="upload_image_button" type="button" value="Upload Image"><br />
                Enter a URL or upload an image
            </p>
            <p class="sliderPreview">
                <img src="" width="" height="">
            </p>
            <p class="captions">
                <strong>Caption</strong><br />
                <textarea class="caption" name="mcSlider_image[%id%][text]" rows="1" cols="30" style=""></textarea>
            </p>
            <p>
                <strong>Link</strong><br />
                <input class="add_link" type="text" name="mcSlider_image[%id%][link]" value=""><br />
                Add a Link for this image (<em>Needs full URL including http://</em>)
            </p>
        </div>
    </li>
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
    .sliderPreview {
        background:#aeaeae;
        width:<?= $imageW ?>px;
        height:<?= $imageH ?>px
    }
    .upload_image {width:<?= $imageW - 100 ?>px;}
    ul.ui-sortable, ul.ui-sortable li {position: relative;}
    .minus, .plus {
        position: absolute;
        top:8px;right: 8px;
        z-index: 20;
        font-weight: bold;
        font-size: 24px;
        background-color: #cc6e54;
        text-decoration: none;
        color: white;
        width: 21px;height: 21px;text-align: center;line-height: 17px;
        -webkit-border-radius: 11px;
        -moz-border-radius: 11px;
        border-radius: 11px;
        border: 1px solid #909090;
    }
    .plus {top:auto;background-color: #A7CD54;}
    .minus:hover, .plus:hover {color: #333;}
    .caption, .add_link {width: <?= $imageW; ?>px;}
    #submit { text-align:right; }
    <?php if($captions != 'true'){?>
    .captions {
        display: none;
    }
    <?php } ?>              
</style>

<div id="mcAdmin" class="wrap">
    <h2><span style="color:#A7CD54;">Message:<strong>Creative</strong></span> Slider Manager <a href="#" onclick="return false" class="showMenu">Show Settings</a></h2>
    <form name="mcSlider_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <div>   
            <table id="mcOptionsMenu" class="form-table" style="margin-bottom:20px;">
                <input type="hidden" name="mcSlider_hidden" value="Y"> 
                <tr>
                    <th><label>What size would you like your images:</label> </th>
                    <td>
                        <input style="width:40px;text-align:right;" type="text" name="mcSlider_imageWidth" value="<?php echo $imageW ?>">px
                        &nbsp;x&nbsp; 
                        <input style="width:40px;text-align:right;" type="text" name="mcSlider_imageHeight" value="<?php echo $imageH ?>">px 
                        &nbsp;<em>Press Return after</em><br />
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
                <input class="order" type="hidden" name="mcSlider_image[<?= $i; ?>][order]" value="<?= $image[$i]['order'] ? $image[$i]['order'] : $i + 1; ?>">
                <a href="#" class="minus" alt="remove this slide">-</a>
                <h4 class="sliderHeader">Slider Image <span class="order"><?= $i+1; ?></span></h4>
                <div class="slider">
                    <p>
                        <input class="upload_image" type="text" name="mcSlider_image[<?= $i; ?>][image]" value="<?php echo $image[$i]['image']; ?>" style="">
                        <input class="upload_image_button" type="button" value="Upload Image"><br />
                        Enter a URL or upload an image
                    </p>
                    <p class="sliderPreview">
                        <img src="<?= plugins_url('timthumb.php', __FILE__); ?>?src=<?php echo $image[$i]['image']; ?>&amp;w=<?= $imageW ?>&amp;h=<?= $imageH ?>" width="<?= $imageW ?>" height="<?= $imageH ?>">
                    </p>
                    <p class="captions">
                        <strong>Caption</strong><br />
                        <textarea class="caption" name="mcSlider_image[<?= $i; ?>][text]" rows="1" cols="30"><?php echo $image[$i]['text']; ?></textarea>
                    </p>
                    <p>
                        <strong>Link</strong><br />
                        <input class="add_link" type="text" name="mcSlider_image[<?= $i; ?>][link]" value="<?php echo $image[$i]['link']; ?>"><br />
                        Add a Link for this image (<em>Needs full URL including http://</em>)
                    </p>
                </div>
            </li>
        <?php } ?>
            <a href="#" class="plus" alt="add a new slide">+</a>
        </ul>
            <p class="help" style="float:left;">(Drag to re-arrange)</p>
            <p id="submit" class="submit" >

                <input class="button-primary" type="submit" name="Submit" value="Update Options" style="margin-top:10px;" />
            </p>
        
    </form>
</div><!-- end wrap -->