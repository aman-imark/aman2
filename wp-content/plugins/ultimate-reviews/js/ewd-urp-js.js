jQuery(function(){ //DOM Ready
    URPSetClickHandlers();
    URPSetKeyStrokeCounters();
    URPSetKarmaHandlers();
    URPSetStarHandlers();
    URPSetFilteringHandlers();
    URPSetPaginationHandlers();

    jQuery('#urp-ajax-text-input').on('keyup', function() {
    	
	    var Question = jQuery('.urp-text-input').val();
	    var product_name = jQuery('#urp-search-product-name').val();
	    var orderby = jQuery('#urp-search-orderby').val();
	    var order = jQuery('#urp-search-order').val();
	    var post_count = jQuery('#urp-search-post-count').val();
	
	    jQuery('#urp-ajax-results').html('<h3>Retrieving results...</h3>');
	
	    var data = 'Q=' + Question + '&product_name=' + product_name + '&orderby=' + orderby + '&order=' + order + '&post_count=' + post_count + '&action=urp_search';
	    jQuery.post(ajaxurl, data, function(response) {
	        response = response.substring(0, response.length - 1);
	        jQuery('#urp-ajax-results').html(response);
	        URPSetClickHandlers();
	        URPSetFilteringHandlers();
	        URPSetKarmaHandlers();
	        URPSetPaginationHandlers();
	    });
	});

});

function URPSetClickHandlers() {
	jQuery('.ewd-urp-product-name-text-input').on('keyup', function() {
		if (typeof autocompleteProductNames === 'undefined' || autocompleteProductNames === null) {autocompleteProductNames = "No";}
		if (typeof restrictProductNames === 'undefined' || restrictProductNames === null) {restrictProductNames = "No";}

		if (restrictProductNames == "Yes") {
			if (jQuery.inArray(jQuery('.ewd-urp-product-name-text-input').val(), productNames) == -1) {
				jQuery('#ewd-urp-restrict-product-names-message').html("Please make sure the product name matches exactly.");
				jQuery('#ewd-urp-review-submit').prop('disabled', true);
			}
			else {
				jQuery('#ewd-urp-restrict-product-names-message').html("");
				jQuery('#ewd-urp-review-submit').prop('disabled', false);
			}
		}

		if (autocompleteProductNames == "Yes") {
			jQuery('.ewd-urp-product-name-text-input').autocomplete({
				source: productNames
			});
			if (jQuery('.ewd-urp-product-name-text-input').val().length >1) {
				jQuery('.ewd-urp-product-name-text-input').autocomplete( "enable" );
			}
			else {
				jQuery('.ewd-urp-product-name-text-input').autocomplete( "disable" );
			}
		}
	});

	jQuery('.ewd-urp-expandable-title').on('click', function(event) {
		if (typeof accordionExpandable === 'undefined' || accordionExpandable === null) {accordionExpandable = "No";}

		var reviewID = jQuery(this).data('postid');

		if (jQuery('#ewd-urp-review-content-'+reviewID).hasClass('ewd-urp-content-hidden')) {var action = 'Open';}
		else {var action = 'Close';}

		if (accordionExpandable == "Yes") {
			jQuery('.ewd-urp-review-content').addClass('ewd-urp-content-hidden');
		}

		if (action == 'Close') {jQuery('#ewd-urp-review-content-'+reviewID).addClass('ewd-urp-content-hidden');}
		else {
			jQuery('#ewd-urp-review-content-'+reviewID).removeClass('ewd-urp-content-hidden');
			var data = 'post_id=' + reviewID + '&action=urp_record_view';
    		jQuery.post(ajaxurl, data, function(response) {});
		}

		event.preventDefault();
	});
}

function URPSetKeyStrokeCounters() {
	jQuery('.ewd-urp-review-textarea').on('keyup', function() {
		var textareaCount = jQuery(this).data('textareacount');
		var currentChars = jQuery(this).val().length;

		if (ewd_urp_php_data.review_character_limit == "") {return;}

		var returnText = "<label></label>Characters remaining: " + (parseInt(ewd_urp_php_data.review_character_limit) - currentChars);

		if (currentChars > ewd_urp_php_data.review_character_limit) {
			jQuery('#ewd-urp-review-submit').prop('disabled', true);
			jQuery('#ewd-urp-review-character-count-'+textareaCount).css('color', 'red');
		}
		else {
			jQuery('#ewd-urp-review-submit').prop('disabled', false);
			jQuery('#ewd-urp-review-character-count-'+textareaCount).css('color', 'inherit');
		}

		jQuery('#ewd-urp-review-character-count-'+textareaCount).html(returnText);
	})
}

