<?php

/* 
 Template Name: Detail_Page
 */
function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background=false) {
    list($original_width, $original_height, $original_type) = getimagesize($filepath);
    if ($original_width > $original_height) {
        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);
    } else {
        $new_height = $thumbnail_height;
        $new_width = intval($original_width * $new_height / $original_height);
    }
    $dest_x = intval(($thumbnail_width - $new_width) / 2);
    $dest_y = intval(($thumbnail_height - $new_height) / 2);
    if ($original_type === 1) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    } else if ($original_type === 2) {
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    } else if ($original_type === 3) {
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    } else {
        return false;
    }
    $old_image = $imgcreatefrom($filepath);
    $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height); // creates new image, but with a black background
    // figuring out the color for the background
    if(is_array($background) && count($background) === 3) {
      list($red, $green, $blue) = $background;
      $color = imagecolorallocate($new_image, $red, $green, $blue);
      imagefill($new_image, 0, 0, $color);
    // apply transparent background only if is a png image
    } else if($background === 'transparent' && $original_type === 3) {
      imagesavealpha($new_image, TRUE);
      $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
      imagefill($new_image, 0, 0, $color);
    }
    imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
    $imgt($new_image, $thumbpath, 100);
    return file_exists($thumbpath);
}

//function get_image_thumb($imageName, $width, $height)
//{
//    $dir = $_SERVER['DOCUMENT_ROOT'].'/campsite/wp-content/themes/campsitetheme/upload';
//    
//    $image = $_SERVER['DOCUMENT_ROOT'].'/campsite/wp-content/themes/campsitetheme/upload/'.$imageName;
//    
//    $image_properties = getimagesize($image);
//    
//    $image_width = $image_properties[0];
//	$image_height = $image_properties[1];
//	$image_ratio = $image_width / $image_height;
//	$type = $image_properties["mime"];
//
//	if(!$width && !$height) {
//		$width = $image_width;
//		$height = $image_height;
//	}
//	if(!$width) {
//		$width = round($height * $image_ratio);
//	}
//	if(!$height) {
//		$height = round($width / $image_ratio);
//	}
//
////	if($type == "image/jpeg") {
////		header('Content-type: image/jpeg');
////		$thumb = imagecreatefromjpeg($image);
////	} elseif($type == "image/png") {
////		header('Content-type: image/png');
////		$thumb = imagecreatefrompng($image);
////	} else {
////		return false;
////	}
//        
////      $temp_image = imagecreatetruecolor($width, $height);
////	imagecopyresampled($temp_image, $thumb, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
//	$thumbnail = imagecreatetruecolor($width, $height);
//        return $thu
////	imagecopyresampled($thumbnail, $temp_image, 0, 0, 0, 0, $width, $height, $width, $height);
////
////	if($type == "image/jpeg") {
////		return imagejpeg($thumbnail);
////	} else {
////		imagepng($thumbnail);
////	}
//
////	imagedestroy($temp_image);
////	imagedestroy($thumbnail);
//        
//
//    //print_r($image_properties); @die;
//    // Open a directory, and read its contents
////    $image;
////    if (is_dir($dir)){
////      if ($dh = opendir($dir)){
////        while (($file = readdir($dh)) !== false){
////            if($imageName == $file)
////            {
////                //echo "filename:" . $file . "<br>";  
////                $image = $file;
////            }
////        }
////        closedir($dh);
////      }
////    }
////    //print_r($image);@die;
////    return $image;
//}


function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Mi') 
{
   $theta = $longitude1 - $longitude2;
   $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))+ (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta))));
   $distance = acos($distance); $distance = rad2deg($distance); 
   $distance = $distance * 60 * 1.1515;

   switch($unit) 
   { 
     case 'Mi': break;
     case 'Km' : $distance = $distance * 1.609344; 
   } 
   return (round($distance,2)); 
}

function get_nearby_campgrounds($campID, $lat, $lon) {
    
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $near_by = 10;
//    $lat = 52;
//    $lon = -177;
    $radius = 3961; // Km
    $angle_radius = $radius / 111; // Every lat|lon degreeÂ° is ~ 111Km
    $min_lat = $lat - $angle_radius;
    $max_lat = $lat + $angle_radius;
    $min_lon = $lon - $angle_radius;
    $max_lon = $lon + $angle_radius;
    $result = $wpdb->get_results("SELECT $camps_table.ID, $camps_table.camp_name, $camps_table.camp_type, $camps_table.camp_latitude, $camps_table.camp_longitude, $cities_table.city_name, $states_table.state_name  FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code WHERE $camps_table.camp_latitude BETWEEN $min_lat AND $max_lat AND $camps_table.camp_longitude BETWEEN $min_lon AND $max_lon AND $camps_table.ID != $campID AND $camps_table.camp_status = 1");
    
    $results = json_decode(json_encode($result), True);
    
    $n_rows = count($results);
    //$miles = array();
    //$j = 0;
    for ($i = 0; $i < $n_rows; $i++) 
    {
        $distance = getDistanceBetweenPointsNew($lat, $lon, $results[$i]['camp_latitude'], $results[$i]['camp_longitude']);
        if ($distance > $near_by)       
        {
            // This is out of the "perfect" circle radius. Strip it out.
            unset($results[$i]);
        }
        else
        {
            //$miles[$j] = $distance;
            $results[$i]['distance'] = $distance; 
            //array_push($results[$i], $distance);
            //$array1 = json_decode(json_encode($results[$i]), True);
            //$size_result = sizeof($array1);
            //$j++;
        }
        //print_r($results);
        //print_r($size_result);
    }
    
    $array = json_decode(json_encode($results), True);
    return $array;
    
// Now do something with your result set
    //var_dump($results);

    //$wpdb->show_errors();
    //$wpdb->print_error();

    //print_r($results);

    //@die;
//    SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * 
//cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) * 
//sin( radians( lat ) ) ) ) AS distance FROM your_table_name HAVING
//distance < 25 ORDER BY distance LIMIT 0 , 20;
}

function get_park_details_by_id($campId) 
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $results = $wpdb->get_results("SELECT $camps_table.ID,  $camps_table.camp_name, $camps_table.states_code, $camps_table.camp_type,$camps_table.camp_price,$camps_table.camp_reserve,$camps_table.camp_climate,$camps_table.camp_sites,$camps_table.camp_months,$camps_table.camp_hookups, $camps_table.camp_amenities,$camps_table.camp_phone, $camps_table.camp_website, $camps_table.camp_latitude, $camps_table.camp_longitude,$camps_table.camp_images, $cities_table.city_name, $states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code WHERE $camps_table.ID = $campId AND $camps_table.camp_status = 1");

    $array = json_decode(json_encode($results), True);
    $array2 = $array[0];
    
    //print_r($array2);
//    
   //@die;
    return $array2;
}

function get_count_of_reviews_by_id($campId)
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT * FROM $reviews_table WHERE $reviews_table.camp_id = $campId");
    $total = sizeof( $results );
    return $total;
}
function get_paginated_reviews_by_id($campId, $offset, $items_per_page)
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT * FROM $reviews_table WHERE $reviews_table.camp_id = $campId" . " ORDER BY ID LIMIT ${offset}, ${items_per_page}");
    $array = json_decode(json_encode($results), True);
    return $array;
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

function get_campsites_reviewed_by_user($userId)
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT count(review_by) as Reviewed FROM $reviews_table WHERE $reviews_table.review_by = $userId");
    //print_r($results);
    //@die;
    return $results[0]->Reviewed;
}

