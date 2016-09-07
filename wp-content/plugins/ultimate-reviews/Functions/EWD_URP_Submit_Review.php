<?php
function  EWD_URP_Submit_Review($success_message) {
	$Maximum_Score = get_option("EWD_URP_Maximum_Score");
	$Review_Image = get_option("EWD_URP_Review_Image");
	$InDepth_Reviews = get_option("EWD_URP_InDepth_Reviews");
	$Review_Categories_Array = get_option("EWD_URP_Review_Categories_Array");
	$Admin_Notification = get_option("EWD_URP_Admin_Notification");
	$Admin_Approval = get_option("EWD_URP_Admin_Approval");
	$Use_Captcha = get_option("EWD_URP_Use_Captcha");
	$Require_Email = get_option("EWD_URP_Require_Email");
	$Email_Confirmation = get_option("EWD_URP_Email_Confirmation");

	$Salt = get_option("EWD_URP_Hash_Salt");

	$Post_Title = sanitize_text_field($_POST['Post_Title']);
	$Product_Name = sanitize_text_field($_POST['Product_Name']);
	$Post_Author = sanitize_text_field($_POST['Post_Author']);
	$Post_Body = sanitize_text_field($_POST['Post_Body']);
	$Post_Email = sanitize_text_field($_POST['Post_Email']);
	$Current_URL = $_POST['Current_URL'];

	if (isset($_POST['Item_Reviewed'])) {
		$Item_Reviewed = $_POST['Item_Reviewed'];
		$Item_ID = $_POST['Item_ID'];
	}
	else {
		$Item_Reviewed = "urp_product";
		$Item_ID = 0;
	}

	if ($_POST['Post_Author_Type'] == "AutoEntered") {
		if (sha1($Post_Author.$Salt) == $_POST['Post_Author_Check']) {
			$AuthorCheck = true;
		}
		else {
			$AuthorCheck = false;
		}
	}
	else {
		$AuthorCheck = true;
	}

	if ($Use_Captcha == "Yes") {$Validate_Captcha = EWD_URP_Validate_Captcha();}
	else {$Validate_Captcha = "Yes";}

	if ($Validate_Captcha != "Yes") {$user_message = __("The number entered in the captcha field was not correct.", 'EWD_URP'); return $user_message;}

	$post = array(
		'post_content' => $Post_Body,
		'post_title' => $Post_Title,
		'post_type' => 'urp_review',
		'post_status' => 'publish' 
	);
	if ($Admin_Approval == "Yes") {$post['post_status'] = 'draft';}
	$post_id = wp_insert_post($post);
	if ($post_id == 0 or !$AuthorCheck) {$user_message = __("Review was not created succesfully.", 'EWD_URP'); return $user_message;}

	if ($Review_Image == "Yes") {
		$File = EWD_URP_Upload_Review_Image();
		if ($File and !isset($File['error'])) {$Attachment_ID = EWD_URP_Create_Thumbnail_Image($File, $Post_Title);}
		if (isset($Attachment_ID) and $Attachment_ID != 0) {set_post_thumbnail($post_id, $Attachment_ID);}
	}

	unset($_POST['Post_Title']);
	unset($_POST['Product_Name']);
	unset($_POST['Post_Author']);
	unset($_POST['Post_Body']);
	unset($_POST['Post_Email']);
	unset($_POST['Current_URL']);

	update_post_meta($post_id, "EWD_URP_Product_Name", $Product_Name);
	update_post_meta($post_id, "EWD_URP_Post_Author", $Post_Author);
	update_post_meta($post_id, "EWD_URP_Post_Email", $Post_Email);
	update_post_meta($post_id, "EWD_URP_Email_Confirmed", "No");
	update_post_meta($post_id, "EWD_URP_Item_Reviewed", $Item_Reviewed);
	update_post_meta($post_id, "EWD_URP_Item_ID", $Item_ID);
	
	if ($InDepth_Reviews == "No" or $Review_Categories_Array[0]['CategoryName'] == "") {
		$Overall_Score = min(round(sanitize_text_field($_POST['Overall_Score']), 2), $Maximum_Score);
		update_post_meta($post_id, "EWD_URP_Overall_Score", $Overall_Score);
		unset($_POST['Overall_Score']);
	}
	else {
		foreach ($Review_Categories_Array as $Review_Category_Item) {
			if ($Review_Category_Item['CategoryName'] != "") {
				$CategoryName = str_replace(" ", "_", $Review_Category_Item['CategoryName']);
				$Value = min($_POST[$CategoryName], $Maximum_Score);
				$Overall_Score += $Value;
				update_post_meta($post_id, "EWD_URP_" . $Review_Category_Item['CategoryName'], $Value);
				unset($_POST[$CategoryName]);

				if ($Review_Category_Item['ExplanationAllowed'] == "Yes") {
					$Section_Description = sanitize_text_field($_POST[$CategoryName . "_Description"]);
					update_post_meta($post_id, "EWD_URP_" . $Review_Category_Item['CategoryName'] . "_Description", $Section_Description);
					unset($_POST[$CategoryName . "_Description"]);
				}
			}
		}

		if (sizeOf($Review_Categories_Array) > 0) {$Overall_Score = min(round($Overall_Score / sizeOf($Review_Categories_Array), 2), $Maximum_Score);}
		else {$Overall_Score = 0;}
		update_post_meta($post_id, "EWD_URP_Overall_Score", $Overall_Score);
	}

	if ($Email_Confirmation == "Yes") {
		EWD_URP_Send_Confirmation_Email($post_id, $Post_Title, $Post_Email, $Current_URL);
	}

	if ($Admin_Notification == "Yes") {
		EWD_URP_Send_Admin_Notification_Email($post_id, $Post_Title, $Post_Body);
	}

	return $success_message;
}

