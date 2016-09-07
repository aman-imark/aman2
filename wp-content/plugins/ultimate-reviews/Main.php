<?php
/*
Plugin Name: Ultimate Reviews
Plugin URI: http://www.EtoileWebDesign.com/plugins/ultimate-reviews/
Description: Accept and display reviews, style them for your site and much more!
Author: Etoile Web Design
Author URI: http://www.EtoileWebDesign.com/plugins/ultimate-reviews/
Terms and Conditions: http://www.etoilewebdesign.com/plugin-terms-and-conditions/
Text Domain: EWD_URP
Version: 1.2.2
*/

global $ewd_urp_message;
global $URP_Full_Version;

$EWD_URP_Version = '1.2.0';

define( 'EWD_URP_CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EWD_URP_CD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

//define('WP_DEBUG', true);

register_activation_hook(__FILE__,'Set_EWD_URP_Options');

/* Hooks neccessary admin tasks */
if ( is_admin() ){
	add_action('admin_head', 'EWD_URP_Admin_Options');
	add_action('widgets_init', 'Update_EWD_URP_Content');
	add_action('admin_init', 'Add_EWD_URP_Scripts');
	add_action('admin_notices', 'EWD_URP_Error_Notices');
}

add_action('admin_menu', 'EWD_URP_Unapproved_Reviews');
function EWD_URP_Unapproved_Reviews($Title) {
	global $wpdb;
	global $menu;

	$Admin_Approval = get_option("EWD_URP_Admin_Approval");
	if ($Admin_Approval == "Yes") {
		$Unapproved_Reviews = $wpdb->get_results("SELECT ID FROM " . $wpdb->posts . " WHERE post_status='draft' and post_type='urp_review'");
		foreach ($menu as $key => $menu_item) {
			if ($menu_item[0] == "Reviews") {
				if ($menu_item[2] == "edit.php?post_type=urp_review") {
					if ($wpdb->num_rows != 0) {$menu[$key][0] .= " <span class='update-plugins count-2' title='Unapproved Reviews'><span class='update-count'>" . $wpdb->num_rows . "</span></span>";}
				}
			} 
		}
	}
}

function EWD_URP_Enable_Sub_Menu() {
	add_submenu_page('edit.php?post_type=urp_review', 'URP WooCommerce Import', 'Import', 'edit_posts', 'urp-woocommerce-import', 'EWD_URP_Output_Options_Page');
	add_submenu_page('edit.php?post_type=urp_review', 'URP Options', 'Settings', 'edit_posts', 'urp-options', 'EWD_URP_Output_Options_Page');
}
add_action('admin_menu' , 'EWD_URP_Enable_Sub_Menu');

/* Add localization support */
function EWD_URP_localization_setup() {
		load_plugin_textdomain('EWD_URP', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}
add_action('after_setup_theme', 'EWD_URP_localization_setup');

// Add settings link on plugin page
function EWD_URP_plugin_settings_link($links) {
  $settings_link = '<a href="edit.php?post_type=urp_review&page=urp-options">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'EWD_URP_plugin_settings_link' );

function Add_EWD_URP_Scripts() {
		if (isset($_GET['post_type']) && $_GET['post_type'] == 'urp_review') {
			wp_enqueue_script(  'jquery-ui-core' );
        	wp_enqueue_script(  'jquery-ui-sortable' );
			$url_one = plugins_url("ultimate-reviews/js/Admin.js");
			wp_enqueue_script('PageSwitch', $url_one, array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'));
			wp_enqueue_script('spectrum', plugins_url("ultimate-reviews/js/spectrum.js"), array('jquery'));
		}
}

add_action( 'wp_enqueue_scripts', 'Add_EWD_URP_FrontEnd_Scripts' );
function Add_EWD_URP_FrontEnd_Scripts() {
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('jquery-ui-slider');

	wp_register_script('ewd-urp-js', plugins_url( '/js/ewd-urp-js.js' , __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-autocomplete', 'jquery-ui-slider' ));
	
	$Maximum_Score = get_option("EWD_URP_Maximum_Score");
	$Review_Character_Limit = get_option('EWD_URP_Review_Character_Limit');
	$Data_Array = array( 'maximum_score' => $Maximum_Score,
						'review_character_limit' => $Review_Character_Limit
		);
	wp_localize_script( 'ewd-urp-js', 'ewd_urp_php_data', $Data_Array );
	
	wp_enqueue_script('ewd-urp-js');
}


add_action( 'wp_enqueue_scripts', 'EWD_URP_Add_Stylesheet' );
function EWD_URP_Add_Stylesheet() {
    global $URP_Full_Version;

    $Reviews_Skin = get_option("EWD_URP_Reviews_Skin");

    wp_register_style( 'ewd-urp-style', plugins_url('css/ewd-urp-styles.css', __FILE__) );
    wp_enqueue_style( 'ewd-urp-style' );

    wp_register_style( 'ewd-urp-jquery-ui', plugins_url('css/ewd-urp-jquery-ui.css', __FILE__) );
    wp_enqueue_style( 'ewd-urp-jquery-ui' );

    if ($Reviews_Skin != "Basic" and $Reviews_Skin != "") {
    	wp_register_style('ewd-urp-addtl-stylesheet', EWD_URP_CD_PLUGIN_URL . "css/addtl/" . $Reviews_Skin . ".css"); 
    	wp_enqueue_style('ewd-urp-addtl-stylesheet');
    }
}

function EWD_URP_Admin_Options() {
	wp_enqueue_style( 'ewd-urp-admin', plugins_url("ultimate-reviews/css/Admin.css"));
	wp_enqueue_style( 'spectrum', plugins_url("ultimate-reviews/css/spectrum.css"));
}

add_action('activated_plugin','save_urp_error');
function save_urp_error(){
		update_option('plugin_error',  ob_get_contents());
		file_put_contents("Error.txt", ob_get_contents());
}

function Set_EWD_URP_Options() {
	if (get_option("EWD_URP_Maximum_Score") == "") {update_option("EWD_URP_Maximum_Score", "5");}
	if (get_option("EWD_URP_Review_Style") == "") {update_option("EWD_URP_Review_Style", "Points");}
	if (get_option("EWD_URP_Review_Score_Input") == "") {update_option("EWD_URP_Review_Score_Input", "Text");}
	if (get_option("EWD_URP_Review_Image") == "") {update_option("EWD_URP_Review_Image", "No");}
	if (get_option("EWD_URP_Review_Filtering") == "") {update_option("EWD_URP_Review_Filtering", array("Name","Score"));}
	if (get_option("EWD_URP_Allow_Reviews") == "") {update_option("EWD_URP_Allow_Reviews", array());}
	if (get_option("EWD_URP_InDepth_Reviews") == "") {update_option("EWD_URP_InDepth_Reviews", "No");}
	if (get_option("EWD_FEUP_Review_Categories_Array") == "") {update_option("EWD_FEUP_Review_Categories_Array", array());}
	if (get_option("EWD_URP_Autocomplete_Product_Names") == "") {update_option("EWD_URP_Autocomplete_Product_Names", "Yes");}
	if (get_option("EWD_URP_Restrict_Product_Names") == "") {update_option("EWD_URP_Restrict_Product_Names", "No");}
	if (get_option("EWD_URP_Product_Name_Input_Type") == "") {update_option("EWD_URP_Product_Name_Input_Type", "Text");}
	if (get_option("EWD_URP_UPCP_Integration") == "") {update_option("EWD_URP_UPCP_Integration", "No");}
	if (get_option("EWD_URP_Product_Names_Array") == "") {update_option("EWD_URP_Product_Names_Array", array());}
	if (get_option("EWD_URP_Link_To_Post") == "") {update_option("EWD_URP_Link_To_Post", "No");}
	if (get_option("EWD_URP_Display_Author") == "") {update_option("EWD_URP_Display_Author", "Yes");}
	if (get_option("EWD_URP_Display_Date") == "") {update_option("EWD_URP_Display_Date", "Yes");}
	if (get_option("EWD_URP_Reviews_Per_Page") == "") {update_option("EWD_URP_Reviews_Per_Page", "1000");}
	if (get_option("EWD_URP_Pagination_Location") == "") {update_option("EWD_URP_Pagination_Location", "Both");}

	if (get_option("EWD_URP_Review_Format") == "") {update_option("EWD_URP_Review_Format", "Standard");}
	if (get_option("EWD_URP_Thumbnail_Characters") == "") {update_option("EWD_URP_Thumbnail_Characters", "140");}
	if (get_option("EWD_URP_Replace_WooCommerce_Reviews") == "") {update_option("EWD_URP_Replace_WooCommerce_Reviews", "No");}
	if (get_option("EWD_URP_Override_WooCommerce_Theme") == "") {update_option("EWD_URP_Override_WooCommerce_Theme", "No");}
	if (get_option("EWD_URP_Review_Weights") == "") {update_option("EWD_URP_Review_Weights", "No");}
	if (get_option("EWD_URP_Review_Karma") == "") {update_option("EWD_URP_Review_Karma", "No");}
	if (get_option("EWD_URP_Use_Captcha") == "") {update_option("EWD_URP_Use_Captcha", "No");}
	if (get_option("EWD_URP_Summary_Statistics") == "") {update_option("EWD_URP_Summary_Statistics", "None");}
	if (get_option("EWD_URP_Infinite_Scroll") == "") {update_option("EWD_URP_Infinite_Scroll", "No");}
	if (get_option("EWD_URP_Admin_Notification") == "") {update_option("EWD_URP_Admin_Notification", "No");}
	if (get_option("EWD_URP_Admin_Approval") == "") {update_option("EWD_URP_Admin_Approval", "No");}
	if (get_option("EWD_URP_Require_Email") == "") {update_option("EWD_URP_Require_Email", "No");}
	if (get_option("EWD_URP_Email_Confirmation") == "") {update_option("EWD_URP_Email_Confirmation", "No");}
	if (get_option("EWD_URP_Display_On_Confirmation") == "") {update_option("EWD_URP_Display_On_Confirmation", "Yes");}
	if (get_option("EWD_URP_Require_Login") == "") {update_option("EWD_URP_Require_Login", "No");}
	if (get_option("EWD_URP_Login_Options") == "") {update_option("EWD_URP_Login_Options", array());}

	if (get_option("EWD_URP_Group_By_Product") == "") {update_option("EWD_URP_Group_By_Product", "No");}
	if (get_option("EWD_URP_Group_By_Product_Order") == "") {update_option("EWD_URP_Group_By_Product_Order", "ASC");}
	if (get_option("EWD_URP_Ordering_Type") == "") {update_option("EWD_URP_Ordering_Type", "Date");}
	if (get_option("EWD_URP_Order_Direction") == "") {update_option("EWD_URP_Order_Direction", "DESC");}

	if (get_option("EWD_URP_Reviews_Skin") == "") {update_option("EWD_URP_Reviews_Skin", "Basic");}
	if (get_option("EWD_URP_Display_Numerical_Score") == "") {update_option("EWD_URP_Display_Numerical_Score", "Yes");}

	if (get_option("EWD_URP_Install_Flag") == "") {update_option("EWD_URP_Install_Flag", "Yes");}
	if (get_option("EWD_URP_Install_Flag") == "") {update_option("EWD_URP_Install_Flag", "Yes");}
}

$URP_Full_Version = get_option("EWD_URP_Full_Version");
if (isset($_GET['post_type']) and $_GET['post_type'] == 'urp_review' and $_GET['page'] == "urp-options" and $URP_Full_Version != "Yes") {add_action("admin_notices", "EWD_URP_Upgrade_Box");}

if (isset($_POST['Upgrade_To_Full'])) {
	add_action('admin_init', 'EWD_URP_Upgrade_To_Full');
}

include "Functions/Error_Notices.php";
include "Functions/EWD_URP_Add_Views_Column.php";
include "Functions/EWD_URP_Captcha.php";
include "Functions/EWD_URP_Facebook_Config.php";
include "Functions/EWD_URP_Helper_Functions.php";
include "Functions/EWD_URP_Output_Buffering.php";
include "Functions/EWD_URP_Output_Options_Page.php";
include "Functions/EWD_URP_Replace_WooCommerce_Reviews.php";
include "Functions/EWD_URP_Styling.php";
include "Functions/EWD_URP_Submit_Review.php";
include "Functions/EWD_URP_Twitter_Login.php";
include "Functions/EWD_URP_Upgrade_Box.php";
include "Functions/EWD_URP_Version_Update.php";
include "Functions/EWD_URP_Widgets.php";
include "Functions/EWD_URP_WooCommerce_Review_Import.php";
include "Functions/FrontEndAjaxUrl.php";
include "Functions/Full_Upgrade.php";
include "Functions/Process_Ajax.php";
include "Functions/Register_EWD_URP_Posts_Taxonomies.php";
include "Functions/Update_EWD_URP_Admin_Databases.php";
include "Functions/Update_EWD_URP_Content.php";

include "Shortcodes/Display_URP_Search.php";
include "Shortcodes/DisplayReviews.php";
include "Shortcodes/SelectReview.php";
include "Shortcodes/SubmitReview.php";

if ($EWD_URP_Version != get_option('EWD_URP_Version')) {
	Set_EWD_URP_Options();
	EWD_URP_Version_Update();
}
?>