function get_profile_image($userId)
{
    $image_name =  get_user_meta ( $userId, "image_name", false );
    if(isset($image_name[0]))
    {
        return $image_name[0];
    }
    else
    {
        return $image_name;
    }
}

function get_top_tips_by_camp_id($campId)
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $users_table = $wpdb->prefix . 'users'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $results = $wpdb->get_results("SELECT $reviews_table.camp_tip,$reviews_table.review_date, $users_table.display_name FROM $reviews_table JOIN $users_table ON $reviews_table.review_by = $users_table.ID WHERE $reviews_table.camp_id = $campId AND $reviews_table.camp_tip != '' ORDER BY $reviews_table.review_date DESC LIMIT 3");

    $array = json_decode(json_encode($results), True);
    //$array2 = $array[0];
//    $wpdb->show_errors();
//    $wpdb->print_error();
    //print_r($array);
////    
   //@die;
    return $array;
}

// ***START OF AHMAD BAJWA's CODE's function ***
function update_time_for_review($campId)
{
	 global $wpdb;
	
	
	 $blogtime = current_time( 'mysql' ); 
	
	 
	
	$results = $wpdb->update( 'wp_camps', array( 'camp_recently_viewed' => $blogtime), array( 'ID' => $campId ) );
	
	if(false===$results)
	{
		echo "something went wrong";
	}
	
	
} 

// ***end OF AHMAD BAJWA's CODE's function ***

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

//Functions ends here

//get_top_tips_by_camp_id(27);
get_header();

$amenities =  array('pets' => "Pets Allowed", 'play' => "Playground Access", 'wifi' => "Wifi",  'showers' => "Showers", 'flush' => "Flush Toilets", 'vault' => "Vault Toilets", 'other' => "Other");

$campTypes = array('car' => "Car Camping", 'hike' => "Hike In", 'rvs' => "RVs Only");

$reserve = array(1 => "Yes", 0 => "No");

//$temporary = explode("/", $_SERVER['REQUEST_URI']);
//$key = sizeof($temporary);
//$camp_id =  $temporary[$key-1];
//print_r($temporary);
//@die;
//print_r(is_numeric($camp_id));
//
//@die;

if($_GET['id'])
{
    $camp_id =  $_GET['id'];
    $camp_details = get_park_details_by_id($camp_id);
    if(empty($camp_details))
    {
        $location = get_site_url();
        wp_redirect($location);
    }
    else
    {
        $total = get_count_of_reviews_by_id($camp_id);
        $items_per_page = 5;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
        $reviews = get_paginated_reviews_by_id($camp_id, $offset, $items_per_page);
        $nearby_camps = get_nearby_campgrounds($camp_id, $camp_details['camp_latitude'], $camp_details['camp_longitude']);
        $topTips = get_top_tips_by_camp_id($camp_id);
        $updated_time_user_view=update_time_for_review($camp_id);
    }
    
//  print_r($reviews);@die;
    if(!empty($total))
    {
        //$total_reviews  = sizeof($reviews);
        $campOverAllRating = get_overall_rating($total, $camp_id);
        //print_r($total_reviews);@die;
    }
    //print_r($nearby_camps); @die;
}
else
{
    //    echo "not int";
//@die;
    $location = get_site_url();
    wp_redirect($location);
}
//@die;
//if(is_numeric($camp_id))
//{
//    //echo "int";
//    $camp_details = get_park_details_by_id($camp_id);
//    if(empty($camp_details))
//    {
//        $location = get_site_url();
//        wp_redirect($location);
//    }
//    
//}




?>
<!-- Slider Start -->

<!-- Slider End -->
<div class="clearfix"></div>
<style>

/* CSS used here will be applied after bootstrap.css */
.carousel {
    margin-top: 0px;
}

.item .thumb img {
	width: 100%;
	margin: 2px;
}
.item img {
	width: 100%;	
}
.item .thumb {
  cursor: pointer;
    float: left;
    width: 19%;
	margin-left:5px;
}
.carousel-inner > .active
{
	margin-left:44px;
}
.carousel-control.left
{
	  background: transparent none repeat scroll 0 0;
}
.carousel-control.right
{
	  background: transparent none repeat scroll 0 0;
}
.mrg_left
{
	margin-left:0 !important;
}
.crsl_big
{
	margin:0;
}
.crsl_big img
{
	width:427px !important;
	/*height:300px !important;*/
	border-radius:5px;
}
.crsl_bigsm
{
	margin:0;
}
.crsl_bigsm img {
    border-radius: 5px;
    height: 44px !important;
    margin-top: 10px !important;
    width: 60px !important;
}
.center_lable
{
	   color: #272727;
    font-family: "Merriweather sans",Arial;
    font-size: 16px;
    font-weight: normal;
}
.center_lable span
{
	   color: #8e8e8e;
    font-family: "Merriweather sans",Arial;
    font-size: 16px;
    font-weight: normal;
}
.campground-badge-7, .campground-badge-8 {
    background-color: #fe7445;
    border-color: #fe7445;
}
.campground-badge-stats {
    padding-left: 13px;
    padding-right: 13px;
    padding-bottom: 8px;
    padding-top: 8px;
}
.campground-badge-stars {
    background-color: #fff;
    border-radius: 5px;
    padding: 5px 5px 5px 8px;
}
.campground-badge {
    float: left;
    margin-right: 10px;
    //margin-top: 3px;
	padding:2px;
	border-radius:5px;
}
.campground-badge-stat {
    color: #fff;
    font-size: 22px;
    font-weight: bold;
    //margin-top: 5px;
    display: block;
}
.campground-badge-label {
    color: #fff;
    font-weight: 100;
    line-height: 40px;
}
.hdg_link_botm
{
	font-size:20px; margin-top:10px; display:block; float:left;
}
.thumbnail {
    background-color: #fff;
    border: 3px solid #dedede;
    border-radius: 50%;
    display: block;
    height: 100px;
    line-height: 1.42857;
    margin-bottom: 20px;
    padding: 0px;
    transition: border 0.2s ease-in-out 0s;
    width: 100px;
}
.thumbnail > img, .thumbnail a > img {
    border-radius: 50%;
    height: 100px;
    margin-left: auto;
    margin-right: auto;
    width: 100px;
}
.thumbnail > img, .thumbnail a > img {
    border-radius: 50%;
    height: 100px;
    margin-left: auto;
    margin-right: auto;
    width: 100px;
}
.campgrounds-list-small .campground-list-title {
    color: #555;
	display: block;
    margin-bottom: 5px;
}

.campgrounds-list-small .campgrounds-list-item {
    clear: both;
    margin-bottom: 0 !important;
    margin-top: 5px !important;
    min-height: 0 !important;
    padding-bottom: 5px !important;
    padding-left: 0;
}
.row-divider, .campgrounds-list-small .campgrounds-list-item, .form-amenities-item, .review-detail-item, .reviews-list-item, .tips-list-item-sm {
    border-bottom: 1px solid #eee;
    margin-bottom: 10px;
    padding-bottom: 10px;
}

.campgrounds-list-item {
    min-height: 70px;
    padding-bottom: 10px;
    position: relative;
}
.list-unstyled, .list-inline, .change-country-list, .campgrounds-list, .region-list, .review-type-list, .reviews-list {
    list-style: outside none none;
    padding-left: 0;
}
h4, .h4, .campground-state-hdr, .city-name-hdr, .content-section-header h1, .hdr, .hdr-owners {
    font-size: 20px;
	margin:0;
	margin-bottom:20px;
}

