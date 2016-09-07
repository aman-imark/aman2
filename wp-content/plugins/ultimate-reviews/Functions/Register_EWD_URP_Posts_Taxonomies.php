<?php
add_action( 'init', 'EWD_URP_Create_Posttype' );
function EWD_URP_Create_Posttype() {
		$labels = array(
				'name' => __('Reviews', 'EWD_URP'),
				'singular_name' => __('Review', 'EWD_URP'),
				'menu_name' => __('Reviews', 'EWD_URP'),
				'add_new' => __('Add New', 'EWD_URP'),
				'add_new_item' => __('Add New Review', 'EWD_URP'),
				'edit_item' => __('Edit Review', 'EWD_URP'),
				'new_item' => __('New Review', 'EWD_URP'),
				'view_item' => __('View Review', 'EWD_URP'),
				'search_items' => __('Search Reviews', 'EWD_URP'),
				'not_found' =>  __('Nothing found', 'EWD_URP'),
				'not_found_in_trash' => __('Nothing found in Trash', 'EWD_URP'),
				'parent_item_colon' => ''
		);

		$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'query_var' => true,
				'has_archive' => true,
				'menu_icon' => null,
				'rewrite' => array('slug' => 'review'),
				'capability_type' => 'post',
				'menu_position' => null,
				'menu_icon' => 'dashicons-format-status',
				'supports' => array('title','editor','author','excerpt','comments', 'thumbnail')
	  ); 

	register_post_type( 'urp_review' , $args );
}

add_action( 'add_meta_boxes', 'EWD_URP_Add_Meta_Boxes' );
function EWD_URP_Add_Meta_Boxes () {
	add_meta_box("review-meta", __("Review Details", 'EWD_URP'), "EWD_URP_Meta_Box", "urp_review", "normal", "high");
}

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function EWD_URP_Meta_Box( $post ) {
	$Review_Weights = get_option("EWD_URP_Review_Weights");
	$Review_Karma = get_option("EWD_URP_Review_Karman");
	$Email_Confirmation = get_option("EWD_URP_Email_Confirmation");
	$InDepth_Reviews = get_option("EWD_URP_InDepth_Reviews");
	$Review_Categories_Array = get_option("EWD_URP_Review_Categories_Array");
	if (!is_array($Review_Categories_Array)) {$Review_Categories_Array = array();}

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'EWD_URP_Save_Meta_Box_Data', 'EWD_URP_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */

	if ($Review_Weights == "Yes") {
		$Value = get_post_meta( $post->ID, 'EWD_URP_Review_Weight', true );
	?>	
		<div class="ewd-urp-meta-field">
		<label class="ewd-urp-meta-label" for="Review_Weight">
		<?php _e( "Review Weight:", 'EWD_URP' ); ?>
		</label>
		<input type='text' id='ewd-urp-review-weight' name='Review_Weight' value='" . esc_attr( $Value ) . "' size='25' />";
		</div>
	<?php }

	if ($Review_Karma == "Yes") {
		$Value = get_post_meta( $post->ID, 'EWD_URP_Review_Karma', true );
	?>	
		<div class="ewd-urp-meta-field">
		<label class="ewd-urp-meta-label" for="Review_Karma">
		<?php _e( "Review Karma:", 'EWD_URP' ); ?>
		</label>
		<input type='text' id='ewd-urp-review-karma' name='Review_Karma' value='" . esc_attr( $Value ) . "' size='25' />";
		</div>
	<?php }

	if ($Email_Confirmation == "Yes") {
		$Value = get_post_meta( $post->ID, 'EWD_URP_Email_Confirmed', true );
	?>	
		<div class="ewd-urp-meta-field">
		<label class="ewd-urp-meta-label" for="Email_Confirmed">
		<?php _e( "Email Confirmed:", 'EWD_URP' ); ?>
		</label>
		<input type="radio" id="ewd-urp-email-confirmed" name="Email_Confirmed" value='Yes' <?php if ($Value != "No") {echo "checked=checked";} ?> />Yes &nbsp;&nbsp;&nbsp;
		<input type="radio" id="ewd-urp-email-confirmed" name="Email_Confirmed" value='No' <?php if ($Value == "No") {echo "checked=checked";} ?> />No
		</div>
	<?php }

	if ($Review_Weights == "Yes" or $Review_Karma == "Yes" or $Email_Confirmation == "Yes") {echo "<div class='ewd-urp-meta-separator'></div>";}

	$Value = get_post_meta( $post->ID, 'EWD_URP_Product_Name', true );

	echo "<div class='ewd-urp-meta-field'>";
	echo "<label class='ewd-urp-meta-label' for='Product_Name'>";
	echo __( "Product Name:", 'EWD_URP' );
	echo " </label>";
	echo "<input type='text' id='ewd-urp-product-name' name='Product_Name' value='" . esc_attr( $Value ) . "' size='25' />";
	echo "</div>";

	$Value = get_post_meta( $post->ID, 'EWD_URP_Post_Author', true );

	echo "<div class='ewd-urp-meta-field'>";
	echo "<label class='ewd-urp-meta-label' for='Post_Author'>";
	echo __( "Post Author:", 'EWD_URP' );
	echo " </label>";
	echo "<input type='text' id='ewd-urp-post-author' name='Post_Author' value='" . esc_attr( $Value ) . "' size='25' />";
	echo "</div>";

	if ($InDepth_Reviews == "No" or sizeOf($Review_Categories_Array) == 0) {
		$Value = get_post_meta($post->ID, "EWD_URP_Overall_Score", true);

		echo "<div class='ewd-urp-meta-field'>";
		echo "<label class='ewd-urp-meta-label' for='Overall Score'>";
		echo  __("Overall Score", 'EWD_URP') . ": ";
		echo "</label>";
		echo "<input type='text' id='ewd-urp-overall-score' name='EWD_URP_Overall_Score' value='" . esc_attr( $Value ) . "' size='25' />";
		echo "</div>";
	}
	else {
		foreach ($Review_Categories_Array as $Review_Categories_Item) {
			$Value = get_post_meta($post->ID, "EWD_URP_" . $Review_Categories_Item['CategoryName'], true);
			if ($Review_Categories_Item['ExplanationAllowed'] == "Yes") {$Description = get_post_meta($post->ID, "EWD_URP_" . $Review_Categories_Item['CategoryName'] . "_Description", true);}

			echo "<div class='ewd-urp-meta-field'>";
			echo "<label class='ewd-urp-score-label' for='" . $Review_Categories_Item['CategoryName'] . "'>";
			echo $Review_Categories_Item['CategoryName'] . " " . __("Score", 'EWD_URP') . ": ";
			echo "</label>";
			echo "<input type='text' id='ewd-urp-" . $Review_Categories_Item['CategoryName'] . "' name='EWD_URP_" . $Review_Categories_Item['CategoryName'] . "' value='" . esc_attr( $Value ) . "' size='25' />";
			echo "</div>";

			if ($Review_Categories_Item['ExplanationAllowed'] == "Yes") {
				echo "<div class='ewd-urp-meta-field'>";
				echo "<label class='ewd-urp-explanation-label' for='" . $Review_Categories_Item['CategoryName'] . " Description'>";
				echo $Review_Categories_Item['CategoryName'] . " " . __("Explanation", 'EWD_URP') . ": ";
				echo "</label>";
				echo "<textarea name='EWD_URP_" . $Review_Categories_Item['CategoryName'] . " Description'>";
				echo esc_attr($Description);
				echo "</textarea>";
				echo "</div>";
			}
		}
	}
}

