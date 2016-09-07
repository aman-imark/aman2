<html>
<!--<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/jvectormap/css/style-map.css" type="text/css">-->
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/jvectormap/css/jquery-jvectormap.css" type="text/css">
<style>
#back_search{
  top: 95px !important;
  right: 43.2% !important;
}
.modal-backdrop{
    z-index: 0 !important;
}
</style>
</html>

<?php 
//functions starts from here
function array_orderby()
{
  $args = func_get_args();
  $data = array_shift($args);
  foreach ($args as $n => $field) {
    if (is_string($field)) {
      $tmp = array();
      foreach ($data as $key => $row)
        $tmp[$key] = $row[$field];
      $args[$n] = $tmp;
    }
  }
  $args[] = &$data;
  call_user_func_array('array_multisort', $args);
  return array_pop($args);
}
function get_overall_rating($sizeOfReviews, $campId)
{
  global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT SUM(review_score) as Rating FROM $reviews_table WHERE $reviews_table.camp_id = $campId");
    
    //$wpdb->show_errors();
    //$wpdb->print_error();
    
    $Rating = round($results[0]->Rating, 2);
    if($sizeOfReviews > 0)
    {
      $number = $Rating/$sizeOfReviews;    
    }
    else
    {
      $number = 0;
    }
    $overallRating = number_format((float)$number, 1, '.', '');
    
   //print_r($overallRating);
//    
   //@die;
    return $overallRating;
  }
  function get_reviews_by_id($campId)
  {
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $results = $wpdb->get_results("SELECT * FROM $reviews_table WHERE $reviews_table.camp_id = $campId");

    $array = json_decode(json_encode($results), True);
    //$array2 = $array[0];
    
    //print_r($array);
//    
   //@die;
    return $array;
  }

  function get_top_rated_camps_by_state($stateID)
  {
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $state_code = '%'.$stateID.'%';
    $results = array();
    $results = $wpdb->get_results("SELECT $camps_table.ID, camp_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code WHERE states_code LIKE '$state_code' AND camp_status = 1");
    $array = json_decode(json_encode($results), True);
     //print_r($array); @die;
    foreach ( $array as $key => $value) 
    { 
     $reviewsOfSearchedCamp = get_reviews_by_id($value['ID']);
     $totalReviewsOfSearchedCamp = count($reviewsOfSearchedCamp);
     $searchedCampOverAllRating = get_overall_rating($totalReviewsOfSearchedCamp, $value['ID']);
     $array[$key]['Rating'] = $searchedCampOverAllRating;
   }

   $search = array_orderby($array, 'Rating', SORT_DESC);
   $sliced_array = array_slice($search, 0, 3);
   return $sliced_array;
    //print_r($sliced_array); @die;
 }

 function get_states()
 {
  global $wpdb;
$states_table = $wpdb->prefix . 'states'; //Good practice
$results = $wpdb->get_results( "SELECT * FROM $states_table");
$array = json_decode(json_encode($results), True);
$data = array();
foreach ($array as $value)
{
  $data["US-".$value['state_code']] = get_top_rated_camps_by_state($value[state_code]); 
    //print_r($data); @die;
}

//print_r($randomFact); @die;
return $data;


//$NumRows = count((array) $randomFact);

//print $randomFact[$RandNum]->philosopher;
//print $randomFact[$RandNum]->about;
//or return $randomFact[$RandNum]; ?

}
function populate_array()
{
  $statesWithTopRatedCamps = get_states();
    //print_r($statesWithTopRatedCamps); @die;
  $data_array = array();
  foreach($statesWithTopRatedCamps as $key => $value)
  {
    $i = 0;
    $data = array();
    if(!empty($value))
    {
      while($i < 3)
      {
                //$data[$i] = $value[$i]['camp_name'];
        if(!empty($value[$i]))
        {
          $data_array[$key.'-'.$i] = $value[$i]['ID'].",".$value[$i]['camp_name'].",".$value[$i]['Rating'];
        }
        $i++;
      }
    }
    else
    {
     $data_array[$key] = '';
   }

 }
 return $data_array;
}


function get_users_by_photo_latest_reviews()
{

  global $wpdb;
  $users = $wpdb->get_results(
   "SELECT `wp_users`.`ID`,`wp_reviews`.`camp_id` FROM `wp_users` JOIN `wp_usermeta` ON `wp_users`.`ID` = `wp_usermeta`.`user_id` JOIN `wp_reviews` ON `wp_users`.`ID` = `wp_reviews`.`review_by` WHERE `wp_usermeta`.`meta_key` LIKE 'photo' AND `wp_usermeta`.`meta_value` = 1 GROUP BY `wp_users`.`ID` LIMIT 6" );
         //$wpdb->show_errors();
	       //$wpdb->print_error();
         //print_r($users); @die;

  $results = json_decode(json_encode($users), True);
  return $results;                      
}
  //$recent_users_photos = get_users_by_photo_latest_reviews();

