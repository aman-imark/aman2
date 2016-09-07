<?php

add_filter( 'woocommerce_product_tabs', 'EWD_URP_Replace_WooCommerce_Reviews', 98 );
function EWD_URP_Replace_WooCommerce_Reviews($tabs) {
	$Replace_WooCommerce_Reviews = get_option("EWD_URP_Replace_WooCommerce_Reviews");

	if ($Replace_WooCommerce_Reviews == "Yes") {
		$tabs['reviews']['callback'] = 'EWD_URP_WooCommerce_Reviews';
	}

	return $tabs;
}

function EWD_URP_WooCommerce_Reviews() {
	global $product;

	$Maximum_Score = get_option("EWD_URP_Maximum_Score");

	$Post_Data = $product->get_post_data();

	echo "<h2>" . __("Reviews", 'EWD_URP') . "</h2>";

	/*echo "<div itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating'>";
	echo "<meta itemprop='ratingValue' content='" . EWD_URP_Get_Aggregate_Score($Post_Data->post_title) . "' />";
	echo "<meta itemprop='bestRating' content='10' />";
    echo "<meta itemprop='worstRating' content='1' />";
    echo "<meta itemprop='ratingCount' content='" . EWD_URP_Get_Review_Count($Post_Data->post_title) . "' />";
    echo "</div>";*/
	
	echo do_shortcode("[ultimate-reviews product_name='" . $Post_Data->post_title . "']");
	echo "<div class='ewd-urp-woocommerce-tab-divider'></div>";
	echo "<h2>" . __("Leave a review", 'EWD_URP') . "</h2>";
	echo "<style>.ewd-urp-form-header {display:none;}</style>";
	echo do_shortcode("[submit-review product_name='" . $Post_Data->post_title . "']");
}

add_filter( 'woocommerce_product_tabs', 'EWD_URP_WooCommerce_Add_Review_Count', 98 );
function EWD_URP_WooCommerce_Add_Review_Count($tabs) {
	global $product, $wp_filter;

	$Replace_WooCommerce_Reviews = get_option("EWD_URP_Replace_WooCommerce_Reviews");

	if ($Replace_WooCommerce_Reviews == "Yes" and is_object($product)) {
		$Post_Data = $product->get_post_data();

		$Title = __('Reviews', 'EWD_URP') . " (" . EWD_URP_Get_Review_Count($Post_Data->post_title) . ")";

		$tabs['reviews']['title'] = $Title;	
	}

	return $tabs;
}

add_filter('woocommerce_product_get_rating_html', 'EWD_URP_WooCommerce_Rating_Filter', 98, 2);
add_filter('woocommerce_template_single_rating', 'EWD_URP_WooCommerce_Rating_Filter', 98, 2);
function EWD_URP_WooCommerce_Rating_Filter($content, $rating) {
	global $product;

	$Maximum_Score = get_option("EWD_URP_Maximum_Score");
	$Replace_WooCommerce_Reviews = get_option("EWD_URP_Replace_WooCommerce_Reviews");

	if ($Replace_WooCommerce_Reviews != "Yes") {return $content;}

	$Post_Data = $product->get_post_data();
	$EWD_URP_Rating = EWD_URP_Get_Aggregate_Score($Post_Data->post_title);

	$rating_html  = '<div class="star-rating" title="' . sprintf( __( 'Rated %s out of %s', 'woocommerce' ), $EWD_URP_Rating, $Maximum_Score ) . '">';
	$rating_html .= '<span style="width:' . (( $EWD_URP_Rating / $Maximum_Score ) * 100 ) . '%"><strong class="rating">' . $EWD_URP_Rating . '</strong> ' . sprintf( __( 'out of %s', 'woocommerce' ), $Maximum_Score) . '</span>';
	$rating_html .= '</div>';

	return $rating_html;
}

add_filter('woocommerce_product_review_count', 'EWD_URP_WooCommerce_Review_Count_Filter', 98, 2);
function EWD_URP_WooCommerce_Review_Count_Filter($count, $item) {
	global $product;

	$Post_Data = $product->get_post_data();

	return EWD_URP_Get_Review_Count($Post_Data->post_title);
}

add_filter('woocommerce_locate_template', 'EWD_URP_WooCommerce_Locate_Template', 10, 3);
function EWD_URP_WooCommerce_Locate_Template($template, $template_name, $template_path) {
	global $woocommerce;

	if ($template_name != "single-product/rating.php") {return $template;}

	$Replace_WooCommerce_Reviews = get_option("EWD_URP_Replace_WooCommerce_Reviews");
	$Override_WooCommerce_Theme = get_option("EWD_URP_Override_WooCommerce_Theme");

	$_template = $template;

	// Look within passed path within the theme - this is priority
	if (!$template_path) {$template_path = $woocommerce->template_url;}
	$template = locate_template(array(
								$template_path . $template_name,
								$template_name
							)
 	);

	// Modification: Get the template from this plugin, if it exists
	$Ratings_File_Path  = EWD_URP_CD_PLUGIN_PATH . 'html/WC_Rating.php';
	if ($Replace_WooCommerce_Reviews == "Yes" and (!$template or $Override_WooCommerce_Theme == "Yes") and file_exists($Ratings_File_Path)) {$template = $Ratings_File_Path;}

	// Use default template
	if (!$template) {$template = $_template;}

	return $template;
}

?>