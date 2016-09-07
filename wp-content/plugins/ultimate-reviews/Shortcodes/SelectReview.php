<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Display_Select_Review($atts) {
	global $EWD_URP_Summary_Statistics_Array;
	$EWD_URP_Summary_Statistics_Array = array();

	$Custom_CSS = get_option("EWD_URP_Custom_CSS");

	$ReturnString = "";
	$Review_Params = array();

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
									'review_name' => "",
									'review_slug' => "",
									'review_id' => ""),
									$atts
		)
	);

	$name_array = explode(",", $review_name);
	$slug_array = explode(",", $review_slug);
	$id_array = explode(",", $review_id);

	foreach ($name_array as $post_name) {
		$single_post = get_page_by_title($post_name, "OBJECT", "urp_review");
		$post_id_array[] = $single_post->ID;
	}

	foreach ($slug_array as $post_slug) {
		$single_post = get_page_by_path($post_slug, "OBJECT", "urp_review");
		$post_id_array[] = $single_post->ID;
	}

	foreach ($id_array as $post_id) {
		$post_id_array[] = $post_id;
	}

	$params = array(
					'posts_per_page' => -1,
					'post_type' => 'urp_review',
					'include' => $post_id_array
				);
	$Reviews = get_posts($params);

	$ReturnString .= EWD_URP_Add_Modified_Styles();
	$ReturnString .= "<div class='ewd-urp-review-list' id='ewd-urp-review-list'>";

	if ($Custom_CSS != "") {$ReturnString .= "<style>" . $Custom_CSS . "</style>";}

	foreach ($Reviews as $Review) {
		$ReturnString .= EWD_URP_Display_Review($Review, $Review_Params);
	}

	$ReturnString .= "<div class='ewd-urp-clear'></div>";
	
	$ReturnString .= "</div>";

	return $ReturnString;
}
add_shortcode("select-review", "Display_Select_Review");

