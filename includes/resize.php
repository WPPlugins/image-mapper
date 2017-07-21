<?php
## ADDS A FUNCTION TO RESIZE THE FLOORPLAN
########################

//TODO: merge dm3_resize_image with dm4_resize_image

		function dm3_resize_image($url, $w, $h) {
			$post_id = $_REQUEST['eid'];
                    
			if (!is_string($url))
				return false;
			$path_parts = explode('.', $url);
			$patharr = explode('/', $url);
			$path_parts[count($path_parts) - 2] .= '-'.$w.'x'.$h;
			$resized_url = implode('.', $path_parts);
			$resized_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $resized_url);
			if (!file_exists($resized_path)) {
				$original_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);
				    
                        // Load the stamp and the photo to apply the watermark to
                        $im = imagecreatefromjpeg(WP_CONTENT_DIR.'/plugins/floorplan-plugin/images/white_background_540_288.jpg');
                        $upload_dir = wp_upload_dir();
                        
                        $mysock = getimagesize($original_path);
                    
                                                //Get image extension
						$info = getimagesize($original_path);                                            
						$extension = image_type_to_extension($info[2]);
						
                                                $newWidth  = 540;
                                                $newHeight = 288;
                                                
                                                $width_percent = $newWidth/ $info[0];
                                                $height_percent = $newHeight /$info[1];
                                                
                                                if($width_percent < $height_percent) {
                                                        $newWidth = $newWidth;
                                                        $newHeight = $width_percent * $info[1];
                                                } else if( $width_percent >= $height_percent) {
                                                        $newWidth = $height_percent * $info[0];
                                                        $newHeight = $newHeight;
                                                }
                                                
                                                if($info[1] < 288 && $info[0] < 540){
                                                   $newWidth  = $info[0];
                                                   $newHeight = $info[1]; 
                                                }
                                                else if($info[1] < 288 && $info[0] > 540){
                                                    $newWidth  = 540;
                                                }
                                                else if($info[1] > 288 && $info[0] < 540){
                                                    $newHeight = 288; 
                                                }
						$resized_path = image_resize($original_path, $newWidth, $newHeight, false, null, $resized_path);                                      
						
                                                //var_dump($resized_path);
                                                
                                                
						if ($extension == '.png')
						{
							$stamp= imagecreatefrompng($resized_path );
								
						}
						if ($extension == '.gif')
						{
							$stamp= imagecreatefromgif($resized_path );
						
						}
						if ($extension == '.jpeg' || $extension == '.jpg')
						{
                                                   
							$stamp= imagecreatefromjpeg($resized_path );
						
						}

                        // Set the margins for the stamp and get the height/width of the stamp image

                        $sx = imagesx($stamp);
                        $sy = imagesy($stamp);

                        $marge_right = 270 - $sx/2;
                        $marge_bottom = 288 - $sy;
                      
                        // Merge the stamp onto our photo with an opacity of 50%
                        imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 100);
                        
                        $rand = rand(0,999);
                        // Save the image to file and free memory
                        $new_merged_file = WP_CONTENT_DIR.'/uploads/floorplans/'.$post_id.'_floorplan_image_'.$rand.'.jpg';
                        
                        //echo $new_merged_file;
                        imagejpeg($im, $new_merged_file);
                        imagedestroy($im);
                        $res =  update_post_meta($post_id, 'floor_plan_merged', '/floorplans/'.$post_id.'_floorplan_image_'.$rand.'.jpg');
                        //return site_url().'/wp-content/uploads/floorplans/'.$post_id.'_floorplan_image.jpg';
      
                        }
		}
		

		function dm4_resize_image($post_id, $url, $w, $h) {
			
			if(isset($post_id))
				{
					$post_id = $post_id;
				}
			else	
				{
				$post_id = $_REQUEST['eid'];
				}
		
			if (!is_string($url))
				return false;
			$path_parts = explode('.', $url);
			$patharr = explode('/', $url);
			$path_parts[count($path_parts) - 2] .= '-'.$w.'x'.$h;
			$resized_url = implode('.', $path_parts);
			$resized_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $resized_url);
			if (!file_exists($resized_path)) {
				$original_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);
		
				// Load the stamp and the photo to apply the watermark to
				$im = imagecreatefromjpeg(WP_CONTENT_DIR.'/plugins/floorplan-plugin/images/white_background_704-389.jpg');
				$upload_dir = wp_upload_dir();
		
				$mysock = getimagesize($original_path);
				//image_dimension = imageResize($mysock[0],$mysock[1], 288);
				//var_dump($image_dimension);
		
				//Get image extension
				$info = getimagesize($original_path);
				$extension = image_type_to_extension($info[2]);
		
				//TODO: add floor_plan_merged_big (794x389)
		
				$newWidth  = 704;
				$newHeight = 389;
		
				$width_percent = $newWidth/ $info[0];
				$height_percent = $newHeight /$info[1];
		
				if($width_percent < $height_percent) {
					$newWidth = $newWidth;
					$newHeight = $width_percent * $info[1];
				} else if( $width_percent >= $height_percent) {
					$newWidth = $height_percent * $info[0];
					$newHeight = $newHeight;
				}
		
				if($info[1] < 389 && $info[0] < 704){
					$newWidth  = $info[0];
					$newHeight = $info[1];
				}
				else if($info[1] < 389 && $info[0] > 704){
					$newWidth  = 704;
				}
				else if($info[1] > 389 && $info[0] < 704){
					$newHeight = 389;
				}
				$resized_path = image_resize($original_path, $newWidth, $newHeight, false, null, $resized_path);
		
				//var_dump($resized_path);
		
		
				if ($extension == '.png')
				{
					$stamp= imagecreatefrompng($resized_path );
		
				}
				if ($extension == '.gif')
				{
					$stamp= imagecreatefromgif($resized_path );
		
				}
				if ($extension == '.jpeg' || $extension == '.jpg')
				{
					 
					$stamp= imagecreatefromjpeg($resized_path );
		
				}
		
				// Set the margins for the stamp and get the height/width of the stamp image
		
				$sx = imagesx($stamp);
				$sy = imagesy($stamp);
		
				$marge_right = 352 - $sx/2;
				$marge_bottom = 389 - $sy;
		
				// Merge the stamp onto our photo with an opacity of 50%
				imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 100);
		
				$rand = rand(0,999);
				// Save the image to file and free memory
				$new_merged_file = WP_CONTENT_DIR.'/uploads/floorplans/'.$post_id.'_floorplan_image_big_'.$rand.'.jpg';
		
				//echo $new_merged_file;
				imagejpeg($im, $new_merged_file);
				imagedestroy($im);
				$res =  update_post_meta($post_id, 'floor_plan_merged_big', '/floorplans/'.$post_id.'_floorplan_image_big_'.$rand.'.jpg');
				//return site_url().'/wp-content/uploads/floorplans/'.$post_id.'_floorplan_image.jpg';
		
			}
		}
                               

