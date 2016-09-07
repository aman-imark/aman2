<?php
/* 
Template Name: Edit_Camp
 */

//Function starts from here

function get_states()
{
global $wpdb;
$states_table = $wpdb->prefix . 'states'; //Good practice
$randomFact = $wpdb->get_results( "SELECT * FROM $states_table");
return $randomFact;
}

function upload_image($data)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    $j = 0;     // Variable for indexing uploaded image.
    if(get_site_url() == "http://hadi/campsite" || get_site_url() == "http://localhost/campsite")
    {
$target_path = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/"; // Target path where file is to be stored
    }
    else
    {
       $target_path = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/"; // Target path where file is to be stored
    }

    
    $imagesNames = array();
//    if(count($data['file']['name']) > 4)
//    {
//        $reg_errors->add('Error', 'Max 4 images are allowed');
//        
//    }
//    else
//    {
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
                    //$reg_errors->add('Success', 'Images uploaded successfully');
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
//    }
    //print_r($imagesNames);
    //@die;
    $array = json_encode($imagesNames);
    return $array;
}

function edit_campsite_validation($data)
{
    //print_r($data); @die;
    //global $reg_errors;
    //$reg_errors = new WP_Error;
    
	if ( empty( $data['country'] ) || empty( $data['state']) || empty( $data['camp']) || empty( $data['city']) || empty( $data['type'] ) || empty( $data['price'] ) || empty( $data['climate'] ) || empty( $data['sites'] )|| empty( $data['hookups'] ) || empty( $data['amenities'] ) || empty( $data['website'])) 
	{
            //@die;
            return false;
	}
	else
	{ 
	return true;
	}
}

function edit_campsite_data($data)
{
    //print_r(sizeof($data['images'])); @die;
    $campID = filter_input(INPUT_GET, 'id');
    //$json_months = json_encode($data['months']);
    $json_hookups = json_encode($data['hookups']);
    $json_amenities = json_encode($data['amenities']);
    global $wpdb;
    $table = $wpdb->prefix."camps";
    $result =  $wpdb->update($table, array( 
                            'camp_name' => $data['camp'],
                            'states_code' => $data['state'],
                            'cities_id' => $data['city'],
                            'camp_address' => $data['address'],
                            'camp_phone' => $data['phone'],
                            'camp_type' => $data['type'],
                            'camp_price' => $data['price'],
                            'camp_reserve' => $data['reserve'],
                            'camp_climate' => $data['climate'],
                            'camp_sites' => $data['sites'],
                            'camp_months' => $data['months'],
                            'camp_hookups' => $json_hookups,
                            'camp_amenities' => $json_amenities,
                            'camp_website' => $data['website'],
                            //'camp_postal_code' => $data['code'],
                            'camp_latitude' => $data['lat'],
                            'camp_longitude' => $data['long'],
                            'camp_images' => $data['images'],
                            'camp_images_count' => $data['campImagesCount'],
                            'camp_status' => 1,
                            ), 
                            array( 'ID' => $campID ) 
    );
    
//    $wpdb->show_errors();
//    $wpdb->print_error();
//print_r($result);    @die;
    return $result;
}

function get_park_details_by_id($campId) 
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    $results = $wpdb->get_row("SELECT $camps_table.ID,$camps_table.camp_latitude,$camps_table.camp_longitude,  $camps_table.camp_name, $camps_table.states_code, $camps_table.cities_id, $camps_table.camp_address, $camps_table.camp_type,$camps_table.camp_price,$camps_table.camp_reserve,$camps_table.camp_climate,$camps_table.camp_sites,$camps_table.camp_months,$camps_table.camp_hookups, $camps_table.camp_amenities,$camps_table.camp_phone, $camps_table.camp_website, $camps_table.camp_postal_code, $camps_table.camp_latitude, $camps_table.camp_longitude,$camps_table.camp_images,$camps_table.camp_status, $cities_table.city_name, $states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code WHERE $camps_table.ID = $campId");

    $array = json_decode(json_encode($results), True);
    //$array2 = $array[0];
    
    //print_r($array);
//    
   //@die;
    return $array;
}