$statesWith = populate_array();

//print_r($statesWith); @die;
function get_recent_blog_posts()
{
  $args = array( 'post_type' => 'blog_post', 'posts_per_page' => 3);
//$args = array( 'post_type' => 'blog_post', 'posts_per_page' => 10 );
  $result = new WP_Query( $args );
  return $result;
}
function get_page_by_name($pagename)
{
  $pages = get_pages();
  foreach ($pages as $page)
  {
    if ($page->post_name == $pagename)
    {
      return $page;   
    }
  }
  return false;
}
$actual_link = $_SERVER['REQUEST_URI'];
$temporary = explode("/", $actual_link);
$pageSearched;
$pageKey;
foreach($temporary as $key => $value)
{
  if($value == 'campsite')
  {
    $pageKey = $key;
    break;
  }
}
if(!empty($temporary[$pageKey+1]))
{
  $pageSearched = $temporary[$pageKey+1];
  if($pageSearched != 'wp-content')
  {
    $page = get_page_by_name($pageSearched);
    if (empty($page)) 
    {
      $location = get_site_url();
      wp_redirect($location);
    }
  }
}

get_header();
//wp_nav_menu(array('theme_location' => 'primary'));
//print_r(get_template_directory_uri()); @die;
//print_r(get_site_url()); @die;

$user = wp_get_current_user();
//
$the_query = get_recent_blog_posts();
//function get_camps_by_name($data)
//{
//    global $wpdb;
//    $camps_table = $wpdb->prefix . 'camps'; //Good practice
//    $keyword = '%'.$data.'%';
//    $results = $wpdb->get_results("SELECT camp_name FROM $camps_table WHERE camp_name LIKE '$keyword'");
//    return $results;
//}
//function get_cities_id_by_name($data)
//{
//    global $wpdb;
//    $cities_table = $wpdb->prefix . 'cities'; //Good practice
//    $keyword = '%'.$data.'%';
//    $results = $wpdb->get_results("SELECT ID FROM $cities_table WHERE city_name LIKE '$keyword'");
//    return $results;
//} 
//function get_camp_name_by_city_id($city_id)
//{
//    global $wpdb;
//    $camps_table = $wpdb->prefix . 'camps'; //Good practice
//    $result = $wpdb->get_results("SELECT camp_name FROM $camps_table WHERE cities_id = $city_id");
//    return $result;
//}
//function get_camps_by_cites_id($data)
//{
//    $reslut1 = get_cities_id_by_name($data);
//    $camps = array();
//    $i = 0;
//    foreach ($reslut1 as $key => $row):
//        $reslut2 = get_camp_name_by_city_id($row->ID);
//        if(!empty($reslut2)):
//            $camps[$i] = $reslut2;
//            $i++;
//        endif;
//    endforeach;
//    return $camps;
//}
//
//function get_camps($data)
//{
//    $result1 = get_camps_by_name($data);
//    $result2 = get_camps_by_cites_id($data);
//    
//    $camps = array();
//    if(!empty($result2))
//    {
//        $i = 0;
//        foreach($result2 as $key => $row):
//            foreach($row as $key1 => $row1):
//                $camps[$i] = $row1->camp_name;
//                $i++;
//            endforeach;
//        endforeach;
//        
//        //echo json_encode($result2);
//        print_r($camps);
//    }
//    $camps2 = array();
//    if(!empty($result1))
//    {
//        $j = 0;
//        foreach($result1 as $key => $row):
//            $i = 0;
//            while($i < sizeof($camps)):
//                if($row->camp_name != $camps[$i]):
//                    $camps2[$j] = $row->camp_name;
//                    $j++;
//                    endif;
//                $i++;
//            endwhile;
//        endforeach;
//        //print_r($camps);
//        
//        foreach($camps2 as $key1 => $row1):
//            $k = 0; 
//            while($k < sizeof($camps)):
//                if($camps[$k] != $row1)
//                {
//                    $camps2[$j] = $camps[$k];
//                    $j++;
//                }
//                $k++;
//            endwhile;
//        endforeach;  
//        //echo json_encode($result1);
//       // print_r($camps2); @die;
//        $serach_camps = array_unique($camps2);
//        //echo json_encode($serach_camps);
//        print_r($camps2);
//    }
//    else
//    {
//        echo json_encode($camps);
//    }
//    @die;
//}


