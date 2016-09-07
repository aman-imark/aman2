<?php
/* Processes the ajax requests being put out in the admin area and the front-end
*  of the URP plugin */

// Returns the FAQs that are found for a specific search
function EWD_URP_Search() {
    $Path = ABSPATH . 'wp-load.php';
    include_once($Path);

    if (isset($_POST['Q'])) {$search_string = strtolower($_POST['Q']);}
    else {$search_string = "";}
    if (isset($_POST['product_name']) and $_POST['product_name'] != "undefined" and $_POST['product_name'] != "") {$product_name = $_POST['product_name'];}
    else {$product_name = "";}
    if (isset($_POST['min_score']) or $_POST['min_score'] == "") {$min_score = $_POST['min_score'];}
    else {$min_score = 0;}
    if (isset($_POST['max_score']) or $_POST['max_score'] == "") {$max_score = $_POST['max_score'];}
    else {$max_score = 1000000;}
    if (isset($_POST['orderby'])) {$orderby = $_POST['orderby'];}
    else {$orderby = "";}
    if (isset($_POST['order'])) {$order = $_POST['order'];}
    else {$order = "";}
    if (isset($_POST['post_count']) or $_POST['post_count'] == "") {$post_count = $_POST['post_count'];}
    else {$post_count = -1;}
    if (isset($_POST['current_page']) or $_POST['current_page'] == "") {$current_page = $_POST['current_page'];}
    else {$current_page = 1;}
    if (isset($_POST['only_reviews']) or $_POST['only_reviews'] == "") {$only_reviews = $_POST['only_reviews'];}
    else {$only_reviews = "No";}

    echo do_shortcode("[ultimate-reviews search_string='" . $search_string . "' min_score='" . $min_score . "' max_score='" . $max_score . "' product_name='" . $product_name . "' orderby='" . $orderby . "' order='" . $order . "' post_count='" . $post_count . "' only_reviews='" . $only_reviews . "' current_page='" . $current_page . "']") ;
}
add_action('wp_ajax_urp_search', 'EWD_URP_Search');
add_action( 'wp_ajax_nopriv_urp_search', 'EWD_URP_Search');

// Records the number of time a review post is opened
function EWD_URP_Record_View() {
    $Path = ABSPATH . 'wp-load.php';
    include_once($Path);

    global $wpdb;
    $wpdb->show_errors();
    $post_id = $_POST['post_id'];
    $Meta_ID = $wpdb->get_var($wpdb->prepare("SELECT meta_id FROM $wpdb->postmeta WHERE post_id=%d AND meta_key='urp_view_count'", $post_id));
    if ($Meta_ID != "" and $Meta_ID != 0) {$wpdb->query($wpdb->prepare("UPDATE $wpdb->postmeta SET meta_value=meta_value+1 WHERE post_id=%d AND meta_key='urp_view_count'", $post_id));}
    else {$wpdb->query($wpdb->prepare("INSERT INTO $wpdb->postmeta (post_id,meta_key,meta_value) VALUES (%d,'urp_view_count','1')", $post_id));}

}
add_action('wp_ajax_urp_record_view', 'EWD_URP_Record_View');
add_action('wp_ajax_nopriv_urp_record_view', 'EWD_URP_Record_View');

function EWD_URP_Update_Karama() {
    $Path = ABSPATH . 'wp-load.php';
    include_once($Path);

    $Review_ID = $_POST['ReviewID'];
    $Direction = $_POST['Direction'];

    $Karma = get_post_meta( $Review_ID, 'EWD_URP_Review_Karma', true );

    if ($Direction == 'down') {update_post_meta( $Review_ID, 'EWD_URP_Review_Karma', $Karma - 1 );}
    else {update_post_meta( $Review_ID, 'EWD_URP_Review_Karma', $Karma + 1 );}

    $EWD_URP_Karma_IDs = unserialize(stripslashes($_COOKIE['EWD_URP_Karma_IDs']));
    $EWD_URP_Karma_IDs[] = $Review_ID;
    setcookie('EWD_URP_Karma_IDs', serialize($EWD_URP_Karma_IDs), time()+3600*24*365, '/');

}
add_action('wp_ajax_urp_update_karma', 'EWD_URP_Update_Karama');
add_action('wp_ajax_nopriv_urp_update_karma', 'EWD_URP_Update_Karama');