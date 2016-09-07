<?php

/* 
Template Name: All_Blogs
 */
//Functions starts from here
function get_terms_by_post_type( $taxonomies, $post_types ) {

    global $wpdb;

    $query = $wpdb->prepare(
        "SELECT t.*, COUNT(*) from $wpdb->terms AS t
        INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
        INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id
        WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s')
        GROUP BY t.term_id",
        join( "', '", $post_types ),
        join( "', '", $taxonomies )
    );

    $results = $wpdb->get_results( $query );

    return $results;

} 
//Function ends here
//Code starts from here
get_header();
$blogCategories = get_terms_by_post_type(array('category'), array('blog_post'));
//Protect against arbitrary paged values
$paged = ( get_query_var('paged') ) ? absint(get_query_var('paged')) : 1;
//For blog search
if(isset($_GET['search_blog']))
{
    $s_var = $_GET['search_blog'];
    $args = array('s' => $s_var,'post_type'=>'blog_post','posts_per_page' => 6, 'paged' => $paged);
}
else if(isset($_GET['cat_id']))
{
    //For categories 
    $args = array(
    'posts_per_page' => 6,
    'post_type' => 'blog_post',
    'cat'=> $_GET['cat_id'],
    'paged' => $paged,
);
}
else
{
    //For all blogs
    $args = array(
    'posts_per_page' => 6,
    'post_type' => 'blog_post',
    'paged' => $paged,
); 
}
$the_query = new WP_Query($args);
//For recent blog posts
$recentArgs = $args = array(
    'numberposts' => 3,
    'post_type' => 'blog_post'
);
$recentPosts = wp_get_recent_posts($recentArgs, ARRAY_A);
//Code ends here
?>
<style>
    .list-unstyled li a {

        color:white;
    }
    .well7 {
        border: 2px solid #ff7444 !important;
    }
    .lead p{
        font-family: lato, Arial !important;
        font-size: 14px !important;
    }
    .page-numbers.current{
        background-color: #23527c;
        color: white !important;
        border: 1px solid #23527c;
    }
    .page-numbers.current:hover{
        background-color: #23527c;
        border: 1px solid #23527c;
    }
</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">
    <div class="blog_hd"> Owner's Blog
        <div class="clear10"></div>
        <img class="img-border-custom" src="<?php echo get_template_directory_uri() ?>/images/img_btm_hd.png" alt="">
    </div>
    <div class="container white_box_login" style="padding-right:30px; padding-left:30px;">
        <div class="row padding0">
            <div class="col-sm-8">

                <?php
                $num = $the_query->post_count;
                if ($the_query->have_posts()) {
                    $i = 0;
                    while ($the_query->have_posts()) {
                        $i++;
                        $the_query->the_post();
                        ?>
                        <div class="col-lg-6">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('homepage-thumb');
                            } else {
                                ?>
                                
                            <img class="img-responsive" style="height: 248px;" src="<?php echo get_template_directory_uri() ?>/images/pic1.png?>">
                                <?php }
                            ?>
                            
                            <div class="clear20"></div>
                            <h1 style="margin:0; font-weight:bold; font-size:24px;"><?php the_title(); ?></h1>
                            <p class="lead">
                                by <a href="#"><?php the_author(); ?></a>
                            </p>
                            <hr>
                            <p style="height: 67px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;  color: #535353;"><?php
                    $content = get_the_content();
                    echo substr($content, 0, 145);
                            ?></p>
                            <a href="<?php echo get_site_url() ?>/blogdetail?id=<?php echo the_ID() ?>" class="btn_blue col-sm-4 col-xs-12 col-lg-offset-4">Read More</a></p>
                        </div>
        <?php
        if ($i % 2 == 0 || $i == $num) {
            ?>
                            <div class="clear10"></div>
                            <hr>
                            <div class="clear10"></div>
            <?php
        }
        ?>

                        <?php
                        wp_reset_postdata();
                        ?>
                        <?php
                    }
                    $big = 999999999; // need an unlikely integer
                    $pages = paginate_links(array(
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => $the_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'type' => 'array'
                    ));

                    if (is_array($pages)) {
                        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
                        echo '<ul class="pagination">';
                        foreach ($pages as $page) {
                            echo "<li>$page</li>";
                        }
                        echo '</ul>';
                    }
                } else {
                    ?>
                    <p><?php _e('Sorry, no blog posts matched your criteria.'); ?></p>
                    <?php
                }
                ?>
            </div>
            <!-- Blog Sidebar Widgets Column -->
            <div class="col-sm-4">
                <!-- Blog Search Well -->
                <div class="well7">
                    <h4>Blog Search</h4>
                    <form method="get" action="<?php echo get_site_url() ?>/blogs/">
                        <div class="input-group">
                            <input name="search_blog" type="text" class="form-control" value="<?php echo isset($_GET['search_blog'])? $_GET['search_blog']: '';?>">
                            <span class="input-group-btn">
                                <button  class="btn btn-default btn_search" style="height:34px;" id="submit" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>

                        </div>
                    </form>
                    <!-- /.input-group -->
                </div>
                <!-- Blog Categories Well -->
                <div class="well7">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
<?php if(!empty($blogCategories)){  foreach ($blogCategories as $category) {    ?>
                                    <li><a href="<?php echo get_site_url() ?>/blogs?cat_id=<?php echo $category->term_id; ?>"><?php echo $category->name ?></a></li>
                                    <?php   }   
                                }
                                else{  echo "No Categories";    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Side Widget Well -->
                <div class="well7">
                    <h4>Latest Blog Posts</h4>
                    <?php
                    foreach ($recentPosts as $mn) 
                        {
                        $myid=$mn['ID'];
                          $myname = $mn['post_title'];
                          $mycontent = $mn['post_content'];
    ?><hr>
                        <h4><?php echo $myname; ?></h4>
                        <p><?php   echo substr($mycontent, 0, 145);//echo $mycontent; ?> <a href="<?php echo get_site_url()?>/blogdetail?id=<?php echo $myid; ?>" style="color:#ff7444 ">Read More</a></p>
    <?php
                          }
?>
                </div>
            </div>
        </div>
        <div class="clear40"></div>
    </div>
</div>
<?php  get_footer(); ?>
