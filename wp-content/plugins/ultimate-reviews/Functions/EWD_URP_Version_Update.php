<?php
function EWD_URP_Version_Update() {
	global $EWD_URP_Version;

	$Posts = get_posts('post_type=urp_review');

	foreach ($Posts as $Post) {
		if (get_post_meta($Post->ID, 'EWD_URP_Item_Reviewed', true) == "") {
			update_post_meta($Post->ID, 'EWD_URP_Item_Reviewed', 'urp_product');
			update_post_meta($Post->ID, 'EWD_URP_Item_ID', 0);
		}
	}

	update_option('EWD_URP_Version', $EWD_URP_Version);
}

add_filter('upgrader_pre_install', 'EWD_URP_SetUpdateOption');
function EWD_URP_SetUpdateOption() {
	update_option('EWD_URP_Update_Flag', "Yes");
}

if (isset($_GET['post_type']) and $_GET['post_type'] == 'urp_review' and get_option('EWD_URP_Update_Flag') == "Yes") {add_action("admin_notices", "EWD_URP_Version_Update_Box");}

function EWD_URP_Version_Update_Box() {
?>
	<div id="side-sortables" class="metabox-holder ">
		<div id="EWD_URP_pro" class="postbox " >
			<div class="handlediv" title="Click to toggle"></div>
			<h3 class='hndle'><span><?php _e("Thank You!", 'EWD_URP') ?></span></h3>
		 	<div class="inside">
				<?php  if (get_option("EWD_URP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Reviews plugin.", "EWD_URP"); ?><br> <a href='https://www.youtube.com/channel/UCZPuaoetCJB1vZOmpnMxJNw'><?php _e("Subscribe to our YouTube channel ", "EWD_URP"); ?></a> <?php _e("for tutorial videos on this and our other plugins!", "EWD_URP");?> </li></ul>
				<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 1.2.2!", "EWD_URP"); ?><br> <a href='https://wordpress.org/support/view/plugin-reviews/ultimate-reviews?filter=5'><?php _e("Please rate our plugin", "EWD_URP"); ?></a> <?php _e("if you find Ultimate Reviews useful!", "EWD_URP");?> </li></ul><?php } ?>
											
				<?php /* if (get_option("EWD_URP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_URP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_URP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_URP");?> </li></ul>
				<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 2.2.9!", "EWD_URP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_URP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_URP");?> </li></ul><?php } */ ?>
											
				<?php /* if (get_option("EWD_URP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_URP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_URP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_URP");?>  </li></ul>
				<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 3.0.16!", "EWD_URP"); ?><br> <a href='http://wordpress.org/support/view/plugin-reviews/ultimate-product-catalogue'><?php _e("Please rate our plugin", "EWD_URP"); ?></a> <?php _e("if you find the Ultimate Product Catalogue Plugin useful!", "EWD_URP");?> </li></ul><?php } */ ?>
											
				<?php /* if (get_option("EWD_URP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_URP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_URP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_URP");?>  </li></ul>
				<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 3.4.8!", "EWD_URP"); ?><br> <a href='http://wordpress.org/plugins/order-tracking/'><?php _e("Try out order tracking plugin ", "EWD_URP"); ?></a> <?php _e("if you ship orders and find the Ultimate Product Catalogue Plugin useful!", "EWD_URP");?> </li></ul><?php } */ ?>

				<?php /* if (get_option("EWD_URP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_URP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_URP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_URP");?>  </li></ul>
				<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 2.3.9!", "EWD_URP"); ?><br> <a href='http://wordpress.org/support/topic/error-hunt'><?php _e("Please let us know about any small display/functionality errors. ", "EWD_URP"); ?></a> <?php _e("We've noticed a couple, and would like to eliminate as many as possible.", "EWD_URP");?> </li></ul><?php } */ ?>
											
				<?php /* if (get_option("EWD_URP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_URP"); ?><br> <a href='https://www.youtube.com/channel/UCZPuaoetCJB1vZOmpnMxJNw'><?php _e("Check out our YouTube channel ", "EWD_URP"); ?></a> <?php _e("for tutorial videos on this and our other plugins!", "EWD_URP");?> </li></ul>
				<?php } elseif ($Full_Version == "Yes") { ?><ul><li><?php _e("Thanks for upgrading to version 3.5.0!", "EWD_URP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_URP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_URP");?> </li></ul>
				<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 3.4!", "EWD_URP"); ?><br> <?php _e("Love the plugin but don't need the premium version? Help us speed up product support and development by donating. Thanks for using the plugin!", "EWD_URP");?>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="AQLMJFJ62GEFJ">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
						</li></ul>
				<?php } */ ?>

			</div>
		</div>
	</div>

<?php 
update_option("EWD_URP_Update_Flag", "No");
update_option("EWD_URP_Install_Flag", "No");
}

?>