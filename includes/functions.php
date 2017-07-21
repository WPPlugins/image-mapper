<?php

##SAVE ROOM DETAILS
function ajax_room_details()
{       
        $postID             =  $_POST['post_id'];
        $markerImgId        =  $_POST['marker_img_id'];
        $roomTitle          =  $_POST['roomTitle'];
        $roomDimension      =  $_POST['roomDimension'];
		$roomImage          =  $_POST['roomImage'];
        $marker_image_top   =  $_POST['marker_image_top'];
        $marker_image_left  =  $_POST['marker_image_left'];                       
        $roomCapacity       =  $_POST['roomCapacity'];
        $roomDescription    =  $_POST['roomDescription'];
        $roomImage = str_replace(site_url(),'', $roomImage); 
        $position = array('top'=>$marker_image_top,'left'=>$marker_image_left);
        $meta_values = get_post_meta( $postID, 'mark_count', true );
        $valueForMarker = 0;
        $markerImage = explode('_',$markerImgId);
        
        if(empty($meta_values)){
            $markercount=1;
            $valueForMarker = 1;
        }
        else if(!empty($marker_existing)){
            $markercount=$meta_values;
            $valueForMarker=$marker_existing;
        }
		else
		{
	            $markercount=$meta_values+1;
	            $valueForMarker=$meta_values+1;
		}
		
	
        $markerId = '';
        $arrImage = array();
	
        if($markerImage[0] == 'newimg'){
            $markerId = '_mark_image_'.$valueForMarker;
            update_post_meta($postID,'mark_count',$markercount);
            $arrImage['id']=$postID.'_mark_image_'.$valueForMarker;
        }
        else
	        {
	            $markerId= '_'.$markerImage[1]."_".$markerImage[2]."_".$markerImage[3];
	            $arrImage['id']=$markerImgId;
	        }
	            
	        
                    update_post_meta($postID,$markerId,$roomImage);
	            update_post_meta($postID,$markerId.'_position',$position);
	            update_post_meta($postID,$markerId.'_title', $roomTitle);
	            update_post_meta($postID,$markerId.'_dimension',$roomDimension);
	            update_post_meta($postID,$markerId.'_capacity',$roomCapacity);
	            update_post_meta($postID,$markerId.'_description',$roomDescription);
	            echo json_encode($arrImage);
        
            die();
}
add_action('wp_ajax_room_details', 'ajax_room_details');
add_action('wp_ajax_nopriv_room_details', 'ajax_room_details');

function ajax_floorplan_get_existing_image()
{
	//ob_clean();
	$mark_image_id =  $_POST['marker_id'];
	$mark_post_image = explode('_', $mark_image_id);
	$metaKey    = '_mark_image_'.$mark_post_image[3];
	$metaValue  = '';

	$arr=  maybe_unserialize(get_post_meta($mark_post_image[0],'_mark_image_'.$mark_post_image[3], true ));
        
        $mark_image = get_post_meta($mark_post_image[0], '_mark_image_'.$mark_post_image[3], true );
	$mark_image_title = get_post_meta($mark_post_image[0],'_mark_image_'.$mark_post_image[3].'_title', true );
	$mark_image_dimension = get_post_meta($mark_post_image[0],'_mark_image_'.$mark_post_image[3].'_dimension', true );
	$mark_image_capacity = get_post_meta($mark_post_image[0],'_mark_image_'.$mark_post_image[3].'_capacity', true );
	$mark_image_description = get_post_meta($mark_post_image[0],'_mark_image_'.$mark_post_image[3].'_description', true );

	$arrImage = array(
			'img'=>site_url().$mark_image,
			'title'=>$mark_image_title,
			'dimension'=>$mark_image_dimension,
			'capacity'=>$mark_image_capacity,
			'description'=>$mark_image_description );
	echo json_encode($arrImage);
	die();
}
add_action('wp_ajax_floorplan_get_image', 'ajax_floorplan_get_existing_image');
add_action('wp_ajax_nopriv_floorplan_get_image', 'ajax_floorplan_get_existing_image');

function ajax_floor_plan_delete(){
    $markerImgId =  $_POST['marker_id'];
    $q           =  $_POST['q'];
    if($q == 'deleteAll'){
        for($i=0;$i < count($markerImgId);$i++){
            $marker = explode('_',$markerImgId[$i]);
            $markerId = '_mark_image_'.$marker[3];
            update_post_meta($marker[0],$markerId,'');
        }
        $arrImage = array('result'=>TRUE);
    }
    else {
        $marker = explode('_',$markerImgId);
        $markerId = '_mark_image_'.$marker[3];
        update_post_meta($marker[0],$markerId,'');
        $arrImage = array('result'=>TRUE);
    }
    
    echo json_encode($arrImage);
    die();
}
add_action('wp_ajax_floorplan_delete_changes_action', 'ajax_floor_plan_delete');
add_action('wp_ajax_nopriv_floorplan_delete_changes_action', 'ajax_floor_plan_delete');
//CHECK URL
function is_200($url)
{
	$options['http'] = array(
			'method' => "HEAD",
			'ignore_errors' => 1,
			'max_redirects' => 0
	);
	$body = file_get_contents($url, NULL, stream_context_create($options));
	sscanf($http_response_header[0], 'HTTP/%*d.%*d %d', $code);

	return $code === 200;
}
	// STORE FLOORPLAN DATA
function save_floorplan_img() {
		$postID = $_POST ['post_ID'];
		$post = get_post ( $postID );
		
		if ($post->post_type == 'image_maps') {
			
			if ($_POST ['floorplan_image_id'] != '') {
				update_post_meta ( $postID, 'floorplan_image_attachment_id', $_POST ['floorplan_image_id'] );
			}
	}
}
add_action ( 'save_post', 'save_floorplan_img' );