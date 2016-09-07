<?php
/* 
Template Name: Edit_Review
 */
//function starts from here
function upload_image($data)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    $j = 0;     // Variable for indexing uploaded image.
    if(get_site_url() == "http://localhost/campsite" || get_site_url() == "http://hadi/campsite")
    {
$target_path = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/"; // Target path where file is to be stored
    }
    else
    {
       $target_path = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/"; // Target path where file is to be stored
    }

    
    $imagesNames = array();
    if(count($data['file']['name']) > 5)
    {
        $reg_errors->add('Error', 'Max 5 images are allowed');
        
    }
    else
    {
        for ($i = 0; $i < count($data['file']['name']); $i++) 
        {
            // Loop to get individual element from the array
            $validextensions = array("jpeg", "jpg", "png");      // Extensions which are allowed.
            $ext = explode('.', basename($data['file']['name'][$i]));   // Explode file name from dot(.)
            $file_extension = end($ext); // Store extensions in the variable.

            $date = new DateTime();
            $timestamp = $date->getTimestamp();
            $file_name_without_extension = $ext[0];
            $updated_file_name = $file_name_without_extension . '_' . $timestamp . '.' . $file_extension;

            $target_path1 = $target_path . $updated_file_name;

            //$target_path1 = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];// Set the target path with a new name of image.
            //print_r($target_path); @die;
            //$imagesNames = $target_path;
                 // Increment the number of uploaded images according to the files in array.
            // Approx. 100kb files can be uploaded.
            if (($data["file"]["size"][$i] < 1000000) && in_array($file_extension, $validextensions)) 
            {
                if (move_uploaded_file($data['file']['tmp_name'][$i], $target_path1)) 
                {
                    $imagesNames[$j] = $updated_file_name;
                    $j = $j + 1; 
                    // If file moved to uploads folder.
                    $reg_errors->add('Success', 'Images uploaded successfully');
                    //$target_path = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/";
                } 
                else 
                {     //  If File Was Not Moved.
                    $reg_errors->add('Error', 'Images not uploaded successfully');
                }
            } 
            else 
            {     //   If File Size And File Type Was Incorrect.
                $reg_errors->add('Error', 'Check images type or size');
            }
            
        }
    }
    //print_r($imagesNames);
    //@die;
    $array = json_encode($imagesNames);
    return $array;
}
function get_id_user_for_edit_problem($id_ep)
{
global $wpdb;
$table_reviews = $wpdb->prefix."reviews";
$user_ep = $wpdb->get_row("SELECT review_by from $table_reviews WHERE ID = $id_ep");
return $user_ep;
}
function get_review_details_by_id($reviewId)
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $reviews_table = $wpdb->prefix. 'reviews'; //Good practice
    $results = $wpdb->get_row("SELECT $camps_table.camp_name, $camps_table.states_code, date_stay, length_stay, camp_scene, camp_location,camp_friendly, camp_privacy, camp_clean, camp_bug, camp_desc, $reviews_table.camp_sites, camp_tip, review_images  FROM $reviews_table JOIN $camps_table ON $reviews_table.camp_id = $camps_table.ID WHERE $reviews_table.ID = $reviewId");      
