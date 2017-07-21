<?php 
class floorplan_settings{
	function __construct()
	{
		//SET CAMERAS ON OR OFF
		function save_camera_options()
		{
			$postID = $_POST ['post_ID'];
			$post = get_post ( $postID );
		
			if ($post->post_type == 'image_maps') {
				if ($_POST['camera_options'] == 'on') {
					update_post_meta($postID, 'floorplan_camera_options', 'off');
				}
				else{
					update_post_meta($postID, 'floorplan_camera_options', 'on');
					}
			}
		}
		add_action ( 'save_post', 'save_camera_options' );
		
		#SET CUSTOM POST TYPE
		add_action( 'init', 'create_post_type' );
		function create_post_type()
		{
			register_post_type(
			'image_maps',
			array
			(
			'labels' => array(
			'name' => __('Image Maps'),
			'singular_name' => __('Image Map'),
			'all_items' => __('View All'),
			'edit_item' => __('Edit Image Map'),
			'new_item' => __('New Image Map'),
			'view_item' => __('View Image Map'),
			'search_items' => __('Search Image Maps')),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array(
			'slug' => 'image maps',
			'with_front' => false),
			'has_archive' => true,
			'query_var' => true,
			'menu_position' => 25,
			'supports' => array('title','excerpt', 'author','editor','thumbnail','excerpt','custom-fields','revisions','comments','page-attributes')
			)
			);
		}
		
		// ADD NEW COLUMN
		function shortcodes_head($defaults) {
			$defaults['Shortcode'] = 'Shortcode';
			return $defaults;
		}
		add_filter('manage_posts_columns', 'shortcodes_head');
		
		// SHOW THE FEATURED IMAGE
		function shortcodes_head_content($column_name, $post_ID) {
			if ($column_name == 'Shortcode') {
			?>
				 [FLOORPLAN post_id=<?php echo $post_ID; ?>]
			<?php 
			}
		}
		add_action('manage_posts_custom_column', 'shortcodes_head_content', 10, 2);
		
		
		/*function image_maps_menu() 
			{
				//add_menu_page ( 'WP Tooling', 'WP Tooling', 'manage_options', 'wp-tooling', 'floorplans_plugins' );
				//add_submenu_page( 'edit.php?post_type=image maps', 'Feedback', 'Feedback', 'manage_options', 'wp-tooling', 'image_maps_feedback');
				//add_submenu_page( 'edit.php?post_type=image_maps', 'Help', 'Help', 'manage_options', 'help', 'image_maps_faq');
				//add_submenu_page( 'edit.php?post_type=floorplans', 'Settings', 'Settings', 'manage_options', 'settings', 'floorplans_settings');	
			}
		add_action ( 'admin_menu', 'image-mapper' );*/
		
		function floorplans_plugins()
			{
			}
		
		function floorplans_faq()
			{
					?>
					<ul>
						<li><a href="http://wptooling.com/forum" target="_blank">Forums</a></li>
						<li><a href="http://wptooling.com/faq" target="_blank">FAQs</a></li>
					</ul>
					<?php 
			}
				
		function floorplans_feedback()
			{
						?>
						<br/>
						<div>
						<iframe src='http://wptooling.com/feedback-so-we-can-make-our-plugins-even-better' style="width:100%; height:500px;" frameborder='0'></iframe>
						</div>
						<a href="http://wptooling.com/feedback-so-we-can-make-our-plugins-even-better" target="_blank">Open in new tab.</a>
						<?php 
			}
			
		function floorplans_settings()
		{
			?>
			<br/>
			<div>Floorplan Settings</div>
			<div>
			<input type='checkbox' name="floorplan_backlinks"> Don't show 'Plugin by WP Tooling' on website
			</div>
			<?php 
		}
		
		##INIT DIRECTORY FOR UPLOADING PDF##
		$dir_name = WP_CONTENT_DIR.'/uploads/floorplans/';
		if ( ! is_dir($dir_name) )
			{
				wp_mkdir_p($dir_name) or die("Could not create Image Maps directory " . $dir_name);
			}
		
		
	}
	
}