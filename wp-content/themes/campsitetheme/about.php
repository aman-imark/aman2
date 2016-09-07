<?php
/*
    Template Name: About_Me
*/
get_header(); 
?>
<style>
    
    .list-unstyled li a {
        
        color:white;
    }
    .well7 {
     border: 2px solid #ff7444 !important;
    }
</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

<div class="blog_hd"> About Find Your Campsite
    <div class="clear10"></div>
    <img class="img-border-custom" src="<?php echo get_template_directory_uri()?>/images/img_btm_hd.png" alt="">
</div>
   
<div class="container white_box_login" style="padding-right:30px; padding-left:30px;">

        <div class="row padding0">


            <!-- Blog Post Content Column -->
            <div class="col-lg-12">

                <!-- Blog Post -->
<?php
if(have_posts()) :

            while(have_posts()): 

            echo    the_post();
      // echo do_shortcode('[wp-review]');

        ?>

                <p><?php echo the_content();?> </p>

                <h3><?php// echo the_title();?></h3>

            <?php    

            endwhile;

        endif;

?>

                <!-- Date/Time -->
<!--              <p><?php //echo the_content();?> </p>

                <h3><?php //echo the_title();?></h3>

                 Preview Image 
               
                 Post Content 
                <p><b>Mission:</b> To make the search for a campsite an easier and more enjoyable process, and to give back (url link here) to environmental organizations that help protect the same outdoor areas we love.</p>

				<p>Find Your Campsite was founded with one simple idea in mind: to make the search for a campsite an easier and more informative process. We longed for one website where you could browse multiple reviews and high quality photos in the same place. Thus, we decided to create it, and with an environmental focus in mind. We give 5% of our proceeds back to non-profit environmental groups (url link here) that help protect the outdoor areas we love and enjoy.</p>

				<p>I hope you find this site helpful in researching your next camping trip. And please leave a review or post some photos from your last trip if you have the time! At Find Your Campsite, we love tips and suggestions (considering the whole website is founded on the user experience!). Please email us at <a href="mailto:info@findyourcampsite.com" title="mailto:info@findyourcampsite.com">info@findyourcampsite.com</a> and expect a quick response.</p>

				<p>Many thanks for using our site and happy camping!</p>-->

              

                

            </div>

            <!-- Blog Sidebar Widgets Column -->
<!--            <div class="col-md-3">

                

                 Blog Categories Well 
                <div class="well7">
                    <h4>About</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-unstyled">
                                <li><a href="#">Help | FAQ's</a>
                                </li>
                                <li><a href="#">Community Rules</a>
                                </li>
                                <li><a href="#">Review Guidelines</a>
                                </li>
                                <li><a href="#">Park Owner Guidelines</a>
                                </li>
								<li><a href="#">Contact</a>
                                </li>
                                <li><a href="#">Term of Service</a>
                                </li>
                                <li><a href="#">Privacy Policy</a>
                                </li>
                               
                            </ul>
                        </div>
                        
                    </div>
                     /.row 
                </div>

                

            </div>-->

        </div>
        <!-- /.row -->

        

        <div class="clear40"></div>

    </div>

</div>




<?php
//if(have_posts()) :
//            while(have_posts()): 
//            echo    the_post();
//        echo do_shortcode('[submit-review]');
//        echo do_shortcode('[ultimate-reviews]');
//        
//               // echo do_shortcode('[wp-review]');
//        ?>
<!--                <p><?php //echo the_content();?> </p>
                <h3><?php //echo the_title();?></h3>-->
            <?php    
//            endwhile;
//        endif;
//
//if(is_user_logged_in()):
//    //wp_nav_menu(array('theme_location' => 'primary'));
//
//        //echo do_shortcode('[display-posts posts_per_page="1000" order="DESC"]');
//        
//        
//else:
//    
//    wp_safe_redirect( wp_login_url(get_permalink()));
////auth_redirect(); 
//endif;
    
get_footer(); ?>