//    $wpdb->show_errors();
//    $wpdb->print_error(); @die;
    $array = json_decode(json_encode($results), True);
    return $array;
}
function get_states()
{
global $wpdb;
$states_table = $wpdb->prefix . 'states'; //Good practice
$randomFact = $wpdb->get_results( "SELECT * FROM $states_table");
return $randomFact;
}
function edit_review_validation($data)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    
    if ( empty( $data['country'] ) || empty( $data['state']) || empty( $data['camp']) || empty( $data['stay_date']) || empty( $data['scene']) || empty( $data['location'] ) || empty( $data['family'] ) || empty( $data['privacy'] ) || empty( $data['clean'] ) || empty( $data['bug'] ) || empty( $data['description'] )) 
    {
        //$reg_errors->add('field', 'Required form field is missing');
        return false;
    }
    
    if ( is_wp_error( $reg_errors ) && !empty($reg_errors->errors))
    {
        return false;
    }
    else
    { 
        return true;
    }
}
function edit_review_data($data)
{
    $reviewId = filter_input(INPUT_GET, 'id');
    global $wpdb;
    $table = $wpdb->prefix."reviews";
    
    $overall_score = ($data['scene'] + $data['location'] + $data['family'] + $data['privacy'] + $data['clean'] + $data['bug']) / 6;
    
    //print_r($over_score);
//    /@die;
    
    $result = $wpdb->update($table, array(
                            'date_stay' => $data['stay_date'],
                            'length_stay' => $data['length_stay'],
                            'camp_scene' => $data['scene'],
                            'camp_location' => $data['location'],
                            'camp_friendly' => $data['family'],
                            'camp_privacy' => $data['privacy'],
                            'camp_clean' => $data['clean'],
                            'camp_bug' => $data['bug'],
                            'camp_desc' => $data['description'],
                            'camp_sites' => $data['sites'],
                            'camp_tip' => $data['tip'],
                            'review_images' => $data['images'],
                            'review_images_count' => $data['reviewImagesCount'],
                            'review_score' => $overall_score  
                            ),
                            array( 'ID' => $reviewId )
    );
//    $wpdb->show_errors();
//    $wpdb->print_error();
//    print_r($result);
//    @die;
    return $result;
}
function get_previous_images()
{
    $reviewID = filter_input(INPUT_GET, 'id');
    global $wpdb;
    $table = $wpdb->prefix."reviews";
    $randomFact = $wpdb->get_row( "SELECT review_images FROM $table WHERE ID = $reviewID");
    //$images = json_decode($randomFact->camp_images);
    return $randomFact->review_images;
}
function get_and_unlink_previous_images()
{
    $images = json_decode(get_previous_images());
    $imagesUnliked = true;
    if(isset($images))
    {
        if (get_site_url() == "http://hadi/campsite" || get_site_url() == "http://localhost/campsite") {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/";
        } else {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/";
        }
        foreach($images as $value)
        {
            $targetPathWithImage = $targetPath.$value;
            unlink($targetPathWithImage);
        }
    }
    return $imagesUnliked;
}
//function ends here
//header code starts from here
get_header();
if($_POST['review_hidden'] == 'Y')
{ 
    global $reg_errors;
    $reg_errors = new WP_Error;
    //check if form was submitted
    $reviewEdited = FALSE;
    if ($_FILES["file"]["error"][0] == 0) 
    {
        if(get_and_unlink_previous_images())
        {
            $uploadedImages = upload_image($_FILES);
            $reviewImagesCount = sizeof(json_decode($uploadedImages));
            $_POST['images'] = $uploadedImages;
            $_POST['reviewImagesCount'] = $reviewImagesCount;
        }
        $reviewEdited = True; 
    }
    else
    {
        $reviewImagesCount = sizeof(json_decode(get_previous_images()));
        $_POST['images'] = get_previous_images();
        $_POST['reviewImagesCount'] = $reviewImagesCount;
    }
    if(edit_review_validation($_POST))
    {
        if(edit_review_data($_POST))
        {
            $reviewEdited = True;    
        }
    }
    if($reviewEdited)
    {
        $reg_errors->add('message', "<div class='alert alert-success' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>Review updated successfully</div>");
        //$reg_errors->add( 'failed', 'Registeration failed' );
    }
    else
    {
        $reg_errors->add('message', "<div class='alert alert-danger' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>Review not updated successfully</div>");
    }
}


