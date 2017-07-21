<?php
class admin_floorplan {
	function __construct() {
		function load_backend_scripts_image_mapper() {
			wp_enqueue_media ();                        
			wp_enqueue_script ( 'jquery-ui-droppable' );
                        
            //SET FANCYBOX
            wp_enqueue_script( 'jquery_fancybox', plugins_url( '/image-mapper/fancybox/jquery.fancybox-1.3.5.js'));
            wp_enqueue_script( 'jquery_fancyboxgallery', plugins_url( '/image-mapper/js/gallery_without_noConflict.js'));
            wp_enqueue_style( 'fancybox_css', plugins_url('/image-mapper/fancybox/jquery.fancybox-1.3.5.pack.css') );
                        
			wp_register_script ( 'uploader-js', plugins_url ( '/image-mapper/js/uploader.js' ) );
			wp_enqueue_script ( 'uploader-js' );
                        
            wp_register_script ( 'floorplan_custom-js', plugins_url ( '/image-mapper/js/floorplan_custom.js' ), array( 'jquery'));
			wp_enqueue_script ( 'floorplan_custom-js' );
                          
			wp_register_style ( 'floorplan-css', plugins_url ( '/image-mapper/css/floorplan.css' ) );
			wp_enqueue_style ( 'floorplan-css' );

			if (get_post_type() == 'image_maps')
				{
	          	  wp_register_style ( 'css.bootstrap-css', plugins_url ( '/image-mapper/css/bootstrap/css.bootstrap.css' ) );
				  wp_enqueue_style ( 'css.bootstrap-css' );
				}
			
            wp_register_script ( 'bootstrap-modal-js', plugins_url ( '/image-mapper/js/bootstrap/bootstrap-modal.js' ) );
			wp_enqueue_script ( 'bootstrap-modal-js' );
		}
		add_action ( 'admin_enqueue_scripts', 'load_backend_scripts_image_mapper' );
		
		function admin_init_image_mapper() {
			add_meta_box ( "image_map-meta", "Image Map", "image_map_admin", "image_maps", "normal", "high" );
		}
		add_action ( "admin_init", "admin_init_image_mapper" );
		
	function image_map_admin() {
			?>

<label for="upload_image"> 
    <input id="image_id" type="hidden" name="floorplan_image_id" value="" /> 
    <input id="upload_image_button" class="button" type="button" value="Upload Image" /> <br /> <br />
</label>

<div class="clear"></div>

<div style="clear: both;"></div><?php
	
	//TODO: check if post is saved == if there is PostID
	
			// CHECK IF THERE IS AN IMAGE MAP
			if (isset ( $_REQUEST ['post'] )) {
				$attachment_id = get_post_meta ( $_REQUEST ['post'], 'floorplan_image_attachment_id', true );
				
				if ($attachment_id == true) 
					{
						$imgurl = wp_get_attachment_url ( $attachment_id );
					}
				?>
		<div id="droppable" style="overflow: auto;position: relative;width:750px;">
			<img id="floorplan_image" src="<?php if(isset($imgurl)){echo $imgurl;}?>">
				  <?php
				$mark_count = get_post_meta($_REQUEST ['post'],'mark_count','true');
				for($i = 1; $i <= $mark_count; $i ++) {
                  	$mark_image =  get_post_meta ( $_REQUEST ['post'], '_mark_image_' . $i, true );
                    $position  = maybe_unserialize(get_post_meta ( $_REQUEST ['post'], '_mark_image_' . $i.'_position', true ));
                                        
					$mark_image_left = $position["left"];
					$mark_image_top =  $position["top"];
					
					
					if (! empty ( $mark_image )) {
						?>
	                      <a id="<?php echo $_REQUEST['post'],'_mark_image_'.$i;?>" href="<?php  echo site_url().$mark_image;?>" class="btn" rel="map_gallery" style="position: absolute; top:<?php echo $mark_image_top; ?>px; left:<?php echo $mark_image_left; ?>px; " >
								<!--img src="<?php echo plugins_url('/image-mapper/images/camera-button.png');?>" onclick="addToFancyboxWrap(this)" /-->
										<div onclick="addToFancyboxWrap(this)"  style=" width: 25px;height: 25px;background: red;-moz-border-radius: 50px;-webkit-border-radius: 50px;border-radius: 50px;color: #fff;"></div>
						</a>
	                     <?php
					}
				}
				?> 
        <!-- </div> -->
		<?php  } ?>   
	</div>
<div>
	<a href="javascript:void(0);" class="box btn" role="button"
		data-toggle="modal" id="source-btn"> 
		<!--img src="<?php echo plugins_url('/image-mapper/images/camera-button.png');?>"id="draggableimg" class="ui-widget-header" /-->
		
		<div id="draggableimg" class="ui-widget-header" style=" width: 25px;height: 25px;background: red;-moz-border-radius: 50px;-webkit-border-radius: 50px;border-radius: 50px;color: #fff;"></div>
		</a><br >
	<div id="fp-instruction-msg">Please drag the circle to your image</div>
        <!--input type="button" value="Clear All" onclick="clearAllCameras();" class="btn btn-primary" /-->
</div><br/>


<?php if(isset($_REQUEST['post']))
		{?>
	<div><br/>
		Use this shortcode to display the image map on your website: [FLOORPLAN post_id=<?php echo $_REQUEST['post']; ?>]
	</div>
<?php }?>
<br/>

<div>
<input type='checkbox' name="camera_options" id='<?php echo $_REQUEST['post'];?>' <?php if (get_post_meta($_REQUEST['post'], 'floorplan_camera_options', true) == 'off'){echo 'CHECKED';} ?>> Don't show circles on website
</div>

<div class="clear"></div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true"
	style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">X</button>
		<h3 id="myModalLabel">Please Upload File</h3>
	</div>
	<div class="modal-body">
		<form id="markupload_file" method="post" action=""
			enctype="multipart/form-data">
			<p id="err-msg"></p>
			<p>
				<label for="upload_img"> </label> <a id="upload_img"
					name="upload_img" size="30">Image </a> <input type="hidden"
					id="top_val" name="top_val" value="" /> <input type="hidden"
					id="left_val" name="left_val" value="" /> <input type="hidden"
					id="marker_img_id" name="marker_img_id" value="" /> <input
					type="hidden" id="post_id" name="post_id"
					value="<?php echo $_REQUEST ['post'];?>" />
			
			
			<div style="float: left;">
				<label> Title </label>
				<input type="text" id="img-title" name="title"
					value="" /> 
					
					<!--label> Dimension </label>
				
				<input type="text"
					id="dimension" name="dimension" value="" /> <label> Capacity </label><input
					type="text" id="capacity" name="capacity" value="" /--> <label>
					Description </label><input type="text" id="description"
					name="description" value="">
			</div>
			<div style="float: left; margin-left: 50px; width: 200px;">
				<img id="existing-floor-image" />
			</div>
			</p>
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<input type="button" name="upload_image_dummy" id="upload_image_dummy"
			value="Save" class="btn btn-primary" onclick="add_edit_room_details();" />
	</div>
</div>
<?php
		}
	}
}
	



