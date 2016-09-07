<?php get_header(); 
if(is_user_logged_in()):


        if(have_posts()) :
            while(have_posts()): 
                the_post();?>
                <p><?php the_content();?> </p>
                <h3><?php the_title();?></h3>
            <?php    
            endwhile;
        endif;
else:
    
    //wp_safe_redirect( wp_login_url(get_permalink()));
auth_redirect(); 
endif;
    
get_footer(); ?>
