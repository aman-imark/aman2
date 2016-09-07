<?php

/* 
    Template Name: Review_users
 */
    
function add_campsite_validation($data)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    
	if ( empty( $data['country'] ) || empty( $data['state']) || empty( $data['camp']) || empty( $data['city']) || empty( $data['address']) || empty( $data['phone']) || empty( $data['type'] ) || empty( $data['price'] ) || empty( $data['climate'] ) || empty( $data['sites'] )|| empty( $data['months'] ) || empty( $data['hookups'] ) || empty( $data['amenities'] ) || empty( $data['lat'] ) || empty( $data['long'] )) 
	{
	$reg_errors->add('field', 'Required form field is missing');
	}
	if($data['price'] < 0)
	{
		$reg_errors->add('Error', 'Price can not be negative');
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

function add_campsite_data($data)
{
    $user = wp_get_current_user();
    $json_months = json_encode($data['months']);
    $json_hookups = json_encode($data['hookups']);
    $json_amenities = json_encode($data['amenities']);
    global $wpdb;
    $table = $wpdb->prefix."camps";
    $result = $wpdb->insert($table, array(
                            'camp_name' => $data['camp'],
                            'states_code' => $data['state'],
                            'cities_id' => $data['city'],
                            'camp_address' => $data['address'],
                            'camp_phone' => $data['phone'],
                            'camp_type' => $data['type'],
                            'camp_price' => $data['price'],
                            'camp_climate' => $data['climate'],
                            'camp_sites' => $data['sites'],
                            'camp_months' => $json_months,
                            'camp_hookups' => $json_hookups,
                            'camp_amenities' => $json_amenities,
                            'camp_website' => $data['website'],
                            'camp_postal_code' => $data['code'],
                            'camp_latitude' => $data['lat'],
                            'camp_longitude' => $data['long'],
                            'camp_images' => $data['images'],
                            'camp_created_by' => $user->ID    
                            )
    );

    return $result;
}

function upload_image($data)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    $j = 0;     // Variable for indexing uploaded image.
    if(get_site_url() == "http://localhost/campsite")
    {
$target_path = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/"; // Target path where file is to be stored
    }
    else
    {
       $target_path = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/"; // Target path where file is to be stored
    }

    
    $imagesNames = array();
    if(count($data['file']['name']) > 4)
    {
        $reg_errors->add('Error', 'Max 4 images are allowed');
        
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

function get_states()
{
global $wpdb;
$states_table = $wpdb->prefix . 'states'; //Good practice
$randomFact = $wpdb->get_results( "SELECT * FROM $states_table");
return $randomFact;
//print_r($randomFact); @die;

//$NumRows = count((array) $randomFact);

//print $randomFact[$RandNum]->philosopher;
//print $randomFact[$RandNum]->about;
//or return $randomFact[$RandNum]; ?

}

/*
Info: This function return cities of given state id
Return: Cities
Parameter: State ID
*/
function get_cities_by_state($data)
{
    global $wpdb;
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $randomFact = $wpdb->get_results( "SELECT * FROM $cities_table WHERE state_code LIKE '$data'");
    return $randomFact;
}


get_header();

$climate =  array('continental' => Continental, 'dry' => Dry, 'moderate' => Moderate, 'polar' => Polar, 'tropical' => Tropical);

$months =  array('january' => January, 'february' => February, 'march' => March, 'april' => April, 'may' => May, 'june' => June, 'july' => July, 'august' => August, 'september' => September, 'october' => October, 'november' => November, 'december' => December, );

$hookups =  array('electricity' => Electricity, 'water' => Water, 'sewer' => Sewer, 'wifi' => Wifi, 'phone' => Phone, 'cable' => Cable);

$amenities =  array('pets' => "Pets Allowed", 'pool' => "Pool Access", 'play' => "Play Ground", 'club' => "Club House", 'benquet' => "Banquet Facilities");


if(isset($_POST['submit']))
{
    global $reg_errors;
    $reg_errors = new WP_Error;
        //print_r($_POST);
      //print_r($_FILES["file"]["error"][0]);
      //@die;
       if ($_FILES["file"]["error"][0] == 0) 
       {
             $uploadedImages = upload_image($_FILES);
             $_POST['images'] = $uploadedImages;
       }
       if(add_campsite_validation($_POST))
        {
              if(add_campsite_data($_POST))
              {
                  $reg_errors->add('Success', 'Campsite Added Successfully');
                  //$location = site_url()."/reviews?1";
                  //wp_redirect($location);
              }

        }
        else
        {
          $reg_errors->add('Failed', 'Campsite Not Added Successfully');
          //$location = site_url()."/reviews";
            //      wp_redirect($location);
        }    
    
    
        
//        @die;
//        //print_r($json_months);
//        //@die;
//        
 }


if($_GET)
{
    //sprint_r($_GET);
    $selectedState = $_GET['state'];
}

    if(!is_user_logged_in()):
    $location = get_site_url()."/login";
    //auth_redirect();
    //get_custom_url();
    //$location = get_site_url()."/login";
    wp_redirect($location);
//campsite_login();
    endif;
    static $count = 0;
    //add_campsite();
    if($count == 0 && empty($_POST['camp']))
    {
        //add_campsite();
        $count++;
    }
     
    $user = wp_get_current_user();
?>

<!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header padding0">
	<div class="container padding0">
    	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-9 padding0"><a href="index.html"><img src="<?php echo get_template_directory_uri()?>/images/logo.png" class="img-responsive" style="margin-bottom:2px; margin-top:5px;" /></a></div>
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
        <li class="active"><a href="<?php echo get_site_url()?>/reviews">Add Campsite</a></li> 
        <li><a href="<?php echo get_site_url()?>/blog">Owner's Blog</a></li>   
        <li><a href="<?php echo get_site_url()?>/about">About Us </a></li>   
        <li><a href="myaccount.html">My Account</a></li>   
        <?php if(!$user->user_login) :?>
        <li><a href="<?php echo get_site_url()?>/login" style="color:#ff7444 !important;">Login </a></li>   
        <li><a href="<?php echo get_site_url()?>/signup" style="color:#ff7444 !important;">Signup</a></li>  
        <?php elseif(!current_user_can('administrator') && !is_admin()):
            ?>
        <div class="dropdown">
        <button class="dropbtn">Welcome <?php echo $user->user_login;?></button>
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
        
        endif; ?>
      </ul>
      
      
    </div> /.navbar-collapse 
  </div> /.container-fluid 
</nav>
        </div>
    </div>
</div>-->

<!-- Slider Start -->

<!-- Slider End -->
<div class="clearfix"></div>



<!--<div style="background:#ff7444; padding:20px; text-align:center; font-size:35px; color:#fff;">
 Add Camp Site <br />

   <a href="#" style="font-size:14px; color:#fff;">  Back to Submit Review
  </a>
</div>-->

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

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

        echo '<div>';
        echo '<strong>Message</strong>: ';
        echo $error . '<br/>';
        echo '</div>';

    }
        
    }
    
    ?>
    
    
    
    
    <form action="" method="POST" enctype="multipart/form-data">
    
    <div class="col-sm-3 col-xs-12 lable_login">Country  <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><input id="country" name="country" required="required" readonly="readonly" type="text" class="form-control" value="United States" /></div>
  <div class="clear20"></div>
    <div class="col-sm-3 col-xs-12 lable_login">State or Region  <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><select id="select" required="required" class="form-control" name="state">
            <option value="0">Select State</option>
        <?php if(!empty(get_states())):
            foreach (get_states() as $state) :
            ?>
            <option value="<?php echo $state->state_code ?>"<?php if($selectedState == $state->state_code){echo("selected");}?>><?php echo $state->state_name ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></div>
  <div class="clear20"></div>
 <div class="col-sm-3 col-xs-12 lable_login">Camp Name  <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 "><input id="park" required="required" name="camp" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Camp City  <span style="color:#F00;">*</span></div>
           <div class="col-sm-9 col-xs-12 ">
            <?php 
           if(!empty(get_cities_by_state($selectedState)))
           {
               //print_r(get_cities_by_state($selectedState));
               ?>
               <select id="select_city" required="required" class= "form-control" name="city">
                   <option value="0">Select Camp</option>
               <?php
            foreach (get_cities_by_state($selectedState) as $city) :
            
                ?>
            <option value="<?php echo $city->ID ?>"><?php echo $city->city_name ?></option>
            <?php
            endforeach;
            ?>
            </select>
               <?php
               
           }
           else
           {
                ?>
            <select id="select_city" required="required" class="form-control" name="city"></select>
            <?php   
           }
           ?> 
           </div>
           <a id="fetch-geodata" type="button" class="btn btn-primary btn-sm" style="margin-left: 172px; margin-top: 12px;">Fetch Geodata</a>
   
  <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Camp Address  <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  required="required" name="address" class="form-control" type="text" /></div>
    <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Camp Phone <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  name="phone" required="required" class="form-control" type="text" />
  e.g., 555-000-5555 or 5550005555
  </div>
      <div class="clear20"></div>

   <div class="col-sm-3 col-xs-12 lable_login">Camp Type <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><select  class="form-control" required="required" name="type">
       <option value="">- Any Type -</option>
        <option value="commercial"> Commercial</option>
        <option value="national"> National</option>
        <option value="state"> State</option>
        <option value="provincial"> Provincial (Canada)</option>
        <option value="county"> County</option>
        <option value="city"> City</option>
        <option value="coe"> Corps of Engineers</option>
        <option value="dnr"> DNR (Dept of Natural Resources)</option>
        <option value="military"> Military Only</option>
        <option value="members_only"> Club or Membership Required</option>
        <option value="55"> 55+</option>
        <option value="other"> Other</option>
       </select></div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Price (US Dollars) <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  required="required" value="0" name="price" class="form-control" type="number" min="0"/></div>
   
   <div class="clear20"></div>
    <div class="col-sm-3 col-xs-12 lable_login">Climate <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><select id="climate"  class="form-control" required="required" name="climate">
            <option value="0">Select Climate</option>
        <?php foreach ($climate as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></div>
   
	<div class="clear20"></div>
	<div class="col-sm-3 col-xs-12 lable_login">No of Sites <span style="color:#F00;">*</span></div>
	<div class="col-sm-9 col-xs-12 "><input required="required"  value="0" name="sites" class="form-control" type="number" min="0"/></div>

     <div class="clear20"></div>
     
      <div class="col-sm-3 col-xs-12 lable_login">Best Month's to Visit <span style="color:#F00;">*</span></div>
      <div class="col-sm-9 col-xs-12 "><select required="required" id="month" multiple="multiple" class="form-control" name="months[]">
        <?php if(!empty($months)):
            foreach ($months as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
       </select></div>
     <div class="clear20"></div>
     
     <div class="col-sm-3 col-xs-12 lable_login">Hookups Available <span style="color:#F00;">*</span></div>
      <div class="col-sm-9 col-xs-12 "><select required="required" multiple class="form-control" name="hookups[]">
       <?php if(!empty($hookups)):
            foreach ($hookups as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></div>
     <div class="clear20"></div>
      
      <div class="col-sm-3 col-xs-12 lable_login">Amenities <span style="color:#F00;">*</span></div>
      <div class="col-sm-9 col-xs-12 "><select required="required" multiple class="form-control" name="amenities[]">
       <?php if(!empty($amenities)):
            foreach ($amenities as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></div>
     <div class="clear20"></div>

      <div class="col-sm-3 col-xs-12 lable_login">Website  </div>
  <div class="col-sm-9 col-xs-12 "><input name="website" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <div class="col-sm-3 col-xs-12 lable_login">Postal Code  </div>
  <div class="col-sm-9 col-xs-12 "><input id="zipcode" name="code" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <div class="col-sm-3 col-xs-12 lable_login">Latitude <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 "><input id="latitude" required="required" name="lat" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <div class="col-sm-3 col-xs-12 lable_login">Longitude  <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 "><input id="longitude" required="required" name="long" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <div class="col-sm-3 col-xs-12 lable_login">Upload Images  <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 ">
  <header role="banner" class="sidebar-header">
                            <div id="message"></div>
                            <input name="file[]" id="file" type="file" multiple="multiple">
                            <p>Max 4 images are allowed...</p>
                        </header>
  </div>
      
  <div class="col-sm-9 col-xs-12 col-lg-offset-3">

  
   

  <div class="col-sm-3 col-xs-12 padding0"><a class="btn_blue2" href="#" style="background:#CCC;">Cancel</a> </div>
   <div class="col-sm-3 col-xs-12 padding0"><input class="btn_blue2" type="submit" name="submit" value="Add Campsite" style="margin-left:3px;"/></div>
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
        	<p>Copyright © 2000-2016 tentcampsite - All Rights Reserved</p>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
        	<p style="float:right;">Designed & Developed by <a href="http://leadconcept.com/" target="_blank" style="color:#fff;">LEADconcept</a></p>
        </div>
    </div>
</div>
</div>-->
<script type="text/javascript">

    $("#file").change(function () {
        $("#message").empty(); // To remove the previous error message
        console.log(this.files);
        var file = this.files;
        //console.log(file.length);
        //for(file)
        
        if(file.length > 4)
        {
            $("#message").html("<p id='error'>Please Select Maximum 4 Image Files</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
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

<script type="text/javascript">
    var txt_filter;
    jQuery("#select_city").click(function(){
        txt_filter = $('#select').val();
        //alert(txt_filter);
    if(txt_filter == 0)
    {
      alert("Please Select the State First.");  
    }
        //alert(txt_filter);
    });
    </script>

<script type="text/javascript">
    
     var txt_filter;
jQuery("#select").change(function(){
    txt_filter = $('#select').val();
    //alert(txt_filter);
jQuery.ajax({
  type: "POST",
  url: "<?php echo get_site_url()?>/city",
  data: {selected: txt_filter},
  success: function (data) {
      
      var arr = JSON.parse(data);
      
      //alert(arr);
    if(arr.length == ''){
         $('#select_city').html('<option value="0" selected="selected">No City Exist</option>');
       }
       else
       {
       var resulted_data =  '<option value="0">Select City</option>';
       for(i=0;i<arr.length;i++)
       {
           //console.log(data[i]);
        resulted_data = resulted_data + "<option value = "+arr[i].ID+">"+arr[i].city_name+"</option>"
        
      }
      //alert(resulted_data);
      $('#select_city').html(resulted_data);
       }
       
  },
  error: function(data) {
    // Stuff
  }
});


});

</script>

<script type="text/javascript">

jQuery('#fetch-geodata').on('click', function(){
  //$('#select_city').select2();
        var country = "us";//$('#country').val();
        var region = $('#select :selected').text();
        var region1 = $('#select :selected').val();
        var city = $('#select_city :selected').text();
        var city1 = $('#select_city :selected').val();
        var park = $('#park').val();
        if(country == '' || region == '' || city == '' || park == '' || city1 == 0 || region1 == 0){
            alert('Please enter country, region, city, and park name');
            return false;
        }
        var url="http://maps.googleapis.com/maps/api/geocode/json?address=";
         $.getJSON(url+encodeURI(park)+'&components=locality:'+city+'|country:'+country+'|administrative_area:'+region+'&sensor=false', function(data){
            if(data.status != 'OK'){
                alert('Geodata retrieval failed.');
                return false;
            }
            var place = data.results[0];
            var street_number, route;
            $.each(place.address_components, function(k, v){
                if($.inArray('postal_code', v.types) >= 0 ){
                    $('#zipcode').val(v.long_name);
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
            $('#address-formatted').show().html('<b>'+place.formatted_address+'</b>');
            if(typeof street_number != 'undefined' && typeof route != 'undefined') {
                $('#address').val(street_number+' '+route);
            }
            $('#latitude').val(place.geometry.location.lat);
            $('#longitude').val(place.geometry.location.lng);
        });
    });



     



</script>
<script type="text/javascript">
// $("select").multipleSelect({
//         placeholder: "Select Users",
//         filter: true
//         });    
jQuery(document).ready(function(){
    
    
    
//    var country = "us";//$('#country').val();
//        var region = "Alabama";$('#select').text();
//        var city = "Birmingham"; //$('#select_city :selected').text();
//        var park = $('#park').val();
//    var url="http://maps.googleapis.com/maps/api/geocode/json?address=";
//    var query = 'address='+encodeURI(park)+'&components=locality:'+city+'|country:'+country+'|administrative_area:'+region;
//    var sensor="&sensor=false";
//    var callback="&callback=?";
//
//
//    $("button").click(function(){
//          
//          $.getJSON(url+query+sensor+callback,function(json){
//alert(json);
//            // $('#results').append('<p>Latitude : ' + json.results.geometry.location.lat+ '</p>');
            // $('#results').append('<p>Longitude: ' + json.results.geometry.location.lng+ '</p>');
//
//});
//
//
//    });


      
      
      
      
      

//jQuery( document ).ready(function() {
//    
//    var myAddressQuery = 'O. J. Brochs g 16a, Bergen';
//    var geocoder = new google.maps.Geocoder(); 
//    geocoder.geocode(
//        { address : myAddressQuery, 
//          region: 'no' 
//        }, function(results, status){
//          // result contains an array of hits.
//          alert(results);
//    });
//    
    
//    
//            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
//                var head = document.getElementsByTagName('head')[0];
//                var js = document.createElement("script");
//
//                js.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyC7dELAlGhs-ur5aBTWXzGLXJDzi2L5kck&libraries=places";
//
//                head.appendChild(js);
//            }
//});
});

      </script>
  
<?php

get_footer();
?>