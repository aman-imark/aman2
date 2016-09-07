<?php
$Custom_CSS = get_option("EWD_URP_Custom_CSS");
$Maximum_Score = get_option("EWD_URP_Maximum_Score");
$Review_Style = get_option("EWD_URP_Review_Style");
$Review_Score_Input = get_option("EWD_URP_Review_Score_Input");
$Review_Image = get_option("EWD_URP_Review_Image");
$Review_Filtering = get_option("EWD_URP_Review_Filtering");
if (!is_array($Review_Filtering)) {$Review_Filtering = array();}
$Allow_Reviews = get_option("EWD_URP_Allow_Reviews");
$InDepth_Reviews = get_option("EWD_URP_InDepth_Reviews");
$Review_Categories_Array = get_option("EWD_URP_Review_Categories_Array");
$Autocomplete_Product_Names = get_option("EWD_URP_Autocomplete_Product_Names");
$Restrict_Product_Names = get_option("EWD_URP_Restrict_Product_Names");
$Product_Name_Input_Type = get_option("EWD_URP_Product_Name_Input_Type");
$UPCP_Integration = get_option("EWD_URP_UPCP_Integration");
$Product_Names_Array = get_option("EWD_URP_Product_Names_Array");
$Link_To_Post = get_option("EWD_URP_Link_To_Post");
$Display_Author = get_option("EWD_URP_Display_Author");
$Display_Date = get_option("EWD_URP_Display_Date");
$Review_Character_Limit = get_option("EWD_URP_Review_Character_Limit");
$Reviews_Per_Page = get_option("EWD_URP_Reviews_Per_Page");
$Pagination_Location = get_option("EWD_URP_Pagination_Location");

$Review_Format = get_option("EWD_URP_Review_Format");
$Summary_Statistics = get_option("EWD_URP_Summary_Statistics");
$Replace_WooCommerce_Reviews = get_option("EWD_URP_Replace_WooCommerce_Reviews");
$Override_WooCommerce_Theme = get_option("EWD_URP_Override_WooCommerce_Theme");
$Review_Weights = get_option("EWD_URP_Review_Weights");
$Review_Karma = get_option("EWD_URP_Review_Karma");
$Use_Captcha = get_option("EWD_URP_Use_Captcha");
$Infinite_Scroll = get_option("EWD_URP_Infinite_Scroll");
$Thumbnail_Characters = get_option("EWD_URP_Thumbnail_Characters");
$Admin_Notification = get_option("EWD_URP_Admin_Notification");
$Admin_Approval = get_option("EWD_URP_Admin_Approval");
$Require_Email = get_option("EWD_URP_Require_Email");
$Email_Confirmation = get_option("EWD_URP_Email_Confirmation");
$Display_On_Confirmation = get_option("EWD_URP_Display_On_Confirmation");
$Require_Login = get_option("EWD_URP_Require_Login");
$Login_Options = get_option("EWD_URP_Login_Options");
if (!is_array($Login_Options)) {$Login_Options = array();}

$WordPress_Login_URL = get_option("EWD_URP_WordPress_Login_URL");
$FEUP_Login_URL = get_option("EWD_URP_FEUP_Login_URL");
$Facebook_App_ID = get_option("EWD_URP_Facebook_App_ID");
$Facebook_Secret = get_option("EWD_URP_Facebook_Secret");
$Twitter_Key = get_option("EWD_URP_Twitter_Key");
$Twitter_Secret = get_option("EWD_URP_Twitter_Secret");

$Group_By_Product = get_option("EWD_URP_Group_By_Product");
$Group_By_Product_Order = get_option("EWD_URP_Group_By_Product_Order");
$Ordering_Type = get_option("EWD_URP_Ordering_Type");
$Order_Direction = get_option("EWD_URP_Order_Direction");

$Display_Numerical_Score = get_option("EWD_URP_Display_Numerical_Score");
$Reviews_Skin = get_option("EWD_URP_Reviews_Skin");
$Review_Group_Separating_Line = get_option("EWD_URP_Review_Group_Separating_Line");
$InDepth_Block_Layout = get_option("EWD_URP_InDepth_Block_Layout");

$Posted_Label = get_option("EWD_URP_Posted_Label");
$By_Label = get_option("EWD_URP_By_Label");
$On_Label = get_option("EWD_URP_On_Label");
$Score_Label = get_option("EWD_URP_Score_Label");
$Explanation_Label = get_option("EWD_URP_Explanation_Label");
$Submit_Product_Label = get_option("EWD_URP_Submit_Product_Label");
$Submit_Author_Label = get_option("EWD_URP_Submit_Author_Label");
$Submit_Author_Comment_Label = get_option("EWD_URP_Submit_Author_Comment_Label");
$Submit_Title_Label = get_option("EWD_URP_Submit_Title_Label");
$Submit_Title_Comment_Label = get_option("EWD_URP_Submit_Title_Comment_Label");
$Submit_Score_Label = get_option("EWD_URP_Submit_Score_Label");
$Submit_Review_Label = get_option("EWD_URP_Submit_Review_Label");
$Submit_Cat_Score_Label = get_option("EWD_URP_Submit_Cat_Score_Label");
$Submit_Explanation_Label = get_option("EWD_URP_Submit_Explanation_Label");
$Submit_Button_Label = get_option("EWD_URP_Submit_Button_Label");
$Submit_Success_Message = get_option("EWD_URP_Submit_Success_Message");
$Submit_Draft_Message = get_option("EWD_URP_Submit_Draft_Message");