//get_camps('');

      //  print_r($user);@die;


?>


<!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header padding0">
	<div class="container padding0">
    	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-9 padding0"><a href="<?php echo get_site_url()?>"><img src="<?php echo get_template_directory_uri()?>/images/logo.png" class="img-responsive" style="margin-bottom:2px; margin-top:5px;" /></a></div>
         Menu bar main div <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding0 pull-right mrg_topnav">
        	<nav class="navbar navbar-default pull-right">
  <div class="container-fluid nav_cstm padding0">
     Brand and toggle get grouped for better mobile display 
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
     Collect the nav links, forms, and other content for toggling 
    <div class="collapse navbar-collapse padding0" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo get_site_url()?>/reviews">Add Campsite</a></li> 
        <li><a href="<?php echo get_site_url()?>/blog">Owner's Blog</a></li>   
        <li><a href="<?php echo get_site_url()?>/about">About Us </a></li>   
        <?php if(!$user->user_login) :?>
        <li><a href="<?php echo get_site_url()?>/login" style="color:#ff7444 !important;">Login </a></li>   
        <li><a href="<?php echo get_site_url()?>/signup" style="color:#ff7444 !important;">Signup</a></li>  
        <?php elseif(!current_user_can('administrator') && !is_admin()):
            ?>
        <div class="dropdown">
        <button class="dropbtn"><a href="myaccount.html"><?php echo $user->user_login;?></a></button>
            <div class="dropdown-content">
                <?php $redirect_1 = get_site_url()?>
            <a href="<?php echo wp_logout_url($redirect_1); ?>">Logout</a>
            <a href="#">Link 2</a>
            <a href="#">Link 3</a>
            </div>
        </div>
        <li><a href="#">Welcome <?php //echo $user->user_login;?></a></li>
        <li><a href="<?php //echo get_template_directory_uri()?>/change_password">Change Password </a></li>
        <li><a href="<?php// echo wp_logout_url(); ?>">Logout</a></li>
        <?php
        
        endif; 
        
        
        
        
        ?>
      </ul>
      
      
    </div> /.navbar-collapse 
  </div> /.container-fluid 
</nav>
        </div>
    </div>
  </div>-->

  <!-- Slider Start -->
  <div class="container-fluid padding0">
    <?php
//    if(have_posts()) :
//            while(have_posts()): 
//                the_post();
//        echo do_shortcode('[submit-review]');
//        echo do_shortcode('[ultimate-reviews]');
//        
//               // echo do_shortcode('[wp-review]');
//        ?>
<p><?php // the_content();?> </p>
<h3><?php //the_title();?></h3>
<?php    
//            endwhile;
//        endif;
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myslider">
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>

    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="<?php echo get_template_directory_uri()?>/images/slider_1.jpg" class="slider_img" align="right">
        <div class="carousel-caption slider_label">

          <div class="clear"></div>
        </div>
      </div>
      <div class="item">
        <img src="<?php echo get_template_directory_uri()?>/images/slider_3.jpg" class="slider_img" align="right">
        <div class="carousel-caption slider_label">
          <div class="clear"></div>
        </div>
      </div>
      <div class="item">
        <img src="<?php echo get_template_directory_uri()?>/images/slider_2.jpg" class="slider_img" align="right">
        <div class="carousel-caption slider_label">
          <div class="clear"></div>
        </div>
      </div>
    </div>


    <a data-slide="prev" href="#myCarousel" class="left carousel-control">
      <span class="icon-prev"><img src="<?php echo get_template_directory_uri()?>/images/left_arrow.png" alt="" /></span>
    </a>
    <a data-slide="next" href="#myCarousel" class="right carousel-control">
      <span class="icon-next"><img src="<?php echo get_template_directory_uri()?>/images/right_arrow.png" alt="" /></span>
    </a>
  </div>



  <div class="slider_img_shadow">

   <div class="index_slider_text">
      Your one stop for all campground reviews, photos, and information. Let's get outside!
    <div class="clear10"></div>
    <div class="search_bg col-lg-6 col-md-12 col-sm-12 col-xs-12 col-lg-offset-3">

      <form action="<?php echo get_site_url()?>/grandsearch" method="get" role="search" id="topsearch">

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-13 padding0">


          <input name="query" id="query" type="text" class="form-control ui-autocomplete-input"  value=""placeholder="Search City, Campground, etc..." />
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><button class="btn_blue2" type="submit" style="border: none; padding: 9px; margin-top: 0px; margin-left: -5px; width:100%;"><i class="fa fa-search"></i>Search</button></div>

        
      </form>



    </div>
   </div>
    <!-- start of ahmad's search    onkeyup="autocomplet()" -->
    <div id="back_search">

    </div>

    <script>
  //$(document).ready(function(){

    $('#query').keyup(function(){
      //alert("abcd");
      
      var query = $(this).val();
      
      if(query == '')
      {
          $('#back_search').hide();
          return false;
      }
    //  console.log(query);
    //alert(query);
    $.post('<?php echo get_site_url()?>/ajaxsearch' , {query:query} , function(data){
        
        if(data != '')
        {
            $('div#back_search').css({'display':'block'});
            $('div#back_search').html(data);
        }
        else
        {
            var result = "<?php echo 'No results found'?>";
           $('div#back_search').css({'display':'block', 'color': 'black'});
            $('div#back_search').html(result); 
        }
      
        // alert(data);
      });
  });
  //});
  
