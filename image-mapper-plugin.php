<?php
/*
Plugin Name: Image Mapper
Plugin URI: http://www.wptooling.com
Description: Upload an image and add position markers to show (room) images.
Version: 0.2.5.3
Author: WPTooling
Author URI: http://www.wptooling.com
License: A "Slug" license name e.g. GPL2
*/

//ini_set( 'display_errors', 1 );
//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

#INCLUDES#
include('includes/admin.class.php');
include('includes/frontend.class.php');
include('includes/settings.class.php');
include('includes/resize.php');
include('includes/functions.php');

##INITIATE CLASSES##
$settings = new floorplan_settings();
$frontend = new floorplan_frontend();
$admin = new admin_floorplan();
