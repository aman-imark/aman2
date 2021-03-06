<?php

function EWD_URP_Upgrade_Box() {
?>
	<div id="side-sortables" class="metabox-holder ">
	<div id="upcp_pro" class="postbox " >
		<div class="handlediv" title="Click to toggle"></div><h3 class='hndle'><span><?php _e("Full Version", 'EWD_URP') ?></span></h3>
		<div class="inside">
			<ul><li><a href="http://www.etoilewebdesign.com/plugins/ultimate-reviews/"><?php _e("Upgrade to the full version ", "EWD_URP"); ?></a><?php _e("to take advantage of all the available features of Ultimate Reviews for Wordpress!", 'EWD_URP'); ?></li></ul>
			<h3 class='hndle'><span><?php _e("What you get by upgrading:", 'EWD_URP') ?></span></h3>
				<ul>
					<li>Control who reviews by requiring email confirmation or login.</li>
					<li>WooCommerce integration, to let you create more detailed reviews for your products.</li>
					<li>Two extra review formats, admin notifications when a review is received, and dozens of styling and labeling options!</li>
					<li>Access to e-mail support.</li>
				</ul>
			<div class="full-version-form-div">
				<form action="edit.php?post_type=urp_review" method="post">
					<div class="form-field form-required">
						<label for="Key"><?php _e("Product Key", 'EWD_URP') ?></label>
						<input name="Key" type="text" value="" size="40" />
					</div>							
					<input type="submit" name="Upgrade_To_Full" value="<?php _e('Upgrade', 'EWD_URP') ?>">
				</form>
			</div> 
		</div>
	</div>
	</div>

<?php
}

?>