function EWD_URP_Display_Review($Review, $Review_Params = array()) {
	global $EWD_URP_Summary_Statistics_Array;

	$Maximum_Score = get_option("EWD_URP_Maximum_Score");
	$Review_Style = get_option("EWD_URP_Review_Style");
	$Review_Image = get_option("EWD_URP_Review_Image");
	$InDepth_Reviews = get_option("EWD_URP_InDepth_Reviews");
	$Review_Categories_Array = get_option("EWD_URP_Review_Categories_Array");
	$Link_To_Post = get_option("EWD_URP_Link_To_Post");
	$Display_Author = get_option("EWD_URP_Display_Author");
	$Display_Date = get_option("EWD_URP_Display_Date");

	$Review_Format = get_option("EWD_URP_Review_Format");
	$Review_Weights = get_option("EWD_URP_Review_Weights");
	$Review_Karma = get_option("EWD_URP_Review_Karma");
	$Thumbnail_Characters = get_option("EWD_URP_Thumbnail_Characters");
	$Display_Numerical_Score = get_option("EWD_URP_Display_Numerical_Score");
	$Reviews_Skin = get_option("EWD_URP_Reviews_Skin");

	$Group_By_Product = get_option("EWD_URP_Group_By_Product");

	$Posted_Label = get_option("EWD_URP_Posted_Label");
		if ($Posted_Label == "") {$Posted_Label = __("Posted ", 'EWD_URP');}
	$By_Label = get_option("EWD_URP_By_Label");
		if ($By_Label == "") {$By_Label = __("by ", 'EWD_URP');}
	$On_Label = get_option("EWD_URP_On_Label");
		if ($On_Label == "") {$On_Label = __("on ", 'EWD_URP');}
	$Score_Label = get_option("EWD_URP_Score_Label");
		if ($Score_Label == "") {$Score_Label = __("Score ", 'EWD_URP');}
	$Explanation_Label = get_option("EWD_URP_Explanation_Label");
		if ($Explanation_Label == "") {$Explanation_Label = __("Explanation ", 'EWD_URP');}

	$Unique_ID = EWD_URP_Rand_Chars(3);

	if (array_key_exists("review_skin", $Review_Params)) {
		if ($Reviews_Skin != "Basic" and $Reviews_Skin = $Review_Params['review_skin']) {
    		$ReturnString .= "<link rel='stylesheet' href='" . EWD_URP_CD_PLUGIN_URL . "css/addtl/" . $Reviews_Skin . ".css' type='text/css' media='all' />";
    	}
		$Reviews_Skin = $Review_Params['review_skin'];
	}
	if (array_key_exists("review_format", $Review_Params)) {$Review_Format = $Review_Params['review_format'];}
	
	$Weight = get_post_meta( $Review->ID, 'EWD_URP_Review_Weight', true );
	$Karma = get_post_meta( $Review->ID, 'EWD_URP_Review_Karma', true );
	$Product_Name = get_post_meta($Review->ID, 'EWD_URP_Product_Name', true);		
	$Overall_Score = get_post_meta($Review->ID, 'EWD_URP_Overall_Score', true);
	$Post_Author = get_post_meta($Review->ID, 'EWD_URP_Post_Author', true);
	$Permalink = get_the_permalink($Review->ID);

	if ($Karma == "") {$Karma = 0;}
	$EWD_URP_Karma_IDs = unserialize(stripslashes($_COOKIE['EWD_URP_Karma_IDs']));
	if (!is_array($EWD_URP_Karma_IDs)) {$EWD_URP_Karma_IDs = array();}
	if (in_array($Review->ID, $EWD_URP_Karma_IDs)) {$Karma_ID = "0";}
	else {$Karma_ID = $Review->ID;}

	if ($Review_Weights == "Yes") {
		if ($Review_Weight == "") {$Review_Weight = 0;}
		$EWD_URP_Summary_Statistics_Array[$Product_Name]['Total Weights'] += $Weight;
		$EWD_URP_Summary_Statistics_Array[$Product_Name][$Overall_Score] += $Weight;
	}
	else {
		$EWD_URP_Summary_Statistics_Array[$Product_Name][$Overall_Score]++;
	}

	if ($Review_Format == "Thumbnail") {$Body_Class = 'ewd-urp-review-div ewd-urp-thumbnail-review';}
	else {$Body_Class = 'ewd-urp-review-div';}
	
	$ReturnString .= "<div class='" . $Body_Class . "'>";

	if ($Group_By_Product == "No" and isset($Review_Params['product_name']) and $Review_Params['product_name'] == "") {
		$ReturnString .= "<div class='ewd-urp-review-product-name'>";
		$ReturnString .= __("Review for ", 'EWD_URP') . $Product_Name;
		$ReturnString .= "</div>";
	}

	if ($Review_Format == "Expandable") {$Header_Class = "ewd-urp-review-header ewd-urp-expandable-title";}
	else {$Header_Class = "ewd-urp-review-header";}

	$ReturnString .= "<div class='" . $Header_Class . "' data-postid='" . $Unique_ID . "-" . $Review->ID . "'>";
	$ReturnString .= "<div class='ewd-urp-review-score'>";

	if ($Reviews_Skin == "SimpleStars" or $Reviews_Skin == "Thumbs" or $Reviews_Skin == "Hearts") {
		if ($Reviews_Skin == "SimpleStars") {
			$Filled_Class_Name = "dashicons dashicons-star-filled";
			$Half_Class_Name = "dashicons dashicons-star-half";
			$Empty_Class_Name = "dashicons dashicons-star-empty";
		}
		elseif ($Reviews_Skin == "Thumbs") {
			$Filled_Class_Name = "ewd-urp-thumb ewd-urp-full";
			$Half_Class_Name = "ewd-urp-thumb ewd-urp-half";
			$Empty_Class_Name = "ewd-urp-thumb ewd-urp-empty";
		}
		elseif ($Reviews_Skin == "Hearts") {
			$Filled_Class_Name = "ewd-urp-heart ewd-urp-full";
			$Half_Class_Name = "ewd-urp-heart ewd-urp-half";
			$Empty_Class_Name = "ewd-urp-heart ewd-urp-empty";
		}
		
		$ReturnString .= "<div class='ewd-urp-review-graphic'>";
		for ($i = 1; $i <= $Maximum_Score; $i++) {
			if ($i <= ($Overall_Score + .25)) {$ReturnString .= "<div class='" . $Filled_Class_Name . "'></div>";}
			elseif ($i <= ($Overall_Score + .75)) {$ReturnString .= "<div class='" . $Half_Class_Name . "'></div>";}
			else {$ReturnString .= "<div class='" . $Empty_Class_Name . "'></div>";}
		}
		$ReturnString .= "</div>";
	}
	elseif ($Reviews_Skin == "ColorBar" or $Reviews_Skin == "SimpleBar") {
		if ($Reviews_Skin == "SimpleBar") {$ColorBar_Class = "ewd-urp-blue-bar";}
		elseif ($Overall_Score < 1.67 and $Reviews_Skin == "ColorBar") {$ColorBar_Class = "ewd-urp-red-bar";}
		elseif ($Overall_Score < 3.34 and $Reviews_Skin == "ColorBar") {$ColorBar_Class = "ewd-urp-yellow-bar";}
		else {$ColorBar_Class = "ewd-urp-green-bar";}
		$ColorBar_Width = round(($Overall_Score * (100 / $Maximum_Score)) * 0.95,2);
		$ColorBar_Margin = round((100 - $Overall_Score * (100 / $Maximum_Score)) * 0.95, 2);
		$ReturnString .= "<div class='ewd-urp-review-graphic'>";
		$ReturnString .= "<div class='ewd-urp-color-bar " . $ColorBar_Class . "' style='width:" . $ColorBar_Width . "%;margin-right:" . $ColorBar_Margin . "%;'></div>";
		$ReturnString .= "</div>";
	}

	if ($Display_Numerical_Score == "Yes") {
		if ($Review_Style == "Percentage") {$ReturnString .= "<div class='ewd-urp-review-score-number'>" . round($Overall_Score,1) . "%</div>";}
		else {$ReturnString .= "<div class='ewd-urp-review-score-number'>" . round($Overall_Score,1) . "/" . $Maximum_Score . "</div>";}
	}

	$ReturnString .= "</div>";

	if ($Review_Karma == "Yes") {
		$ReturnString .= "<div class='ewd-urp-clear'></div>";
		$ReturnString .= "<div class='ewd-urp-review-karma'>";
		$ReturnString .= "<div class='ewd-urp-karma-control ewd-urp-karma-up' data-reviewid='" . $Karma_ID . "'></div>";
		$ReturnString .= "<div class='ewd-urp-karma-score' id='ewd-urp-karma-score-" . $Review->ID . "'>" . $Karma . "</div>";
		$ReturnString .= "<div class='ewd-urp-karma-control ewd-urp-karma-down' data-reviewid='" . $Karma_ID . "'></div>";
		$ReturnString .= "</div>";
	}
	
	if ($Link_To_Post == "Yes") {$ReturnString .= "<a href='" . $Permalink . "' class='ewd-urp-review-link'>";}
	$ReturnString .= "<div class='ewd-urp-review-title' id='ewd-urp-title-" . $Unique_ID . "-" . $Review->ID . "' data-postid='" . $Unique_ID . "-" . $Review->ID . "'>" . $Review->post_title . "</div>";
	if ($Link_To_Post == "Yes") {$ReturnString .= "</a>";}

	$ReturnString .= "</div>";

	$ReturnString .= "<div class='ewd-urp-clear'></div>";

	if ($Review_Format == "Expandable") {$Content_Class = "ewd-urp-review-content ewd-urp-content-hidden";}
	else {$Content_Class = "ewd-urp-review-content";}
	
	$ReturnString .= "<div class='" . $Content_Class . "' id='ewd-urp-review-content-" . $Unique_ID . "-" . $Review->ID . "' data-postid='" . $Unique_ID . "-" . $Review->ID . "'>";

	if ($Display_Author == "Yes"  or $Display_Date == "Yes") {
		$Post_Author = get_post_meta($Review->ID, 'EWD_URP_Post_Author', true);
		$ReturnString .= "<div class='ewd-urp-author-date'>";
		$ReturnString .= $Posted_Label . " " ;
		if ($Display_Author == "Yes" and $Post_Author != "") {$ReturnString .= $By_Label . " <span class='ewd-urp-author'>" . $Post_Author . "</span> ";}
		if ($Display_Date == "Yes") {$ReturnString .= $On_Label . " <span class='ewd-urp-date'>" . $Review->post_date . "</span> ";}
		$ReturnString .= "</div>";
	}

	$ReturnString .= "<div class='ewd-urp-clear'></div>";

	if ($Review_Image == "Yes") {
		if (has_post_thumbnail($Review->ID)) {
			$ReturnString .= "<div class='ewd-urp-review-image ewd-urp-image-" . $Review_Format ."'>";
			$ReturnString .= get_the_post_thumbnail($Review->ID);
			$ReturnString .= "</div>";
		}
	}

	$ReturnString .= "<div class='ewd-urp-clear'></div>";

	if ($Review_Format == "Thumbnail") {$Content = substr($Review->post_content, 0, $Thumbnail_Characters);}
	else {$Content = $Review->post_content;}
	
	$ReturnString .= "<div class='ewd-urp-review-body' id='ewd-urp-body-" . $Unique_ID . "-" . $Review->ID . "'>";
	$ReturnString .= "<div class='ewd-urp-review-margin ewd-urp-review-post' id='ewd-urp-review-" . $Unique_ID . "-" . $Review->ID . "'>" . apply_filters('the_content', html_entity_decode($Content)) . "</div>";
	if ($Review_Format == "Thumbnail") {$ReturnString .= "<div class='ewd-urp-thumbnail-read-more'><a href='" . $Permalink . "'>" . __('Read More', 'EWD_URP') . "..." . "</a></div>";}
	$ReturnString .= "</div>";
	
	if ($InDepth_Reviews == "Yes" and $Review_Categories_Array[0]['CategoryName'] != "") {
		foreach ($Review_Categories_Array as $Review_Category_Item) {
			if ($Review_Category_Item['CategoryName'] != "") {
				$Review_Category_Score = get_post_meta($Review->ID, "EWD_URP_" . $Review_Category_Item['CategoryName'], true);
				$Review_Category_Description = get_post_meta($Review->ID, "EWD_URP_" . $Review_Category_Item['CategoryName'] . "_Description", true);
				
				$ReturnString .= "<div class='ewd-urp-category-field'>";
				$ReturnString .= "<div class='ewd-urp-category-score'>";
				$ReturnString .= "<div class='ewd-urp-category-score-label'>"; 
				$ReturnString .= $Review_Category_Item['CategoryName'] . " " . $Score_Label . ": ";
				$ReturnString .= "</div>";
				$ReturnString .= "<div class='ewd-urp-category-score-number'>"; 
				$ReturnString .= $Review_Category_Score;
				$ReturnString .= "</div>";
				$ReturnString .= "</div>";

				if ($Review_Category_Item['ExplanationAllowed'] == "Yes") {
					$ReturnString .= "<div class='ewd-urp-category-explanation'>";
					$ReturnString .= "<div class='ewd-urp-category-explanation-label'>"; 
					$ReturnString .= $Review_Category_Item['CategoryName'] . " " . $Explanation_Label . ": ";
					$ReturnString .= "</div>";
					$ReturnString .= "<div class='ewd-urp-category-explanation-text'>"; 
					$ReturnString .= $Review_Category_Description;
					$ReturnString .= "</div>";
					$ReturnString .= "</div>";
				}
				$ReturnString .= "</div>";
			}
		}
	}

	$ReturnString .= "</div>";

	$ReturnString .= "<div class='ewd-urp-clear'></div>";

	$ReturnString .= "</div>";

	return $ReturnString;
}