function EWD_URP_Send_Confirmation_Email($post_id, $Post_Title, $Post_Email, $Current_URL) {

	$Confirmation_Code = EWD_URP_RandomString();
	if (strpos($Current_URL, "?") !== false) {
		$ConfirmationLink = $Current_URL . "&ConfirmEmail=true&Post_ID=" . $post_id . "&Confirmation_Code=" . $Confirmation_Code;
	}
	else {
		$ConfirmationLink = $Current_URL . "?ConfirmEmail=true&Post_ID=" . $post_id . "&Confirmation_Code=" . $Confirmation_Code;
	}
	update_post_meta($post_id, "EWD_URP_Confirmation_Code", $Confirmation_Code);

	$Subject_Line = __("Email Confirmation for Product Review", 'EWD_URP');

	$Message_Body = __("Hello,", 'EWD_URP') . "<br/><br/>";
	$Message_Body .= __("Please confirm your email address for the product review you submitted titled ", 'EWD_URP') . $Post_Title . " ";
	$Message_Body .= __("by going to the following link:", 'EWD_URP') . "<br/><br/>";
	$Message_Body .= "<a href='" . $ConfirmationLink . "' />" . __("Confirm your email address", 'EWD_URP') . "</a><br/><br/>";
	$Message_Body .= __("Thank you for the review, and have a great day,", 'EWD_URP') . "<br/><br/>";
	$Message_Body .= get_bloginfo("name");

	$headers = array('Content-Type: text/html; charset=UTF-8');
	$Mail_Success = wp_mail($Post_Email, $Subject_Line, $Message_Body, $headers);
}

function EWD_URP_Confirm_Email() {
	$Post_ID = $_GET['Post_ID'];
	$Entered_Confirmation_Code = $_GET['Confirmation_Code'];

	$Actual_Confirmation_Code = get_post_meta($Post_ID, 'EWD_URP_Confirmation_Code', true);

	if ($Actual_Confirmation_Code == $Entered_Confirmation_Code) {
		update_post_meta($Post_ID, "EWD_URP_Email_Confirmed", "Yes");
	}

	$user_update = __('Thank you for confirming your email address!', 'EWD_URP');

	return $user_update;
}

function EWD_URP_Send_Admin_Notification_Email($post_id, $Post_Title, $Post_Body) {
	$Admin_Notification = get_option("EWD_URP_Admin_Notification");

	$ReviewLink = site_url() . "/wp-admin/post.php?post=" . $post_id . "&action=edit";

	$Subject_Line = __("New Review Received", 'EWD_URP');

	$Message_Body = __("Hello Admin,", 'EWD_URP') . "<br/><br/>";
	$Message_Body .= __("You've received a new for the product", 'EWD_URP') . " " . $Post_Title . ".<br/><br/>";
	$Message_Body .= __("The review reads:<br>", 'EWD_URP');
	$Message_Body .= $Post_Body . "<br><br><br>";
	$Message_Body .= __("You can view the entire review by going to the following link:<br>");
	$Message_Body .= "<a href='" . $ReviewLink . "' />" . __("See the review", 'EWD_URP') . "</a><br/><br/>";
	$Message_Body .= __("Have a great day,", 'EWD_URP') . "<br/><br/>";
	$Message_Body .= __("Ultimate Reviews Team");

	$headers = array('Content-Type: text/html; charset=UTF-8');
	if ($Admin_Notification == "Yes") {$Mail_Success = wp_mail($Post_Email, $Subject_Line, $Message_Body, $headers);}
}

function EWD_URP_RandomString($CharLength = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $CharLength; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

/* Prepare the data to add multiple products from a spreadsheet */
function EWD_URP_Upload_Review_Image() {
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}

	$uploadedfile = $_FILES['Post_Image'];

	if(!preg_match("/\.(jpg.?)$/", strtolower($_FILES['Post_Image']['name'])) and !preg_match("/\.(png.?)$/", strtolower($_FILES['Post_Image']['name']))) {
        $error['error'] = __('File must be .jpg or .png', 'EWD_URP');
        return $error;
    }
	
	$upload_overrides = array( 'test_form' => false );
	
	$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

	return $movefile;		
}

function EWD_URP_Create_Thumbnail_Image($movefile, $Post_Title) {
	// Check the type of file. We'll use this as the 'post_mime_type'.
	$filetype = wp_check_filetype( basename( $movefile['file'] ), null );
	
	// Get the path to the upload directory.
	$wp_upload_dir = wp_upload_dir();
	
	// Prepare an array of post data for the attachment.
	$attachment = array(
		'guid'           => $wp_upload_dir['url'] . '/' . basename( $movefile['file'] ), 
		'post_mime_type' => $filetype['type'],
		//'post_title'     => $Post_Title . " Image",
		'post_title' => basename($movefile['file']),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);

	
	// Insert the attachment.
	$attach_id = wp_insert_attachment( $attachment, $movefile['file'], $parent_post_id );
	
	// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	// Generate the metadata for the attachment, and update the database record.
	$attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );

	return $attach_id;
}
?>