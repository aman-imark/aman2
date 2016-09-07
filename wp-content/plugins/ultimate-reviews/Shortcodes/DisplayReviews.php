<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Display_Reviews($atts) {
	global $EWD_URP_Summary_Statistics_Array;
	$EWD_URP_Summary_Statistics_Array = array();

	$Custom_CSS = get_option("EWD_URP_Custom_CSS");
	$Maximum_Score = get_option("EWD_URP_Maximum_Score");
	$Review_Filtering = get_option("EWD_URP_Review_Filtering");
	$Reviews_Per_Page = get_option("EWD_URP_Reviews_Per_Page");
	$Pagination_Location = get_option("EWD_URP_Pagination_Location");

	$Summary_Statistics = get_option("EWD_URP_Summary_Statistics");
	$Email_Confirmation = get_option("EWD_URP_Email_Confirmation");

	$Group_By_Product = get_option("EWD_URP_Group_By_Product");
	$Group_By_Product_Order = get_option("EWD_URP_Group_By_Product_Order");
	$Ordering_Type = get_option("EWD_URP_Ordering_Type");
	$Order_Direction = get_option("EWD_URP_Order_Direction");

	$Reviews_Pagination_Label= get_option("EWD_URP_Pagination_Label");
		if ($Reviews_Pagination_Label == "") {$Reviews_Pagination_Label = __(" reviews ", 'EWD_URP');}

	$Pagination_Background = "";
	$Pagination_Border = "";
	$Pagination_Shadow = "";
	$Pagination_Gradient = "";

	$Order_By_Setting = 'date';
	$Order_Setting = "DESC";
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
			'search_string' => "",
			'product_name' => "",
			'only_reviews' => "No",
			'min_score' => 0,
			'max_score' => 1000000,
			'review_skin' => "",
			'review_format' => "",
			'orderby' => "",
			'order' => "",
			'current_page' => 1,
            'post_count'=>-1),
			$atts
		)
	);

	if (isset($_GET['current_page'])) {$current_page = $_GET['current_page'];}

	if ($order == "") {$order = $Order_Direction;}

	if ($orderby == "") {$orderby = $Ordering_Type;}

	$orderby_selection = array();

	if ($Group_By_Product == "Yes" and $product_name == "") {$orderby_selection['meta_value'] = $Group_By_Product_Order;}

	if ($orderby == "Rating") {$orderby_selection["meta_value_num"] = $order;}
	elseif ($orderby == "Date") {$orderby_selection['date'] = $order;}
	elseif ($orderby == "Title") {$orderby_selection['title'] = $order;}
	else {$orderby_selection[$orderby]= $order;}

	$HeaderString .= EWD_URP_Add_Modified_Styles();
	$HeaderString .= "<div class='ewd-urp-review-list' id='ewd-urp-review-list'>";

	$HeaderString .= "<input type='hidden' name'search_string' value='" . $search_string . "' id='urp-search-string' />";
	$HeaderString .= "<input type='hidden' name'product_name' value='" . $product_name . "' id='urp-product-name' />";
	$HeaderString .= "<input type='hidden' name'min_score' value='" . $min_score . "' id='urp-min-score' />";
	$HeaderString .= "<input type='hidden' name'max_score' value='" . $max_score . "' id='urp-max-score' />";
    $HeaderString .= "<input type='hidden' name'orderby' value='" . $orderby . "' id='urp-orderby' />";
    $HeaderString .= "<input type='hidden' name'order' value='" . $order . "' id='urp-order' />";
    $HeaderString .= "<input type='hidden' name'post_count' value='" . $post_count . "' id='urp-post-count' />";

	if ($product_name != "") {
		$meta_query_array = array(
								array(
									'key' => 'EWD_URP_Product_Name',
									'value' => $product_name,
									'compare' => '=',
								)
							);
	}
	else {
		$meta_query_array = array(
								array(
									'key' => 'EWD_URP_Product_Name',
									'value' => array(''),
									'compare' => 'NOT IN',
								)
							);
	}
	$meta_query_array[] = array(
								'key' => 'EWD_URP_Overall_Score',
								'value' => array($min_score, $max_score),
								'compare' => 'BETWEEN',
								'type' => 'DECIMAL'
	);

	$params = array('posts_per_page' => $post_count,
					'post_type' => 'urp_review',
					'orderby' => $orderby_selection,
					'order' => $order,
					'meta_query' => $meta_query_array,
					'suppress_filters' => false
			);

	if ($Group_By_Product == "Yes") {$params['meta_key'] = "EWD_URP_Product_Name";}
	elseif ($orderby == "Rating") {$params['meta_key'] = 'EWD_URP_Overall_Score';}

	if ($search_string != "") {$params['s'] = $search_string;}
	
	$Review_Query = new WP_Query($params);
	$Reviews = $Review_Query->get_posts();
	//echo $Review_Query->request;
	
	$Review_Params['product_name'] = $product_name;
	if ($review_skin != "") {$Review_Params['review_skin'] = $review_skin;}
	if ($review_format != "") {$Review_Params['review_format'] = $review_format;}

	if ($Custom_CSS != "") {$HeaderString .= "<style>" . $Custom_CSS . "</style>";}

	if (($Summary_Statistics == "Full" or $Summary_Statistics == "Limited") and $product_name != "") {$HeaderString .= "%SUMMARY_STATISTICS_PLACEHOLDER%";}

	if (!empty($Review_Filtering)) {$HeaderString .= "%FILTERING_PLACEHOLDER%";}
	$ReviewsString .= "%PAGINATION_PLACEHOLDER_TOP%";
	
	$Counter = 1;
	$Current_Product_Group = "";
	foreach ($Reviews as $Review) {
		if ($Email_Confirmation == "Yes") {
			$Email_Confirmed = get_post_meta($Review->ID, 'EWD_URP_Email_Confirmed', true);

			if ($Email_Confirmed != "Yes") {
				continue;
			}
		}

		$Product_Name = get_post_meta($Review->ID, 'EWD_URP_Product_Name', true);
		$Product_Names[] = $Product_Name;
		
		if ($Counter <= ($current_page - 1) * $Reviews_Per_Page or $Counter > $current_page * $Reviews_Per_Page) {
			$Counter++;
			continue;
		}

		if ($Group_By_Product == "Yes" and $product_name == "") {
			if ($Current_Product_Group != $Product_Name) {
				if ($Summary_Statistics == "Full" or $Summary_Statistics == "Limited") {$SummaryString = EWD_URP_Build_Summary_Statistics_String($Current_Product_Group, $Review_Params);}
				else {$SummaryString = "<div class='ewd-urp-product-group-heading'>" . __("Reviews for", 'EWD_URP') . " " . $Current_Product_Group . "</div>";}
				$ReviewsString = str_replace("%PRODUCT_SUMMARY_STATISTICS_PLACEHOLDER%", $SummaryString, $ReviewsString);
				$ReviewsString .= "%PRODUCT_SUMMARY_STATISTICS_PLACEHOLDER%";
				$Current_Product_Group = $Product_Name;
			}
		}

		$ReviewsString .= EWD_URP_Display_Review($Review, $Review_Params); 

		$Counter++;

	}

	if ($Group_By_Product == "Yes" and $product_name == "") {
		if ($Summary_Statistics == "Full" or $Summary_Statistics == "Limited") {$SummaryString = EWD_URP_Build_Summary_Statistics_String($Current_Product_Group, $Review_Params);}
		else {$SummaryString = "<div class='ewd-urp-product-group-heading'>" . __("Reviews for", 'EWD_URP') . " " . $Current_Product_Group . "</div>";}
		$ReviewsString = str_replace("%PRODUCT_SUMMARY_STATISTICS_PLACEHOLDER%", $SummaryString, $ReviewsString);
	}

	$ReviewsString .= "%PAGINATION_PLACEHOLDER_BOTTOM%";

	$FooterString .= "<div class='ewd-urp-clear'></div>";

	$FooterString .= "</div>";

	$Total_Reviews = sizeOf($Reviews);
	if ($Total_Reviews > $Reviews_Per_Page) {
		$Num_Pages = ceil($Total_Reviews / $Reviews_Per_Page);
				
		$PrevPage = max($current_page - 1, 1);
		$NextPage = min($current_page + 1, $Num_Pages);
				
		$Pagination_String .= "<div class='ewd-rup-reviews-nav ";
		$Pagination_String .= "ewd-urp-reviews-nav-bg-" . $Pagination_Background . " ";
		$Pagination_String .= "ewd-urp-reviews-border-" . $Pagination_Border . " ";
		$Pagination_String .= "ewd-urp-reviews-" . $Pagination_Shadow . " ";
		$Pagination_String .= "ewd-urp-reviews-" . $Pagination_Gradient . " ";
		$Pagination_String .= "'>";
		$Pagination_String .= "<input type='hidden' name'post_count' value='" . $current_page . "' id='urp-current-page' />";
		$Pagination_String .= "<input type='hidden' name'post_count' value='" . $Num_Pages . "' id='urp-max-page' />";
		$Pagination_String .= "<span class='displaying-num'>" . $Total_Reviews . $Reviews_Pagination_Label . "</span>";
		$Pagination_String .= "<span class='pagination-links'>";
		$Pagination_String .= "<a class='first-page ewd-urp-page-control' title='Go to the first page' data-controlvalue='first'>&#171;</a>";
		$Pagination_String .= "<a class='prev-page ewd-urp-page-control' title='Go to the previous page' data-controlvalue='back'>&#8249;</a>";
		$Pagination_String .= "<span class='paging-input'>" . $current_page . __(' of ', 'EWD_URP') . "<span class='total-pages'>" . $Num_Pages . "</span></span>";
		$Pagination_String .= "<a class='next-page ewd-urp-page-control' title='Go to the next page'  data-controlvalue='next'>&#8250;</a>";
		$Pagination_String .= "<a class='last-page ewd-urp-page-control' title='Go to the last page'  data-controlvalue='last'>&#187;</a>";
		$Pagination_String .= "</span>";
		$Pagination_String .= "</div>";
				
		if ($current_page == 1) {$Pagination_String = str_replace("first-page", "first-page disabled", $Pagination_String);}
		if ($current_page == 1) {$Pagination_String = str_replace("prev-page", "prev-page disabled", $Pagination_String);}
		if ($current_page == $Num_Pages) {$Pagination_String = str_replace("next-page", "next-page disabled", $Pagination_String);}
		if ($current_page == $Num_Pages) {$Pagination_String = str_replace("last-page", "last-page disabled", $Pagination_String);}
	}
	else {
		$Pagination_String = "";
	}

	if (!empty($Review_Filtering)) {
		$Filtering_String = "<div class='ewd-urp-filtering'>";
		$Filtering_String .= "<div class='ewd-urp-filtering-toggle ewd-urp-filtering-toggle-downcaret'>";
		$Filtering_String .= __("Filter", 'EWD_URP');
		$Filtering_String .= "</div>";
		$Filtering_String .= "<div class='ewd-urp-filtering-controls ewd-urp-hidden'>";
		if (in_array("Name", $Review_Filtering)) {
			if (is_array($Product_Names)) {
				$Unique_Product_Names = array_unique($Product_Names);
				if (sizeOf($Unique_Product_Names) > 1) {
					$Filtering_String .= "<div class='ewd-urp-filtering-product-name-div'>";
					$Filtering_String .= "<label class='ewd-urp-filtering-label'>" . __("Product name:", 'EWD_URP') . "</label>";
					$Filtering_String .= "<select class='ewd-filtering-product-name ewd-urp-filtering-select'>";
					$Filtering_String .= "<option value=''>" . __("All", 'EWD_URP') . "</option>";
					foreach ($Unique_Product_Names as $Product_Name) {$Filtering_String .= "<option value='" . $Product_Name . "'>" . $Product_Name . "</option>";}
					$Filtering_String .= "</select>";
					$Filtering_String .= "</div>";
				}
			}
		}
		if (in_array("Score", $Review_Filtering)) {
			$Filtering_String .= "<div class='ewd-urp-filtering-product-name-div'>";
			$Filtering_String .= "<label class='ewd-urp-filtering-label'>" . __("Review score:", 'EWD_URP') . "</label>";
			$Filtering_String .= "<span id='ewd-urp-score-range'>1 - " . $Maximum_Score . "</span>";
			$Filtering_String .= "<div id='ewd-urp-review-score-filter'></div>";
			$Filtering_String .= "</div>";
		}
		$Filtering_String .= "</div>";
		$Filtering_String .= "</div>";
		$HeaderString = str_replace("%FILTERING_PLACEHOLDER%", $Filtering_String, $HeaderString);
	}

	if (($Summary_Statistics == "Full" or $Summary_Statistics == "Limited") and $product_name != "") {
		$SummaryString = EWD_URP_Build_Summary_Statistics_String($product_name, $Review_Params);
		$HeaderString = str_replace("%SUMMARY_STATISTICS_PLACEHOLDER%", $SummaryString, $HeaderString);
	}

	if ($Pagination_Location == "Top" or $Pagination_Location == "Both") {$ReviewsString = str_replace("%PAGINATION_PLACEHOLDER_TOP%", $Pagination_String, $ReviewsString);}
	else {$ReviewsString = str_replace("%PAGINATION_PLACEHOLDER_TOP%", "", $ReviewsString);}
	if ($Pagination_Location == "Bottom" or $Pagination_Location == "Both") {$ReviewsString = str_replace("%PAGINATION_PLACEHOLDER_BOTTOM%", $Pagination_String, $ReviewsString);}
	else {$ReviewsString = str_replace("%PAGINATION_PLACEHOLDER_BOTTOM%", "", $ReviewsString);}

	if ($only_reviews != "Yes") {
		$ReturnString = $HeaderString;
		$ReturnString .= "<div class='ewd-urp-reviews-container'>";
		$ReturnString .= $ReviewsString;
		$ReturnString .= "</div>";
		$ReturnString .= $FooterString;
	}
	else {
		$ReturnString = $ReviewsString;
	}

	return $ReturnString;
}
add_shortcode("ultimate-reviews", "Display_Reviews");

