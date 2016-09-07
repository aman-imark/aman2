<?php
/*
  Template Name: Manage_Profile
 */

//Function starts from here
function get_camps_count_added_by_user() {
    $user = wp_get_current_user();
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT DISTINCT $camps_table.ID, camp_name, camp_created_by, $cities_table.city_name, $states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code LEFT JOIN $reviews_table ON $camps_table.ID = $reviews_table.camp_id WHERE camp_created_by = $user->ID || $reviews_table.review_by = $user->ID");
    $total = sizeof($results);
    return $total;
}

function get_paginated_camps_addded_by_user($offset, $items_per_page) {
    $user = wp_get_current_user();
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT DISTINCT $camps_table.ID, camp_name, camp_created_by, $cities_table.city_name, $states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code LEFT JOIN $reviews_table ON $camps_table.ID = $reviews_table.camp_id WHERE camp_created_by = $user->ID || $reviews_table.review_by = $user->ID" . " ORDER BY ID LIMIT ${offset}, ${items_per_page}");
    $array = json_decode(json_encode($results), True);
//    print_r($array); 
//    $wpdb->show_errors();
//    $wpdb->print_error();
//    @die;
    return $array;
}

function get_camps_addded_by_user() {
    $user = wp_get_current_user();
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $results = $wpdb->get_results("SELECT $camps_table.ID, camp_name, $cities_table.city_name, $states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code WHERE camp_created_by = $user->ID");
    $array = json_decode(json_encode($results), True);
    return $array;
}

function get_profile_image() {
    $user = wp_get_current_user();
    $image_name = get_user_meta($user->ID, "image_name", false);
    if (isset($image_name[0])) {
        return $image_name[0];
    } else {
        return $image_name;
    }
}