add_action( 'save_post', 'EWD_URP_Save_Meta_Box_Data' );
function EWD_URP_Save_Meta_Box_Data($post_id) {
	$Maximum_Score = get_option("EWD_URP_Maximum_Score");
	$InDepth_Reviews = get_option("EWD_URP_InDepth_Reviews");
	$Review_Categories_Array = get_option("EWD_URP_Review_Categories_Array");
	if (!is_array($Review_Categories_Array)) {$Review_Categories_Array = array();}

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['EWD_URP_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['EWD_URP_meta_box_nonce'], 'EWD_URP_Save_Meta_Box_Data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. If there's no product name, don't save any other information.*/
	if ( ! isset( $_POST['Product_Name'] ) ) {
		return;
	}

	// Sanitize user input.
	$Review_Weight = sanitize_text_field( $_POST['Review_Weight'] );
	$Review_Karma = sanitize_text_field( $_POST['Review_Karma'] );
	$Email_Confirmed = sanitize_text_field( $_POST['Email_Confirmed'] );
	$Product_Name = sanitize_text_field( $_POST['Product_Name'] );
	$Post_Author = sanitize_text_field( $_POST['Post_Author'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'EWD_URP_Review_Weight', $Review_Weight );
	update_post_meta( $post_id, 'EWD_URP_Review_Karma', $Review_Karma );
	update_post_meta( $post_id, 'EWD_URP_Email_Confirmed', $Email_Confirmed );
	update_post_meta( $post_id, 'EWD_URP_Product_Name', $Product_Name );
	update_post_meta( $post_id, 'EWD_URP_Post_Author', $Post_Author );

	if ($InDepth_Reviews == "No" or sizeOf($Review_Categories_Array) == 0) {
		$Overall_Score = min(round(sanitize_text_field($_POST['EWD_URP_Overall_Score']), 2), $Maximum_Score);
		update_post_meta($post_id, "EWD_URP_Overall_Score", $Overall_Score);
	}
	else {
		foreach ($Review_Categories_Array as $Review_Categories_Item) {
			$Category_Score = min($_POST['EWD_URP_' . str_replace(" ", "_", $Review_Categories_Item['CategoryName'])], $Maximum_Score);
			update_post_meta($post_id, "EWD_URP_" . $Review_Categories_Item['CategoryName'], $Category_Score);
			$Overall_Score += $Category_Score;

			if ($Review_Categories_Item['ExplanationAllowed'] == "Yes") {
				$Category_Description = $_POST['EWD_URP_' . str_replace(" ", "_", $Review_Categories_Item['CategoryName']) . "_Description"];
				update_post_meta($post_id, "EWD_URP_" . $Review_Categories_Item['CategoryName'] . "_Description", $Category_Description);
			}
		}
		$Overall_Score = round($Overall_Score / sizeOf($Review_Categories_Array), 2);
		update_post_meta($post_id, "EWD_URP_Overall_Score", $Overall_Score);
	}
}

function EWD_URP_Add_Review_Information($content) {
	global $post;

    if ($post->post_type == 'urp_review') {
   		$Custom_CSS = get_option("EWD_URP_Custom_CSS");
		$InDepth_Reviews = get_option("EWD_URP_InDepth_Reviews");
		$Review_Categories_Array = get_option("EWD_URP_Review_Categories_Array");
		$Display_Author = get_option("EWD_URP_Display_Author");
		$Display_Date = get_option("EWD_URP_Display_Date");

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
	
		$Display_Numerical_Score = get_option("EWD_URP_Display_Numerical_Score");
		$Reviews_Skin = get_option("EWD_URP_Reviews_Skin");

		$Updated_View_Count = get_post_meta($post->ID, 'urp_view_count', true) + 1;
		update_post_meta($post->ID, 'urp_view_count', $Updated_View_Count);

    	$HeaderString = "";
    	$ReturnString = "";

   		$Product_Name = get_post_meta($post->ID, 'EWD_URP_Product_Name', true);		
		$Post_Author = get_post_meta($post->ID, 'EWD_URP_Post_Author', true);

		$HeaderString .= EWD_URP_Add_Modified_Styles();
		if ($Custom_CSS != "") {$HeaderString .= "<style>" . $Custom_CSS . "</style>";}

		$HeaderString .= "<div class='ewd-urp-review-product-name'>";
		$HeaderString .= __("Review for ", 'EWD_URP') . $Product_Name;
		$HeaderString .= "</div>";

   		if ($Display_Author == "Yes"  or $Display_Date == "Yes") {
			$Post_Author = get_post_meta($post->ID, 'EWD_URP_Post_Author', true);
			$ReturnString .= "<div class='ewd-urp-author-date'>";
			$ReturnString .= $Posted_Label . " " ;
			if ($Display_Author == "Yes" and $Post_Author != "") {$ReturnString .= $By_Label . " <span class='ewd-urp-author'>" . $Post_Author . "</span> ";}
			if ($Display_Date == "Yes") {$ReturnString .= $On_Label . " <span class='ewd-urp-date'>" . $post->post_date . "</span> ";}
			$ReturnString .= "</div>";
		}

		if ($InDepth_Reviews == "Yes" and $Review_Categories_Array[0]['CategoryName'] != "") {
			foreach ($Review_Categories_Array as $Review_Category_Item) {
				if ($Review_Category_Item['CategoryName'] != "") {
					$Review_Category_Score = get_post_meta($post->ID, "EWD_URP_" . $Review_Category_Item['CategoryName'], true);
					$Review_Category_Description = get_post_meta($post->ID, "EWD_URP_" . $Review_Category_Item['CategoryName'] . "_Description", true);
						
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
		$content = $HeaderString . $content . $ReturnString;
    }

    return $content;
}
add_filter('the_content', 'EWD_URP_Add_Review_Information');

function EWD_URP_Filter_The_Author($author) {
	global $post;

    if ($post->post_type == 'urp_review') {
    	$author = get_post_meta($post->ID, 'EWD_URP_Post_Author', true);
    }

    return $author;
}
add_filter('the_author', 'EWD_URP_Filter_The_Author');

function EWD_URP_Add_Score_To_Title($title, $id = null) {
	global $post;

    if ($post->post_type == 'urp_review') {
    	$Overall_Score = get_post_meta($post->ID, 'EWD_URP_Overall_Score', true);

    	$title = "<div class='ewd-urp-review-score-number'>" . round($Overall_Score,1) . "/5</div>" . $title;
    }

    return $title;
}
//add_filter('the_title', 'EWD_URP_Add_Score_To_Title');
?>