</script>

<!-- end of ahmad's search   -->

<div class="clear10"></div>
<!--<div><img src="<?php //echo get_template_directory_uri()?>/images/map.png" alt="" /></div>-->


</div>


</div>
</div>
<!-- Slider End -->
<div class="clear"></div>



<!--<div id="vmap" style="width: 600px; height: 400px; margin-top: -405px; margin-left: 423px; position: absolute;"></div>-->
<div class="slider_map col-sm-4" style="height: 0px;">
  <div id="map1" class="map_res" style="height: 350px;margin-top: 40px;">
    <div id="x"></div>
    <div id="y"></div>
    <div id="customTip" class="jvectormap-tip"></div>
  </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">
  <div class="container padding0">
    <div class="clear10"></div>
    <div class="col-sm-12 col-xs-12 blog_hd"> Recently Viewed Campgrounds
      <div class="clear10"></div>
      <img src="<?php echo get_template_directory_uri()?>/images/img_btm_hd.png" alt="" />
    </div>
    <div class="clear20"></div>
    <!--  ahmad 2nd code -->

    <?php 
	// $user = wp_get_current_user();
    function get_recent_viewed_camp()
    {
      global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice

    $results = $wpdb->get_results("SELECT ID,camp_name,camp_images FROM $camps_table WHERE $camps_table.camp_status=1 ORDER BY $camps_table.camp_recently_viewed DESC limit 3");
    
    $array = json_decode(json_encode($results), True);
  // return $results;
    //print_r($array); @die;
    return $array;
  }

  $getting_recent_view_data = get_recent_viewed_camp();
 //print_r($getting_recent_view_data); @die;
  foreach($getting_recent_view_data as $value)
  {
   ?><div class="col-sm-4 col-xs-12">
   <?php
   $c_id = $value['ID'];
   $c_name = $value['camp_name'];

   if(!empty($value['camp_images']))
   {
    $images =  json_decode($value['camp_images']);
    $final_path = $images[0];
    $path = get_template_directory_uri() . '/upload/' . $final_path;
  }
  else
  {
    $path = get_template_directory_uri().'/images/tent-camping-2.jpg';
  }
  ?>
  <img src="<?php echo $path?>" class="img-responsive img_recent" alt="" style="height: 270px !important; border-radius: 5px;"/> 
  <div class="recent_hd"><a href="<?php echo get_site_url()?>/camp?id=<?php echo $c_id ?>"><?php echo $c_name ?> </a></div>
</div>
<?php


}
?> 



<!-- end of 2nd code -->
</div>






