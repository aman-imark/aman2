<?php
/* 
Template Name: Redeem_Rewards
 */
//fucntions starts here
function get_campsites_reviewed_by_user($userId)
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_row("SELECT count(review_by) as Reviewed FROM $reviews_table WHERE $reviews_table.review_by = $userId");
    //print_r($results);
    //@die;
    return $results->Reviewed;
}

function get_camp_images_count_by_user($userId)
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $results = $wpdb->get_row("SELECT SUM(camp_images_count) as Images, COUNT(camp_created_by) as Campsites FROM $camps_table WHERE $camps_table.camp_created_by = $userId AND $camps_table.camp_status = 1");
    //print_r($results);
    //@die;
    return $results;
    
}

function get_review_images_count_by_user($userId)
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $results = $wpdb->get_row("SELECT SUM(review_images_count) as Images FROM $reviews_table JOIN $camps_table ON $reviews_table.camp_id = $camps_table.ID WHERE $reviews_table.review_by = $userId AND $camps_table.camp_status = 1");
//    print_r($results);
//    @die;
    return $results->Images;
}

function get_review_tips_count_by_user($userId)
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $results = $wpdb->get_row("SELECT COUNT(review_by) as Tips FROM $reviews_table JOIN $camps_table ON $reviews_table.camp_id = $camps_table.ID WHERE $reviews_table.review_by = $userId AND $camps_table.camp_status = 1 AND $reviews_table.camp_tip != ''");
//    print_r($results);
//    @die;
    return $results->Tips;
}

//functions ends here

//code starts here
get_header();
if(!is_user_logged_in()):
        $location = get_site_url()."/login";
        wp_redirect($location);
endif;
$memberPoints = 0;
if (!current_user_can('administrator') && !is_admin()) {
  $memberPoints = 25;
}
$user = wp_get_current_user();
$totalCampsitesReviewedByUser =  get_campsites_reviewed_by_user($user->ID);
$totalCampsImagesByUser = get_camp_images_count_by_user($user->ID);
$totalReviewsImagesByUser = get_review_images_count_by_user($user->ID);
$totalImagesByUser = $totalCampsImagesByUser->Images + $totalReviewsImagesByUser;
$totalReviewsTipsByUser = get_review_tips_count_by_user($user->ID);
$totalMemberShipPoints = ($totalCampsitesReviewedByUser * 10) + $totalImagesByUser + ($totalCampsImagesByUser->Campsites * 5) + $totalReviewsTipsByUser + $memberPoints;

//code ends here

?>
<!--
Total Member Ship Points: <?php //echo $totalMemberShipPoints;?>
<br>
Total Reviews Added: <?php //echo $totalCampsitesReviewedByUser;?>
<br>
Total Photos Added: <?php //echo $totalImagesByUser;?>
<br>
Total Campsites Added: <?php //echo $totalCampsImagesByUser->Campsites;?>
<br>
Total Tips Added: <?php //echo $totalReviewsTipsByUser;?>
-->
<style>
	.margin30{
		    margin-top: 30px;
	}
	.headtag{
		text-align: center;
		font-size: 16px;
		font-weight: bold;
		font-family: arial;
	}
	
	.counttag{
	
		font-size: 34px;
		text-align: center;
	}
	
	.label-default {
    background-color: #ff7444 !important;
}

</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

 <div class="blog_hd"> Membership Point System
					<div class="clear10"></div>
					<img class="img-border-custom" src="<?php echo get_template_directory_uri()?>/images/img_btm_hd.png" alt="" />
				</div>
				
<div class="container white_box_login" style="padding-right:30px; padding-left:30px;">

        <div class="row padding0">


            <!-- Blog Post Content Column -->
            <div class="col-lg-12">

                <div class="col-sm-12 margin30">
					
				<div class="blog_hd" style="font-size: 26px;"> Membership Points<br> <span style="color: #ff7444;"><?php echo $totalMemberShipPoints;?></span>

					<div class="clear10"></div>
					
				</div>
					
				</div>
				
				<div class="col-sm-12">
				<hr>
				</div>
			
				
				<div class="col-sm-12 margin30">
					
					<div class="col-sm-3 headtag">Review Count</div>
					<div class="col-sm-3 headtag">Photo Count</div>
					<div class="col-sm-3 headtag">Campsite Count</div>
					<div class="col-sm-3 headtag">Tip Count</div>
				
				</div>
				
				<div class="col-sm-12">
					
					<div class="col-sm-3 counttag"><?php echo $totalCampsitesReviewedByUser;?></div>
					<div class="col-sm-3 counttag"><?php echo $totalImagesByUser;?></div>
					<div class="col-sm-3 counttag"><?php echo $totalCampsImagesByUser->Campsites;?></div>
					<div class="col-sm-3 counttag"><?php echo $totalReviewsTipsByUser;?></div>
				
				</div>
				
<!--				<div class="col-sm-12 margin30">
					
					<div class="col-sm-3 headtag">Review Count</div>
					<div class="col-sm-3 headtag">Add a Campsite</div>
					<div class="col-sm-3 headtag">Add a Tip</div>
					<div class="col-sm-3 headtag">Refer other members</div>
				
				</div>
				
				<div class="col-sm-12">
					
					<div class="col-sm-3 counttag">0</div>
					<div class="col-sm-3 counttag">0</div>
					<div class="col-sm-3 counttag">0</div>
					<div class="col-sm-3 counttag">0</div>
				
				</div>-->
				
				
				<div class="col-sm-12">
				<hr>
				</div>

				<div class="col-sm-12">
					<div class="blog_hd" style="font-size: 26px;"> Reward Possibilities
					<div class="clear10"></div>
					
				</div>
				
				
				</div>
				<div class="col-sm-4 col-sm-offset-2">
					
					<ul class="list-group">
					
					  <li class="list-group-item">
						
						Keychain Bottle Opener
						<span class="label label-default label-pill pull-xs-right pull-right">50 Points</span>
					  </li>
					  <li class="list-group-item">
						
						Water Bottle
						<span class="label label-default label-pill pull-xs-right pull-right">100 Points</span>
					  </li>
					  <li class="list-group-item">
						
						REI $10 Gift Card 
						<span class="label label-default label-pill pull-xs-right pull-right">150 Points</span>
					  </li>
					  <li class="list-group-item">
						
						Free night camping
						<span class="label label-default label-pill pull-xs-right pull-right">150 Points</span>
					  </li>
					</ul>
				
				
				</div>
				<div class="col-sm-4">
					
					<ul class="list-group">
						
					  <li class="list-group-item">
						
						Camp Chair 
						<span class="label label-default label-pill pull-xs-right pull-right">150 Points</span>
					  </li>
					  <li class="list-group-item">
						
						Coleman 2-Person Tent
						<span class="label label-default label-pill pull-xs-right pull-right">500 Points</span>
					  </li>
					  <li class="list-group-item">
						
						Three nights camping
						<span class="label label-default label-pill pull-xs-right pull-right">1000 Points</span>
					  </li>
					  <li class="list-group-item">
						
						Coleman 8-Person Tent
						<span class="label label-default label-pill pull-xs-right pull-right">2000 Points</span>
					  </li>
					</ul>
				
				
				</div>				
				

                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            

        </div>
        <!-- /.row -->

        

        <div class="clear40"></div>

    </div>

</div>


<?php 
get_footer();
?>