add_filter('get_meta_sql','EWD_URP_Cast_Decimal_Precision');
function EWD_URP_Cast_Decimal_Precision( $array ) {
    $array['where'] = str_replace('DECIMAL','DECIMAL(10,2)',$array['where']);

    return $array;
}


function EWD_URP_Build_Summary_Statistics_String($Product_Name, $Review_Params) {
	global $EWD_URP_Summary_Statistics_Array;

	$Maximum_Score = get_option("EWD_URP_Maximum_Score");
	$Review_Style = get_option("EWD_URP_Review_Style");
	$Summary_Statistics = get_option("EWD_URP_Summary_Statistics");

	$SummaryString = "";

	if (isset($EWD_URP_Summary_Statistics_Array[$Product_Name])) {
		foreach ($EWD_URP_Summary_Statistics_Array[$Product_Name] as $ReviewScore => $Count) {
			if ($ReviewScore == "Total Weights") {continue;}
			$Total_Reviews += $Count;
			if (is_int($ReviewScore)) {$Integer_Count += $Count;}
			$Total_Score += $ReviewScore * $Count;
		}

		if ($Total_Reviews != 0) {
			$Average_Score = round($Total_Score / $Total_Reviews, 2);
			if ($Review_Weights == "Yes") {
				if ($EWD_URP_Summary_Statistics_Array[$Product_Name]['Total Weights'] == 0) {$EWD_URP_Summary_Statistics_Array[$Product_Name]['Total Weights'] = 1;}
				else {$EWD_URP_Summary_Statistics_Array[$Product_Name]['Total Weights'] = $EWD_URP_Summary_Statistics_Array[$Product_Name]['Total Weights'] / $Total_Reviews;}
				$Average_Score = $Average_Score / $EWD_URP_Summary_Statistics_Array[$Product_Name]['Total Weights'];
			}
			$Score_Width = max(($Average_Score * (100 / $Maximum_Score)), 0);
		}
		else {
			$Average_Score = "N/A"; 
			$Score_Width = 0;
			$Total_Reviews = 1;
		}

		if ($Integer_Count == 0) {$Integer_Count = 1;}

		$SummaryString .= "<div class='ewd-urp-summary-statistics-div'>";

		$SummaryString .= "<div class='ewd-urp-summary-statistics-header'>";
		if ($Review_Params['product_name'] == "") {$SummaryString .= "<div class='ewd-urp-summary-product-name'>" . __('Summary for', 'EWD_URP') . " " . $Product_Name . "</div>";}
		if ($Total_Reviews == 1) {$Ratings_Text = __("rating", 'EWD_URP');}
		else {$Ratings_Text = __("ratings", 'EWD_URP');}
		$SummaryString .= "<div class='ewd-urp-summary-average-score'>" . __("Average Score", 'EWD_URP') . ": " . $Average_Score . " (" . $Total_Reviews . " " . $Ratings_Text . ")" . "</div>";
		$SummaryString .= "</div>";

		if ($Summary_Statistics == "Full") {
			if ($Review_Style == "Percentage") {
				$SummaryString .= "<div class='ewd-urp-summary-percentage-graphic'>";
				$SummaryString .= "<div class='ewd-urp-summary-percentage-graphic-full' style='width:" . $Score_Width . "%'></div>";
				$SummaryString .= "</div>";
			}
			else {
				$SummaryString .= "<div class='ewd-urp-standard-summary-graphic'>";
				$SummaryString .= "<div class='ewd-urp-standard-summary-graphic-full' style='width:" . $Score_Width . "%'></div>";
				$SummaryString .= "</div>";
				for ($i=$Maximum_Score; $i>=1; $i--) {
					$Sub_Score_Width = max(($EWD_URP_Summary_Statistics_Array[$Product_Name][$i] / $Integer_Count), 0) * 100;
					$SummaryString .= "<div class='ewd-urp-summary-score-value'>" . $i . "</div>";
					$SummaryString .= "<div class='ewd-urp-standard-summary-graphic-sub-group'>";
					$SummaryString .= "<div class='ewd-urp-standard-summary-graphic-full-sub-group'  style='width:" . $Sub_Score_Width . "%'></div>";
					$SummaryString .= "</div>";
					$SummaryString .= "<div class='ewd-urp-summary-score-count'>" . max($EWD_URP_Summary_Statistics_Array[$Product_Name][$i], 0) . "</div>";
					$SummaryString .= "<div class='ewd-urp-clear'></div>";
				}
			}
		}

		$SummaryString .= "</div>";
	}

	return $SummaryString;
}

function EWD_URP_Rand_Chars($CharLength = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $CharLength; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}