<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg" style="padding-top:10px;">
  <div class="container padding0">

    <!-- START OF AHMAD BAJWA's CODE --> 

    <div class="col-sm-8 col-xs-12">
      <div class="white_box">
        <div class="green_bg" style="
        text-align: center;
        font-size: 28px;
        ">Recent User Photos
        <br>
        <img src="<?php echo get_template_directory_uri()?>/images/img_btm_hd.png" alt="">

      </div>
      <div class="clear40"></div>

      <?php 

      function get_users_id()
      {

       global $wpdb;
       $reviews_table = $wpdb->prefix."reviews";
       $users_table = $wpdb->prefix."users";
       $camp_table = $wpdb->prefix."camps";
       $usermeta_table = $wpdb->prefix."usermeta";
       $results = $wpdb->get_results( "SELECT $reviews_table.camp_id, $reviews_table.review_by FROM $reviews_table JOIN $camp_table ON $reviews_table.camp_id = $camp_table.ID WHERE $camp_table.camp_status = 1 ORDER BY $reviews_table.review_date DESC LIMIT 6");
       $array = json_decode(json_encode($results), True);

       return $array;
     }



     ?>



     <?php 

     $get_user_id_images = get_users_by_photo_latest_reviews();
     if(!empty($get_user_id_images))
     {
       foreach($get_user_id_images as $value)
       {

         ?>
         <div class="col-lg-4 col-sm-6" >
           <?php
           $image_mil_gai = $value['ID'];
           $camp_id = $value['camp_id'];
			//echo $image_mil_gai;
           $key = 'image_name';
           $single = true;
           $user_last = get_user_meta( $image_mil_gai, $key, $single ); 

           if (!empty($user_last)):

            $path = get_template_directory_uri() . '/upload/' . $user_last;
          ?>
          <div class="ih-item circle colored effect5"><a href="<?php get_site_url();?>camp?id=<?php echo $camp_id ?>">  
           <div class="img"><img src="<?php echo $path ;?>" alt="img"></div>
           <div class="info">
             <div class="info-back">
               <h3>Read Review</h3>
               <p>Click here</p>
             </div>
           </div></a>  
         </div> 
         <?php
         else:
          ?>

        <div class="ih-item circle colored effect5"><a href="<?php get_site_url();?>camp/<?php echo $camp_id ?>">  
         <div class="img"><img src="<?php echo get_template_directory_uri()  ;?>/images/default.jpg" alt="img"></div>
         <div class="info">
           <div class="info-back">
             <h3>Read Review</h3>
             <p>Click here</p>
           </div>
         </div></a>  
       </div> 
       <?php
       endif;


       ?>
       <!-- colored -->

       <!-- end colored -->

     </div>
     <?php
   }
 }
 else
 {
  ?>
  <p style="    font-family: lato, Arial;font-size: 20px; margin-left: 40px;">No recent reviews found</p>
  <?php

}

?>  

<div class="clear40"></div>
</div>
</div>

<!-- END OF AHMAD BAJWA's CODE -->

<div class="col-sm-4 col-xs-12">
  <div class="white_box">
  <!-- <div class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc"> <a href="#" target="_blank" class="social_icon"><img class="img-responsive" src="<?php echo get_template_directory_uri()?>/images/youtube_icon.png" alt="" /></a></div>
     <div  class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc"> <a href="#" target="_blank"><img class="img-responsive" src="<?php echo get_template_directory_uri()?>/images/social_icon2.png" alt="" /></a></div>
     <div class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc"> <a href="#" target="_blank"><img class="img-responsive" src="<?php echo get_template_directory_uri()?>/images/social_icon4.png" alt="" /></a></div>
     <div class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc"> <a href="#" target="_blank"><img class="img-responsive" src="<?php echo get_template_directory_uri()?>/images/social_icon5.png" alt="" /></a></div>
     <div class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc"> <a href="#" target="_blank"><img class="img-responsive" src="<?php echo get_template_directory_uri()?>/images/social_icon3.png" alt="" /></a></div>
     <div class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc"> <a href="#" target="_blank"><img class="img-responsive" src="<?php echo get_template_directory_uri()?>/images/social_icon6.png" alt="" /></a></div> -->

     <div class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc">
      <a href="" class="social_icon"><img src="<?php echo get_template_directory_uri()?>/images/facebook-icon.png"></a>
    </div>
    <div class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc">
      <a href="" class="social_icon"><img src="<?php echo get_template_directory_uri()?>/images/instagram-icon.png"></a>
    </div>
    <div class="col-lg-4 col-sm-6 col-xs-4 mrg_bot_soc">
     <a href="<?php echo get_site_url()?>/blog" class="social_icon"><img src="<?php echo get_template_directory_uri()?>/images/campsite.png" style="margin-top:17px;"></a>
   </div>

   <div class="clear10"></div>
 </div>

 <div class="col-xs-12 padding0">
  <div class="clear20"></div>
  <img src="<?php echo get_template_directory_uri()?>/images/google_Ad.jpg" class="img-responsive" alt="" />
  <div class="clear10"></div>
</div>
</div>
<div class="col-sm-4 col-xs-12 padding0"></div>
</div>
</div>

<div class="clear20"></div>
<div class="container text-center  padding0">
  <div class="container padding0">

   <div class="col-sm-12 col-xs-12 blog_hd"> Recent blog post
    <div class="clear10"></div>
    <img src="<?php echo get_template_directory_uri()?>/images/img_btm_hd.png" alt="" />
  </div>
  <div class="clear20"></div>

  <?php
  if ( $the_query->have_posts() ) 
  {
    while ( $the_query->have_posts() ) 
    {
      $the_query->the_post(); 
        //the_ID();
      ?>
      <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
        <div class="b_box">
          <?php
          if (has_post_thumbnail()) {
            the_post_thumbnail('homepage-thumb');
          } else {
            ?>
            <img src="<?php echo get_template_directory_uri() ?>/images/pic1.png" style="width: 330px; height: 250px;" class="img-responsive" />
            <?php
          }
          ?>
          <h3><?php the_title(); ?></h3>
          <p style="height: 67px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;  color: #535353;">
            <?php
            $content = get_the_content();
            echo substr($content, 0, 145);
            ?></p>
            <a href="<?php echo get_site_url() ?>/blogdetail?id=<?php echo the_ID() ?>" class="btn_blue col-sm-4 col-xs-12 col-lg-offset-4">Read More</a>
            <div class="clearfix"></div>
          </div>
        </div>
        <?php
      }
    }
    else
    {
      ?>
      <p style="font-family: lato, arial;font-size: 18px;">No blog posts found</p>
      <?php
    }
    ?>
