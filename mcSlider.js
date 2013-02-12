jQuery(document).ready(function($){
    //cache and save the persistent page element
    var el = $('#mcAdmin');
    
    var sort = function () {
        el.find('ul.ui-sortable li').each(function (index) {
            $(this).find('.order').val(index+1);
            $(this).find('span.order').text(index+1);
        });
    };

    //wrap the slide sections of the admin menu in a div.slider
    el.find('div.slider').not(':first').slideUp();
    
    //make the div.sliders into a clickable accordian
    el.find('h4.sliderHeader').css('cursor', 'pointer');
    el.on('click', 'h4.sliderHeader', function(e){
        e.preventDefault();
        $(this).next('div.slider').slideToggle();
    });
    
    //.minus removes this slide
    el.on('click', '.minus', function (e) {
        e.preventDefault();
        $(this).parent().hide('slow').remove();
        sort();
    });
    
    //.plus adds a new slide
    el.on('click', '.plus', function (e) {
        e.preventDefault();
        
        var templ = $('#slideTemplate').html();
        var id = parseInt( $('ul.ui-sortable li').last().find('.order').val() ) || 0;
        var slide = templ.replace(/%id%/g, id).replace(/%id1%/g, id + 1);
        
        // add new slide with new id
        if ( $('ul.ui-sortable li').last().length < 1 ) {
            $('ul.ui-sortable').prepend(slide);
        } else {
            $('ul.ui-sortable li').last().after(slide);
        }
    });
    
    el.find('#mcOptionsMenu').parent().hide();
    el.on('click', '.showMenu', function(){
        $('#mcOptionsMenu').parent().slideToggle();
        $('.showMenu').text($('.showMenu').text() === 'Show Settings' ? 'Hide Settings' : 'Show Settings');
    });
    
    
    //intercept wordpress 'image uploader' and return the clicked image into our url field
    var imageField, formfield, imgurl;
    el.on('click', '.upload_image_button', function(e) {
        e.preventDefault();
        formfield = $(this).prev('input').attr('name');
        tb_show('Add Image to Slider', 'media-upload.php?type=image&amp;TB_iframe=true');
        imageField = $(this).prev('input');
    });
    window.send_to_editor = function(html) {
     imgurl = $(html).attr('href');
     $(imageField).val(imgurl);
     tb_remove();
    };
    
    el.find('ul.ui-sortable').sortable({
        update: function (event, ui) {
            sort();
        }
    });
});