$urp_Review_Title_Font = get_option("EWD_urp_Review_Title_Font");
$urp_Review_Title_Font_Size = get_option("EWD_urp_Review_Title_Font_Size");
$urp_Review_Title_Font_Color = get_option("EWD_urp_Review_Title_Font_Color");
$urp_Review_Title_Margin = get_option("EWD_urp_Review_Title_Margin");
$urp_Review_Title_Padding = get_option("EWD_urp_Review_Title_Padding");
$urp_Review_Content_Font = get_option("EWD_urp_Review_Content_Font");
$urp_Review_Content_Font_Size = get_option("EWD_urp_Review_Content_Font_Size");
$urp_Review_Content_Font_Color = get_option("EWD_urp_Review_Content_Font_Color");
$urp_Review_Content_Margin = get_option("EWD_urp_Review_Content_Margin");
$urp_Review_Content_Padding = get_option("EWD_urp_Review_Content_Padding");
$urp_Review_Postdate_Font = get_option("EWD_urp_Review_Postdate_Font");
$urp_Review_Postdate_Font_Size = get_option("EWD_urp_Review_Postdate_Font_Size");
$urp_Review_Postdate_Font_Color = get_option("EWD_urp_Review_Postdate_Font_Color");
$urp_Review_Postdate_Margin = get_option("EWD_urp_Review_Postdate_Margin");
$urp_Review_Postdate_Padding = get_option("EWD_urp_Review_Postdate_Padding");
$urp_Review_Score_Font = get_option("EWD_urp_Review_Score_Font");
$urp_Review_Score_Font_Size = get_option("EWD_urp_Review_Score_Font_Size");
$urp_Review_Score_Font_Color = get_option("EWD_urp_Review_Score_Font_Color");
$urp_Review_Score_Margin = get_option("EWD_urp_Review_Score_Margin");
$urp_Review_Score_Padding = get_option("EWD_urp_Review_Score_Padding");

$urp_Summary_Stats_Color = get_option("EWD_urp_Summary_Stats_Color");
$urp_Simple_Bar_Color = get_option("EWD_urp_Simple_Bar_Color");
$urp_Color_Bar_High = get_option("EWD_urp_Color_Bar_High");
$urp_Color_Bar_Medium = get_option("EWD_urp_Color_Bar_Medium");
$urp_Color_Bar_Low = get_option("EWD_urp_Color_Bar_Low");

?>