<!--    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
    <div class="b_box">
    	<img src="<?php echo get_template_directory_uri()?>/images/pic2.png" class="img-responsive" />
        <h3>Lorem Itpsum</h3>
            <p style="font-size:16px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>
             <a href="#" class="btn_blue col-sm-4 col-xs-12 col-lg-offset-4">Read More</a>
             <div class="clearfix"></div>
    </div></div>
    
    
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
    <div class="b_box">
    	<img src="<?php echo get_template_directory_uri()?>/images/pic3.png" class="img-responsive" />
    <h3>Lorem Itpsum</h3>
            <p style="font-size:16px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>
             <a href="#" class="btn_blue col-sm-4 col-xs-12 col-lg-offset-4">Read More</a>
             <div class="clearfix"></div>
           </div></div>-->
         </div>  
       </div>
       <div class="clear20"></div>






       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg_main">
        <div class="container padding0">

          <div class="col-sm-12 col-xs-12 blog_hd"> About Us    <div class="clear10"></div>
          <img src="<?php echo get_template_directory_uri()?>/images/img_btm_hd.png" alt="">
        </div>
        <div class="col-sm-5 col-xs-12 pull-right">


          <div style="padding:10px; background:#216462; border-radius:5px; margin-top: 20px;">
            <?php //if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Sidebar')) : ?>
            <?php //dynamic_sidebar('right_sidebar');?>
            <?php //endif; ?>
            <img src="<?php echo get_template_directory_uri()?>/images/whyuspic.png" class="img-responsive" alt="" />
          </div>


        </div>
        <div class="col-sm-6 col-xs-12 pull-left  padding0" style="padding-left:20px !important;">
          <div class="clear10"></div>
          <div class="clear10"></div>
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
wp_reset_query(); //resetting the page query
endif;

?>
<!--Mission: To make the search for a campsite an easier and more enjoyable process, and to give <a href="<?php echo get_site_url()."/grandsearch"?>">feedback</a> to environmental organizations that help protect the same outdoor areas we love.<br><br>
Find Your Campsite was founded with one simple idea in mind: to make the search for a campsite an easier and more informative process. We longed for one website where you could browse multiple reviews and high quality photos in the same place. Thus, we decided to create it, and with an environmental focus in mind. We give 5% of our proceeds back to non-profit environmental groups, give <a href="<?php echo get_site_url()."/grandsearch"?>" >feedback</a> that help protect the outdoor areas we love and enjoy.<br><br>
I hope you find this site helpful in researching your next camping trip. And please leave a review or post some photos from your last trip if you have the time! At Find Your Campsite, we love tips and suggestions (considering the whole website is founded on the user experience!). Please email us at <a href="mailto:info@findyourcampsite.com" title="mailto:info@findyourcampsite.com">info@findyourcampsite.com</a> and expect a quick response.<br><br>
Many thanks for using our site and happy camping!-->
<div class="clear10"></div>
</div>
</div>
</div>
<div class="clear20"></div>

<!--

<div class="container-fluid footer padding0">
	<div class="container">
    	<div class="col-sm-4 col-xs-12 padding0">
        	<h4>Main Links</h4>
            <ul>
            	<li><a href="#"> About tentcampsite Reviews</a></li>
                <li><a href="it-services-managed-services.html">Contact tentcampsite </a></li>
            <li><a href="it-services-remote-support.html"> Downloads</a></li>
            <li><a href="it-services-onsite-support.html">Campground Owners</a></li>
             <li><a href="it-e-mail-defense.html">TOS</a></li>
            <li><a href="it-internet-security.html">Privacy</a></li>
            </ul>
        </div>
        <div class="col-sm-4 col-xs-12">
        	<h4>Our Location</h4>
            <div class="clear10"></div>
         
            	<strong>45 ROCKEFELLER PLAZA 20TH FLOOR<br />
NEW YORK, NY 10111
</strong>
   <div class="clear10"></div>
   <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d26081603.294420466!2d-95.677068!3d37.06250000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1466767749262" width="273" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>