function get_cities_by_state($data)
{
    global $wpdb;
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $randomFact = $wpdb->get_results( "SELECT * FROM $cities_table WHERE state_code LIKE '$data'");
    return $randomFact;
}

function get_cityId_by_stateCode_cityName($stateCode, $cityName)
{
    global $wpdb;
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $randomFact = $wpdb->get_row( "SELECT ID FROM $cities_table WHERE state_code LIKE '$stateCode' AND city_name LIKE '$cityName'");
    return $randomFact->ID;
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

function get_previous_images()
{
    $campID = filter_input(INPUT_GET, 'id');
    global $wpdb;
    $table = $wpdb->prefix."camps";
    $randomFact = $wpdb->get_row( "SELECT camp_images FROM $table WHERE ID = $campID");
    //$images = json_decode($randomFact->camp_images);
    return $randomFact->camp_images;
}
// Start of Ahmad's edit_problem code
function get_id_user_for_edit_problem($id_ep)
{
global $wpdb;
$table_camps=$wpdb->prefix."camps";
$user_ep=$wpdb->get_row("SELECT camp_created_by from $table_camps WHERE ID =$id_ep");
return $user_ep;
}

// END of Ahmad's edit_problem code

//get_and_unlink_previous_images();
//Function ends here

//header code starts from here
get_header();
$authentication = false;
if($_POST['post_hidden'] == 'Y')
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    //print_r($_POST);
      //print_r($_FILES["file"]["error"][0]);
    //@die;
    
    if(isset($_POST['amenities']))
        {
            $sizeOfAmenities = sizeof($_POST['amenities']);
            foreach ($_POST['amenities'] as $key => $value )
            {
                if($value == 'other')
                {
                    $_POST['amenities'][$key] = $_POST['other'];
                }
            }
        }
    
        if(isset($_POST['city']))
        {
            $stateCode = $_POST['state'];
            $cityName = $_POST['city'];
            $cityId = get_cityId_by_stateCode_cityName($stateCode, $cityName);
            $_POST['city'] = $cityId;
            
        }
        
        $json_months = json_encode($_POST['months']);
        
        if(!isset($json_months))
        {
            $json_months = '';
            $_POST['months'] = $json_months;
            //print_r($json_months); @die;
        }
        else
        {
            $_POST['months'] = $json_months;
        }
        if(empty($_POST['address']))
        {
            $_POST['address'] = '';
        }
        if(empty($_POST['reserve']))
        {
            $_POST['reserve'] = 0;
        }
        if(empty($_POST['lat']))
        {
            $_POST['lat'] = '';
        }
        if(empty($_POST['long']))
        {
            $_POST['lat'] = '';
        }
       $campEdited = false;
        //print_r(sizeof($_FILES["file"]["name"])); @die;
       if ($_FILES["file"]["error"][0] == 0) 
       {
            if(get_and_unlink_previous_images())
            {
                $uploadedImages = upload_image($_FILES);
                $campImagesCount = sizeof(json_decode($uploadedImages));
                $_POST['images'] = $uploadedImages;
                $_POST['campImagesCount'] = $campImagesCount;
            }
            $campEdited = true;
       }
        else
        {
            $campImagesCount = sizeof(json_decode(get_previous_images()));
            $_POST['images'] = get_previous_images();
            $_POST['campImagesCount'] = $campImagesCount;
        }
       //print_r($_POST); @die;
       if(edit_campsite_validation($_POST))
        {
           //print_r($_POST); @die;
              if(edit_campsite_data($_POST))
              {
                  //@die;
                  $campEdited = true;
                  //$reg_errors->add('Success', 'Campsite Updated Successfully');
                  //$location = site_url()."/reviews?1";
                  //wp_redirect($location);?>
                  <!--<div class="updated"><p><strong><?php _e('Camp Updated' ); ?></strong></p></div>-->
                  <?php
              }
                
        }
        
        if($campEdited)
        {
            //$reg_errors->add('Success', 'Campsite Updated Successfully');
              $authentication = true;  
                
        }
        else
        {
            $reg_errors->add('Failed', 'Campsite Not Updated Successfully');
        } 
//        @die;
//        //print_r($json_months);
//        //@die;
//        
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
       $ep_id=get_id_user_for_edit_problem($_GET['id']);
       if($user->ID == $ep_id->camp_created_by)
       {
           $camp = get_park_details_by_id($_GET['id']);    
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
//header code ends here

//Arrays starts from here
$campTypes =  array('car' => "Car Camping", 'hike' => "Hike In", 'rvs' => "RVs Only");

$climate =  array('mountain' => "Mountain", 'coastal' => "Coastal", 'forest' => "Forest", 'desert' => "Desert");

$months =  array('january' => "January", 'february' => "February", 'march' => "March", 'april' => "April", 'may' => "May", 'june' => "June", 'july' => "July", 'august' => "August", 'september' => "September", 'october' => "October", 'november' => "November", 'december' => "December");

$hookups =  array('electricity' => "Electricity", 'sewer' => "Sewer", 'water' => "Water",  'none' => "None");

$amenities =  array('pets' => "Pets Allowed", 'play' => "Playground Access", 'wifi' => "Wifi",  'showers' => "Showers", 'flush' => "Flush Toilets", 'vault' => "Vault Toilets", 'other' => "Other");

$status = array(1 => "Active", 0 => "Inactive");

$reserve = array(1 => "Yes", 0 => "No");

//Array ends here
//print_r(json_decode($camp['camp_months'])); @die;
?>
<!--Style starts from here-->                   
<style type="text/css">     
    select {
        width:200px;
    }
    input {
        width:200px;
    }
    .multiselect-container>li>a>label>input[type=checkbox] {
    margin-left: -120px !important;
}
.ui-autocomplete { max-height: 200px; overflow-y: scroll; overflow-x: hidden;}

</style>                   
<!--Style ends here-->

<!--Div wrap starts from here-->
<div class="clearfix"></div>



<!--<div style="background:#ff7444; padding:20px; text-align:center; font-size:35px; color:#fff; font-weight:bold;">
 Add Camp Site <br />

   <a href="#" style="font-size:14px; color:#fff;">  Back to Submit Review
  </a>
</div>-->

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

<div class="col-sm-12 col-xs-12 blog_hd"> Edit Camp Site
    <div class="clear10"></div>
    <img src="<?php echo get_template_directory_uri() ?>/images/img_btm_hd.png" alt="">
   </div>

	<div class="container padding0">



<div class="container padding0">

<div class="col-sm-8 col-xs-12 col-sm-offset-2">
<div class="white_box_login">
    
    
    <?php 
    global $reg_errors;
    $reg_errors = new WP_Error;
    if ( is_wp_error( $reg_errors ) && !empty($reg_errors->errors))
    {
        //print_error($reg_errors);
        foreach ( $reg_errors->get_error_messages() as $error ) 
        {

           echo "<div class='alert alert-danger' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>$error</div>";

        }
    }
    else if(count($reg_errors->get_error_messages()) < 1 && $_POST['post_hidden'] == 'Y' && $authentication)
    {
        $success = "Campsite updated successfully";
        echo "<div class='alert alert-success' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>$success</div>";
    }
    
    ?>
    <?php 
//    if ( is_wp_error( $reg_errors ) && !empty($reg_errors->errors))
//    {
//        //print_error($reg_errors);
//        foreach ( $reg_errors->get_error_messages() as $error ) 
//        {
//
//            echo '<div>';
//            echo '<strong>Message</strong>: ';
//            echo $error . '<br/>';
//            echo '</div>';
//
//        }
//    }
//    ?>
    
    
    
    <form action="" id="add-camp-form" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="post_hidden" value="Y">
    <div class="col-sm-3 col-xs-12 lable_login">Country  <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><input id="country" name="country" required="required" readonly="readonly" type="text" class="form-control" value="United States" /></div>
  <div class="clear20"></div>
    <div class="col-sm-3 col-xs-12 lable_login">State or Region  <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><select id="select" required="required" class="form-control" name="state">
            <option value="">Select State</option>
        <?php if(!empty(get_states())):
            foreach (get_states() as $state) :
            ?>
            <option value="<?php echo $state->state_code ?>"<?php if($camp['states_code'] == $state->state_code){echo("selected");}?>><?php echo $state->state_name ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></div>
  <div class="clear20"></div>
 <div class="col-sm-3 col-xs-12 lable_login">Name of Campsite  <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 "><input id="park" required="required" name="camp" type="text" class="form-control" value="<?php echo $camp['camp_name']?>" /></div>
  <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Closest Town  <span style="color:#F00;">*</span></div>
           <div class="col-sm-9 col-xs-12 ">
               
               <?php 
               if(isset($camp['states_code']))
                {
                    $cities = get_cities_by_state($camp['states_code']);
                    if(!empty($cities))
                     {
                         foreach ($cities as $city) :
                             if($camp['cities_id'] == $city->ID)
                             {
                                 ?>
                    <input id="select_city" required="required" name="city" placeholder="Type a City" type="text" class="form-control" value="<?php echo $city->city_name ?>" />
                    <?php
                             }
                      endforeach;
                     }
                }
               
               ?>
               
               
            <?php 
//           if(!empty(get_cities_by_state($selectedState)))
//           {
//               //print_r(get_cities_by_state($selectedState));
//               ?>
<!--               <select id="select_city" required="required" class= "form-control" name="city">
                   <option value="">Select City</option>-->
               <?php
//            foreach (get_cities_by_state($selectedState) as $city) :
//            
//                ?>
            <!--<option value="//<?php echo $city->ID ?>"><?php echo $city->city_name ?></option>-->
            <?php
//            endforeach;
//            ?>
            <!--</select>-->
               <?php
//               
//           }
//           else
//           {
//                ?>
            <!--<select id="select_city" required="required" class="form-control" name="city"></select>-->
            <?php   
//           }
           ?> 
           </div>
<!--           <a id="fetch-geodata" type="button" class="btn btn-primary btn-sm" style="margin-left: 172px; margin-top: 12px;">Fetch Geodata</a>-->
   
  <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Campsite Address</div>
   <div class="col-sm-9 col-xs-12 "><input  name="address" class="form-control" type="text" value="<?php echo $camp['camp_address']?>" /></div>
   
    <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Latitude <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required name="lat" class="form-control" type="number" value="<?php echo $camp['camp_latitude']?>"/></div>
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Longitude <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required name="long" class="form-control" type="number" value="<?php echo $camp['camp_longitude']?>"/></div>
   
<!--    <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Camp Phone <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  name="phone" required="required" class="form-control" type="text" />
  e.g., 555-000-5555 or 5550005555
  </div>-->
      <div class="clear20"></div>

   <div class="col-sm-3 col-xs-12 lable_login">Type of Campsite<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><select id="type"  class="form-control" required="required" name="type">
            <option value="">Select Type</option>
        <?php foreach ($campTypes as $key => $value) :
            ?>
            <option value="<?php echo $key ?>" <?php if($camp['camp_type'] == $key){echo("selected");}?>><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Price per Night <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  required="required" value="<?php echo $camp['camp_price']?>" name="price" class="form-control" type="number" min="0"/></div>
   
   <!-- start of ahmad's code -->
    <div class="clear20"></div>

   <div class="col-sm-3 col-xs-12 lable_login">Reservations Accepted </div>
   <div class="col-sm-9 col-xs-12 "><select id="reserve"  class="form-control" name="reserve" >
           <option value="">Select Reservations</option>
        <?php foreach ($reserve as $key => $value) :
            ?>
            <option value="<?php echo $key ?>" <?php if($camp['camp_reserve'] == $key){echo("selected");}?>><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></div>
   
  <!-- end of ahmad's code -->
   
   <div class="clear20"></div>
    <div class="col-sm-3 col-xs-12 lable_login">Climate <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><select id="climate"  class="form-control" required="required" name="climate">
            <option value="">Select Climate</option>
        <?php foreach ($climate as $key => $value) :
            ?>
            <option value="<?php echo $key ?>" <?php if($camp['camp_climate'] == $key){echo("selected");}?>><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></div>
   
	<div class="clear20"></div>
	<div class="col-sm-3 col-xs-12 lable_login">Number of Sites <span style="color:#F00;">*</span></div>
	<div class="col-sm-9 col-xs-12 "><input required="required"  value="<?php echo $camp['camp_sites']?>" name="sites" class="form-control" type="number" min="0"/></div>

     <div class="clear20"></div>
     
      <div class="col-sm-3 col-xs-12 lable_login">Best Months to Visit</div>
      <div class="col-sm-9 col-xs-12 "><select id="month" multiple="multiple" class="form-control" name="months[]">
        <?php if(!empty($camp['camp_months'])):
            foreach ($months as $key => $value) :
            ?>
              <option value="<?php echo $key ?>"><?php echo $value ?></option>
            <?php
            endforeach;
            endif;?>
       </select></div>
     <div class="clear20"></div>
     
     <div class="col-sm-3 col-xs-12 lable_login">Hookups Available <span style="color:#F00;">*</span></div>
      <div class="col-sm-9 col-xs-12 "><select id="hookups" required="required" multiple class="form-control" name="hookups[]">
       <?php if(!empty($camp['camp_hookups'])):
           foreach ($hookups as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select>
      
      </div>
     <div class="clear20"></div>
      
      <div class="col-sm-3 col-xs-12 lable_login">Amenities <span style="color:#F00;">*</span></div>
      <div class="col-sm-9 col-xs-12 "><select id="amenities" required="required" multiple class="form-control" name="amenities[]">
       <?php if(!empty($camp['camp_amenities'])):
           foreach ($amenities as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select>
          <input id="other" name="other" type="text" class="form-control" value="" style="display: none; margin-top: 20px;"/>
      </div>
      
     <div class="clear20"></div>

      <div class="col-sm-3 col-xs-12 lable_login">Website to Reserve <span style="color:#F00;">*</span></div>
      <div class="col-sm-9 col-xs-12 "><input name="website" type="text" class="form-control" value="<?php echo $camp['camp_website']?>" /></div>
  <div class="clear20"></div>
  <!--
  <input type="hidden" id="latitude" name="lat" value="">
  <input type="hidden" id="longitude" name="long" value="">
  -->
<!--  <div class="col-sm-3 col-xs-12 lable_login">Postal Code  </div>
  <div class="col-sm-9 col-xs-12 "><input id="zipcode" name="code" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <div class="col-sm-3 col-xs-12 lable_login">Latitude <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 "><input id="latitude" required="required" name="lat" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <div class="col-sm-3 col-xs-12 lable_login">Longitude  <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 "><input id="longitude" required="required" name="long" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>-->
  
  <div class="col-sm-3 col-xs-12 lable_login">Upload Images </div>
  <div class="col-sm-9 col-xs-12 ">
  <header role="banner" class="sidebar-header">
                            <div id="message"></div>
                            <input name="file[]" id="file" type="file" multiple="multiple">
<!--                            <p>Max 5 images are allowed...</p>-->
                        </header>
  </div>
    <div class="clear20"></div>  
  <div class="col-sm-9 col-xs-12 col-lg-offset-3">
      
  <div class="col-sm-3 col-xs-12 padding0" style="width: 98px;"><button class="btn_blue2" type="reset" data-key="reset" id="reset" style="background:#CCC;">Cancel</button> </div>
   <div class="col-sm-3 col-xs-12 padding0"><input class="btn_blue2" type="button" id="submit1" value="Save Changes" style="margin-left:3px;"></div>
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

<!--Div wrap ends here-->

<!--Scripts starts from here-->
<script type="text/javascript">
    $("#add-camp-form :input").change(function() {
   $("#add-camp-form").data("changed",true);
});
$('#submit1').on('click', function(e){
 if ($("#add-camp-form").data("changed")) {
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
$("#add-camp-form").data("changed",false);
});
</script>
<script type="text/javascript">
    $('#month').multiselect({
       dropRight: true,
       includeFilterClearBtn: false,
       buttonWidth: '100%',
       maxHeight: 300,
       includeSelectAllOption: true
});
var data = <?php echo $camp['camp_months']?>;
//Make an array
if(data != null)
{
    var dataarray = data.toString().split(",");
}
else
{
    var dataarray = null;
}

// Set the value
$("#month").val(dataarray);
// Then refresh
$("#month").multiselect("refresh");

    $('#hookups').multiselect({
       dropRight: true,
       includeFilterClearBtn: false,
       buttonWidth: '100%',
       maxHeight: 300,
       includeSelectAllOption: true
       
});
var data = <?php echo $camp['camp_hookups']?>;
//Make an array
var dataarray = data.toString().split(",");
// Set the value
$("#hookups").val(dataarray);
// Then refresh
$("#hookups").multiselect("refresh");

$('#amenities').multiselect({
       dropRight: true,
       includeFilterClearBtn: false,
       buttonWidth: '100%',
       maxHeight: 300,
       includeSelectAllOption: true,
});
var data = <?php echo $camp['camp_amenities']?>;

//Make an array
var dataarray = data.toString().split(",");

// Set the value
$("#amenities").val(dataarray);
// Then refresh
$("#amenities").multiselect("refresh");

    </script>
<script type="text/javascript">
    
     var txt_filter;
    $("#amenities").change(function(){
        txt_filter = $('#amenities').val();
        if(txt_filter != null)
        {
            var data = txt_filter.toString();
            
            if (data.match(/other/g)) {
                $('#other').show("fast");
                //div.style.display = 'none';
            }
            else{
                $('#other').hide("fast");
                //div.style.display = 'block';
            }
        }
        else{
            $('#other').hide("fast");
            //div.style.display = 'block';
        }
        
    //alert(data);
    
    
    
    });
    
//var button = document.getElementById('amenities'); // Assumes element with id='button'
//
//alert(button);
//
//button.onclick = function() {
//    var div = document.getElementById('newpost');
//    if (div.style.display !== 'none') {
//        div.style.display = 'none';
//    }
//    else {
//        div.style.display = 'block';
//    }
//};
</script>

<script type="text/javascript">
    var txt_filter;
    jQuery("#select_city").click(function(){
        txt_filter = jQuery('#select').val();
        //alert(txt_filter);
    if(txt_filter == '')
    {
      alert("Please Select the State First");  
    }
        //alert(txt_filter);
    });
</script>
<script type="text/javascript">
     var txt_filter;
jQuery("#select").change(function(){
    $('#select_city').val('');
    $('#camp_type').val('');
    $('#select_city').attr("placeholder", "Type a City");
    txt_filter = $('#select').val();
     var testingarray1 = [];
    var number_of_names;
    var i;
    //alert(txt_filter);
jQuery.ajax({
  type: "POST",
  url: "<?php echo get_site_url()?>/city",
  data: {selected: txt_filter},
  success: function (data) {
      var arr = JSON.parse(data);
       number_of_names = arr.length;
        for (i = 0; i < number_of_names; i++) {
            testingarray1[i] = arr[i].city_name;
            //console.log(arr[i]);
        }
      jQuery("#select_city").autocomplete({
            source: testingarray1
        });
  },
  error: function(data) {
    // Stuff
  }
});
});

</script>
<script type="text/javascript">
    
     var txt_filter;
jQuery("#select_city").change(function(){
    $('#select_city').val('');
    $('#camp_type').val('');
    $('#select_city').attr("placeholder", "Type a City");
    txt_filter = $('#select').val();
     var testingarray1 = [];
    var number_of_names;
    var i;
    //alert(txt_filter);
jQuery.ajax({
  type: "POST",
  url: "<?php echo get_site_url()?>/city",
  data: {selected: txt_filter},
  success: function (data) {
      
      var arr = JSON.parse(data);
      
       number_of_names = arr.length;
        for (i = 0; i < number_of_names; i++) {
            testingarray1[i] = arr[i].city_name;
            //console.log(arr[i]);
        }
      
      
      
      
      //alert(arr);
//    if(arr.length == ''){
//         $('#select_city').html('<option value="" selected="selected">No City Exist</option>');
//       }
//       else
//       {
//        var resulted_data =  '<option value="">Select City</option>';
//        for(i=0;i<arr.length;i++)
//        {
//            //console.log(data[i]);
//         resulted_data = resulted_data + "<option value = "+arr[i].ID+">"+arr[i].city_name+"</option>"
//
//       }
//      }
      jQuery("#select_city").autocomplete({
            source: testingarray1
        });
//      //alert(resulted_data);
//      $('#select_city').html(resulted_data);
//       }
       
  },
//  {
//      
//      var arr = JSON.parse(data);
//      
//      //alert(arr);
//    if(arr.length == ''){
//         jQuery('#select_city').html('<option value="0" selected="selected">No City Exist</option>');
//       }
//       else
//       {
//       var resulted_data =  '<option value="0">Select City</option>';
//       for(i=0;i<arr.length;i++)
//       {
//           //console.log(data[i]);
//        resulted_data = resulted_data + "<option value = "+arr[i].ID+">"+arr[i].city_name+"</option>"
//        
//      }
//      //alert(resulted_data);
//      jQuery('#select_city').html(resulted_data);
//       }
//       
//  },
  error: function(data) {
    // Stuff
  }
});


});

</script>

<script type="text/javascript">

jQuery('#submit1').on('click', function(){
  //$('#select_city').select2();
        var country = "us";//$('#country').val();
        var region = $('#select :selected').text();
        //var region1 = $('#select :selected').val();
        var city = $('#select_city').val();
        //alert(region);
        //var city1 = $('#select_city :selected').val();
        var park = $('#park').val();
        if(country == '' || region == '' || city == '' || park == ''){
            alert('Please enter country, region, city, and park name');
            return false;
        }
        var url="http://maps.googleapis.com/maps/api/geocode/json?address=";
         jQuery.getJSON(url+encodeURI(park)+'&'+region, function(data){
            if(data.status != 'OK'){
                alert('Geodata retrieval failed.');
                return false;
            }
            var place = data.results[0];
            var street_number, route;
            jQuery.each(place.address_components, function(k, v){
                if(jQuery.inArray('postal_code', v.types) >= 0 ){
                    jQuery('#zipcode').val(v.long_name);
                }
//                if($.inArray('establishment', v.types) >= 0 ){
//                    $('#park').val(v.long_name);
//                }
//                if($.inArray('street_number', v.types) >= 0 ){
//                    street_number = v.long_name;
//                }
//                if($.inArray('route', v.types) >= 0 ){
//                    route = v.long_name;
//                }
            });

//            if (typeof place.formatted_phone_number != 'undefined') {
//                $('#phone').val(place.formatted_phone_number);
//            }
//            if (typeof place.website != 'undefined') {
//                $('#website').val(place.website);
//            }
            jQuery('#address-formatted').show().html('<b>'+place.formatted_address+'</b>');
            if(typeof street_number != 'undefined' && typeof route != 'undefined') {
                jQuery('#address').val(street_number+' '+route);
            }
            
           // jQuery('#latitude').val(place.geometry.location.lat);
          //  jQuery('#longitude').val(place.geometry.location.lng);
            jQuery('#add-camp-form').submit();
            //alert(place.geometry.location.lat);
        });
        
    });

</script>

<script type="text/javascript">

    jQuery("#file").change(function () {
        jQuery("#message").empty(); // To remove the previous error message
        //console.log(this.files);
        var file = this.files;
        //console.log(file.length);
        //for(file)
        
//        if(file.length > 4)
//        {
//            jQuery("#message").html("<p id='error'>Please Select Maximum 4 Image Files</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
//            return false;
//        }
        var match = ["image/jpeg", "image/png", "image/jpg"];
        for (i = 0; i< file.length; i++)
        {
            var file = this.files[i];
            var imagefile = file.type;
            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
            {
                jQuery("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                return false;
            }
        }
//        
//        var imagefile = file.type;
//        
//        var match = ["image/jpeg", "image/png", "image/jpg"];
//        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
//        {
//            $("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
//            return false;
//        }
//        else
//        {
//            var reader = new FileReader();
//            reader.onload = imageIsLoaded;
//            reader.readAsDataURL(this.files[0]);
//        }
    });
    
</script>

<!--Scripts ends here-->


<?php

get_footer();
?>