.row-divider, .campgrounds-list-small .campgrounds-list-item, .form-amenities-item, .review-detail-item, .reviews-list-item, .tips-list-item-sm {
    border-bottom: 1px solid #eee;
    margin-bottom: 10px;
    padding-bottom: 10px;
}
.tips-list-sm
{
	list-style:none;
	margin:0;
	padding:0;
}
.right_pdg
{
	padding-right:0;
}
.pagination
{
    float: right !important;
    margin: 0px;
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
.modal.fade.in{
   display: block !important;
}
.modal-backdrop.in{
    opacity: 0 !important;
}
.modal-backdrop{
    z-index: 0 !important;
}










/*font Awesome http://fontawesome.io*/
//@import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
/*Comment List styles*/
.comment-list .row {
  margin-bottom: 0px;
}
.comment-list .panel .panel-heading {
  padding: 4px 15px;
  position: absolute;
  border:none;
  /*Panel-heading border radius*/
  border-top-right-radius:0px;
  top: 1px;
}
.comment-list .panel .panel-heading.right {
  border-right-width: 0px;
  /*Panel-heading border radius*/
  border-top-left-radius:0px;
  right: 16px;
}
.comment-list .panel .panel-heading .panel-body {
  padding-top: 6px;
}
.comment-list figcaption {
  /*For wrapping text in thumbnail*/
  word-wrap: break-word;
}
/* Portrait tablets and medium desktops */
@media (min-width: 768px) {
  .comment-list .arrow:after, .comment-list .arrow:before {
    content: "";
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
    border-color: transparent;
  }
  .comment-list .panel.arrow.left:after, .comment-list .panel.arrow.left:before {
    border-left: 0;
  }
  /*****Left Arrow*****/
  /*Outline effect style*/
  .comment-list .panel.arrow.left:before {
    left: 0px;
    top: 30px;
    /*Use boarder color of panel*/
    border-right-color: inherit;
    border-width: 16px;
  }
  /*Background color effect*/
  .comment-list .panel.arrow.left:after {
    left: 1px;
    top: 31px;
    /*Change for different outline color*/
    border-right-color: #FFFFFF;
    border-width: 15px;
  }
  /*****Right Arrow*****/
  /*Outline effect style*/
  .comment-list .panel.arrow.right:before {
    right: -16px;
    top: 30px;
    /*Use boarder color of panel*/
    border-left-color: inherit;
    border-width: 16px;
  }
  /*Background color effect*/
  .comment-list .panel.arrow.right:after {
    right: -14px;
    top: 31px;
    /*Change for different outline color*/
    border-left-color: #FFFFFF;
    border-width: 15px;
  }
}
.comment-list .comment-post {
  margin-top: 6px;
  line-height:26px;
  color:#737272;
}



@media (min-width: 320px) and (max-width: 767px) {
.right_pdg
{
	padding:0;
	margin-top:20px;
}
.carousel {
    height: 209px;
    position: relative;
}
.carousel_height {
    height: 87px;
}
.carousel-inner {
    height: 199px;
    overflow: hidden;
    position: relative;
    width: 100%;
}
.carousel-control
{
	height:40px;
	width: 3%;
}
.carousel-inner > .active {
    margin-left: 0px;
}
.item .thumb {
    cursor: pointer;
    float: left;
    margin-left: 5px;
    width: 21%;
}
.crsl_bigsm img {
    border-radius: 5px;
    height: 44px !important;
    margin-top: 10px !important;
    width: 56px !important;
}
.crsl_big img {
    border-radius: 5px;
    height: 325px !important;
    width: 100% !important;
}
}

</style>
<?php 

  $readImages = json_decode($camp_details['camp_images']);
 // print_r($readImages);
//@die;
  $final_read_images; 
  if(empty($readImages) && empty($reviews))
  {
      $final_read_images=NULL;
  }
 else 
       {
//  $count_readimages=count($readImages);
  $images_camp;
  if(!empty($reviews))
  {
      $i = 0;
foreach($reviews as $value)
{
    if($value['review_images'])
    {
    $images_camp[$i] = $value['review_images'];    
    $i++;
    }
    //$images_review=  json_decode($images_camp);
}
}
//print_r($images_camp);
//@die;
 if(!empty($readImages))
  {
$images_camp[] = $camp_details['camp_images'];
  }
$images_review;
$i = 0;

 //print_r($images_camp); @die;
if(!empty($images_camp))
{
 foreach ($images_camp as $value1)
{
  
    $images_review =  json_decode($value1);
    
    foreach($images_review as $value2)
    {
        $final_read_images[$i] = $value2;
        $i++;
    }
}   
}
else
{
    $final_read_images = NULL;
}

  }
  
//print_r($final_read_images);
//@die;




/*
$images_camp[] = $camp_details['camp_images'];
print_r($images_camp);
  @die;
$count_images_review=count($images_review);
$loop_end=$count_readimages+$count_images_review;
 $temp_count=0;
for($l=$count_readimages; $l<$loop_end; $l++)
{
   
    $readImages[$l]=$images_review[$temp_count];
    $temp_count++;
}
*/
//print_r($readImages);
//@die;


?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">
    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-body">
           <div id="mycarousel" class="carousel slide" data-interval="false">
           <div class="carousel-inner ">  
                <?php 
         //       $readImages = json_decode($camp_details['camp_images']);
                if(!empty($final_read_images))
                {
                    foreach ($final_read_images as $key => $image) {
                        if($key == 0)
                        {
                        ?>
                        <div class="item active mrg_left" style="max-height: 300px !important;overflow: hidden;">
                          <!--  <a href="#" data-toggle="modal" data-target="#myModal"> -->
                          <!-- <div data-target="#carousel" data-slide-to="<?php // echo $key?>" class="thumb"> <img src="<?php //echo get_template_directory_uri()?>/upload/<?php //echo $image?>"></div>-->
                          <img style="/*height:430px;*/" id="my_image" src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>">
                      
                        </div>
                        <?php   
                        }
                        else
                        {
                            ?>
                            <div class="item mrg_left">
                         <!--       <a href="#" data-toggle="modal" data-target="#myModal"> -->
                            <img style="height:430px" src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>">
                            </a>
                            </div>
                            <?php
                        }
                    }
                }
                else
                {
                    ?>
                    <div class="item active mrg_left">
                   <!--     <a href="#" data-toggle="modal" data-target="#myModal">    -->
                          <img src="<?php echo get_template_directory_uri()?>/images/camping-tent.jpg">
                  <!--      </a>-->
                    </div>
                    <div class="item mrg_left">
                    <img src="<?php echo get_template_directory_uri()?>/images/tent and campfire.jpg">
                    </div>
                    <div class="item mrg_left">
                        <img src="<?php echo get_template_directory_uri()?>/images/tent-camping-2.jpg">
                    </div>
                    <div class="item mrg_left">
                        <img src="<?php echo get_template_directory_uri()?>/images/whyuspic.png">
                    </div>
                    <div class="item mrg_left">
                          <img src="<?php echo get_template_directory_uri()?>/images/camping-tent.jpg">
                    </div>
                    <div class="item mrg_left">
                         <img src="<?php echo get_template_directory_uri()?>/images/tent and campfire.jpg">
                    </div>
                    <div class="item mrg_left">
                           <img src="<?php echo get_template_directory_uri()?>/images/tent-camping-2.jpg">
                    </div>
                    <div class="item mrg_left">
                      <img src="<?php echo get_template_directory_uri()?>/images/whyuspic.png">
                    </div>
                    <?php
                }   
                ?>
            </div> 
             <a class="left carousel-control" href="#mycarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#mycarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a> 
               
        </div> 
     <!--    <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <?php
            //  $readImages = json_decode($camp_details['camp_images']);
           //  if(!empty($readImages))
           //         {
            //            foreach ($readImages as $key => $image) 
          //              {
                            ?>
                            <div data-target="#mycarousel" data-slide-to="<?php echo $key?>" class="thumb"> <img src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>"></div>
                            <?php
                //        }  
           //         } 
                    ?>
             <!--   <div class="carousel-inner" role="listbox">-->
                    
       <!--     <div class="carousel-inner mrg_left crsl_big">  -->
      <!--    <div class="item active mrg_left"> -->
             
      <!--  <img  id="my_image" class="img-responsive" src="" style="height:430px;"> -->
            
      
            <!--    </div>-->
              
              <!--    </div>  -->
     <!--   </div>  -->
             <!--    </div>  -->
                </div>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    
     
    
<!-- <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content
    <div class="modal-content">
      
      <div class="modal-body">
        <img id="my_image" class="img-responsive" src="<?php //echo get_template_directory_uri()?>/images/camping-tent.jpg" style="height:430px;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>  -->

<div class="container">
    <div class="col-sm-12 padding0">
        <a href="#" class="col-sm-1 padding0">
                    <div itemtype="" itemscope="" itemprop="aggregateRating" class="campground-badge campground-badge-8 ">
                		<div class="campground-badge-stats" style="height: 53px;">
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span itemprop="ratingValue" class="campground-badge-stat">
                                        <?php if(!empty($campOverAllRating))
                                        {
                                            echo $campOverAllRating."/10";
                                        }else
                                        {
                                            echo "0.0/10";
                                        }?>
                                        </span>
<!--                			<span class="campground-badge-label">Good</span>-->
                		</div>
<!--                		<div class="campground-badge-stars">
                			<span>
                				<img alt="" src="<?php echo get_template_directory_uri()?>/images/star1.png">&nbsp;<img alt="" src="<?php echo get_template_directory_uri()?>/images/star1.png">&nbsp;<img alt="" src="<?php echo get_template_directory_uri()?>/images/star1.png">&nbsp;<img alt="" src="<?php echo get_template_directory_uri()?>/images/star1.png">&nbsp;<img alt="" src="<?php echo get_template_directory_uri()?>/images/star1.png">&nbsp;                			</span>
                		</div>-->
                	</div>
                </a>
<div class="col-sm-10 padding0" style="padding-left:10px !important;">
                <!-- Title -->
                <h1 style="margin:0; font-weight:bold; font-size:25px; color: black;"><?php echo $camp_details['camp_name']; ?> (<?php foreach ($campTypes as $key => $value) :
    if($camp_details['camp_type'] == $key)
    { 
        echo $value;
    }
endforeach; ?>)</h1>

                <!-- Author -->
               
                    <a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $camp_details['city_name'] ?>" class="hdg_link_botm"><?php echo $camp_details['city_name']; ?>,</a>
               
                     <a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $camp_details['state_name'] ?>" class="hdg_link_botm" style="margin-left:5px;"><?php echo $camp_details['state_name']; ?></a>
                       <div class="clear10"></div>

<!--
<?php// echo $camp_details['camp_name']; ?> Campsite<br />
<?php //echo $camp_details['camp_name']; ?> , <?php// echo $camp_details['city_name']; ?><br />
Phone: <?php// echo $camp_details['camp_phone']; ?> -->

<!--                <div class="clear20"></div>-->
                </div>
        </div>
<div class="clear10"></div>
    <div class="col-sm-4 padding0">
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner mrg_left crsl_big" style="max-height:300px;overflow:hidden;">
                <?php 
              //  $readImages = json_decode($camp_details['camp_images']);
                if(!empty($final_read_images))
                {
                    foreach ($final_read_images as $key => $image) {
                        if($key == 0)
                        {
                        ?>
                        <div class="item active mrg_left">
                            <a href="#" data-toggle="modal" data-target="#myModal">
                                 <img data-target="#mycarousel" id="myImage" style="border-radius: 0;" data-slide-to="<?php echo $key?>" src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>">
                     <!--   <img id="myImage" src="<?php //echo get_template_directory_uri()?>/upload/<?php //echo $image?>"> -->
                        </a>
                        </div>
                        <?php   
                        }
                        else
                        {
                            ?>
                            <div class="item mrg_left">
                                <a href="#" data-toggle="modal" data-target="#myModal">
                                     <img data-target="#mycarousel" id="myImage" style="height:430px; border-radius: 0;" data-slide-to="<?php echo $key?>" src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>">
                         <!--   <img src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>">-->
                            </a>
                            </div>
                            <?php
                        }
                    }
                }
                else
                {
                    ?>
                    <div class="item active mrg_left">
                        <a href="#" data-toggle="modal" data-target="#myModal">   
                          <img id="myImage" data-target="#mycarousel" style="height: 430px;" src="<?php echo get_template_directory_uri()?>/images/camping-tent.jpg">
                        </a>
                    </div>
                    <div class="item mrg_left">
                         <a href="#" data-toggle="modal" data-target="#myModal">  
                    <img id="myImage" data-target="#mycarousel" style="height: 430px;" src="<?php echo get_template_directory_uri()?>/images/tent and campfire.jpg">
                    </a>
                    </div>
                    <div class="item mrg_left">
                         <a href="#" data-toggle="modal" data-target="#myModal">  
                        <img id="myImage" data-target="#mycarousel" style="height: 430px;" src="<?php echo get_template_directory_uri()?>/images/tent-camping-2.jpg">
                        </a>
                    </div>
                    <div class="item mrg_left">
                         <a href="#" data-toggle="modal" data-target="#myModal">  
                        <img id="myImage" data-target="#mycarousel" style="height: 430px;" src="<?php echo get_template_directory_uri()?>/images/whyuspic.png">
                        </a>
                    </div>
                    <div class="item mrg_left">
                         <a href="#" data-toggle="modal" data-target="#myModal">  
                          <img id="myImage" data-target="#mycarousel" style="height: 430px;" src="<?php echo get_template_directory_uri()?>/images/camping-tent.jpg">
                          </a>
                    </div>
                    <div class="item mrg_left">
                         <a href="#" data-toggle="modal" data-target="#myModal">  
                         <img id="myImage" data-target="#mycarousel" style="height: 430px;" src="<?php echo get_template_directory_uri()?>/images/tent and campfire.jpg">
                         </a>
                    </div>
                    <div class="item mrg_left">
                         <a href="#" data-toggle="modal" data-target="#myModal">  
                           <img id="myImage" data-target="#mycarousel" style="height: 430px;" src="<?php echo get_template_directory_uri()?>/images/tent-camping-2.jpg">
                           </a>
                    </div>
                    <div class="item mrg_left">
                         <a href="#" data-toggle="modal" data-target="#myModal">  
                      <img id="myImage" data-target="#mycarousel" style="height:430px;" src="<?php echo get_template_directory_uri()?>/images/whyuspic.png">
                      </a>
                    </div>
                    <?php
                }   
                ?>
            </div>
        </div> 
    <div class="clearfix">
        <div id="thumbcarousel" class="carousel slide carousel_height" data-interval="false">
            <div class="carousel-inner crsl_bigsm ">
             
                <div class="item active">
                    <?php
                    $myycount='';
                     $mycount=1;
                    if(!empty($final_read_images))
                    {
                       
                        foreach ($final_read_images as $key => $image) 
                        {
                            
                            ?>
                            <div data-target="#carousel" data-slide-to="<?php echo $key?>" class="thumb"> <img src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>"></div>
                            <?php
                            if($mycount==4)
                            {
                                break;
                            }
                            $myycount=$mycount++;
                        }  
                    } 
                    else
                    {
                        ?>
                        <div data-target="#carousel" data-slide-to="0" class="thumb"> <img src="<?php echo get_template_directory_uri()?>/images/camping-tent.jpg"></div>
                        <div data-target="#carousel" data-slide-to="1" class="thumb"> <img src="<?php echo get_template_directory_uri()?>/images/tent and campfire.jpg"></div>
                        <div data-target="#carousel" data-slide-to="2" class="thumb"> <img src="<?php echo get_template_directory_uri()?>/images/tent-camping-2.jpg"></div>
                        <div data-target="#carousel" data-slide-to="3" class="thumb">  <img src="<?php echo get_template_directory_uri()?>/images/whyuspic.png"></div>
                        <?php
                    }  
                    ?>
                    
                    <!--  -->
                </div><!-- /item -->
                 <?php
                  if(!empty($final_read_images) && count($final_read_images) > 4)
                    {
                 $count_images=count($final_read_images);                           
                      
               
             $remaining_images=$count_images-4;
         
             $m='';

             if($remaining_images<4)
             {
                 $loop=1;
                 $m=4;
                // $count_images=$remaining_images;
             }
 else {
     $temp_loop=$remaining_images/4;
    
      $loop=round($temp_loop);
    //  if($temp_loop<=$temp_loop+.49)
   //   {
  //        $loop=$loop+1;
    //  }
    
      $m=4;
 }
 
             for($k=0;$k<$loop;$k++)
             {
               
              ?>
                <div class="item">
                    <?php
             
              
                $zig=0;
                        for($i=$m; $i<$count_images;$i++)
                        { 
                           
                              if($zig==4)
                            {
                                break;
                                 
                            }
                           
                          $my_image= $final_read_images[$i];
                            ?>
                            <div data-target="#carousel" data-slide-to="<?php echo $i?>" class="thumb"> <img src="<?php echo get_template_directory_uri()?>/upload/<?php echo $my_image?>"></div>
                           
                            <?php
                           
                             $zig=$zig+1;
                        
                        } 
             $m=$m+4;
           
             
                    ?>
                </div><!-- /item -->
                <?php
                }
                }
                // else
//                    {
//                        ?>
<!--                        <div data-target="#carousel" data-slide-to="0" class="thumb"> <img src="//<?php echo get_template_directory_uri()?>/images/camping-tent.jpg"></div>
                        <div data-target="#carousel" data-slide-to="1" class="thumb"> <img src="//<?php echo get_template_directory_uri()?>/images/tent and campfire.jpg"></div>
                        <div data-target="#carousel" data-slide-to="2" class="thumb"> <img src="//<?php echo get_template_directory_uri()?>/images/tent-camping-2.jpg"></div>
                        <div data-target="#carousel" data-slide-to="3" class="thumb">  <img src="//<?php echo get_template_directory_uri()?>/images/whyuspic.png"></div>-->
                        <?php
//                    }  
                ?>
         
            </div><!-- /carousel-inner -->
            <a class="left carousel-control" href="#thumbcarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#thumbcarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div> <!-- /thumbcarousel -->
    </div><!-- /clearfix -->
   </div> <!--  col-sm-6 -->

    <div class="col-sm-4 center_lable p_left">
        <h3 style="text-decoration: underline; margin-top:0;">Quick Information</h3>
<!--        
            <?php echo $camp_details['camp_name']; ?>
        <h5 style="font-size: 15px; margin-top: -5px;"><?php echo $camp_details['state_name']; ?>, <?php echo $camp_details['city_name']; ?></h5>-->
<div class="clear10"></div>        
Campground Type: <span>
    <?php 
foreach ($campTypes as $key => $value) :
    if($camp_details['camp_type'] == $key)
    { 
        echo $value;
    }
endforeach;
    ?>
</span><br />
Climate: <span><?php echo ucfirst($camp_details['camp_climate']); ?></span><br />
Best Month's to Visit: <span><?php
$readMonths = json_decode($camp_details['camp_months']);
if(isset($readMonths))
{  
$coma_count=count($readMonths);
$i=0; 
foreach($readMonths as $value):
echo ucfirst($value);

if($i!=$coma_count-1)
{
?>
, 
<?php 
$i++;
}   
endforeach;
}
else
{
?>Not exist
 <?php
}?>
</span><br s/>

Number of sites: <span><?php echo $camp_details['camp_sites']; ?></span><br />
Hookups Available: <span><?php 
$readHookups = json_decode($camp_details['camp_hookups']);
$coma_count=count($readHookups);
$i=0; 
foreach($readHookups as $value):
echo ucfirst($value);
if($i!=$coma_count-1)
{
?>
, 
<?php 
$i++;
}      
endforeach;
 ?></span><br />
Amenities: <span><?php 
$readAmenities = json_decode($camp_details['camp_amenities']);
//print_r(sizeof($readAmenities)); @die;
$i = 0;
$coma_count=count($readAmenities);
//echo $coma_count;

//print_r($readAmenities);
//@die;
$k=0; 
foreach ($amenities as $key => $value) 
{
	
    foreach($readAmenities as $value1)
    {
        if($key == $value1)
        {
           echo ucfirst($value); 
		   if($k!=$coma_count-1)
{
?>
, 
<?php 
$k++;
}   
           $i++;
        }
        
    }
    
}
//print_r($i);
//print_r(sizeof($readAmenities)); @die;
if($i != sizeof($readAmenities))
{
    echo ucfirst($readAmenities[$i++]);
}

?></span><br />
Price: <span>$<?php echo $camp_details['camp_price']; ?></span><br />
Reservations Accepted: <span><?php 
foreach ($reserve as $key => $value) :
  if($camp_details['camp_reserve'] == $key)
      {
      echo $value;
      }
      endforeach;?></span>

<div class="clear20"></div>
 
<a rel="nofollow external" href="<?php echo $camp_details['camp_website'] ?>" itemprop="url" class="btn btn-warning btn-float-padded" target="_blank">Visit Campground Website to Book</a>

<div class="clear20"></div>
<a title="Add Review" href="<?php echo get_site_url()?>/addreview?state=<?php echo $camp_details['states_code']?>&camp=<?php echo $camp_id?>" class="btn btn_blue col-sm-8 col-md-8">Add Your Review</a>

    </div>
    
    <div class="col-sm-4 padding0" style="border:5px">
        <div id="detailmap" style=" width: 100%; height: 300px; border-radius:5px; border:5px"></div>
<!--<iframe id="myIframe" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55565170.29301636!2d-132.08532758867793!3d31.786060306224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited+States!5e0!3m2!1sen!2s!4v1465892771992" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>-->

    </div>
    
     <!-- /col-sm-6 -->
   <!-- /row -->
</div>


<div class="clear40"></div>





<div class="container">
<div class="col-sm-8 padding0">
<div class="white_box_login" style="padding-right:10px; padding-left:10px;">
           



              <div style="font-size:36px; text-align:center; font-weight:bold; width:100%; text-decoration: underline;"> Individual Reviews (<?php
                  if(!empty($total))
                  {
                      echo $total;
                  }
                  else
                  {
                      echo 0;
                  }
                  ?>)</div>
<div class="clear20"></div>
 <div class="clear20"></div>
 <?php 
 
 if(!empty($reviews))
 {
     
     //print_r($reviews);
      $count=1;
     foreach($reviews as $value)
     {
        $count++;
        $totalCampsitesReviewed =  get_campsites_reviewed_by_user($value['review_by']);
        $totalCampsImagesByUser = get_camp_images_count_by_user($value['review_by']);
        $totalReviewsImagesByUser = get_review_images_count_by_user($value['review_by']);
        $totalImagesByUser = $totalCampsImagesByUser->Images + $totalReviewsImagesByUser;
         ?>
 <section class="comment-list">
          <!-- First Comment -->
          <article class="row">
            <div class="col-md-3 col-sm-3 hidden-xs img_inf" style="color:#999999;padding-right:0;">
              <figure class="thumbnail">
                <?php
                if (!empty(get_profile_image($value['review_by']))):
                    $image = get_profile_image($value['review_by']);
                    //print_r($image[0]); @die;
                    $path = get_template_directory_uri() . '/upload/' . $image;
                    //get_image_thumb($image, 200, 200);
                    $dir = $_SERVER['DOCUMENT_ROOT'].'/campsite/wp-content/themes/campsitetheme/upload/'.$image ;
                    $dir1 = $_SERVER['DOCUMENT_ROOT'].'/campsite/wp-content/themes/campsitetheme/upload/thumb_'.$image;
                    $path = createThumbnail($dir, $dir1, 940, 940, true);
                    if($path){$path1 = get_template_directory_uri() . '/upload/thumb_'.$image;}else{$path1 = get_template_directory_uri() . '/upload/'. $image;}
?>
                  <img src="<?php echo $path1 ?>" class="img-responsive" style="height: auto; width: auto; border-radius: 0;">
                        <?php
                else:
                    ?>
                    <img id="previewing" src="<?php echo get_template_directory_uri() ?>/images/default.jpg" width="205" height="205" alt="" /> 
                <?php
                endif;
                ?>
                
              </figure>
             <span>Memberships</span>
          <span>Campsites Reviewed <span class="pull-right">
              <?php echo $totalCampsitesReviewed;?>
              </span></span>
          <span>Photos Posted <span class="pull-right"><?php echo $totalImagesByUser;?></span></span>
          <br />
            </div>
            <div class="col-md-9 col-sm-9">
              <div class="panel panel-default arrow left">
                <div class="panel-body">
                  
                  <div class="comment-post">
                      <div class="col-sm-12">
                    <span class="col-sm-5 comment-date">Date Stayed: 
                        <?php
                        $originalDate = $value['date_stay'];
                        $newDate = date("m-d-Y", strtotime($originalDate));
                        echo $newDate;
                        ?>
                    </span>
                          <div class="col-sm-2" style="padding: 5px 10px 10px 0px;"><span>Site #: <b><?php echo $value['camp_sites']?></b></span></div>      
                    <div class="col-sm-5 score_rate" style="padding-right: 0;">
                        <span class="pull-left">Overall Score:</span> <div itemtype="" itemscope="" itemprop="aggregateRating" class="campground-badge campground-badge-8" style="width: 38%; text-align: center; margin-top: -3px;">
                            <div>
                                <meta content="15" itemprop="ratingCount">
                                <meta content="1" itemprop="worstRating">
                                <meta content="10" itemprop="bestRating">
                                <span style="font-size:16px;" itemprop="ratingValue" class="campground-badge-stat">
                                    <?php
                                    $reviewRating = $value['review_score'];
                                    $overallReviewRating = number_format((float) $reviewRating, 1, '.', '');
                                    echo $overallReviewRating;
                                    ?>
                                    /10</span>

                            </div>
                        </div>
                    </div>
                </div>
                      
                
                 <div class="clearfix"></div>       
                      
                  

                             
                 
                      
                      
            
              <div class="col-sm-12 review_thumbnail padding0">
                 <div class="clear20"></div>
                 
                 <?php 
                 $readReviewImages = json_decode($value['review_images']);
               //   print_r($readReviewImages);
           //   @die;
                  
                 if(!empty($readReviewImages))
                 {
                  // $count=1;
                     foreach($readReviewImages as $key =>$image)
                     {
                    ?>
                 <a href="#" data-toggle="modal" data-target="#myModal<?php echo "$count" ?>">
                    <!--  <img data-target="#mycarousel" id="myImage" data-slide-to="<?php //echo $key?>" src="<?php// echo get_template_directory_uri()?>/upload/<?php// echo $image?>">  -->
                        <img data-target="#mycarousel<?php echo "$count" ?>" id="myImage" data-slide-to="<?php echo $key?>" class="img-thumbnail" style="width: 90px; height: 70px;" src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>" />
                        </a>
                  
                    <?php
                  //  $count++;
                     }
                     // $count++;
                 }
                 else
                 {
                 ?>
                  <img class="img-thumbnail" width="90" height="90" src="http://localhost/campsite/wp-content/themes/campsitetheme/images/tent-camping-2.jpg" />
                  <img class="img-thumbnail" width="90" height="90" src="http://localhost/campsite/wp-content/themes/campsitetheme/images/tent-camping-2.jpg" />
                  <img class="img-thumbnail" width="90" height="90" src="http://localhost/campsite/wp-content/themes/campsitetheme/images/tent-camping-2.jpg" />
                  <img class="img-thumbnail" width="90" height="90" src="http://localhost/campsite/wp-content/themes/campsitetheme/images/tent-camping-2.jpg" />
                  <img class="img-thumbnail" width="90" height="90" src="http://localhost/campsite/wp-content/themes/campsitetheme/images/tent-camping-2.jpg" />
                 <?php
                 }
                 ?>
                 
              </div>
                 
                 
              <div id="myModal<?php echo "$count" ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-body">
           <div id="mycarousel<?php echo "$count" ?>" class="carousel slide" data-interval="false">
           <div class="carousel-inner ">  
                <?php 
                 $readReviewImages = json_decode($value['review_images']);
                if(!empty($readReviewImages))
                {
                    foreach ($readReviewImages as $key => $image) {
                        if($key == 0)
                        {
                        ?>
                        <div class="item active mrg_left">
                          <!--  <a href="#" data-toggle="modal" data-target="#myModal"> -->
                          <!-- <div data-target="#carousel" data-slide-to="<?php // echo $key?>" class="thumb"> <img src="<?php //echo get_template_directory_uri()?>/upload/<?php //echo $image?>"></div>-->
                        <img id="my_image" style="height:430px; border-radius: 0;" src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>">
                      
                        </div>
                        <?php   
                        }
                        else
                        {
                            ?>
                            <div class="item mrg_left">
                         <!--       <a href="#" data-toggle="modal" data-target="#myModal"> -->
                            <img id="my_image" style="height:430px; border-radius: 0;" src="<?php echo get_template_directory_uri()?>/upload/<?php echo $image?>">
                            </a>
                            </div>
                            <?php
                        }
                    }
                }
                else
                {
                    ?>
                    <div class="item active mrg_left">
                   <!--     <a href="#" data-toggle="modal" data-target="#myModal">    -->
                          <img src="<?php echo get_template_directory_uri()?>/images/camping-tent.jpg">
                  <!--      </a>-->
                    </div>
                    <div class="item mrg_left">
                    <img src="<?php echo get_template_directory_uri()?>/images/tent and campfire.jpg">
                    </div>
                    <div class="item mrg_left">
                        <img src="<?php echo get_template_directory_uri()?>/images/tent-camping-2.jpg">
                    </div>
                    <div class="item mrg_left">
                        <img src="<?php echo get_template_directory_uri()?>/images/whyuspic.png">
                    </div>
                    <div class="item mrg_left">
                          <img src="<?php echo get_template_directory_uri()?>/images/camping-tent.jpg">
                    </div>
                    <div class="item mrg_left">
                         <img src="<?php echo get_template_directory_uri()?>/images/tent and campfire.jpg">
                    </div>
                    <div class="item mrg_left">
                           <img src="<?php echo get_template_directory_uri()?>/images/tent-camping-2.jpg">
                    </div>
                    <div class="item mrg_left">
                      <img src="<?php echo get_template_directory_uri()?>/images/whyuspic.png">
                    </div>
                    <?php
                }   
                ?>
            </div> 
             <a class="left carousel-control" href="#mycarousel<?php echo "$count" ?>" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#mycarousel<?php echo "$count" ?>" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a> 
               
        </div> 
     <!--    <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <?php
            //  $readImages = json_decode($camp_details['camp_images']);
           //  if(!empty($readImages))
           //         {
            //            foreach ($readImages as $key => $image) 
          //              {
                            ?>
                            <div data-target="#mycarousel" data-slide-to="<?php //echo $key?>" class="thumb"> <img src="<?php //echo get_template_directory_uri()?>/upload/<?php //echo $image?>"></div>
                            <?php
                //        }  
           //         } 
                    ?>
             <!--   <div class="carousel-inner" role="listbox">-->
                    
       <!--     <div class="carousel-inner mrg_left crsl_big">  -->
      <!--    <div class="item active mrg_left"> -->
             
      <!--  <img  id="my_image" class="img-responsive" src="" style="height:430px;"> -->
            
      
            <!--    </div>-->
              
              <!--    </div>  -->
     <!--   </div>  -->
             <!--    </div>  -->
                </div>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>      
                 
                 
                 
   
                 
                 
                 
                 
                 
                 
              <div class="clear20"></div>
                    <p>
                      <?php
                      echo $value['camp_desc'];
                      ?>
                    </p>
                  </div>
                  <div class="clear20"></div>
                  <div class="col-sm-12 col-md-6 padding0">
                  <div class="col-sm-6">Scenic Beauty</div>
                   <div class="col-sm-6"><div itemtype="" itemscope="" itemprop="aggregateRating" class="campground-badge campground-badge-8 " >
                		<div >
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span style="font-size:14px; font-weight:bold;" itemprop="ratingValue" class="campground-badge-stat"><?php
                        $sceneRating = $value['camp_scene'];
                        $overallSceneRating = number_format((float) $sceneRating, 1, '.', '');
                        echo $overallSceneRating;
                                        ?>/10</span>
                			
                		</div>
                		
                	</div></div>
                   <div class="clear10"></div>
                   <div class="col-sm-6">Family Friendly</div>
                   <div class="col-sm-6"><div itemtype="" itemscope="" itemprop="aggregateRating" class="campground-badge campground-badge-8 " >
                		<div>
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span style="font-size:14px; font-weight:bold;" itemprop="ratingValue" class="campground-badge-stat"><?php
                        $familyRating = $value['camp_friendly'];
                        $overallFamilyRating = number_format((float) $familyRating, 1, '.', '');
                        echo $overallFamilyRating;
                                        ?>/10</span>
                			
                		</div>
                		
                	</div></div>
                   <div class="clear10"></div>
                   <div class="col-sm-6">Bug Factor</div>
                   <div class="col-sm-6"><div itemtype="" itemscope="" itemprop="aggregateRating" class="campground-badge campground-badge-8 " >
                		<div>
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span style="font-size:14px; font-weight:bold;" itemprop="ratingValue" class="campground-badge-stat"><?php
                        $cleanRating = $value['camp_bug'];
                        $overallCleanRating = number_format((float) $cleanRating, 1, '.', '');
                        echo $overallCleanRating;
                                        ?>/10</span>
                			
                		</div>
                		
                	</div></div>
<!--                   <div class="clear10"></div>
                     <div class="col-sm-6">Favorite Site(s) or Loop</div>
                   <div  class="col-sm-6"><div style="background:white !important" itemtype="" itemscope="" itemprop="aggregateRating"  >
                		<div style="background:white !important">
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span style="font-size:14px; font-weight:bold; padding: 0px 6px 2px; color:#737272; background:white" itemprop="ratingValue" class="campground-badge-stat"><?php echo $value['camp_sites']?></span>
                			
                		</div>
                		
                	</div></div>-->
                   
                   
                  </div>
                  
                  <div class="col-sm-12 col-md-6 padding0">
                  <div class="col-sm-6">Location</div>
                   <div class="col-sm-6"><div itemtype="" itemscope="" itemprop="aggregateRating" class="campground-badge campground-badge-8 " >
                		<div>
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span style="font-size:14px; font-weight:bold;" itemprop="ratingValue" class="campground-badge-stat"><?php
                        $locationRating = $value['camp_location'];
                        $overallLocationRating = number_format((float) $locationRating, 1, '.', '');
                        echo $overallLocationRating;
                                        ?>/10</span>
                			
                		</div>
                		
                	</div></div>
                   <div class="clear10"></div>
                   <div class="col-sm-6">Privacy</div>
                   <div class="col-sm-6"><div itemtype="" itemscope="" itemprop="aggregateRating" class="campground-badge campground-badge-8 " >
                		<div>
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span style="font-size:14px; font-weight:bold;" itemprop="ratingValue" class="campground-badge-stat"><?php
                        $privacyRating = $value['camp_privacy'];
                        $overallPrivacyRating = number_format((float) $privacyRating, 1, '.', '');
                        echo $overallPrivacyRating;
                                        ?>/10</span>
                			
                		</div>
                		
                	</div></div>
                   <div class="clear10"></div>
                     <div class="col-sm-6">Cleanliness</div>
                   <div class="col-sm-6"><div itemtype="" itemscope="" itemprop="aggregateRating" class="campground-badge campground-badge-8 " >
                		<div>
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span style="font-size:14px; font-weight:bold;" itemprop="ratingValue" class="campground-badge-stat"><?php
                        $cleanRating = $value['camp_clean'];
                        $overallCleanRating = number_format((float) $cleanRating, 1, '.', '');
                        echo $overallCleanRating;
                                        ?>/10</span>
                			
                		</div>
                		
                	</div></div>
              <!--     <div class="col-sm-6">Favorite Site(s) or Loop</div>
                   <div  class="col-sm-6"><div style="background:white !important" itemtype="" itemscope="" itemprop="aggregateRating"  >
                		<div style="background:white !important">
                			<meta content="15" itemprop="ratingCount">
                			<meta content="1" itemprop="worstRating">
                			<meta content="10" itemprop="bestRating">
                			<span style="font-size:14px; font-weight:bold; padding: 0px 6px 2px; color:black; background:white" itemprop="ratingValue" class="campground-badge-stat"><?php echo $value['camp_sites']?></span>
                			
                		</div>
                		
                	</div></div>-->
                  </div>
                  
                  
                </div>
              </div>
            </div>
          </article>
      
         <div class="clear20"></div>
          
        </section> 
 
 <?php
 //$count++;
     }
 }
 else
 {
     ?>
 <p style="margin-left: 200px;">No reviews found, be the first to <a title="Add Review" href="<?php echo get_site_url()?>/addreview?state=<?php echo $camp_details['states_code']?>&camp=<?php echo $camp_id?>">Add Review</a></p>
 <?php
 }
 ?>
<!--        <div class="clear40"></div>-->
        <?php
        
  					   $pages =  paginate_links( array(
    'base' => add_query_arg( 'cpage', '%#%' ),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($total / $items_per_page),
    'current' => $page,
    'type' => 'array'
));
                                          
                                                   if( is_array( $pages ) ) {
            $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
            echo '<ul class="pagination">';
            foreach ( $pages as $page ) {
                //print_r($page);
                    echo "<li>$page</li>";
            }
           echo '</ul>';
        }
                                                   
    ?>  
        
        
<!--        <nav class="pull-right">
  <ul style="margin:0;" class="pagination">
    <li class="page-item">
      <a aria-label="Previous" href="#" class="page-link">
        <span aria-hidden="true">Â«</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li class="page-item"><a href="#" class="page-link">1</a></li>
    <li class="page-item"><a href="#" class="page-link">2</a></li>
    <li class="page-item"><a href="#" class="page-link">3</a></li>
    <li class="page-item"><a href="#" class="page-link">4</a></li>
    <li class="page-item"><a href="#" class="page-link">5</a></li>
    <li class="page-item">
      <a aria-label="Next" href="#" class="page-link">
        <span aria-hidden="true">Â»</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
</nav> -->
        <div class="clearfix"></div>
    </div>
     </div>
    
    
    <div class="col-sm-4 right_pdg">

        
        <div class="white_box_login" style="padding-right:10px; padding-left:10px;">
            <section class="sidebar-section">
    				<h4 class="campgrounds-list-hdr">Nearby Campgrounds</h4>
                                <ol class="campgrounds-list campgrounds-list-small">
                                    <?php
                                    //print_r($nearby_camps);
                                    if(!empty($nearby_camps))
                                    {
                                        foreach($nearby_camps as $value):
                                        $reviewsOfNearByCamp = get_reviews_by_id($value['ID']);
                                        $totalReviewsOfNearByCamp = count($reviewsOfNearByCamp);
                                        $nearByCampOverAllRating = get_overall_rating($totalReviewsOfNearByCamp, $value['ID']);

                                    ?>
                                    <li class="campgrounds-list-item">
                                        <a title="<?php echo $value['camp_name'];?> in <?php echo $value['city_name'];?>, <?php echo $value['state_name'];?>" href="<?php echo get_site_url().'/camp?id='.$value['ID'];?>" class="campground-list-title"><?php echo $value['camp_name'];?> (<?php foreach ($campTypes as $key => $type) :
    if($camp_details['camp_type'] == $key)
    { 
        echo $type;
    }
endforeach; ?>) </a>
                                        <p class="ratings-container">
                                            <div style="margin-top: -3px;" class="campground-badge campground-badge-8 " itemprop="aggregateRating" itemscope="" itemtype="">
                		<div>
                			<meta itemprop="ratingCount" content="15">
                			<meta itemprop="worstRating" content="1">
                			<meta itemprop="bestRating" content="10">
                			<span class="campground-badge-stat" itemprop="ratingValue" style="font-size:14px; font-weight:bold;"><?php echo $nearByCampOverAllRating;?>/10</span>
                			
                		</div>
                		
                	</div>
                                            <span class="ratings-total-reviews"><?php echo $totalReviewsOfNearByCamp;?> Reviews</span>
                                            <span class="pull-right"><?php echo $value['distance'];?> miles</span>
                                        </p>
                                    </li>
                                    <?php
                                    endforeach;
                                    }
                                    else
                                    {
                                        echo "No campgrounds";
                                    }
                                    
                                    ?>
                                </ol>
		</section>

        <div class="clearfix"></div>
    </div>
        
        <div class="white_box_login" style="padding-right:10px; padding-left:10px;">
            <section class="sidebar-section">
            		<h4>Top Tips for this Campground</h4>
                        <?php 
                        if(!empty($topTips))
                        {
                            foreach($topTips as $tip)
                            {
                                $converted_date = date('F Y',strtotime($tip['review_date']));
                            ?>
                        <ul class="tips-list-sm">
                            <li class="tips-list-item-sm">
                                <p><?php echo $tip['camp_tip']?></p>
                                <a title="<?php echo $tip['display_name']?>'s profile" href="#"><?php echo $tip['display_name']?></a><?php echo " • "; ?> <span><?php echo $converted_date?></span>
                            </li>
                        </ul>
                            <?php
                            }
                        }
                        else
                        {
                            echo "No tips";
                        }
                        ?>
        </section>
        <div class="clearfix"></div>
    </div>
        <img src="<?php echo get_template_directory_uri()?>/images/google_Ad.jpg" class="img-responsive" alt="" /></div>
     </div>
     </div>





<div class="clear"></div>
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
<img src="<?php echo get_template_directory_uri()?>/images/footer_map.jpg" class="img-responsive" alt="" />
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
        	<p>Copyright Â© 2000-2016 tentcampsite - All Rights Reserved</p>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
        	<p style="float:right;">Designed & Developed by <a href="http://leadconcept.com/" target="_blank" style="color:#fff;">LEADconcept</a></p>
        </div>
    </div>
</div>
</div>-->
<!--      <script src="js/jquery-1.11.1.js"></script>
  <script src="js/bootstrap.min.js"></script>
  -->
  
  <!--<script language=javascript src='http://maps.google.com/maps/api/js?sensor=false'></script>-->
<!--<div id="map" style=" width: 500px; height: 400px;"></div>-->
 
<!--<script>
    jQuery(document).ready(function (){
     var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
     var myOptions = {
         zoom: 4,
         center: myLatlng,
         mapTypeId: google.maps.MapTypeId.ROADMAP
         }
      map = new google.maps.Map($('#map'), myOptions);
      var marker = new google.maps.Marker({
          position: myLatlng, 
          map: map,
      title:"Fast marker"
     });
});
    </script>-->
<script>
    
    $('img').click(function()
{
    src = $(this).attr('src');
    $('#my_image').attr('src',src);
    //alert($(this).attr('src'));
});

    
//    $('#myImage').on({
//    'click': function(){
//        $('#my_image').attr('src',src);
//    }
//});
    </script>
<script>
    function initialize(){
        
        //var bounds = new google.maps.LatLngBounds();
        var lat = <?php echo $camp_details['camp_latitude']; ?>;
        var long =  <?php echo $camp_details['camp_longitude']; ?>;
     var myLatlng = new google.maps.LatLng(lat,long);
     //bounds.extend(myLatlng);
     var myOptions = {
         zoom: 15,
         center: myLatlng,
         mapTypeId: google.maps.MapTypeId.ROADMAP
//         mapTypeControl: true,
//         style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
//         navigationControl: true,
//         style: google.maps.NavigationControlStyle.ZOOM_PAN
         }
      map = new google.maps.Map(document.getElementById("detailmap"), myOptions);
      //document.getElementById('myIframe').src = "https://maps.google.com/maps/embed?q=51.88,-176.6580556";
      var marker = new google.maps.Marker({
          position: myLatlng, 
          map: map,
      title:"Fast marker"
     });
     //map.fitBounds(bounds);
} 

google.maps.event.addDomListener(window,'load', initialize);



</script>
  
<!--  <div id="map_canvas" style=" width: 500px; height: 400px;"></div>
  <script>
    function initialize() {
      var myLatlng = new google.maps.LatLng(51.88, -176.6580556);
      var myOptions = {
        zoom: 8,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    }

    function loadScript() {
      var script = document.createElement("script");
      script.type = "text/javascript";
      script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=initialize";
      document.body.appendChild(script);
    }

    window.onload = loadScript;


</script>-->
  
</body>
<?php
get_footer();
?>
</html>

