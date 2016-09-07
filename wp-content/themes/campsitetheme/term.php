<?php

/* 
Template Name: Terms_Services
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

<div class="blog_hd"> Terms of Service
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
get_footer(); ?>
