/* Used to show and hide the admin tabs for URP */
function ShowTab(TabName) {
		jQuery(".OptionTab").each(function() {
				jQuery(this).addClass("HiddenTab");
				jQuery(this).removeClass("ActiveTab");
		});
		jQuery("#"+TabName).removeClass("HiddenTab");
		jQuery("#"+TabName).addClass("ActiveTab");
		
		jQuery(".nav-tab").each(function() {
				jQuery(this).removeClass("nav-tab-active");
		});
		jQuery("#"+TabName+"_Menu").addClass("nav-tab-active");
}


function ShowOptionTab(TabName) {
	jQuery(".urp-option-set").each(function() {
		jQuery(this).addClass("urp-hidden");
	});
	jQuery("#"+TabName).removeClass("urp-hidden");

	jQuery(".options-subnav-tab").each(function() {
		jQuery(this).removeClass("options-subnav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("options-subnav-tab-active");
}

jQuery(document).ready(function() {
	SetCategoryDeleteHandlers();

	jQuery('.ewd-urp-add-review-category').on('click', function(event) {
		var ID = jQuery(this).data('nextid');

		var HTML = "<tr id='ewd-urp-review-category-row-" + ID + "'>";
		HTML += "<td><a class='ewd-urp-delete-review-category' data-reviewid='" + ID + "'>Delete</a></td>";
		HTML += "<td><input type='text' name='Review_Category_" + ID + "_Name'></td>";
		HTML += "<td><select name='Review_Category_" + ID + "_Explanation'>";
		HTML += "<option value='Yes'>Yes</option>";
		HTML += "<option value='No'>No</option>";
		HTML += "</select></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-uasp-add-reminder').before(HTML);
		jQuery('#ewd-urp-review-categories-table tr:last').before(HTML);

		ID++;
		jQuery(this).data('nextid', ID); //updates but doesn't show in DOM

		SetCategoryDeleteHandlers();

		event.preventDefault();
	});
});

function SetCategoryDeleteHandlers() {
	jQuery('.ewd-urp-delete-review-category').on('click', function(event) {
		var ID = jQuery(this).data('reviewid');
		var tr = jQuery('#ewd-urp-review-category-row-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	SetProductDeleteHandlers();

	jQuery('.ewd-urp-add-product-list-item').on('click', function(event) {
		var ID = jQuery(this).data('nextid');

		var HTML = "<tr id='ewd-urp-product-list-item-" + ID + "'>";
		HTML += "<td><a class='ewd-urp-delete-product-list-item' data-productid='" + ID + "'>Delete</a></td>";
		HTML += "<td><input type='text' name='Product_List_" + ID + "_Name'></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-uasp-add-reminder').before(HTML);
		jQuery('#ewd-urp-product-list-table tr:last').before(HTML);

		ID++;
		jQuery(this).data('nextid', ID); //updates but doesn't show in DOM

		SetProductDeleteHandlers();

		event.preventDefault();
	});
});

function SetProductDeleteHandlers() {
	jQuery('.ewd-urp-delete-product-list-item').on('click', function(event) {
		var ID = jQuery(this).data('productid');
		var tr = jQuery('#ewd-urp-product-list-item-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	jQuery('#ewd-urp-wordpress-login-option').on('change', {optionType: "wordpress"}, Update_Options);
	jQuery('#ewd-urp-feup-login-option').on('change', {optionType: "feup"}, Update_Options);
	jQuery('#ewd-urp-facebook-login-option').on('change', {optionType: "facebook"}, Update_Options);
	jQuery('#ewd-urp-twitter-login-option').on('change', {optionType: "twitter"}, Update_Options);
	
	Update_Options();
});

jQuery(function() {
    ProductsReorderList();
});


function ProductsReorderList() {
    jQuery("#ewd-urp-product-list-table > tbody").sortable({
    	stop: function( event, ui ) {saveOrderClick(); }
    }).disableSelection();
}

function saveOrderClick() {
    // ----- Retrieve the li items inside our sortable list
    var items = jQuery("#ewd-urp-product-list-table tbody tr");

    var productIDs = [items.size()];
    var index = 0;

    // ----- Iterate through each li, extracting the ID embedded as an attribute
    items.each( function(intIndex) {
            jQuery(this).children().each(function() {
            	if (jQuery(this).html().substring(0,6) == "<input") {
            		jQuery(this).children().each(function() {
            			jQuery(this).attr('name', 'Product_List_'+intIndex+'_Name');
            		});
            	}
            });
        });
}

function Update_Options(params) {
	if (params === undefined || params.data.optionType == "wordpress") {
		if (jQuery('#ewd-urp-wordpress-login-option').is(':checked')) {
			jQuery('.ewd-urp-wordpress-login-option').removeClass('ewd-urp-hidden');
		}
		else {
			jQuery('.ewd-urp-wordpress-login-option').addClass('ewd-urp-hidden');
		}
	}
	if (params === undefined || params.data.optionType == "feup") {
		if (jQuery('#ewd-urp-feup-login-option').is(':checked')) {
			jQuery('.ewd-urp-feup-login-option').removeClass('ewd-urp-hidden');
		}
		else {
			jQuery('.ewd-urp-feup-login-option').addClass('ewd-urp-hidden');
		}
	}
	if (params === undefined || params.data.optionType == "facebook") {
		if (jQuery('#ewd-urp-facebook-login-option').is(':checked')) {
			jQuery('.ewd-urp-facebook-login-option').removeClass('ewd-urp-hidden');
		}
		else {
			jQuery('.ewd-urp-facebook-login-option').addClass('ewd-urp-hidden');
		}
	}
	if (params === undefined || params.data.optionType == "twitter") {
		if (jQuery('#ewd-urp-twitter-login-option').is(':checked')) {
			jQuery('.ewd-urp-twitter-login-option').removeClass('ewd-urp-hidden');
		}
		else {
			jQuery('.ewd-urp-twitter-login-option').addClass('ewd-urp-hidden');
		}
	}
}

jQuery(document).ready(function() {
	jQuery('.urp-spectrum').spectrum({
		showInput: true,
		showInitial: true,
		preferredFormat: "hex",
		allowEmpty: true
	});

	jQuery('.urp-spectrum').css('display', 'inline');

	jQuery('.urp-spectrum').on('change', function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_URP_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
		else {
			jQuery(this).css('background', 'none');
		}
	});

	jQuery('.urp-spectrum').each(function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_UPCP_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
	});
});

function EWD_URP_hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}