<div class="wrap urp-options-page-tabbed">
	<div class="urp-options-submenu-div">
		<ul class="urp-options-submenu urp-options-page-tabbed-nav">
			<li><a id="Basic_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == '' or $Display_Tab == 'Basic') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Basic');">Basic</a></li>
			<li><a id="Premium_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Premium') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Premium');">Premium</a></li>
			<li><a id="Order_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Order') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Order');">Ordering</a></li>
			<li><a id="Labelling_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Labelling') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Labelling');">Labelling</a></li>
			<li><a id="Styling_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Styling') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Styling');">Styling</a></li>
		</ul>
	</div>


	<div class="urp-options-page-tabbed-content">
		<form method="post" action="edit.php?post_type=urp_review&page=urp-options&Action=EWD_URP_UpdateOptions">
			<div id='Basic' class='urp-option-set'>
				<h2 id='label-basic-options' class='urp-options-page-tab-title'>Basic Options</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Custom CSS</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Custom CSS</span></legend>
								<label title='Custom CSS'></label><textarea class='ewd-urp-textarea' name='custom_css'> <?php echo $Custom_CSS; ?></textarea><br />
								<p>You can add custom CSS styles for your reviews in the box above.</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Maximum Review Score</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Maximum Review Score</span></legend>
								<input type='text' name='maximum_score' value='<?php echo $Maximum_Score; ?>' />
								<p>What should the maximum score be on the review form? Common values are 100 for the 'percentage' review style, and 5 or 10 for the other styles.</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Review Style</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Style</span></legend>
								<label title='Points'><input type='radio' name='review_style' value='Points' <?php if($Review_Style == "Points") {echo "checked='checked'";} ?> /> <span>Points</span></label><br />
								<label title='Percentage'><input type='radio' name='review_style' value='Percentage' <?php if($Review_Style  == "Percentage") {echo "checked='checked'";} ?> /> <span>Percentage</span></label><br />
								<p>What style should the submit-review form use to collect reviews?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Review Score Input</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Score Input</span></legend>
								<label title='Text'><input type='radio' name='review_score_input' value='Text' <?php if($Review_Score_Input == "Text") {echo "checked='checked'";} ?> /> <span>Text</span></label><br />
								<label title='Select'><input type='radio' name='review_score_input' value='Select' <?php if($Review_Score_Input  == "Select") {echo "checked='checked'";} ?> /> <span>Select</span></label><br />
								<label title='Stars'><input type='radio' name='review_score_input' value='Stars' <?php if($Review_Score_Input  == "Stars") {echo "checked='checked'";} ?> /> <span>Stars</span></label><br />
								<p>What type of input should be used for review scores in the submit-review shortcode?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Review Image</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Image</span></legend>
								<label title='Yes'><input type='radio' name='review_image' value='Yes' <?php if($Review_Image == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='review_image' value='No' <?php if($Review_Image  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should there be a field for reviewer to upload an image of what they're reviewing?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Review Filtering</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Filtering</span></legend>
								<label title='Score'><input type='checkbox' name='review_filtering[]' value='Score' <?php if(in_array("Score", $Review_Filtering)) {echo "checked='checked'";} ?> /> <span>Review Score</span></label><br />
								<label title='Name'><input type='checkbox' name='review_filtering[]' value='Name' <?php if(in_array("Name", $Review_Filtering)) {echo "checked='checked'";} ?> /> <span>Product Name</span></label><br />
								<p>Should there be a field for reviewer to upload an image of what they're reviewing?</p>
							</fieldset>
						</td>
					</tr>

					<!--<tr>
						<th scope="row">Post/Page/Category Reviews</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Post/Page/Category Reviews</span></legend>
								<label title='Posts'><input type='checkbox' name='allow_reviews[]' value='Posts' <?php if(in_array("Posts", $Allow_Reviews)) {echo "checked='checked'";} ?> /> <span>Posts</span></label><br />
								<label title='Pages'><input type='checkbox' name='allow_reviews[]' value='Pages' <?php if(in_array("Pages", $Allow_Reviews)) {echo "checked='checked'";} ?> /> <span>Pages</span></label><br />
								<label title='Categories'><input type='checkbox' name='allow_reviews[]' value='Categories' <?php if(in_array("Categories", $Allow_Reviews)) {echo "checked='checked'";} ?> /> <span>Categories</span></label><br />
								<p>Allow visitors to leave reviews for posts, pages or categories.</p>
							</fieldset>
						</td>
					</tr>-->
					<tr>
						<th scope="row">In-Depth Reviews</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>In-Depth Reviews</span></legend>
								<label title='Yes'><input type='radio' name='indepth_reviews' value='Yes' <?php if($InDepth_Reviews == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='indepth_reviews' value='No' <?php if($InDepth_Reviews  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should the reviews have multiple parts (set in the table below) rather than just an overall score?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Review Categories</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Categories</span></legend>
								<table id='ewd-urp-review-categories-table'>
									<tr>
										<th></th>
										<th>Category Name</th>
										<th>Allow Explanation</th>
									</tr>
									<?php
									$Counter = 0;
									if (!is_array($Review_Categories_Array)) {$Review_Categories_Array = array();}
									foreach ($Review_Categories_Array as $Review_Category_Item) {
										echo "<tr id='ewd-urp-review-category-row-" . $Counter . "'>";
										echo "<td><a class='ewd-urp-delete-review-category' data-reviewid='" . $Counter . "'>Delete</a></td>";
										echo "<td><input type='hidden' name='Review_Category_" . $Counter . "_Name' value='" . $Review_Category_Item['CategoryName'] . "'/>" . $Review_Category_Item['CategoryName'] . "</td>";
										echo "<td><input type='hidden' name='Review_Category_" . $Counter . "_Explanation' value='" . $Review_Category_Item['ExplanationAllowed'] ."'/>" . $Review_Category_Item['ExplanationAllowed'] . "</td>";
										echo "</tr>";
										$Counter++;
									}
									echo "<tr><td colspan='3'><a class='ewd-urp-add-review-category' data-nextid='" . $Counter . "'>Add</a></td></tr>";
									?>
								</table>
								<p>If in-depth reviews is set to 'Yes', what categories should the reviewers be grading? (ex: Appearance, Value, etc.)</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Autocomplete Product Names</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Autocomplete Product Names</span></legend>
								<label title='Yes'><input type='radio' name='autocomplete_product_names' value='Yes' <?php if($Autocomplete_Product_Names == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='autocomplete_product_names' value='No' <?php if($Autocomplete_Product_Names  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should the names of the available products display in an auto-complete box when a visitor starts typing? Products need to be entered in the list below or UPCP Integration has to be turned on for this to work.</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Restrict Product Names</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Restrict Product Names</span></legend>
								<label title='Yes'><input type='radio' name='restrict_product_names' value='Yes' <?php if($Restrict_Product_Names == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='restrict_product_names' value='No' <?php if($Restrict_Product_Names  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should the names of the products be restricted to only those specified?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Product Name Input Type</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Product Name Input Type</span></legend>
								<label title='Text'><input type='radio' name='product_name_input_type' value='Text' <?php if($Product_Name_Input_Type == "Text") {echo "checked='checked'";} ?> /> <span>Text</span></label><br />
								<label title='Dropdown'><input type='radio' name='product_name_input_type' value='Dropdown' <?php if($Product_Name_Input_Type  == "Dropdown") {echo "checked='checked'";} ?> /> <span>Dropdown</span></label><br />
								<p>Should the product name input be a text field or a dropdown (select) field? (Select only works if UPCP integration is turned on or "Products List" is filled in below)</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">UPCP Integration</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>UPCP Integration</span></legend>
								<label title='Yes'><input type='radio' name='upcp_integration' value='Yes' <?php if($UPCP_Integration == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='upcp_integration' value='No' <?php if($UPCP_Integration  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should the product names be taken from the Ultimate Product Catalogue Plugin if the names are being restricted or the product name input type is set to "Dropdown"? (Ultimate Product Catalogue plugin needs to be installed to work correctly)</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Products List</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Products List</span></legend>
								<table id='ewd-urp-product-list-table'>
									<thead>
										<tr>
											<th></th>
											<th>Product Name</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$Counter = 0;
										if (!is_array($Product_Names_Array)) {$Product_Names_Array = array();}
										foreach ($Product_Names_Array as $Product_Name_Item) {
											echo "<tr id='ewd-urp-product-list-item-" . $Counter . "'>";
											echo "<td><a class='ewd-urp-delete-product-list-item' data-productid='" . $Counter . "'>Delete</a></td>";
											echo "<td class='ewd-urp-move-cursor'><input type='hidden' name='Product_List_" . $Counter . "_Name' value='" . $Product_Name_Item['ProductName'] . "'/>" . $Product_Name_Item['ProductName'] . "</td>";
											echo "</tr>";
											$Counter++;
										}
										echo "<tr><td colspan='2'><a class='ewd-urp-add-product-list-item' data-nextid='" . $Counter . "'>Add</a></td></tr>";
										?>
									</tbody>
								</table>
								<p>If UPCP integration is set to "No", and the product names are restricted or the input type is set to "Dropdown", the list of products above will be used to restrict the possible product names.</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Link To Post</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Link To Post</span></legend>
								<label title='Yes'><input type='radio' name='link_to_post' value='Yes' <?php if($Link_To_Post == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='link_to_post' value='No' <?php if($Link_To_Post  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should the review title link to the single post page for the review?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Display Author Name</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Display Author Name</span></legend>
								<label title='Yes'><input type='radio' name='display_author' value='Yes' <?php if($Display_Author == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='display_author' value='No' <?php if($Display_Author  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should the author's name be posted with the review?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Display Date Submitted</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Require Email Confirmation</span></legend>
								<label title='Yes'><input type='radio' name='display_date' value='Yes' <?php if($Display_Date == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='display_date' value='No' <?php if($Display_Date  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should the date the review was submitted be posted with the review?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Review Character Limit</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Character Limit</span></legend>
								<input type='text' name='review_character_limit' value='<?php echo $Review_Character_Limit; ?>' />
								<p>What should be the limit on the number of characters in a review? Leave blank for unlimited characters.</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Reviews Per Page</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Reviews Per Page</span></legend>
								<input type='text' name='reviews_per_page' value='<?php echo $Reviews_Per_Page; ?>' />
								<p>Set the maximum number of reviews that should be displayed at one time.</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Pagination Location</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Pagination Location</span></legend>
								<label title='Top'><input type='radio' name='pagination_location' value='Top' <?php if($Pagination_Location  == "Top") {echo "checked='checked'";} ?> /> <span>Top</span></label><br />
								<label title='Bottom'><input type='radio' name='pagination_location' value='Bottom' <?php if($Pagination_Location  == "Bottom") {echo "checked='checked'";} ?> /> <span>Bottom</span></label><br />
								<label title='Both'><input type='radio' name='pagination_location' value='Both' <?php if($Pagination_Location  == "Both") {echo "checked='checked'";} ?> /> <span>Both</span></label><br />
								<p>Where should the pagination controls be located, if there are more reviews than the maximum per page?</p>
							</fieldset>
						</td>
					</tr>

				</table>
			</div>

			<div id='Premium' class='urp-option-set urp-hidden'>
				<h2 id='label-premium-options' class='urp-options-page-tab-title'>Premium Options</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Review Format</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Format</span></legend>
								<label title='Standard'><input type='radio' name='review_format' value='Standard' <?php if($Review_Format == "Standard") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Standard</span></label><br />
								<label title='Expandable'><input type='radio' name='review_format' value='Expandable' <?php if($Review_Format == "Expandable") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Expandable</span></label><br />
								<label title='Thumbnail'><input type='radio' name='review_format' value='Thumbnail' <?php if($Review_Format == "Thumbnail") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Thumbnail</span></label><br />
								<p></p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Summary Statistics</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Summary Statistics</span></legend>
								<label title='Full'><input type='radio' name='summary_statistics' value='Full' <?php if($Summary_Statistics == "Full") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Full</span></label><br />
								<label title='Limited'><input type='radio' name='summary_statistics' value='Limited' <?php if($Summary_Statistics == "Limited") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Limited</span></label><br />
								<label title='None'><input type='radio' name='summary_statistics' value='None' <?php if($Summary_Statistics  == "None") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>None</span></label><br />
								<p>Should a summary of the reviews be displayed at the top? (average score, etc.)<br>This feature may not work as expected with in-depth reviews and/or pagination.</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Replace WooCommerce Reviews</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Replace WooCommerce Reviews</span></legend>
								<label title='Yes'><input type='radio' name='replace_woocommerce_reviews' value='Yes' <?php if($Replace_WooCommerce_Reviews == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='replace_woocommerce_reviews' value='No' <?php if($Replace_WooCommerce_Reviews == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Should the "Reviews" tab on the WooCommerce product page use Ultimate Reviews instead of the default WooCommerce system?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Override WooCommerce Theme</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Override WooCommerce Theme</span></legend>
								<label title='Yes'><input type='radio' name='override_woocommerce_theme' value='Yes' <?php if($Override_WooCommerce_Theme == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='override_woocommerce_theme' value='No' <?php if($Override_WooCommerce_Theme == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Should the "Ratings" area under the product name on the WooCommerce product page be overwritten if you're using a custom theme?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Weighted Reviews</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Weighted Reviews</span></legend>
								<label title='Yes'><input type='radio' name='review_weights' value='Yes' <?php if($Review_Weights == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='review_weights' value='No' <?php if($Review_Weights == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Should reviews be weighted when the average rating is calculated, so that some reviews count more? These weights can be set below the review's content when turned on.</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Review Karma</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Karma</span></legend>
								<label title='Yes'><input type='radio' name='review_karma' value='Yes' <?php if($Review_Karma == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='review_karma' value='No' <?php if($Review_Karma == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Should visitors be allowed to vote up or down reviews that they find or don't find useful? ("Did you find this review helpful?")<br />Uses cookies to make it more difficult to vote up or down multiple times.</p>
							</fieldset>
						</td>
					</tr>


					<tr>
						<th scope="row">Captcha</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Captcha</span></legend>
								<label title='Yes'><input type='radio' name='use_captcha' value='Yes' <?php if($Use_Captcha == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='use_captcha' value='No' <?php if($Use_Captcha == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Should Captcha be added to the submit review form to prevent spamming? (requires image-creation support for your PHP installation)</p>
							</fieldset>
						</td>
					</tr>

					<!--<tr>
						<th scope="row">Infinite Scroll</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Infinite Scroll</span></legend>
								<label title='Yes'><input type='radio' name='infinite_scroll' value='Yes' <?php if($Infinite_Scroll == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='infinite_scroll' value='No' <?php if($Infinite_Scroll  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>If there are more than the maximum number of reviews per page displayed, should the next page of reviews be loaded automatically by AJAX so that the page doesn't need to be reloaded?</p>
							</fieldset>
						</td>
					</tr>-->

					<tr>
						<th scope="row">Thumbnail Characters</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Thumbnail Characters</span></legend>
								<input type='text' name='thumbnail_characters' value='<?php echo $Thumbnail_Characters; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
								<p>What is the maximum number of characters that should be shown in the preview in thumbnail format?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Admin Notification Email</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Admin Notification Email</span></legend>
								<label title='Yes'><input type='radio' name='admin_notification' value='Yes' <?php if($Admin_Notification == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='admin_notification' value='No' <?php if($Admin_Notification  == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Should an email be sent to the WordPress admin?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Require Admin Approval</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Require Admin Approval</span></legend>
								<label title='Yes'><input type='radio' name='admin_approval' value='Yes' <?php if($Admin_Approval == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='admin_approval' value='No' <?php if($Admin_Approval  == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Should new reviews have their status set to 'draft' until an admin decides to publish them?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Require Author Email</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Require Author Email</span></legend>
								<label title='Yes'><input type='radio' name='require_email' value='Yes' <?php if($Require_Email == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='require_email' value='No' <?php if($Require_Email  == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Do reviewers have to include their email address (not publicly displayed) when they post a review?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Require Email Confirmation</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Require Email Confirmation</span></legend>
								<label title='Yes'><input type='radio' name='email_confirmation' value='Yes' <?php if($Email_Confirmation == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='email_confirmation' value='No' <?php if($Email_Confirmation  == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Do reviewers have to confirm their email address before their review is displayed?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Display Form on Confirmation</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Display Form on Confirmation</span></legend>
								<label title='Yes'><input type='radio' name='display_on_confirmation' value='Yes' <?php if($Display_On_Confirmation == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='display_on_confirmation' value='No' <?php if($Display_On_Confirmation  == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Should the submit review form be displayed when someone is confirming their email address?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Require Login</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Require Login</span></legend>
								<label title='Yes'><input type='radio' name='require_login' value='Yes' <?php if($Require_Login == "Yes") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='require_login' value='No' <?php if($Require_Login == "No") {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
								<p>Do reviewers have to login before they can post a review?</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Login Options</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Login Options</span></legend>
								<label title='WordPress'><input id='ewd-urp-wordpress-login-option' type='checkbox' name='login_options[]' value='WordPress' <?php if(in_array("WordPress", $Login_Options)) {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>WordPress</span></label><br />
								<label title='FEUP'><input id='ewd-urp-feup-login-option' type='checkbox' name='login_options[]' value='FEUP' <?php if(in_array("FEUP", $Login_Options)) {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span><a href='https://wordpress.org/plugins/front-end-only-users/'>Front-End Only Users</a></span></label><br />
								<label title='Twitter'><input id='ewd-urp-twitter-login-option' type='checkbox' name='login_options[]' value='Twitter' <?php if(in_array("Twitter", $Login_Options)) {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Twitter</span></label><br />
								<label title='Facebook'><input id='ewd-urp-facebook-login-option' type='checkbox' name='login_options[]' value='Facebook' <?php if(in_array("Facebook", $Login_Options)) {echo "checked='checked'";} ?> <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Facebook</span></label><br />
								<p>What methods should users be able to use to log in before posting a review?</p>
							</fieldset>
						</td>
					</tr>

					<tr class='ewd-urp-wordpress-login-option'>
						<th scope="row">WordPress Login URL</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>WordPress Login URL</span></legend>
								<input type='text' name='wordpress_login_url' value='<?php echo $WordPress_Login_URL; ?>' />
								<p>The URL of your WordPress login page.</p>
							</fieldset>
						</td>
					</tr>
					<tr class='ewd-urp-feup-login-option'>
						<th scope="row">FEUP Login URL</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>FEUP Login URL</span></legend>
								<input type='text' name='feup_login_url' value='<?php echo $FEUP_Login_URL; ?>' />
								<p>The URL of your Front-End Only Users login page.</p>
							</fieldset>
						</td>
					</tr>
					<tr class='ewd-urp-facebook-login-option'>
						<th scope="row">Facebook App ID</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Facebook App ID</span></legend>
								<input type='text' name='facebook_app_id' value='<?php echo $Facebook_App_ID; ?>' />
								<p>The App ID displayed when you created the Facebook API application request.<br />
								Check out <a href='https://www.youtube.com/watch?v=txCfgVmsR7g'> this tutorial</a> if you need help getting an App ID or App Secret.</p>
							</fieldset>
						</td>
					</tr>
					<tr class='ewd-urp-facebook-login-option'>
						<th scope="row">Facebook Secret</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Facebook Secret</span></legend>
								<input type='text' name='facebook_secret' value='<?php echo $Facebook_Secret; ?>' />
								<p>The secret displayed when you created the Facebook API application request.</p>
							</fieldset>
						</td>
					</tr>
					<tr class='ewd-urp-twitter-login-option'>
						<th scope="row">Twitter Key</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Twitter Key</span></legend>
								<input type='text' name='twitter_key' value='<?php echo $Twitter_Key; ?>' />
								<p>The key displayed when you created the Twitter API application request.<br />
								Check out <a href='https://www.youtube.com/watch?v=9ckccMDhtQI'> this tutorial</a> if you need help getting an App ID or App Secret.</p>
							</fieldset>
						</td>
					</tr>
					<tr class='ewd-urp-twitter-login-option'>
						<th scope="row">Twitter Secret</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Twitter Secret</span></legend>
								<input type='text' name='twitter_secret' value='<?php echo $Twitter_Secret; ?>' />
								<p>The secret displayed when you created the Twitter API application request.</p>
							</fieldset>
						</td>
					</tr>

				</table>
			</div>

			<div id='Order' class='urp-option-set urp-hidden'>
				<h2 id='label-styling-options' class='urp-options-page-tab-title'>Order Options</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Group By Product</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Group By Product</span></legend>
								<label title='Yes'><input type='radio' name='group_by_product' value='Yes' <?php if($Group_By_Product == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='group_by_product' value='No' <?php if($Group_By_Product  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>If the product_name attribute is left blank, should the reviews be grouped by the product they review?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Group By Product Direction</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Group By Product Direction</span></legend>
								<label title='ASC'><input type='radio' name='group_by_product_order' value='ASC' <?php if($Group_By_Product_Order == "ASC") {echo "checked='checked'";} ?> /> <span>Ascending</span></label><br />
								<label title='DESC'><input type='radio' name='group_by_product_order' value='DESC' <?php if($Group_By_Product_Order  == "DESC") {echo "checked='checked'";} ?> /> <span>Descending</span></label><br />
								<p>If products are grouped by name, should they be grouped in ascending or descending order?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Ordering Type</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Ordering Type</span></legend>
								<label title='Date'><input type='radio' name='ordering_type' value='Date' <?php if($Ordering_Type == "Date") {echo "checked='checked'";} ?> /> <span>Submitted Date</span></label><br />
								<label title='Rating'><input type='radio' name='ordering_type' value='Rating' <?php if($Ordering_Type  == "Rating") {echo "checked='checked'";} ?> /> <span>Rating (Not possible if grouping by product name)</span></label><br />
								<label title='Title'><input type='radio' name='ordering_type' value='Title' <?php if($Ordering_Type  == "Title") {echo "checked='checked'";} ?> /> <span>Review Title</span></label><br />
								<p>What type of ordering should be used for the reviews?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Order Direction</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Order Direction</span></legend>
								<label title='ASC'><input type='radio' name='order_direction' value='ASC' <?php if($Order_Direction == "ASC") {echo "checked='checked'";} ?> /> <span>Ascending</span></label><br />
								<label title='DESC'><input type='radio' name='order_direction' value='DESC' <?php if($Order_Direction  == "DESC") {echo "checked='checked'";} ?> /> <span>Descending</span></label><br />
								<p>Should the ordering be ascending or descending?</p>
							</fieldset>
						</td>
					</tr>
				</table>
			</div>

			<div id='Labelling' class='urp-option-set urp-hidden'>
				<h2 id='label-order-options' class='urp-options-page-tab-title'>Labelling Options (Premium)</h2>
				<div class="urp-label-description"> Replace the default text on the Review pages </div>

			<h3>Review Content</h3>
				<div id='labelling-view-options' class="urp-options-div urp-options-flex">
					<div class='urp-option urp-label-option'>
						<?php _e("Posted", 'urp')?>
						<fieldset>
							<input type='text' name='posted_label' value='<?php echo $Posted_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("By", 'urp')?>
						<fieldset>
							<input type='text' name='by_label' value='<?php echo $By_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("On", 'urp')?>
						<fieldset>
							<input type='text' name='on_label' value='<?php echo $On_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Score", 'urp')?>
						<fieldset>
							<input type='text' name='score_label' value='<?php echo $Score_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Explanation", 'urp')?>
						<fieldset>
							<input type='text' name='explanation_label' value='<?php echo $Explanation_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
				</div>
					<h3>Submit Review</h3>
				<div id='labelling-view-options' class="urp-options-div urp-options-flex">
					<div class='urp-option urp-label-option'>
						<?php _e("Product Name", 'urp')?>
						<fieldset>
							<input type='text' name='submit_product_label' value='<?php echo $Submit_Product_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Review Author", 'urp')?>
						<fieldset>
							<input type='text' name='submit_author_label' value='<?php echo $Submit_Author_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Author 'Comment'", 'urp')?>
						<fieldset>
							<input type='text' name='submit_author_comment_label' value='<?php echo $Submit_Author_Comment_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Review Title", 'urp')?>
						<fieldset>
							<input type='text' name='submit_title_label' value='<?php echo $Submit_Title_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Title 'Comment'", 'urp')?>
						<fieldset>
							<input type='text' name='submit_title_comment_label' value='<?php echo $Submit_Title_Comment_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Overall Score", 'urp')?>
						<fieldset>
							<input type='text' name='submit_score_label' value='<?php echo $Submit_Score_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Review", 'urp')?>
						<fieldset>
							<input type='text' name='submit_review_label' value='<?php echo $Submit_Review_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Category 'Score'", 'urp')?>
						<fieldset>
							<input type='text' name='submit_cat_score_label' value='<?php echo $Submit_Cat_Score_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("Category 'Explanation'", 'urp')?>
						<fieldset>
							<input type='text' name='submit_explanation_label' value='<?php echo $Submit_Explanation_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
						</fieldset>
					</div>
					<div class='urp-option urp-label-option'>
						<?php _e("'Send Review' Button", 'urp')?>
						<fieldset>
							<input type='text' name='submit_button_label' value='<?php echo $Submit_Button_Label; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> />
						</fieldset>
					</div>
				</div>
				<h3>Messages</h3>
				<div id='labelling-view-options' class="urp-options-div urp-options-flex">
					<div class='urp-option urp-label-option ewd-urp-message-input-div'>
						<?php _e("Submit Success Message", 'urp')?>
						<fieldset>
							<input type='text' name='submit_success_message' class='ewd-urp-message-input' value='<?php echo $Submit_Success_Message; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
					<div class='urp-option urp-label-option ewd-urp-message-input-div'>
						<?php _e("Submit Draft Add On Message", 'urp')?>
						<fieldset>
							<input type='text' name='submit_draft_message' class='ewd-urp-message-input' value='<?php echo $Submit_Draft_Message; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?>/>
						</fieldset>
					</div>
				</div>
			</div>

			<div id='Styling' class='urp-option-set urp-hidden'>
				<h2 id='label-styling-options' class='urp-options-page-tab-title'>Styling Options</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Display Score</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Display Score</span></legend>
								<label title='Yes'><input type='radio' name='display_numerical_score' value='Yes' <?php if($Display_Numerical_Score == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='display_numerical_score' value='No' <?php if($Display_Numerical_Score  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Should review score be shown beside the review?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Reviews Skin Style</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Reviews Skin Style</span></legend>
								<label title='Basic'><input type='radio' name='reviews_skin' value='Basic' <?php if($Reviews_Skin == "Basic") {echo "checked='checked'";} ?> /> <span>None</span></label><br />
								<label title='SimpleStars'><input type='radio' name='reviews_skin' value='SimpleStars' <?php if($Reviews_Skin  == "SimpleStars") {echo "checked='checked'";} ?> /> <span>Simple Stars</span></label><br />
								<label title='Thumbs'><input type='radio' name='reviews_skin' value='Thumbs' <?php if($Reviews_Skin  == "Thumbs") {echo "checked='checked'";} ?> /> <span>Thumbs</span></label><br />
								<label title='Hearts'><input type='radio' name='reviews_skin' value='Hearts' <?php if($Reviews_Skin  == "Hearts") {echo "checked='checked'";} ?> /> <span>Hearts</span></label><br />
								<label title='SimpleBar'><input type='radio' name='reviews_skin' value='SimpleBar' <?php if($Reviews_Skin  == "SimpleBar") {echo "checked='checked'";} ?> /> <span>Simple Bar</span></label><br />
								<label title='ColorBar'><input type='radio' name='reviews_skin' value='ColorBar' <?php if($Reviews_Skin  == "ColorBar") {echo "checked='checked'";} ?> /> <span>Color Bar</span></label><br />
								<p>What styling skin should the reviews use?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Review Group Separating Line</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Review Group Separating Line</span></legend>
								<label title='Yes'><input type='radio' name='review_group_separating_line' value='Yes' <?php if($Review_Group_Separating_Line == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='review_group_separating_line' value='No' <?php if($Review_Group_Separating_Line  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Add a separating line between each each group of reviews (must have "Group By Product" enabled).</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">In-Depth Review Block Layout</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>In-Depth Review Block Layout</span></legend>
								<label title='Yes'><input type='radio' name='indepth_block_layout' value='Yes' <?php if($InDepth_Block_Layout == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
								<label title='No'><input type='radio' name='indepth_block_layout' value='No' <?php if($InDepth_Block_Layout  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								<p>Makes separation between each different in-depth element more pronounced.</p>
							</fieldset>
						</td>
					</tr>
				</table>

				<h3>Premium Styling Options</h3>
				<div id='urp-styling-options' class="urp-options-div urp-options-flex">
					<div class='urp-subsection'>
						<div class='urp-subsection-header'>Review Title</div>
						<div class='urp-subsection-content'>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Family</div>
								<div class='urp-option-input'><input type='text' name='urp_review_title_font' value='<?php echo $urp_Review_Title_Font; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Size</div>
								<div class='urp-option-input'><input type='text' name='urp_review_title_font_size' value='<?php echo $urp_Review_Title_Font_Size; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Color</div>
								<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_review_title_font_color' value='<?php echo $urp_Review_Title_Font_Color; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Margin</div>
								<div class='urp-option-input'><input type='text' name='urp_review_title_margin' value='<?php echo $urp_Review_Title_Margin; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Padding</div>
								<div class='urp-option-input'><input type='text' name='urp_review_title_padding' value='<?php echo $urp_Review_Title_Padding; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
						</div>
					</div>
					<div class='urp-subsection'>
						<div class='urp-subsection-header'>Review Content</div>
						<div class='urp-subsection-content'>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Family</div>
								<div class='urp-option-input'><input type='text' name='urp_review_content_font' value='<?php echo $urp_Review_Content_Font; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Size</div>
								<div class='urp-option-input'><input type='text' name='urp_review_content_font_size' value='<?php echo $urp_Review_Content_Font_Size; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Color</div>
								<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_review_content_font_color' value='<?php echo $urp_Review_Content_Font_Color; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Margin</div>
								<div class='urp-option-input'><input type='text' name='urp_review_content_margin' value='<?php echo $urp_Review_Content_Margin; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Padding</div>
								<div class='urp-option-input'><input type='text' name='urp_review_content_padding' value='<?php echo $urp_Review_Content_Padding; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
						</div>
					</div>
					<div class='urp-subsection'>
						<div class='urp-subsection-header'>Review Post Date</div>
						<div class='urp-subsection-content'>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Family</div>
								<div class='urp-option-input'><input type='text' name='urp_review_postdate_font' value='<?php echo $urp_Review_Postdate_Font; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Size</div>
								<div class='urp-option-input'><input type='text' name='urp_review_postdate_font_size' value='<?php echo $urp_Review_Postdate_Font_Size; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Color</div>
								<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_review_postdate_font_color' value='<?php echo $urp_Review_Postdate_Font_Color; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Margin</div>
								<div class='urp-option-input'><input type='text' name='urp_review_postdate_margin' value='<?php echo $urp_Review_Postdate_Margin; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Padding</div>
								<div class='urp-option-input'><input type='text' name='urp_review_postdate_padding' value='<?php echo $urp_Review_Postdate_Padding; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
						</div>
					</div>
					<div class='urp-subsection'>
						<div class='urp-subsection-header'>Review Score</div>
						<div class='urp-subsection-content'>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Family</div>
								<div class='urp-option-input'><input type='text' name='urp_review_score_font' value='<?php echo $urp_Review_Score_Font; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Size</div>
								<div class='urp-option-input'><input type='text' name='urp_review_score_font_size' value='<?php echo $urp_Review_Score_Font_Size; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Font Color</div>
								<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_review_score_font_color' value='<?php echo $urp_Review_Score_Font_Color; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Margin</div>
								<div class='urp-option-input'><input type='text' name='urp_review_score_margin' value='<?php echo $urp_Review_Score_Margin; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Padding</div>
								<div class='urp-option-input'><input type='text' name='urp_review_score_padding' value='<?php echo $urp_Review_Score_Padding; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
						</div>
					</div>
						<div class='urp-subsection'>
						<div class='urp-subsection-header'>Review Color Option</div>
						<div class='urp-subsection-content'>
							<div class='urp-option urp-styling-option'>
								<div class='urp-option-label'>Summary Statistics Color</div>
								<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_summary_stats_color' value='<?php echo $urp_Summary_Stats_Color; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
							<div class='urp-option urp-styling-option'>
									<div class='urp-option-label'>Simple Bar Color</div>
									<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_simple_bar_color' value='<?php echo $urp_Simple_Bar_Color; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
								</div>
							<div class='urp-option urp-styling-option'>
									<div class='urp-option-label'>Color Bar High</div>
									<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_color_bar_high' value='<?php echo $urp_Color_Bar_High; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
								</div>
							<div class='urp-option urp-styling-option'>
									<div class='urp-option-label'>Color Bar Medium</div>
									<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_color_bar_medium' value='<?php echo $urp_Color_Bar_Medium; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
								</div>
							<div class='urp-option urp-styling-option'>
									<div class='urp-option-label'>Color Bar Low</div>
									<div class='urp-option-input'><input type='text' class='urp-spectrum' name='urp_color_bar_low' value='<?php echo $urp_Color_Bar_Low; ?>' <?php if ($URP_Full_Version != "Yes") {echo "disabled";} ?> /></div>
								</div>

						</div></div>
				</div>
				</div>

				<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

			</div>
		</div>
