<?php
/* 
Template Name: Edit_Camp_Reviews
 */
//Functions starts from here
function get_count_of_reviews_on_camp_by_user($campId, $userId)
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT $reviews_table.ID, $reviews_table.camp_id, date_stay, review_by, review_score, $camps_table.camp_name FROM $reviews_table JOIN $camps_table ON $reviews_table.camp_id = $camps_table.ID WHERE $reviews_table.camp_id = $campId AND $reviews_table.review_by = $userId");
    $total = sizeof( $results );
    return $total;
}
function get_paginated_reviews_on_camp_by_user($campId, $userId, $offset, $items_per_page)
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT $reviews_table.ID, $reviews_table.camp_id, date_stay, review_by, review_score, $camps_table.camp_name, $camps_table.states_code FROM $reviews_table JOIN $camps_table ON $reviews_table.camp_id = $camps_table.ID WHERE $reviews_table.camp_id = $campId AND $reviews_table.review_by = $userId" . " ORDER BY ID LIMIT ${offset}, ${items_per_page}");
//    $wpdb->show_errors();
//    $wpdb->print_error();
//    print_r($results);    @die;
    $array = json_decode(json_encode($results), True);
    return $array;
}
function get_camp_state($campID)
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $results = $wpdb->get_row("SELECT $camps_table.states_code FROM $camps_table WHERE $camps_table.ID = $campID");
    $array = json_decode(json_encode($results), True);
    return $array;
}
//Functions ends here
//Header code starts from here
get_header();
if(!is_user_logged_in())
{
    $location = get_site_url()."/login";
    wp_redirect($location);
}
else
{
   if(isset($_GET['id']))
   {
       $camp_state = get_camp_state($_GET['id']);
       if(!empty($camp_state))
       {
           $user = wp_get_current_user();
            $total = get_count_of_reviews_on_camp_by_user($_GET['id'], $user->ID);
             $items_per_page = 10;
             $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
             $offset = ( $page * $items_per_page ) - $items_per_page;
             $reviews = get_paginated_reviews_on_camp_by_user($_GET['id'], $user->ID, $offset, $items_per_page);
       }
       else
       {
           $location = get_site_url();
            wp_redirect($location);
       }
   }
   else
    {
        $location = get_site_url();
        wp_redirect($location); 
    }
     
}
//Header code ends here

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">
   
<div class="container white_box_login" style="padding-right:30px; padding-left:30px;">

        <div class="row padding0">
                            <div class="col-sm-12 col-xs-12 blog_hd" style="
                                 font-size: 26px;
                                 "> Campsite Reviews
                                <div class="clear10"></div>
                                <img src="<?php echo get_template_directory_uri() ?>/images/img_btm_hd.png" alt="" style="
                                     margin-bottom: 12px;
                                     ">
                            </div>
                            <div class="col-sm-12 font_recent">
                                <br>
                                <?php
                                $count = 1;
                                if (!empty($reviews)) {
                                    foreach ($reviews as $value):
                                        $reviewRating = $value['review_score'];
                                    $overallReviewRating = number_format((float) $reviewRating, 1, '.', '');
                                        ?>
                                        <div class="<?php echo ($count % 2 == 0) ? "recent_bg" : "recent_bg2"; ?>">
                                            <div class="col-sm-6" style="padding-left:5px;"><a href="<?php echo get_site_url() ?>/camp?id=<?php echo $value['camp_id'] ?>"><?php echo $value['camp_name']; ?></a></div>
                                            <div class="col-sm-4"> <?php echo $value['date_stay']; ?>&nbsp;<span class="campground-badge-stat pull-right star-dec" itemprop="ratingValue">
                                                    <?php echo $overallReviewRating; ?>/10</span></div>
                                            <div class="col-sm-2 pull-right">
                                                <?php if($user->ID == $value['review_by']){ ?>
                                                <a href="<?php echo get_site_url() ?>/editreview?id=<?php echo $value['ID'] ?>">Edit</a> 
<!--                                                <a style="padding-left:5px;" href="" onclick="getConfirmation(<?php echo $value['ID'] ?>)">Delete</a>-->
                                                    <?php }?>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <?php
                                        $count++;
                                    endforeach;
                                }
                                ?><br>
                                <p >To add a review follow the link <a title="Add Review" href="<?php echo get_site_url()?>/addreview?state=<?php echo $camp_state['states_code'] ?>&camp=<?php echo $_GET['id'] ?>"><span style="text-decoration: underline;">Add Review</span></a></p>
                                <?php
                                
                                $pages = paginate_links(array(
                                    'base' => add_query_arg('cpage', '%#%'),
                                    'format' => '',
                                    'prev_text' => __('&laquo;'),
                                    'next_text' => __('&raquo;'),
                                    'total' => ceil($total / $items_per_page),
                                    'current' => $page,
                                    'type' => 'array'
                                ));

                                if (is_array($pages)) {
                                    $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
                                    echo '<ul class="pagination">';
                                    foreach ($pages as $page) {
                                        //print_r($page);
                                        echo "<li>$page</li>";
                                    }
                                    echo '</ul>';
                                }
                                ?>  
                            </div>
                        </div>
        <!-- /.row -->

        

        <div class="clear40"></div>

    </div>

</div>

<?php
get_footer();
?>