if(!is_user_logged_in())
{
    $location = get_site_url()."/login";
    wp_redirect($location);
}
else
{
   if(isset($_GET['id']))
   {
       $user = wp_get_current_user();
       $ep_id = get_id_user_for_edit_problem($_GET['id']);
       
       if($user->ID == $ep_id->review_by)
       {
           $review = get_review_details_by_id($_GET['id']);
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
//header code ends here?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

<div class="col-sm-12 col-xs-12 blog_hd"> Edit Review
    <div class="clear10"></div>
    <img src="<?php echo get_template_directory_uri()?>/images/img_btm_hd.png" alt="">
   </div>

    <div class="container padding0">
        <div class="container padding0">
            <div class="col-sm-8 col-xs-12 col-sm-offset-2">
                <div class="white_box_login">
    
    <?php 
    if ( is_wp_error( $reg_errors ) && !empty($reg_errors->errors))
    {
        //print_error($reg_errors);
        foreach ( $reg_errors->get_error_messages() as $error ) 
        {

//            echo '<div>';
//            echo '<strong>Message</strong>: ';
            echo $error;
//            echo '</div>';

        }
    }
    ?>
    
    <form action="" method="POST" enctype="multipart/form-data" onsubmit="var text = document.getElementById('desc').value; if(text.length < 250) { alert('Review detail must contain 250 characters!'); return false; } return true;">
    <input type="hidden" name="review_hidden" value="Y">
    <div class="col-sm-3 col-xs-12 lable_login">Country  <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><input id="country" name="country" required="required" readonly="readonly" type="text" class="form-control" value="United States" /></div>
  <div class="clear20"></div>
  <?php $selected_state; 
            if(!empty(get_states())){
            foreach (get_states() as $state) :
            if($review['states_code'] == $state->state_code)
                {
                $selected_state = $state->state_name;
                }
            endforeach;
            };?>
    <div class="col-sm-3 col-xs-12 lable_login">State <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><input id="select" name="state" required="required" readonly="readonly" type="text" class="form-control" value="<?php echo $selected_state;?>" /></div>
  <div class="clear20"></div>
 <div class="col-sm-3 col-xs-12 lable_login">Name of Campsite  <span style="color:#F00;">*</span></div>
           <div class="col-sm-9 col-xs-12 ">
               <input id="select_camp" name="camp" required="required" readonly="readonly" type="text" class="form-control" value="<?php echo $review['camp_name'];?>" />
           </div>
   
           
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Date of Stay<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  required="required" name="stay_date" class="form-control" type="text" id="datepicker" placeholder="Select Date" value="<?php echo $review['date_stay'];?>"/></div>
  
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Length of Stay</div>
   <div class="col-sm-9 col-xs-12 "><input placeholder="0" name="length_stay" class="form-control" type="number"  min="0" value="<?php echo $review['length_stay'];?>"/><p class="help-block">In nights</p></div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Scenic Beauty <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder=" How beautiful was the nature around this campground?"name="scene" class="form-control" type="number" step="0.01" min="0" max="10" value="<?php echo $review['camp_scene'];?>"/><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Location<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Did this campground have a great location? Was it hard to access? etc." name="location" class="form-control" type="number"  step="0.01"  min="0" max="10" value="<?php echo $review['camp_location'];?>"/><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Family Friendly <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Is this a great place for kids and people of all ages?" name="family" class="form-control" type="number" step="0.01" min="0" max="10" value="<?php echo $review['camp_friendly'];?>"/><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Privacy<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Think about the noise during quiet hours, the space between sites, etc." name="privacy" class="form-control" type="number" step="0.01" min="0" max="10" value="<?php echo $review['camp_privacy'];?>"/><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Cleanliness <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Consider the bathrooms, the sites themselves, the trails nearby, etc." name="clean" class="form-control" type="number" step="0.01"  min="0" max="10" value="<?php echo $review['camp_clean'];?>"/><p class="help-block">Out of 10</p>
  </div>  
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Bug Factor <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Add bug factor"  name="bug" class="form-control" type="number" step="0.01" min="0" max="10" value="<?php echo $review['camp_bug'];?>"/><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Description <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><textarea id="desc"  required name="description" maxlength="1000" rows="6" cols="50" placeholder="Tell us about your experience" class="alpaca-control form-control"><?php echo $review['camp_desc'];?></textarea>
       <p class="help-block">Minimum 250 & Maximum 1000 Characters</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Favorite Site(s) or Loop </div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Add favorite sites or loop" name="sites" class="form-control" type="number" min="0" value="<?php echo $review['camp_sites'];?>"/>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Add a tip</div>
   <div class="col-sm-9 col-xs-12 "><textarea id="tip" name="tip" maxlength="250" rows="3" cols="50" placeholder="Do you have any quick tips about the campground or area?" class="alpaca-control form-control" ><?php echo $review['camp_tip'];?></textarea>
       <p class="help-block">Maximum 250 Characters</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Upload Images </div>
  <div class="col-sm-9 col-xs-12 ">
  <header role="banner" class="sidebar-header">
                            <input name="file[]" id="file" type="file" multiple="multiple">
                            <p>Max 5 images..Pick your best shots!</p>
                            <div id="message"></div>
                            <div id="message1"></div>
                        </header>
  </div>
   
  <div class="clear20"></div>
  
      
  <div class="col-sm-9 col-xs-12 col-lg-offset-3">

  
   

  <div class="col-sm-3 col-xs-12 padding0"><a class="btn_blue2" href="#" style="background:#CCC;">Cancel</a> </div>
   <div class="col-sm-3 col-xs-12 padding0"><input class="btn_blue2" type="submit" name="submit" value="Edit Review" style="margin-left:3px;"/></div>
  </div>
    
    </form>

  <div class="clear20"></div>


<div class="clearfix"></div>
</div>
</div>


<div class="col-sm-4 col-xs-12 padding0"></div>
</div>

</div></div>
<div class="clear"></div>
<script type="text/javascript">
    $("#file").change(function () {
        $("#message").empty(); // To remove the previous error message
        console.log(this.files);
        var file = this.files;
        if(file.length > 5)
        {
            $("#message").html("<p id='error'>Please Select Maximum 5 Image Files</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
            return false;
        }
        var match = ["image/jpeg", "image/png", "image/jpg"];
        for (i = 0; i< file.length; i++)
        {
            var file = this.files[i];
            var imagefile = file.type;
            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
            {
                $("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                return false;
            }
        }
if (typeof (FileReader) != "undefined") {
            var dvPreview = $("#message1");
            dvPreview.html("");
            var regex = /(.jpg|.jpeg|.png)$/;
            $($(this)[0].files).each(function () {
                var file = $(this);
                if (regex.test(file[0].name.toLowerCase())) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = $("<img />");
                        img.attr("style", "height:60px;width: 60px;margin-left:4px");
                        img.attr("src", e.target.result);
                        dvPreview.append(img);
                    }
                    reader.readAsDataURL(file[0]);
                } else {
                    alert(file[0].name + " is not a valid image file.");
                    dvPreview.html("");
                    return false;
                }
            });
        } else {
            alert("This browser does not support HTML5 FileReader.");
        }

    });
    
    //START ofAhmad bajwa's code
//    $(function () {
//    $("#file").change(function () {
//        
//    });
//}); 
    
   //END ofAhmad bajwa's code 
    
    
</script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>
<?php get_footer();?>