<img src="<?php// echo get_template_directory_uri()?>/images/footer_map.jpg" class="img-responsive" alt="" />
   <div class="clear10"></div>
</div>
        
        <div class="col-sm-3 col-xs-12 col-lg-offset-1" style="line-height:30px;">
        	<h4>Contact Us</h4>
           <div class="clear10"></div>
            	Main: (0000) 1234.456<br />
Direct : (203) 123456978 EST)<br />
E-mail: <a href="mailto:info@tentcampsite.com">info@tentcampsite.com</a><br />
Skype:  tentcampsite
   
        </div>
        
        
    </div>
    
    <div class="copyright  padding0">
<div class="clear10"></div>
	<div class="container">
    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
        	<p>Copyright © 2000-2016 tentcampsite - All Rights Reserved</p>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
        	<p style="float:right;">Designed & Developed by <a href="http://leadconcept.com/" target="_blank" style="color:#fff;">LEADconcept</a></p>
        </div>
    </div>
</div>
</div>-->
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
<!-- Modal -->
<div id="myModal" class="modal fade out" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Find Your Campsite</h4>
      </div>
      <div class="modal-body">
        <p>Find Your Campsite is still very much a work in progress. We appreciate your patience, and you can support the completion of the website here: <a href="http://gofundme.com/findyourcampsite">gofundme.com/findyourcampsite</a> -Nate Sullivan, Owner.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
    $('#myModal').modal('show');
    if($('.modal-backdrop').hasClass('in'))
    {
        $('.modal-backdrop').removeClass('in');
        $('.modal-backdrop').addClass('out');
    }
//    jQuery('#vmap').vectorMap({
//    
//    map: 'usa_en',
//    //backgroundColor: '#a5bfdd',
//    backgroundColor: null,
//    borderColor: '#818181',
//    borderOpacity: 0.25,
//    borderWidth: 1,
//    color: '#f4f3f0',
//    enableZoom: true,
//    hoverColor: '#c9dfaf',
//    hoverOpacity: null,
//    //normalizeFunction: 'linear',
//    normalizeFunction: 'linear',
//    scaleColors: ['#b6d6ff', '#005ace'],
//    selectedColor: '#c9dfaf',
//    selectedRegions: null,
//    showTooltip: true,
//    zoomOnScroll: true,
//    
//    onLoad: function(event, map)
//    {
//
//    },
//    onLabelShow: function(event, label, code)
//    {
//
//    },
//    onRegionOver: function(event, code, region)
//    {
//
//    },
//    onRegionOut: function(event, code, region)
//    {
//
//    },
//    onRegionClick: function(event, code, region)
//    {
//
//    },
//    onResize: function(event, width, height)
//    {
//
//    }
//    
////    onRegionClick: function(event, code, region){
////        event.preventDefault();
////    }
////    onRegionClick: function(element, code, region)
////    {
////        alert('abcd');
////        var message = 'You clicked "'
////            + region
////            + '" which has the code: '
////            + code.toUpperCase();    
////    }
//    
////    map: 'usa_en',
////    backgroundColor: null,
////    color: '#ffffff',
////    hoverOpacity: 0.7,
////    selectedColor: '#666666',
////    enableZoom: true,
////    showTooltip: true,
////    values: sample_data,
////    scaleColors: ['#C8EEFF', '#006491'],
////    normalizeFunction: 'polynomial'
//
//
////alert($(event.currentTarget).data('mapObject').isMoving);
//
//});
//
//jQuery('#vmap').bind('load.jqvmap',
//    function(event, map)
//    {
//        
//    }
//);
//jQuery('#vmap').bind('labelShow.jqvmap',
//    function(event, label, code)
//    {
//
//    }
//);
//jQuery('#vmap').bind('regionMouseOver.jqvmap',
//    function(event, code, region)
//    {
//
//    }
//);
//jQuery('#vmap').bind('regionMouseOut.jqvmap',
//    function(event, code, region)
//    {
//
//    }
//);
//jQuery('#vmap').bind('regionClick.jqvmap',
//    function(event, code, region)
//    {
//
//    }
//);
//jQuery('#vmap').bind('resize.jqvmap',
//    function(event, width, height)
//    {
//
//    }
//);





</script>