function URPSetKarmaHandlers() {
	jQuery('.ewd-urp-karma-down').on('click', function() {
		var reviewID = jQuery(this).data('reviewid'); 
		if (reviewID == "0") {return;}

		URPKarmaAJAX('down', reviewID);

		var currentScore = jQuery('#ewd-urp-karma-score-'+reviewID).html();
		currentScore--;
		jQuery('#ewd-urp-karma-score-'+reviewID).html(currentScore);

		jQuery(this).data('reviewid', '0');
	});

	jQuery('.ewd-urp-karma-up').on('click', function() {
		var reviewID = jQuery(this).data('reviewid');
		if (reviewID == "0") {return;}

		URPKarmaAJAX('up', reviewID);

		var currentScore = jQuery('#ewd-urp-karma-score-'+reviewID).html();
		currentScore++;
		jQuery('#ewd-urp-karma-score-'+reviewID).html(currentScore);

		jQuery(this).data('reviewid', '0');
	});
}

function URPKarmaAJAX(direction, reviewID) {
	var data = 'Direction=' + direction + '&ReviewID=' + reviewID + '&action=urp_update_karma';
    jQuery.post(ajaxurl, data, function(response) {});
}

function URPSetStarHandlers() {
	jQuery('.ewd-urp-star-input').on('click', function() {
		var score = jQuery(this).data('reviewscore');
		var inputName = jQuery(this).data('inputname');
		var cssIDAdd = jQuery(this).data('cssidadd');
		
		var counter = 1;
		while (counter <= ewd_urp_php_data.maximum_score) {
			if (counter <= score) {jQuery('#ewd-urp-star-input-'+cssIDAdd+'-'+counter).addClass('ewd-urp-star-input-filled');}
			else {jQuery('#ewd-urp-star-input-'+cssIDAdd+'-'+counter).removeClass('ewd-urp-star-input-filled');}
			counter++;
		}

		jQuery("input[name='"+inputName+"']").val(score);
	})
}

function URPSetFilteringHandlers() {
	jQuery('.ewd-urp-filtering-toggle').on('click', function() {
		if (jQuery('.ewd-urp-filtering-controls').hasClass('ewd-urp-hidden')) {
			jQuery('.ewd-urp-filtering-controls').removeClass('ewd-urp-hidden');
			jQuery('.ewd-urp-filtering-toggle').removeClass('ewd-urp-filtering-toggle-downcaret');
			jQuery('.ewd-urp-filtering-toggle').addClass('ewd-urp-filtering-toggle-upcaret');
		}
		else {
			jQuery('.ewd-urp-filtering-controls').addClass('ewd-urp-hidden');
			jQuery('.ewd-urp-filtering-toggle').removeClass('ewd-urp-filtering-toggle-upcaret');
			jQuery('.ewd-urp-filtering-toggle').addClass('ewd-urp-filtering-toggle-downcaret');
		}
	});

	jQuery('.ewd-urp-filtering-select').on('change', function(event) {
		URPFilterResults();
	});

    jQuery("#ewd-urp-review-score-filter").slider({
    	range: true,
    	min: 1,
    	max: ewd_urp_php_data.maximum_score,
    	values: [ 1, ewd_urp_php_data.maximum_score ],
        change: function( event, ui ) {
           jQuery("#ewd-urp-score-range").text( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
           URPFilterResults();
        }
    });
}

function URPFilterResults() {
	var search_string = jQuery('#urp-search-string').val();
	if (jQuery('.ewd-filtering-product-name').val() == "All" || jQuery('.ewd-filtering-product-name').val() == undefined) {var product_name = jQuery('#urp-product-name').val();}
	else {var product_name = jQuery('.ewd-filtering-product-name').val();}
	var values = jQuery('#ewd-urp-review-score-filter').slider("option", "values");
	var min_score = values[0];
	var max_score = values[1];
	if (min_score == undefined) {min_score = 0;}
	if (max_score == undefined) {max_score = 1000000;}
	var orderby = jQuery('#urp-orderby').val();
	var order = jQuery('#urp-order').val();
	var post_count = jQuery('#urp-post-count').val();
	var current_page = jQuery('#urp-current-page').val();
	
	jQuery('.ewd-urp-reviews-container').html('<h3>Retrieving results...</h3>');
	var data = 'Q=' + search_string + '&product_name=' + product_name + '&min_score=' + min_score + '&max_score=' + max_score + '&orderby=' + orderby + '&order=' + order + '&post_count=' + post_count + '&current_page=' + current_page + '&only_reviews=Yes&action=urp_search';
	jQuery.post(ajaxurl, data, function(response) {
	    response = response.substring(0, response.length - 1);
	    jQuery('.ewd-urp-reviews-container').html(response);
	    URPSetClickHandlers();
	    URPSetPaginationHandlers();
	});
}

function URPSetPaginationHandlers() {
	jQuery('.ewd-urp-page-control').on('click', function() {
		var action = jQuery(this).data('controlvalue');
		if (action == 'first') {jQuery('#urp-current-page').val(1);}
		if (action == 'back') {jQuery('#urp-current-page').val(Math.max(1, jQuery('#urp-current-page').val()-1));}
		if (action == 'next') {jQuery('#urp-current-page').val(Math.min(jQuery('#urp-max-page').val(), parseInt(jQuery('#urp-current-page').val())+1));}
		if (action == 'last') {jQuery('#urp-current-page').val(jQuery('#urp-max-page').val());}
		URPFilterResults();
	});
}