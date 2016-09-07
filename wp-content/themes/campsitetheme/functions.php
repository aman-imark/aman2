<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function campsite_script_enqueue()
{
    wp_enqueue_style('customstyle', get_template_directory_uri() . '/css/bootstrap-multiselect.css', array(), '', 'all');
    wp_enqueue_style('customstyle1', get_template_directory_uri() . '/css/bootstrap-theme.css', array(), '', 'all');
    wp_enqueue_style('customstyle2', get_template_directory_uri() . '/css/bootstrap-theme.min.css', array(), '', 'all');
    wp_enqueue_style('customstyle3', get_template_directory_uri() . '/css/bootstrap.css', array(), '', 'all');
    //wp_enqueue_style('customstyle4', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '', 'all');
    //wp_enqueue_style('customstyle5', get_template_directory_uri() . '/css/ihover.css', array(), '', 'all');
    //wp_enqueue_style('customstyle6', get_template_directory_uri() . '/css/style.css', array(), '', 'all');
    
    //wp_enqueue_script('customjs1', get_template_directory_uri() . '/js/bootstrap.js', array(), '', true);
    wp_enqueue_script('customjs2', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '', true);
    wp_enqueue_script('customjs5', get_template_directory_uri() . '/js/wow.min.js', array(), '', true);
    wp_enqueue_script('customjs6', get_template_directory_uri() . '/js/imtech_pager.js', array(), '', true);
}
add_action('wp_enqueue_scripts', 'campsite_script_enqueue' );

function campsite_theme_setup()
{
    add_theme_support('menus');  //theme support hook
    
    register_nav_menu('primary', 'Primary Header Navigation');
    register_nav_menu('body', 'Body Navigation');
    register_nav_menu('secondary-left', 'Footer Navigation Left');
    register_nav_menu('secondary-right', 'Footer Navigation Right');
}
add_action('init', 'campsite_theme_setup');

//add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );
//
//function wti_loginout_menu_link( $items, $args ) {
//   if ($args->theme_location == 'primary') {
//      if (is_user_logged_in()) {
//          $redirect_1 = get_site_url();
//         $items .= '<li class="right"><a href="'. wp_logout_url($redirect_1) .'">'. __("Log Out") .'</a></li>';
//      } else {
//          $redirect_1 = get_site_url()."/login";
//         $items .= '<li class="right"><a href="'. $redirect_1 .'">'. __("Log In") .'</a></li>';
//      }
//   }
//   return $items;
//}

//function wpsites_before_post_widget( $content ) {
//	if ( is_singular( array( 'post', 'page' ) ) && is_active_sidebar( 'before-post' ) && is_main_query() ) {
//		dynamic_sidebar('before-post');
//	}
//	return $content;
//}
//add_filter( 'the_content', 'wpsites_before_post_widget' );
//register_sidebar( array(
//	'id'          => 'before-post',
//	'name'        => 'Before Posts Widget',
//	'description' => __( 'Your Widget Description.', 'text_domain' ),
//) );

if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name'=> 'Top Tabs',
		'id' => 'top_tabs',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> 'Top Sidebar',
		'id' => 'top_sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name'=> 'Left Sidebar',
		'id' => 'left_sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name'=> 'Right Sidebar',
		'id' => 'right_sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}

function custom_post_type() {
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Blogs', 'Post Type General Name', 'campsitetheme' ),
        'singular_name'       => _x( 'Blog', 'Post Type Singular Name', 'campsitetheme' ),
        'menu_name'           => __( 'Blogs', 'campsitetheme' ),
        'parent_item_colon'   => __( 'Parent Blog', 'campsitetheme' ),
        'all_items'           => __( 'All Blogs', 'campsitetheme' ),
        'view_item'           => __( 'View Blog', 'campsitetheme' ),
        'add_new_item'        => __( 'Add New Blog', 'campsitetheme' ),
        'add_new'             => __( 'Add New', 'campsitetheme' ),
        'edit_item'           => __( 'Edit Blog', 'campsitetheme' ),
        'update_item'         => __( 'Update Blog', 'campsitetheme' ),
        'search_items'        => __( 'Search Blog', 'campsitetheme' ),
        'not_found'           => __( 'Not Found', 'campsitetheme' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'campsitetheme' ),
    );
     
// Set other options for Custom Post Type
    $args = array(
        'label'               => __( 'blogs', 'campsitetheme' ),
        'description'         => __( 'Blog news and blogs', 'campsitetheme' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'revisions'),
//        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies'          => array( 'category'),
        /* A hierarchical CPT is like Pages and can have Parent and child items. A non-hierarchical CPT is like Posts. */
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'rewrite' => array('slug' => 'blog')
    );

    add_theme_support( 'post-thumbnails' );
    add_image_size( 'homepage-thumb', 330, 330 ); // Soft Crop Mode
    add_image_size( 'singlepost-thumb', 590, 9999 ); // Unlimited Height Mode
    
    // Registering your Custom Post Type
    register_post_type( 'blog_post', $args );
}
/* Hook into the 'init' action so that the function Containing our post type registration is not unnecessarily executed.*/

add_action( 'init', 'custom_post_type', 0 );

/*To hide the wp-amdin bar for subscribers*/
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
/*To hide the wp-amdin dashboard for subscribers*/
add_filter( 'init', 'blockusers_init' );
function blockusers_init() {
    if(is_user_logged_in())
    {
        if (is_admin() && !current_user_can('administrator') && !( defined('DOING_AJAX') && DOING_AJAX)) {
            wp_redirect(home_url());
            exit;
        }
    }
}

/*To add a custom field on the admin side and enable editing*/
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>

	<h3>Extra profile information</h3>

	<table class="form-table">

		<tr>
			<th><label for="photo">Set User Photo</label></th>

			<td>
                            <input type="checkbox" name="photo" id="photo" value="1" /><br />
				<span class="description">Set as recent user on homepage</span>
			</td>
		</tr>

	</table>
        <script>
    <?php if (get_the_author_meta( 'photo', $user->ID ) == 1){
        ?>
            document.getElementById("photo").checked = true;
            <?php
    }?>
        
    </script>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	if(!update_usermeta( $user_id, 'photo', $_POST['photo'] ))
        {
            add_user_meta( $user_id, 'photo', $_POST['photo'] );
        }
}
?>