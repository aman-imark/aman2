<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the customer-order shortcode */
function Insert_Review_Form($atts) {
	global $user_message;
	global $wpdb;
		
	$Custom_CSS = get_option('EWD_URP_Custom_CSS');
	$InDepth_Reviews = get_option("EWD_URP_InDepth_Reviews");
	$Maximum_Score = get_option("EWD_URP_Maximum_Score");
	$Review_Style = get_option("EWD_URP_Review_Style");
	$Review_Score_Input = get_option("EWD_URP_Review_Score_Input");
	$Review_Image = get_option("EWD_URP_Review_Image");
	$Review_Categories_Array = get_option("EWD_URP_Review_Categories_Array");
	$Autocomplete_Product_Names = get_option("EWD_URP_Autocomplete_Product_Names");	
	$Restrict_Product_Names = get_option("EWD_URP_Restrict_Product_Names");
	$Product_Name_Input_Type = get_option("EWD_URP_Product_Name_Input_Type");
	$UPCP_Integration = get_option("EWD_URP_UPCP_Integration");
	$Product_Names_Array = get_option("EWD_URP_Product_Names_Array");
	$Review_Character_Limit = get_option("EWD_URP_Review_Character_Limit");

	$Use_Captcha = get_option("EWD_URP_Use_Captcha");
	$Admin_Approval = get_option("EWD_URP_Admin_Approval");
	$Require_Email = get_option("EWD_URP_Require_Email");
	$Email_Confirmation = get_option("EWD_URP_Email_Confirmation");
	$Display_On_Confirmation = get_option("EWD_URP_Display_On_Confirmation");
	$Require_Login = get_option("EWD_URP_Require_Login");
	$Salt = get_option("EWD_URP_Hash_Salt");

	$Submit_Product_Label = get_option("EWD_URP_Submit_Product_Label");
		if ($Submit_Product_Label == "") {$Submit_Product_Label = __("Product Name", 'EWD_URP');}
	$Submit_Author_Label = get_option("EWD_URP_Submit_Author_Label");
			if ($Submit_Author_Label == "") {$Submit_Author_Label = __("Review Author", 'EWD_URP');}
	$Submit_Author_Comment_Label = get_option("EWD_URP_Submit_Author_Comment_Label");
			if ($Submit_Author_Comment_Label == "") {$Submit_Author_Comment_Label = __("What name should be displayed with your review?", 'EWD_URP');}
	$Submit_Title_Label = get_option("EWD_URP_Submit_Title_Label");
			if ($Submit_Title_Label == "") {$Submit_Title_Label = __("Review Title", 'EWD_URP');}
	$Submit_Title_Comment_Label = get_option("EWD_URP_Submit_Title_Comment_Label");
			if ($Submit_Title_Comment_Label == "") {$Submit_Title_Comment_Label = __("What title should be displayed with your review?", 'EWD_URP');}
	$Submit_Score_Label = get_option("EWD_URP_Submit_Score_Label");
			if ($Submit_Score_Label == "") {$Submit_Score_Label = __("Overall Score", 'EWD_URP');}
	$Submit_Review_Label = get_option("EWD_URP_Submit_Review_Label");
			if ($Submit_Review_Label == "") {$Submit_Review_Label = __("Review", 'EWD_URP');}
	$Submit_Cat_Score_Label = get_option("EWD_URP_Submit_Cat_Score_Label");
			if ($Submit_Cat_Score_Label == "") {$Submit_Cat_Score_Label = __("Score", 'EWD_URP');}
	$Submit_Explanation_Label = get_option("EWD_URP_Submit_Explanation_Label");
			if ($Submit_Explanation_Label == "") {$Submit_Explanation_Label = __("Explanation", 'EWD_URP');}
	$Submit_Button_Label = get_option("EWD_URP_Submit_Button_Label");
			if ($Submit_Button_Label == "") {$Submit_Button_Label = __("Send Review", 'EWD_URP');}


	$ReturnString = "";
		
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
		 		'product_name' => '',
		 		'redirect_page' => '',
		 		'success_message' => __('Thank you for submitting a review.', 'EWD_URP'),
		 		'draft_message' => __("Your review will be visible once it's approved by an administrator.", 'EWD_URP'),
		 		'review_form_title' => __('Submit a Review', 'EWD_URP'),
				'review_instructions' => __('Please fill out the form below to submit a review.', 'EWD_URP'),
				'submit_text' => __('Send Review', 'EWD_URP')),
		$atts
		)
	);

	if (get_option("EWD_URP_Submit_Success_Message") != "") {$success_message = get_option("EWD_URP_Submit_Success_Message");}
	if (get_option("EWD_URP_Submit_Draft_Message") != "") {$draft_message = get_option("EWD_URP_Submit_Draft_Message");}

	if ($Admin_Approval == "Yes") {$success_message .= " " . $draft_message;}
	
	if (isset($_POST['Submit_Review'])) {$user_update = EWD_URP_Submit_Review($success_message);}
	if (isset($_GET['ConfirmEmail'])) {$user_update = EWD_URP_Confirm_Email();}

	if (isset($_REQUEST['product_name'])) {$product_name = $_REQUEST['product_name'];}

	if ($user_update == $success_message and $redirect_page != '') {header('location:'. $redirect_page);}

	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= ".ui-autocomplete {background:#FFF; border: #000 solid 1px; max-width:400px; max-height:200px; overflow:auto;}";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= "</style>";

	if ($Require_Login == "Yes") {
		$Logged_In_User = EWD_URP_Get_Login_Information();
		if ($Logged_In_User['Login_Status'] == "None") {
			$ReturnString .= "<div class='ewd-urp-login-message'>";
			$ReturnString .= __("Please log in to leave a review", 'EWD_URP');
			$ReturnString .= "<br />";
			$ReturnString .= __("Login Options:", 'EWD_URP');
			$ReturnString .= "</div>";
			$ReturnString .= "<div class='ewd-urp-login-options'>";
			$ReturnString .= $Logged_In_User['ManageLogin'];
			$ReturnString .= "</div>";
			return $ReturnString;
		}
	}
	else {
		$Logged_In_User['Author_Name'] = "";
	}

	//Pass the possible product names to javascript for autocomplete and name restricting
	if ($Product_Name_Input_Type == "Text") {
		$ReturnString .= "<script>";
		if ($Autocomplete_Product_Names == "Yes") {$ReturnString .= "var autocompleteProductNames = 'Yes';\n";}
		if ($Restrict_Product_Names == "Yes") {$ReturnString .= "var restrictProductNames = 'Yes';\n";}
		if ($UPCP_Integration == "Yes") {
			$items_table_name = $wpdb->prefix . "UPCP_Items";
			$Products = $wpdb->get_results("SELECT Item_Name FROM $items_table_name");
			$ReturnString .= "var productNames = [";
			foreach ($Products as $Product) {
				$ReturnString .= "'" . $Product->Item_Name . "',";
			}
			if (sizeof($Products) > 0) {$ReturnString = substr($ReturnString, 0, -1);}
			$ReturnString .= "];\n";
		}
		else {
			$ReturnString .= "var productNames = [";
			if (!is_array($Product_Names_Array)) {$Product_Names_Array = array();}
			foreach ($Product_Names_Array as $Product_Name_Item) {
				$ReturnString .= "'" . $Product_Name_Item['ProductName'] . "',";
			}
			if (sizeof($Product_Names_Array) > 0) {$ReturnString = substr($ReturnString, 0, -1);}
			$ReturnString .= "];\n";
		}
		$ReturnString .= "</script>";
	}

	$ReturnString .= "<div class='ewd-urp-review-form'>";

	if (isset($_GET['ConfirmEmail']) and $Display_On_Confirmation == "No") {$ReturnString .= $user_update . "</div>"; return $ReturnString;}

	if (isset($_POST['Submit_Review'])) {
		$ReturnString .= "<div class='ewd-urp-review-update'>";
		$ReturnString .= $user_update;
		$ReturnString .= "</div>";
	}

	$ReturnString .= "<form id='review_order' method='post' action='#' enctype='multipart/form-data'>";
	$ReturnString .= wp_nonce_field();
	$ReturnString .= wp_referer_field();

	if ($Email_Confirmation == "Yes") {
		$ReturnString .= "<input type='hidden' name='Current_URL' value='" . get_permalink() . "' />";
	}

	if ($product_name != "") {
		$ReturnString .= "<div class='ewd-urp-form-header'>";
		$ReturnString .= __("Review of ", 'EWD_URP') . " " . $product_name;
		$ReturnString .= "</div>";
		$ReturnString .= "<input type='hidden' name='Product_Name' value='" . $product_name ."' />";
	}
	elseif ($Product_Name_Input_Type == "Dropdown") {
		$ReturnString .= "<div class='form-field'>";
		$ReturnString .= "<label id='ewd-urp-review-product-name-label' class='ewd-urp-review-label'>";
		$ReturnString .= $Submit_Product_Label . ": ";
		$ReturnString .= "</label>";
		$ReturnString .= "<select name='Product_Name' id='Product_Name'>";
		if ($UPCP_Integration == "Yes") {
			$items_table_name = $wpdb->prefix . "UPCP_Items";
			$Products = $wpdb->get_results("SELECT Item_Name FROM $items_table_name");
			foreach ($Products as $Product) {
				$ReturnString .= "<option value='" . $Product->Item_Name . "'>" . $Product->Item_Name . "</option>";
			}
		}
		else {
			if (!is_array($Product_Names_Array)) {$Product_Names_Array = array();}
			foreach ($Product_Names_Array as $Product_Name_Item) {
				$ReturnString .= "<option value='" . $Product_Name_Item['ProductName'] . "'>" . $Product_Name_Item['ProductName'] . "</option>";
			}
		}
		$ReturnString .= "</select>";
		$ReturnString .= "</div>";
	}
	else {
		$ReturnString .= "<div class='form-field'>";
		$ReturnString .= "<label id='ewd-urp-review-product-name-label' class='ewd-urp-review-label'>";
		$ReturnString .=  $Submit_Product_Label . ": </label>";
		// $ReturnString .= "</div>";
		// $ReturnString .= "<div id='ewd-urp-product-name-input' class='ewd-urp-review-input'>";
		$ReturnString .= "<input name='Product_Name' id='Product_Name' type='text' class='ewd-urp-product-name-text-input' value='" . $_POST['Product_Name'] . "' size='60' required/>";
		// $ReturnString .= "</div>";
		if ($Restrict_Product_Names == "Yes") {$ReturnString .= "<div id='ewd-urp-restrict-product-names-message'></div>";}
		$ReturnString .= "</div>";
	}

	$ReturnString .= "<div class='form-field'>";
	$ReturnString .= "<label id='ewd-urp-review-author' class='ewd-urp-review-label'>";
	$ReturnString .= $Submit_Author_Label . ": </label>";
	// $ReturnString .= "</div>";
	// $ReturnString .= "<div id='ewd-urp-review-author-input' class='ewd-urp-review-input'>";
	if ($Logged_In_User['Author_Name'] == "") {
		$ReturnString .= "<input type='hidden' name='Post_Author_Type' id='Post_Author_Type' value='Manual' />";
		$ReturnString .= "<input type='text' name='Post_Author' id='Post_Author' value='" . $_POST['Post_Author'] . "' />";
		$ReturnString .= "<div id='ewd-urp-author-explanation' class='ewd-urp-review-explanation'>";
		$ReturnString .= "<label for='explanation'></label><span>" . $Submit_Author_Comment_Label  . "</span>";
		$ReturnString .= "</div>";
	}
	else {
		$ReturnString .= "<input type='hidden' name='Post_Author_Type' id='Post_Author_Type' value='AutoEntered' />";
		$ReturnString .= "<input type='hidden' name='Post_Author_Check' id='Post_Author_Check' value='" . sha1($Logged_In_User['Author_Name'].$Salt) . "' />";
		$ReturnString .= "<input type='hidden' name='Post_Author' id='Post_Author' value='" . $Logged_In_User['Author_Name'] . "' />" . $Logged_In_User['Author_Name'];
		if ($Logged_In_User['Login_Status'] == "Twitter" or $Logged_In_User['Login_Status'] == "Facebook") {
			$ReturnString .= "<div id='ewd-urp-author-explanation' class='ewd-urp-review-explanation'>";
			$ReturnString .= "<label for='explanation'></label><span>" . $Logged_In_User['ManageLogin']  . "</span>";
			$ReturnString .= "</div>";
		}
	}
	// $ReturnString .= "</div>";
	$ReturnString .= "</div>";

	if ($Require_Email == "Yes") {
		$ReturnString .= "<div class='form-field'>";
		$ReturnString .= "<label id='ewd-urp-email-author' class='ewd-urp-review-label'>";
		$ReturnString .= __("Reviewer's Email Address", 'EWD_URP') . ": </label>";
		// $ReturnString .= "</div>";
		// $ReturnString .= "<div id='ewd-urp-review-email-input' class='ewd-urp-review-input'>";
		$ReturnString .= "<input type='email' name='Post_Email' id='Post_Email' value='" . $_POST['Post_Email'] . "' required/>";
		// $ReturnString .= "</div>";
		$ReturnString .= "<div id='ewd-urp-author-explanation' class='ewd-urp-review-explanation'>";
		$ReturnString .= "<label for='explanation'></label><span>" . __('Please confirm your e-mail, to verify your identity. It will not be displayed.', 'EWD_URP')  . "</span>";
		$ReturnString .= "</div>";
		$ReturnString .= "</div>";
	}

	$ReturnString .= "<div class='form-field'>";
	$ReturnString .= "<label id='ewd-urp-review-title' class='ewd-urp-review-label'>";
	$ReturnString .= $Submit_Title_Label . ": </label>";
	// $ReturnString .= "</div>";
	// $ReturnString .= "<div id='ewd-urp-review-author-input' class='ewd-urp-review-input'>";
	$ReturnString .= "<input type='text' name='Post_Title' id='Post_Title' value='" . $_POST['Post_Title'] . "' />";
	// $ReturnString .= "</div>";
	$ReturnString .= "<div id='ewd-urp-title-explanation' class='ewd-urp-review-explanation'>";
	$ReturnString .= "<label for='explanation'></label><span>" . $Submit_Title_Comment_Label  . "</span>";
	$ReturnString .= "</div>";
	$ReturnString .= "</div>";

	if ($Review_Image == "Yes") {
		$ReturnString .= "<div class='form-field'>";
		$ReturnString .= "<label id='ewd-urp-review-title' class='ewd-urp-review-label'>";
		$ReturnString .= __('Review Image', 'EWD_URP') . ": </label>";
		$ReturnString .= "<input type='file' name='Post_Image' id='Post_Image' accept='.jpg,.png'/>";
		$ReturnString .= "<div id='ewd-urp-image-explanation' class='ewd-urp-review-explanation'>";
		$ReturnString .= "<label for='explanation'></label><span>" . __('The image that should be associated with your review', 'EWD_URP') . "</span>";
		$ReturnString .= "</div>";
		$ReturnString .= "</div>";
	}
	
	$Textarea_Counter = 0;
	if ($InDepth_Reviews == "No" or $Review_Categories_Array[0]['CategoryName'] == "") {
		$ReturnString .= "<div class='ewd-urp-meta-field'>";
		$ReturnString .= "<label for='Overall Score' class='submitReviewLabels'>";
		$ReturnString .=  $Submit_Score_Label . ": ";
		$ReturnString .= "</label>";
		if ($Review_Score_Input == "Text") {$ReturnString .= "<input type='text' id='ewd-urp-overall-score' name='Overall_Score' value='" . $_POST['Overall_Score'] . "' />";}
		elseif ($Review_Score_Input == "Stars") {
			$ReturnString .= "<div class='ewd-urp-stars-input'>";
			for ($i=1; $i<=$Maximum_Score; $i++) {$ReturnString .= "<div class='ewd-urp-star-input' id='ewd-urp-star-input-" . $Textarea_Counter . "-" . $i . "' data-reviewscore='" . $i . "' data-cssidadd='" . $Textarea_Counter . "' data-inputname='Overall_Score'></div>";}
			$ReturnString .= "</div>";
			$ReturnString .= "<input type='hidden' id='ewd-urp-overall-score' name='Overall_Score' value='" . $_POST['Overall_Score'] . "' />";
		}
		else {
			$ReturnString .= "<select class='ewd-urp-dropdown-score-input' id='ewd-urp-overall-score' name='Overall_Score'/>";
			for ($i=$Maximum_Score; $i>=1; $i--) {$ReturnString .= "<option value='" . $i . "'>" . $i . "</option>";}
			$ReturnString .= "</select>";
		}
		if ($Review_Style == "Percentage") {$ReturnString .= "%";}
		elseif ($Review_Score_Input != "Stars") {$ReturnString .= " " . __("out of", 'EWD_URP') . " " . $Maximum_Score;}
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='ewd-urp-meta-field'>";
		$ReturnString .= "<label for='Post_Body'>";
		$ReturnString .= $Submit_Review_Label . ": ";
		$ReturnString .= "</label>";
		$ReturnString .= "<textarea name='Post_Body' class='ewd-urp-review-textarea' data-textareacount='" . $Textarea_Counter ."'>" . $_POST['Post_Body'] . "</textarea>";
		if ($Review_Character_Limit != "") {$ReturnString .= "<div class='ewd-urp-review-character-count'  id='ewd-urp-review-character-count-" . $Textarea_Counter ."'><label></label>" . __('Characters remaining:', 'EWD_URP') . " " . $Review_Character_Limit . "</div>";}
		$ReturnString .= "</div>";
	}
	else {
		$ReturnString .= "<div class='ewd-urp-meta-field'>";
		$ReturnString .= "<label for='Post_Body'>";
		$ReturnString .= $Submit_Review_Label . ": ";
		$ReturnString .= "</label>";
		$ReturnString .= "<textarea name='Post_Body' class='ewd-urp-review-textarea' data-textareacount='" . $Textarea_Counter ."'>" . $_POST['Post_Body'] . "</textarea>";
		if ($Review_Character_Limit != "") {$ReturnString .= "<div class='ewd-urp-review-character-count'  id='ewd-urp-review-character-count-" . $Textarea_Counter ."'><label></label>" . __('Characters remaining:', 'EWD_URP') . " " . $Review_Character_Limit . "</div>";}
		$ReturnString .= "</div>";

		foreach ($Review_Categories_Array as $Review_Category_Item) {
			if ($Review_Category_Item['CategoryName'] != "") {
				$Textarea_Counter++;
				$ReturnString .= "<div class='ewd-urp-meta-field'>";
				$ReturnString .= "<label for='" . $Review_Category_Item['CategoryName'] . "' class='submitReviewLabels'>";
				$ReturnString .= $Review_Category_Item['CategoryName'] . " " . $Submit_Cat_Score_Label . ": ";
				$ReturnString .= "</label>";
				if ($Review_Score_Input == "Text") {$ReturnString .= "<input type='text' id='ewd-urp-" . $Review_Category_Item['CategoryName'] . "' name='" . $Review_Category_Item['CategoryName'] . "' value='" . $_POST[$Review_Category_Item['CategoryName']] . "' />";}
				elseif ($Review_Score_Input == "Stars") {
					$ReturnString .= "<div class='ewd-urp-stars-input'>";
					for ($i=1; $i<=$Maximum_Score; $i++) {$ReturnString .= "<div class='ewd-urp-star-input' id='ewd-urp-star-input-" . $Textarea_Counter . "-" . $i . "' data-reviewscore='" . $i . "' data-cssidadd='" . $Textarea_Counter . "' data-inputname='" . $Review_Category_Item['CategoryName'] . "'></div>";}
					$ReturnString .= "</div>";
					$ReturnString .= "<input type='hidden' id='ewd-urp-overall-score' name='" . $Review_Category_Item['CategoryName'] . "' value='" . $_POST['Overall_Score'] . "' />";
				}
				else {
					$ReturnString .= "<select class='ewd-urp-dropdown-score-input' id='ewd-urp-" . $Review_Category_Item['CategoryName'] . "' name='" . $Review_Category_Item['CategoryName'] . "'/>";
					for ($i=$Maximum_Score; $i>=1; $i--) {$ReturnString .= "<option value='" . $i . "'>" . $i . "</option>";}
					$ReturnString .= "</select>";
				}
				if ($Review_Style == "Percentage") {$ReturnString .= "%";}
				elseif ($Review_Score_Input != "Stars") {$ReturnString .= " " . __("out of", 'EWD_URP') . " " . $Maximum_Score;}
				$ReturnString .= "</div>";

				if ($Review_Category_Item['ExplanationAllowed'] == "Yes") {
					$ReturnString .= "<div class='ewd-urp-meta-field'>";
					$ReturnString .= "<label for'" . $Review_Category_Item['CategoryName'] . " Description'>";
					$ReturnString .= $Review_Category_Item['CategoryName'] . " " . $Submit_Explanation_Label . ": ";
					$ReturnString .= "</label>";
					$ReturnString .= "<textarea name='" . $Review_Category_Item['CategoryName'] . " Description' class='ewd-urp-review-textarea'  data-textareacount='" . $Textarea_Counter ."'>" . $_POST[$Review_Category_Item['CategoryName'] . " Description"] . "</textarea>";
					if ($Review_Character_Limit != "") {$ReturnString .= "<div class='ewd-urp-review-character-count'  id='ewd-urp-review-character-count-" . $Textarea_Counter ."'><label></label>" . __('Characters remaining:', 'EWD_URP') . " " . $Review_Character_Limit . "</div>";}
					$ReturnString .= "</div>";
				}
			}
		}
	}

	if ($Use_Captcha == "Yes") {$ReturnString .= EWD_URP_Add_Captcha();}

	$ReturnString .= "<div class='ewd-urp-submit'><label for='submit'></label><span class='submit'><input type='submit' name='Submit_Review' id='ewd-urp-review-submit' class='button-primary' value='" . $Submit_Button_Label . "'  /></span></div></form>";
	$ReturnString .= "</div>";

	return $ReturnString;
}
add_shortcode("submit-review", "Insert_Review_Form");