<script type="text/javascript">

  function autocomplet()
  {
        //var input = (document.getElementById('query'));
        var min_length = 0; // min caracters to display the autocomplete
        var keyword = $('#query').val();
        
        //alert(keyword);
        
        if (keyword.length >= min_length) {
          $.ajax({
           url: 'search',
           type: 'POST',
           data: {keyword:keyword},
           success:function(data){
                            //console.log(data);
                            //alert(data);
                            $('#country_list_id').show();
                            $('#country_list_id').html(data);
                          }
                        });
        } else {
          $('#country_list_id').hide();
        }
      }

      $(document).ready(function(){





      });



    </script>
    
    
    <script>
      var gdpData = {
        "AF": 16.63,
        "AL": 11.58,
        "DZ": 158.97
      };

      var gdpData = <?php echo json_encode($statesWith);?>;
      console.log(gdpData);
      $('#map1').vectorMap({
        map: 'us_lcc_en',
        zoomOnScroll: false,
        zoomButtons : false,
        onRegionClick: function(e, code)
        {
          e.preventDefault();
          var arr = code.split('-');
                //alert(arr[1]);
                var region = arr[1];
                //window.location.href = 'http://www.google.com';
                //window.location.assign("http://www.w3schools.com");
                window.location = '<?php echo get_site_url()?>/grandsearch?regionId='+region;
                //window.location.href = '<?php get_site_url()?>/grandsearch?regionId='.arr[1];
              },
//            series: {
//                regions: [{
//                  values: gdpData,
//                  scale: ['#C8EEFF', '#0071A4'],
//                  normalizeFunction: 'polynomial'
//                }]
//              },
onRegionTipShow: function(e, el, code){
  e.preventDefault();
  var map = $('#map1').vectorMap('get', 'mapObject');
  var customTip=$('#customTip');
  customTip.css({
    left: left,
    top: top1,
    zIndex: "10"
  })
                    //var arr = gdpData[code+'-'+0].split(',');
                //alert(arr);
                //var region = arr[1];
                if(gdpData[code] == "")
                {
                  el.html(el.html()+' <br> (Top 3 Campgorunds) <br> Not exists <br>');
                }
                else
                {
                  var i = 0;
                  el.html(el.html()+' (Top 3 Campgorunds) <br>');
                  while(i < 3)
                  {
                    if(gdpData[code+'-'+i] != null)
                    {
                      var camp = gdpData[code+'-'+i].split(',');
                      el.html(el.html()+'<a style="color: white;" href="<?php echo get_site_url()?>/camp?id='+camp[0]+'">'+camp[1]+'</a> - <span class="campground-badge-stat pull-right star-dec-map" itemprop="ratingValue">'+camp[2]+'/10</span><br>');
                    }
                    i++;
                  }
//                    var camp1 = gdpData[code+'-'+0].split(',');
//                    var camp2 = gdpData[code+'-'+1].split(',');
//                    var camp3 = gdpData[code+'-'+2].split(',');
//                    //alert(arr[1]);
//                    //var region = arr[1];
//                   el.html(el.html()+' (Top 3 Campgorunds) <br><a style="color: white;" href="<?php echo get_site_url()?>/camp?id='+camp1[0]+'">'+camp1[1]+'</a> - '+camp1[2]+'<br><a style="color: white;" href="<?php echo get_site_url()?>/camp?id='+camp2[0]+'">'+camp2[1]+'</a> - '+camp2[2]+'<br><a style="color: white;" href="<?php echo get_site_url()?>/camp?id='+camp3[0]+'">'+camp3[1]+ '</a> - '+camp3[2]+'<br>');
}
customTip.html(map.tip.html());
customTip.show();
//                customTip.append("<br><p>Click to Close</p>");
//                customTip.children("p").click(function(){
//                    map.clearSelectedRegions();
//                   customTip.hide(); 
//                })   
},
});
      var left;
      var top1;
      $('#map1').vectorMap('get', 'mapObject').container.mousemove(function(e){
//alert(e.pageY);
left = e.pageX - 510;
top1 = e.pageY - 260;     
});
//    $(function(){
//      var map1,
//          map2;
//      new jvm.MultiMap({
//        container: $('#map1'),
//        maxLevel: 1,
//        mapUrlByCode: function(code, multiMap){
//          return 'assets/us/jquery-jvectormap-data-'+code.toLowerCase()+'-'+multiMap.defaultProjection+'-en.js';
//        },
//        main: {
//          map: 'us_lcc_en',
//        }
//      });
//    });

$( "div#map1" )
//  .mouseenter(function() {
//    $('#customTip').show();
//  })
.mouseleave(function() {
  $('#customTip').hide();
});


// if($('#back_search').empty()){
//   alert('');
// }


</script>
<?php get_footer();?>

<!--      <script src="<?php //echo get_template_directory_uri()?>/js/jquery-1.11.1.js"></script>
  <script src="<?php //echo get_template_directory_uri()?>/js/bootstrap.min.js"></script>-->