function imageResize($width, $height, $target) {

//takes the larger size of the width and height and applies the
//formula accordingly...this is so this script will work
//dynamically with any size image

if ($width > $height) {
$percentage = ($target / $width);
} else {
$percentage = ($target / $height);
}

//gets the new value and applies the percentage, then rounds the value
$width = round($width * $percentage);
$height = round($height * $percentage);

//returns the new sizes in html image tag format...this is so you
//can plug this function inside an image tag and just get the
$arrDimension = array();
$arrDimension[0] = $width;
$arrDimension[1] = $height;
return $arrDimension;

}

//FUNCTION FOR RESIZING OLD UPLOADED FLOORPLANS - REMOVE THIS FUNCTION AFTER A WHILE
function resize_image($url, $w, $h) {
	if (!is_string($url))
		return false;
	$path_parts = explode('.', $url);
	$patharr = explode('/', $url);
	$path_parts[count($path_parts) - 2] .= '-'.$w.'x'.$h;
	$resized_url = implode('.', $path_parts);
	$resized_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $resized_url);
	if (!file_exists($resized_path)) {
		$original_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);
		$resized_path = image_resize($original_path, $w, $h, true, null, $resized_path);

		$image =$patharr[count($patharr)-1];
		$uploadedfile = $original_path;

		$filename = stripslashes($image);
		//start added by mk
		$fileTempNameArr=explode('.',$filename,2);
		$fileTempName=$fileTempNameArr[0].'_'.time().'.'.$fileTempNameArr[1];
		//$filename = ABSPATH."/wp-content/uploads/mentor/".$fileTempName;
		//end added by mk
		$path_info = pathinfo($filename);
		$extension = $path_info['extension'];
		$extension = strtolower($extension);
		if (($extension != "jpg") && ($extension != "jpeg")
		&& ($extension != "png") && ($extension != "gif"))
		{
			echo ' Unknown Image extension ';
			$errors=1;
		}
		else
		{
			$size=filesize($uploadedfile);
			list($width,$height)=getimagesize($uploadedfile);
			$filename = $resized_path;
			$newwidth=$w;
			$newheight=$h;
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			if($extension=="jpg" || $extension=="jpeg" )
			{
				$src = imagecreatefromjpeg($uploadedfile);
				imagealphablending($tmp, false);
				imagesavealpha($tmp,true);
				$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
				imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
				imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight,$width, $height);
				imagejpeg($tmp,$filename,100);
			}
			else if($extension=="png")
			{
				$src = imagecreatefrompng($uploadedfile);
				imagealphablending($tmp, false);
				imagesavealpha($tmp,true);
				$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
				imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
				imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight,$width, $height);

				imagepng($tmp,$filename);
			}
			else
			{
				$src = imagecreatefromgif($uploadedfile);
				imagealphablending($tmp, false);
				imagesavealpha($tmp,true);
				$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
				imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
				imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight,$width, $height);

				imagegif($tmp,$filename);
			}
			imagedestroy($src);
			imagedestroy($tmp);
		}
	}
	if (is_string($resized_path)) {
		return $resized_url;
	} else {
		return false;
	}
	return $resized_url;
}