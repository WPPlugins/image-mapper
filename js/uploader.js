jQuery(document).ready(function($){
 
    var custom_uploader;
    var custom_uploader_rooms;

 
    $('#upload_image_button').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#image_id').val(attachment.id);

           // $("#floorplan_img").attr("src",attachment.url);
            $("#floorplan_image").attr("src",attachment.url);

        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
    
    $('#upload_img').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader_rooms) {
        	custom_uploader_rooms.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader_rooms = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader_rooms.on('select', function() {
            attachment = custom_uploader_rooms.state().get('selection').first().toJSON();
            //$('#upload_image').val(attachment.url);
            //$('#image_id').val(attachment.id);
            $("#existing-floor-image").attr("src",attachment.url);

        });
 
        //Open the uploader dialog
        custom_uploader_rooms.open();
 
    });
    
 
 
});