function EWD_URP_Get_Login_Information() {
	$Login_Options = get_option("EWD_URP_Login_Options");

	$WordPress_Login_URL = get_option("EWD_URP_WordPress_Login_URL");
	$FEUP_Login_URL = get_option("EWD_URP_FEUP_Login_URL");

	$Permalink = get_the_permalink();
	if (strpos($Permalink, "?") !== false) {$PageLink = $Permalink . "&";}
	else {$PageLink = $Permalink . "?";}

	$facebook = EWD_URP_Facebook_Config();
	$fbuser = $facebook->getUser();
	$fbPermissions = 'public_profile';  //Required facebook permissions

	if (isset($_GET['Run_Login']) and $_GET['Run_Login'] == "Twitter" or isset($_GET['oauth_token'])) {EWD_URP_Twitter_Login($PageLink);}

	$Facebook_Output = "";
	if (!$fbuser and in_array("Facebook", $Login_Options)) {
		$fbuser = null;
		$LoginURL = $facebook->getLoginUrl(array('redirect_uri'=>$Permalink,'scope'=>$fbPermissions));
		$Facebook_Output = "<div class='ewd-urp-login-option' id='ewd-urp-facebook-login'>";
		$Facebook_Output .= "<a href='".$LoginURL."'><img src='" . EWD_URP_CD_PLUGIN_URL . "images/fb_login.png'></a>";
		$Facebook_Output .= "</div>";
	}
	else {
		$Facebook_Output = "<a href='" . $PageLink . "Logout=Facebook' >" . __("Logout", 'EWD_URP') . "</a>";
	}

	$Twitter_Output = "";
	if ((!isset($_COOKIE['EWD_URP_status']) && $_COOKIE['EWD_URP_status'] != 'verified') and in_array("Twitter", $Login_Options)) {
		$Twitter_Output = "<div class='ewd-urp-login-option' id='ewd-urp-Twitter-login'>";
		$Twitter_Output .= "<a href='" . $PageLink . "Run_Login=Twitter'><img src='" . EWD_URP_CD_PLUGIN_URL . "images/sign-in-with-twitter.png' width='151' height='24' border='0' /></a>";
		$Twitter_Output .= "</div>";
	}
	else {
		$Twitter_Output = "<a href='" . $PageLink . "Logout=Twitter' >" . __("Logout", 'EWD_URP') . "</a>";
	}
	
	if (array_key_exists('Logout',$_GET)) {
		if ($_GET['Logout'] == "Facebook") {
			$facebook->destroySession();
			$Facebook_Output = __("You have been successfully logged out via Facebook. ", 'EWD_URP');
			$Facebook_Output .= "<a href='" . $Permalink . "'>";
			$Facebook_Output .= __("Please reload the page", 'EWD_URP');
			$Facebook_Output .= "</a>";
			$Facebook_Output .= " " . __("if you'd like to log in again", 'EWD_URP');
		}
		if ($_GET['Logout'] == "Twitter") {
			EWD_URP_Erase_Twitter_Data();
			$Twitter_Output = __("You have been successfully logged out via Twitter. ", 'EWD_URP');
			$Twitter_Output .= "<a href='" . $Permalink . "'>";
			$Twitter_Output .= __("Please reload the page", 'EWD_URP');
			$Twitter_Output .= "</a>";
			$Twitter_Output .= " " . __("if you'd like to log in again", 'EWD_URP');
		}
	}

	if (function_exists("FEUP_User")) {
		$FEUP_User = new FEUP_User;
		$FEUP_Logged_In = $FEUP_User->Is_Logged_In();
	}
	else {
		$FEUP_Logged_In = false;
	}

	if ($fbuser and (!array_key_exists('Logout',$_GET) or $_GET['Logout'] != "Facebook")) {
		$Facebook_Logged_In = true;
	}

	if (isset($_COOKIE['EWD_URP_status']) and $_COOKIE['EWD_URP_status'] == 'verified'  and (!array_key_exists('Logout',$_GET) or $_GET['Logout'] != "Twitter")) {
		$Twitter_Logged_In = true;
	}

	if (in_array("WordPress", $Login_Options) and is_user_logged_in()) {
		$Logged_In_User['Login_Status'] = "WordPress";
		$User_Meta = array_map("EWD_URP_WP_User_Array_Map", get_user_meta(get_current_user_id()));
		$Logged_In_User['Author_Name'] = $User_Meta['first_name'] . " " . $User_Meta['last_name'];
	}
	elseif (in_array("FEUP", $Login_Options) and $FEUP_Logged_In) {
		$Logged_In_User['Login_Status'] = "FEUP";
		$Logged_In_User['Author_Name'] = "";
	}
	elseif (in_array("Twitter", $Login_Options) and $Twitter_Logged_In) {
		$Logged_In_User['Login_Status'] = "Twitter";
		$Logged_In_User['Author_Name'] = $_COOKIE['EWD_URP_Twitter_Full_Name'];
	}
	elseif (in_array("Facebook", $Login_Options) and $Facebook_Logged_In) {
		$Logged_In_User['Login_Status'] = "Facebook";
		$user_profile = $facebook->api('/me?fields=name');
		if (!empty($user_profile)) {
			$Logged_In_User['Author_Name'] = $user_profile['name'];
		}
	}
	else {
		$Logged_In_User['Login_Status'] = "None";
	}

	$Logged_In_User['ManageLogin'] = "";
	if (in_array("WordPress", $Login_Options) and $Logged_In_User['Login_Status'] == "None") {
		$Logged_In_User['ManageLogin'] .= "<div class='ewd-urp-login-option' id='ewd-urp-WordPress-login'>";
		$Logged_In_User['ManageLogin'] .= "<a href='" . $WordPress_Login_URL . "'>" . __("WordPress Login", 'EWD_URP') . "</a>";
		$Logged_In_User['ManageLogin'] .= "</div>";
	}
	if (in_array("FEUP", $Login_Options) and $Logged_In_User['Login_Status'] == "None") {
		$Logged_In_User['ManageLogin'] .= "<div class='ewd-urp-login-option' id='ewd-urp-FEUP-login'>";
		$Logged_In_User['ManageLogin'] .= "<a href='" . $FEUP_Login_URL . "'>" . __("Login", 'EWD_URP') . "</a>";
		$Logged_In_User['ManageLogin'] .= "</div>";
	}
	if (in_array("Facebook", $Login_Options) and ($Logged_In_User['Login_Status'] == "None" or $Logged_In_User['Login_Status'] == "Facebook")) {$Logged_In_User['ManageLogin'] .= $Facebook_Output;}
	if (in_array("Twitter", $Login_Options) and ($Logged_In_User['Login_Status'] == "None" or $Logged_In_User['Login_Status'] == "Twitter")) {$Logged_In_User['ManageLogin'] .= $Twitter_Output;}

	return $Logged_In_User;
}

function EWD_URP_WP_User_Array_Map($a) {
	return $a[0];
}

?>