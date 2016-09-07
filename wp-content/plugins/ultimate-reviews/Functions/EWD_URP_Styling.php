<?php
function EWD_URP_Add_Modified_Styles() {
	$StylesString = "<style>";
	$StylesString .=".ewd-urp-review-title { ";
		if (get_option("EWD_urp_Review_Title_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_urp_Review_Title_Font") . " !important;";}
		if (get_option("EWD_urp_Review_Title_Font_Size") != "") {$StylesString .= "font-size:" .  get_option("EWD_urp_Review_Title_Font_Size") . " !important;";}
		if (get_option("EWD_urp_Review_Title_Font_Color") != "") {$StylesString .="color:" . get_option("EWD_urp_Review_Title_Font_Color") . " !important;";}
		if (get_option("EWD_urp_Review_Title_Margin") != "") {$StylesString .= "margin:" . get_option("EWD_urp_Review_Title_Margin") . " !important;";}
		if (get_option("EWD_urp_Review_Title_Padding") != "") {$StylesString .= "padding:" . get_option("EWD_urp_Review_Title_Padding") . " !important;";}
		$StylesString .="}\n";
	$StylesString .=".ewd-urp-review-body { ";
		if (get_option("EWD_urp_Review_Content_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_urp_Review_Content_Font") . " !important;";}
		if (get_option("EWD_urp_Review_Content_Font_Size") != "") {$StylesString .= "font-size:" .  get_option("EWD_urp_Review_Content_Font_Size") . " !important;";}
		if (get_option("EWD_urp_Review_Content_Font_Color") != "") {$StylesString .="color:" . get_option("EWD_urp_Review_Content_Font_Color") . " !important;";}
		if (get_option("EWD_urp_Review_Content_Margin") != "") {$StylesString .= "margin:" . get_option("EWD_urp_Review_Content_Margin") . " !important;";}
		if (get_option("EWD_urp_Review_Content_Padding") != "") {$StylesString .= "padding:" . get_option("EWD_urp_Review_Content_Padding") . " !important;";}
		$StylesString .="}\n";
	$StylesString .=".ewd-urp-author-date { ";
		if (get_option("EWD_urp_Review_Postdate_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_urp_Review_Postdate_Font") . " !important;";}
		if (get_option("EWD_urp_Review_Postdate_Font_Size") != "") {$StylesString .= "font-size:" .  get_option("EWD_urp_Review_Postdate_Font_Size") . " !important;";}
		if (get_option("EWD_urp_Review_Postdate_Font_Color") != "") {$StylesString .="color:" . get_option("EWD_urp_Review_Postdate_Font_Color") . " !important;";}
		if (get_option("EWD_urp_Review_Postdate_Margin") != "") {$StylesString .= "margin:" . get_option("EWD_urp_Review_Postdate_Margin") . " !important;";}
		if (get_option("EWD_urp_Review_Postdate_Padding") != "") {$StylesString .= "padding:" . get_option("EWD_urp_Review_Postdate_Padding") . " !important;";}
		$StylesString .="}\n";
	$StylesString .=".ewd-urp-review-score { ";
		if (get_option("EWD_urp_Review_Score_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_urp_Review_Score_Font") . " !important;";}
		if (get_option("EWD_urp_Review_Score_Font_Size") != "") {$StylesString .= "font-size:" .  get_option("EWD_urp_Review_Score_Font_Size") . " !important;";}
		if (get_option("EWD_urp_Review_Score_Font_Color") != "") {$StylesString .="color:" . get_option("EWD_urp_Review_Score_Font_Color") . " !important;";}
		if (get_option("EWD_urp_Review_Score_Margin") != "") {$StylesString .= "margin:" . get_option("EWD_urp_Review_Score_Margin") . " !important;";}
		if (get_option("EWD_urp_Review_Score_Padding") != "") {$StylesString .= "padding:" . get_option("EWD_urp_Review_Score_Padding") . " !important;";}
		$StylesString .="}\n";
	$StylesString .=".ewd-urp-standard-summary-graphic-full-sub-group { ";
		if (get_option("EWD_urp_Summary_Stats_Color") != "") {$StylesString .= "background-color:" .  get_option("EWD_urp_Summary_Stats_Color") . " !important;";}
		$StylesString .="}\n";
		$StylesString .=".ewd-urp-blue-bar { ";
		if (get_option("EWD_urp_Simple_Bar_Color") != "") {$StylesString .= "background-color:" .  get_option("EWD_urp_Simple_Bar_Color") . " !important;";}
		$StylesString .="}\n";
		$StylesString .=".ewd-urp-green-bar { ";
		if (get_option("EWD_urp_Color_Bar_High") != "") {$StylesString .= "background-color:" .  get_option("EWD_urp_Color_Bar_High") . " !important;";}
		$StylesString .="}\n";
		$StylesString .=".ewd-urp-yellow-bar { ";
		if (get_option("EWD_urp_Color_Bar_Medium") != "") {$StylesString .= "background-color:" .  get_option("EWD_urp_Color_Bar_Medium") . " !important;";}
		$StylesString .="}\n";
		$StylesString .=".ewd-urp-red-bar { ";
		if (get_option("EWD_urp_Color_Bar_Low") != "") {$StylesString .= "background-color:" .  get_option("EWD_urp_Color_Bar_Low") . " !important;";}
		$StylesString .="}\n";
	$StylesString .=".ewd-urp-summary-statistics-header { ";
		if (get_option("EWD_URP_Review_Group_Separating_Line") == "Yes") {$StylesString .= "border-top: 1px solid #ccc; padding-top: 18px;";}
		$StylesString .="}\n";
	$StylesString .=".ewd-urp-category-field { ";
		if (get_option("EWD_URP_InDepth_Block_Layout") == "Yes") {$StylesString .= "margin-bottom: 0; padding: 4px 8px;";}
		$StylesString .="}\n";
		$StylesString .=".ewd-urp-category-field:nth-of-type(2n+1) { ";
		if (get_option("EWD_URP_InDepth_Block_Layout") == "Yes") {$StylesString .= "background: #f4f4f4;";} 
		$StylesString .="}\n";

	$StylesString .= "</style>";

	return $StylesString;
}