function upload_image($data) {
    $user = wp_get_current_user();
    $date = new DateTime();
    $timestamp = $date->getTimestamp();
    $validextensions = array("jpeg", "jpg", "png");
    $temporary = explode(".", $data["file"]["name"]);
    $file_name_without_extension = $temporary[0];
    $file_extension = end($temporary);
    $updated_file_name = $file_name_without_extension . '_' . $timestamp . '.' . $file_extension;
    if ((($data["file"]["type"] == "image/png") || ($data["file"]["type"] == "image/jpg") || ($data["file"]["type"] == "image/jpeg")
            ) && ($data["file"]["size"] < 1000000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) {
        if ($data["file"]["error"] > 0) {
            echo "Return Code: " . $data["file"]["error"] . "<br/><br/>";
        } else {
            if (file_exists("upload/" . $data["file"]["name"])) {
                echo $data["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
            } else {
                $sourcePath = $data['file']['tmp_name']; // Storing source path of the file in a variable
                if (get_site_url() == "http://hadi/campsite" || get_site_url() == "http://localhost/campsite") {
                    $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/" . $updated_file_name; // Target path where file is to be stored
                } else {
                    $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/" . $updated_file_name; // Target path where file is to be stored 
                }
                // Moving Uploaded file
                if (move_uploaded_file($sourcePath, $targetPath)) {
                    $image_name = get_profile_image();
                    if (get_site_url() == "http://localhost/campsite" || get_site_url() == "http://hadi/campsite") {
                        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/" . $image_name;
                        $targetPath1 = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/thumb_" . $image_name;
                        if(file_exists($targetPath1))
                        {
                            unlink($targetPath1);
                        }
                    } else {
                        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/" . $image_name;
                        $targetPath1 = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/thumb_" . $image_name;
                        if(file_exists($targetPath1))
                        {
                            unlink($targetPath1);
                        }
                    }
                    unlink($targetPath);
                    $upload_image = update_usermeta($user->ID, "image_name", $updated_file_name);
                    if ($upload_image) {
                        return true;
                        //echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
                    }
                }
            }
        }
    } else {
        echo "<span id='invalid'>***Invalid file Size or Type***<span>";
    }
}

function user_info_validation($data, $userId) {
    $f_name = $data['first'];
    $l_name = $data['last'];
    $desc = $data['bio'];
    if (!empty($f_name) || !empty($l_name) || !empty($desc)) {
        $first = update_usermeta($userId, "first_name", $f_name);
        $last = update_usermeta($userId, "last_name", $l_name);
        $bio = update_usermeta($userId, "description", $desc);
        if ($first || $last || $bio) {
            return true;
        }
    } else {
        return false;
    }
}

//Function ends here
//Code starts from here
get_header();
if ($_POST['form_hidden'] == 'Y') {
    global $reg_errors;
    $reg_errors = new WP_Error;
    $profileUpdated = false;
    if (!empty($_FILES["file"]["type"])) {
        //print_r($_FILES);
        if (upload_image($_FILES)) {
            $profileUpdated = true;
        }
    }
    $user = wp_get_current_user();
    if (user_info_validation($_POST, $user->ID)) {
        $profileUpdated = true;
    }
    if ($profileUpdated) {
       $reg_errors->add('message', "<div class='alert alert-success' class='close' data-dismiss='alert' aria-label='close' style='clear:both; text-align:center;'>User updated successfully</div>");
        }
        else
        {
            $reg_errors->add('message', "<div class='alert alert-danger' class='close' data-dismiss='alert' aria-label='close' style='clear:both; text-align:center;'>User not updated successfully</div>");
    }
}
if (!is_user_logged_in()):
    $location = get_site_url() . "/login";
    wp_redirect($location);
endif;

$user = wp_get_current_user();
$first = get_user_meta($user->ID, 'first_name', false);
$last = get_user_meta($user->ID, 'last_name', false);
$bio = get_user_meta($user->ID, 'description', false);
$total = get_camps_count_added_by_user();
$items_per_page = 10;
$page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;
$offset = ( $page * $items_per_page ) - $items_per_page;
$stayedCamps = get_paginated_camps_addded_by_user($offset, $items_per_page);
//Code ends here
?>
<style>

    .title
    {
        color: #333;
        font-size: 24px;
        padding-left: 20px;
        text-align: left;
    }
    .well_profile {
        background-color: #216462;
        border: 1px solid #216462;
        border-radius: 4px;
        box-shadow: none;
        color: #fff;
        margin-bottom: 20px;
        min-height: 20px;
        padding: 5px;
    }
    .btn_blue_new {
        background: #FF7444 none repeat scroll 0 0;
        border-radius: 3px;
        color: #fff;
        display: block;
        font-family: Lato,Arial;
        font-size: 16px;
        margin-top: 6px;
        padding: 9px;
        text-align: center;
        text-decoration: none;
        text-shadow: none !important;
        border:none;
        margin-right:5px;
    }

    .btn_blue_new.cancel{
        background-color: #CCCCCC !important;

    }
    h3
    {
        color:#216462;
        font-weight:bold;
        margin:0;
    }
    .star-dec{
        font-size: 14px;
        font-weight: bold;
        background-color: #ff7444;
        width: 54px;
        border-radius: 5px;
        display: block;
        text-align: center;
        color: white;
        margin-top: 4px;
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
    
    .thumb_img{      
       border-radius: 50%;
        height: 100px;
        width: 100px;
        border: none; 
        box-shadow: none;
        
    }

</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg"><!--Div middle_bg starts from here-->
    <form  action="" id="manageProfile" method="post" enctype="multipart/form-data"><!--Form starts from here-->
        <input type="hidden" name="form_hidden" value="Y">
        <!--Div container white_box starts from here-->
        <div class="container white_box_login" style="padding-right:30px; padding-left:30px;">
            <div class="container-fluid"><!--Div container-fluid starts from here-->
<?php
if (is_wp_error($reg_errors) && !empty($reg_errors->errors)) {
    foreach ($reg_errors->get_error_messages() as $error) {
        echo $error . '<br/>';
    }
}
?>
                <div class="row top">
                    <div class="col-md-3 sidebar-holder">
                        <!-- -- sidebar.html ---->
                        <br>
                        <br>
                        <header role="banner" class="sidebar-header">
                            <div id="image_preview">
<?php
if (!empty(get_profile_image())):
    $image = get_profile_image();
    $path = get_template_directory_uri() . '/upload/' . $image;
//    if(file_exists($path)):
//        @die;
    ?>                            
        <img id="previewing" class="img-thumbnail thumb_img" src="<?php echo $path ?>"  width="" height="" style="border-radius:50%" alt="" /> 
    <?php 
//    else:
    ?>
<!--    <img id="previewing" src="<?php echo get_template_directory_uri() ?>/images/default.jpg" width="205" height="205" alt="" /> -->
    <?php
//    endif;
    else:
   ?>
        <img id="previewing" src="<?php echo get_template_directory_uri() ?>/images/default.jpg" width="250" height="270" style="border-radius:50%" alt="" /> 
    <?php
    endif;
    ?>
                            </div>
                            <div id="message"></div>
                            <div class="clear20"></div>
                            <h3 class="title">
<?php echo $user->display_name; ?>
                            </h3>
                            <div class="clear20"></div>
                            <input name="file" id="file" type="file" style="width:100% !important;" class="well_profile well-sm">
                        </header>
                    </div>
                    <div class="col-md-9">
                        <div class="alpaca-form-buttons-container">
                            <button data-key="reset" type="reset" id="reset" class="alpaca-form-button alpaca-form-button-reset btn btn-default pull-right btn_blue_new cancel">Cancel</button>
                            <button  type="submit" name="submit" id="uploadimage" class="alpaca-form-button alpaca-form-button-submit btn btn-default pull-right btn_blue_new">Save Changes</button>
                        </div>
                        <div class="clear20"></div>
                        <div class="row alpaca-field alpaca-field-object alpaca-optional alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca38" data-alpaca-field-path="/" data-alpaca-field-name="">

                            <div id="leftcolumn" class="col-md-6"><div class="alpaca-layout-binding-holder" alpaca-layout-binding-field-name="name"><div class="alpaca-container-item alpaca-container-item-first" data-alpaca-container-item-index="0" data-alpaca-container-item-name="name" data-alpaca-container-item-parent-field-id="alpaca38">

                                        <div class="form-group alpaca-field alpaca-field-text alpaca-required alpaca-autocomplete alpaca-field-valid" data-alpaca-field-id="alpaca40" data-alpaca-field-path="/name" data-alpaca-field-name="name">
                                            <div class="col-md-12 padding0">
                                                <h3>Profile Info</h3>
                                            </div>
                                            <div class="clear20"></div>
                                            <label for="alpaca41" class="control-label alpaca-control-label">First Name</label>
                                            <input type="text" value="<?php echo $first[0] ?>" name="first" id="alpaca41" class="alpaca-control form-control" autocomplete="off">

                                            <div class="clear20"></div>
                                            <label for="alpaca41" class="control-label alpaca-control-label">Last Name</label>
                                            <input type="text" value="<?php echo $last[0] ?>" name="last" id="alpaca41" class="alpaca-control form-control" autocomplete="off">

                                            <div class="clear20"></div>
                                            <label for="alpaca40" class="control-label alpaca-control-label">Bio</label>
                                            <textarea name="bio" cols="" placeholder="Tell us something about yourself.." class="alpaca-control form-control" rows=""><?php
if (isset($bio[0])) {
    echo $bio[0];
}
?></textarea>
                                            <div class="clear20"></div>
                                        </div>
                                    </div></div>
                            </div>
                            <div id="rightcolumn" class="col-md-6"><div class="alpaca-layout-binding-holder" alpaca-layout-binding-field-name="phone"><div class="alpaca-container-item" data-alpaca-container-item-index="6" data-alpaca-container-item-name="phone" data-alpaca-container-item-parent-field-id="alpaca38">
                                        <div class="form-group alpaca-field alpaca-field-phone alpaca-optional alpaca-autocomplete alpaca-field-valid" data-alpaca-field-id="alpaca46" data-alpaca-field-path="/phone" data-alpaca-field-name="phone">
                                            <div class="col-md-12 padding0">
                                                <h3>Account Settings</h3>
                                            </div>
                                            <div class="clear20"></div>
                                            <label for="alpaca46" class="control-label alpaca-control-label">Email address</label>
                                            <input type="tel" name="email" readonly="readonly" value="<?php echo $user->user_email; ?>" id="alpaca46" class="alpaca-control form-control" autocomplete="off">
                                        </div>
                                    </div></div></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row padding0">
                    <div class="col-sm-12 col-xs-12 blog_hd" style="
                         font-size: 26px;
                         "> Recently Viewed Campgrounds
                        <div class="clear10"></div>
                        <img src="<?php echo get_template_directory_uri() ?>/images/img_btm_hd.png" alt="" style="
                             margin-bottom: 12px;
                             ">
                    </div>
                    <!--<div class="col-sm-12 font_recent">-->
                        <!-- START OF AHMAD BAJWA'S 2ND CODE  -->
<?php

function get_reviews_by_id($campId) {
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $results = $wpdb->get_results("SELECT * FROM $reviews_table WHERE $reviews_table.camp_id = $campId");
    $array = json_decode(json_encode($results), True);
    return $array;
}

// review15-8-16 starts
//function get_camps_by_id($campId) {
//    $user = wp_get_current_user();
//    global $wpdb;
//    $camps_table = $wpdb->prefix . 'camps'; //Good practice
//    $cities_table = $wpdb->prefix . 'cities'; //Good practice
//    $states_table = $wpdb->prefix . 'states'; //Good practice
//	$reviews_table = $wpdb->prefix . 'reviews'; //Good practice
//    $results = $wpdb->get_results("SELECT DISTINCT $camps_table.ID, camp_name, $cities_table.city_name, $states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code JOIN $reviews_table ON $camps_table.ID = $reviews_table.camp_id WHERE $reviews_table.review_by = $user->ID AND $camps_table.camp_created_by != $user->ID");
//    $array = json_decode(json_encode($results), True);
//    return $array;
//}
// $userw = wp_get_current_user();
//$test_review=get_camps_by_id($userw);
//@die;
// review15-8-16 ends
function get_overall_rating($sizeOfReviews, $campId) {
    global $wpdb;
    $reviews_table = $wpdb->prefix . 'reviews'; //Good practice
    $results = $wpdb->get_results("SELECT SUM(review_score) as Rating FROM $reviews_table WHERE $reviews_table.camp_id = $campId");
    $Rating = round($results[0]->Rating, 2);
    if ($sizeOfReviews > 0) {
        $number = $Rating / $sizeOfReviews;
    } else {
        $number = 0;
    }
    $overallRating = number_format((float) $number, 1, '.', '');

    return $overallRating;
}
?>
                        <!-- END OF AHMAD BAJWA'S 2ND CODE  -->
                        <!-- START OF AHMAD BAJWA'S code -->
<?php

function get_recent_viewed_camp() {
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $results = $wpdb->get_results("SELECT   $camps_table.ID, $camps_table.camp_name, $camps_table.camp_images,$cities_table.city_name,$states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id=$cities_table.ID JOIN $states_table ON $cities_table.state_code=$states_table.state_code ORDER BY $camps_table.camp_recently_viewed DESC LIMIT 10");
    $array = json_decode(json_encode($results), True);
    return $array;
}

$getting_recent_view_data = get_recent_viewed_camp();
?>
                    <!--</div>-->
                    <div class="col-sm-12 font_recent">
                        <?php
                        $count = 1;
                        foreach ($getting_recent_view_data as $value) {
                            $c_id = $value['ID'];
                            $c_name = $value['camp_name'];
                            $reviewsOfRecentCamp = get_reviews_by_id($value['ID']);
                            $totalReviewsOfRecentCamp = count($reviewsOfRecentCamp);
                            $recentCampOverAllRating = get_overall_rating($totalReviewsOfRecentCamp, $value['ID']);
                            ?>
                            <div class="<?php echo ($count % 2 == 0) ? "recent_bg" : "recent_bg2"; ?>">
                                <div class="col-sm-7" style="padding-left:5px;"><a href="<?php echo get_site_url() ?>/camp?id=<?php echo $c_id; ?>"><?php echo $c_name; ?></a></div>
                                <div class="col-sm-5 text-right"><a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $value['city_name'] ?>"><?php echo $value['city_name']; ?>,</a> <a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $value['state_name'] ?>"><?php echo $value['state_name']; ?> 
                                        &nbsp;<span class="campground-badge-stat pull-right star-dec" itemprop="ratingValue">
                                            <?php echo $recentCampOverAllRating; ?>/10</span>
                                        <div>
                                        </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                            $count++;
                        }
//endforeach;
                        ?>    
                        <!-- end  OF AHMAD BAJWA'S code -->
                        <hr>
                        <div class="row padding0">
                            <div class="col-sm-12 col-xs-12 blog_hd" style="
                                 font-size: 26px;
                                 "> Plan Your Trip
                                <div class="clear10"></div>
                                <img src="<?php echo get_template_directory_uri() ?>/images/img_btm_hd.png" alt="" style="
                                     margin-bottom: 12px;
                                     ">
                            </div>
                        </div>
                        <hr>
                        <div class="row padding0">
                            <div class="col-sm-12 col-xs-12 blog_hd" style="
                                 font-size: 26px;
                                 "> Previous Campsite Stays
                                <div class="clear10"></div>
                                <img src="<?php echo get_template_directory_uri() ?>/images/img_btm_hd.png" alt="" style="
                                     margin-bottom: 12px;
                                     ">
                            </div>
                            <div class="col-sm-12 font_recent">

                                <?php
                                $count = 1;
                                if (!empty($stayedCamps)) {
                                    foreach ($stayedCamps as $value):
                                        $reviewsOfStayedCamp = get_reviews_by_id($value['ID']);
                                        $totalReviewsOfStayedCamp = count($reviewsOfStayedCamp);
                                        $stayedCampOverAllRating = get_overall_rating($totalReviewsOfStayedCamp, $value['ID']);
                                        ?>
                                        <div class="<?php echo ($count % 2 == 0) ? "recent_bg" : "recent_bg2"; ?>">
                                            <div class="col-sm-4" style="padding-left:5px;"><a href="<?php echo get_site_url() ?>/camp?id=<?php echo $value['ID'] ?>"><?php echo $value['camp_name']; ?></a></div>
                                            <div class="col-sm-2">
                                                <?php if($user->ID == $value['camp_created_by']){ ?>
                                                <a href="<?php echo get_site_url() ?>/editcamp?id=<?php echo $value['ID'] ?>">Edit</a> 
                                                <a style="padding-left:5px;" href="" onclick="getConfirmation(<?php echo $value['ID'] ?>)">Delete</a><?php }?>
                                            </div>        
                                            <div class="col-sm-4"><a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $value['city_name'] ?>"><?php echo $value['city_name']; ?>,</a> <a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $value['state_name'] ?>"><?php echo $value['state_name']; ?></a> &nbsp;<span class="campground-badge-stat pull-right star-dec" itemprop="ratingValue">
                                                    <?php echo $stayedCampOverAllRating; ?>/10</span></div>
                                            <div class="col-sm-2 text-right">
                                                <a href="<?php echo get_site_url() ?>/editcampreviews?id=<?php echo $value['ID'] ?>">Review/Edit</a>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <?php
                                        $count++;
                                    endforeach;
                                }
//								// start of ahmad's review15-8-16 code
//								// $count = 1;
//                                if (!empty($test_review)) {
//                                    foreach ($test_review as $value):
//                                        $reviewsOfStayedCamp = get_reviews_by_id($value['ID']);
//                                        $totalReviewsOfStayedCamp = count($reviewsOfStayedCamp);
//                                        $stayedCampOverAllRating = get_overall_rating($totalReviewsOfStayedCamp, $value['ID']);
//                                        ?>
<!--                                        <div class="//<?php echo ($count % 2 == 0) ? "recent_bg" : "recent_bg2"; ?>">
                                            <div class="col-sm-6" style="padding-left:5px;"><a href="//<?php echo get_site_url() ?>/camp?id=<?php echo $value['ID'] ?>"><?php echo $value['camp_name']; ?></a></div>
                                           <div class="col-sm-2">
                                                <a href="//<?php echo get_site_url() ?>/editcamp?id=<?php echo $value['ID'] ?>"></a> 
                                                <a style="padding-left:5px;" href="" onclick="getConfirmation(//<?php echo $value['ID'] ?>)"></a>
                                            </div>      
                                            <div class="col-sm-4 text-right"><a href="//<?php echo get_site_url() ?>/grandsearch?query=<?php echo $value['city_name'] ?>"><?php echo $value['city_name']; ?>,</a> <a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $value['state_name'] ?>"><?php echo $value['state_name']; ?></a> &nbsp;<span class="campground-badge-stat pull-right star-dec" itemprop="ratingValue">
                                                    //<?php echo $stayedCampOverAllRating; ?>/10</span></div>
                                            <div class="clearfix"></div>
                                        </div>-->
                                        <?php
//                                        $count++;
//                                    endforeach;
//                                }
//								
//								
//								// end of ahmad's review15-8-16 code
                                if(empty($stayedCamps) && empty($test_review))
								{
                                    echo '<div>';
                                    echo '<strong>Message</strong>: ';
                                    echo 'No campgrounds exist.<br/>';
                                    echo '</div>';
                                }

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
                        <hr>       
                    </div>
                    <!--                             Blog Sidebar Widgets Column -->
                </div>
                <!-- /.row -->
                <div class="clear20"></div>
                <div class="alpaca-form-buttons-container pull-right">
                    <button class="alpaca-form-button alpaca-form-button-submit btn btn-primary btn_blue_new pull-left" type="submit" id="uploadimage" name="submit" data-key="submit">Save Changes</button>
                    <button class="alpaca-form-button alpaca-form-button-reset btn btn-default btn_blue_new cancel pull-left" type="reset" id="reset" data-key="reset">Cancel</button>
                </div>
            </div><!--Div container-fluid ends here-->
        </div><!--Div container white_box starts from here-->
        <div class="clear40"></div>
    </form><!--Form ends here-->
</div><!--Div middle_bg ends here-->

</div>
<div class="clear"></div>
<?php
get_footer();
?>
<script>
$("#manageProfile :input").change(function() {
   $("#manageProfile").data("changed",true);
});
$('#manageProfile').submit(function(e){
 if ($("#manageProfile").data("changed")) {
   // submit the form
   return true;
}
else
{ 
    alert("Please update any field before saving changes");
    return false;
}
});
$("#reset").click(function() {
$("#manageProfile").trigger('reset');
});
</script>

<script type="text/javascript">
    function getConfirmation(id) {
        var txt_filter = id;
        //console.log(txt_filter);
        var retVal = confirm("Do you want to continue ?");
        if (retVal == false) {
            return false;
        }
        else {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo get_site_url() ?>/city",
                data: {deleted: txt_filter},
                success: function (data) {
                    //console.log(data);
                    alert("Camp not deleted succefully");
                },
                error: function (data) {
                    // Stuff
                    //console.log(data);  
                    alert("Camp deleted succefully");
                }
            });
            //location.reload();
        }
    }
</script>

<script type="text/javascript">

    $("#file").change(function () {
        $("#message").empty(); // To remove the previous error message
        var file = this.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
        {
            $('#previewing').attr('src', '<?php echo get_template_directory_uri() ?>/images/default.jpg');
            $("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
            return false;
        }
        else
        {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
    function imageIsLoaded(e) {
        $('#previewing').attr('src', e.target.result);
    }
    ;
</script>

</body>
</html>
