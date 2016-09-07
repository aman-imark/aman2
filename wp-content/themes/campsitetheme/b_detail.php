<?php
/*
  Template Name: Blog_Detail
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
//For recent blog posts
$recentArgs  = array(
    'numberposts' => 3,
    'post_type' => 'blog_post'
);
$recentPosts = wp_get_recent_posts($recentArgs, ARRAY_A);
//For blog detail
if ($_GET) {
    $selectedBlog = $_GET['id'];
} else {
    $location = get_site_url();
    wp_redirect($location);
}
//$args = array('post_type' => 'blog_post', 'ID' => $selectedBlog);
//$the_query = new WP_Query($args);
$post = get_post($selectedBlog);
$blogCategories = get_terms_by_post_type(array('category'), array('blog_post'));
get_header();
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

    .col-sm-12.img_auto img{

        height:80%;
        width:80%;
    }
</style>
<div class="clearfix"></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">
    <div class="container white_box_login" style="padding-right:30px; padding-left:30px;">
        <div class="row padding0">
            <div class="col-sm-8">
<?php
if ($post->post_type == 'blog_post') {
    ?>
                    <!-- Blog Post Content Column -->
                    <div class="col-sm-12 img_auto">
                        <!-- Blog Post -->
                        <!-- Title -->
                        <h1 style="margin:0; font-weight:bold;"><?php echo $post->post_title; ?></h1>
                        <!-- Author -->
                        <p class="lead img_auto">
                            by <a href="#"><?php
    $userid = $post->post_author;
    $userdata = get_userdata($userid);
    $user = $userdata->user_nicename;
    echo $user;
    ?></a>
                        </p>
                        <hr>
                        <!-- Date/Time -->
                        <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo get_the_time('M-d-Y', $post->ID); ?> at <?php echo get_the_time(); ?></p>
                        <hr>
                        <!-- Preview Image -->
    <?php
    if (has_post_thumbnail()) {
        the_post_thumbnail('singlepost-thumb');
    } else {
        ?>
                            <img class="img-responsive" src="<?php echo get_template_directory_uri() ?>/images/pic1.png?>">
                        <?php } ?>
                        <hr>
                        <!-- Post Content -->
                        <p><?php echo $post->post_content; ?></p>
                        <?php wp_reset_postdata();
                        ?>
                    </div>
                        <?php
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
                    <!-- /.row -->
                </div>
                <!-- Side Widget Well -->
                <div class="well7">
                    <h4>Latest Blog Posts</h4>
<?php
foreach ($recentPosts as $mn) {
    $myid = $mn['ID'];
    $myname = $mn['post_title'];
    $mycontent = $mn['post_content'];
    ?><hr>
                        <h4><?php echo $myname; ?></h4>
                        <p><?php echo substr($mycontent, 0, 145); //echo $mycontent; ?> <a href="<?php echo get_site_url() ?>/blogdetail?id=<?php echo $myid; ?>" style="color:#ff7444 ">Read More</a></p>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="clear40"></div>
    </div>
</div>
<div class="clear"></div>
</body>
<?php get_footer(); ?>
</html>


