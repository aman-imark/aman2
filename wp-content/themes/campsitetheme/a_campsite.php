<?php
/* 
Template Name: Add_Campsite
*/
//Functions starts from here   

get_header();

$climate =  array('mountain' => "Mountain", 'coastal' => "Coastal", 'forest' => "Forest", 'desert' => "Desert");

$months =  array('january' => January, 'february' => February, 'march' => March, 'april' => April, 'may' => May, 'june' => June, 'july' => July, 'august' => August, 'september' => September, 'october' => October, 'november' => November, 'december' => December, );

$hookups =  array('electricity' => Electricity, 'sewer' => Sewer, 'water' => Water,  'not' => "Not Sure",  'none' => "None");

$amenities =  array('pets' => "Pets Allowed", 'play' => "Playground Access", 'wifi' => "Wifi",  'showers' => "Showers", 'flush' => "Flush Toilets", 'vault' => "Vault Toilets", 'other' => "Other", 'tent' => "Tent Pad", 'picnic' => "Picnic Table", 'fire' => "Fire Ring");

$reserve = array(1 => "Yes", 0 => "No");

$authentication = FALSE;
if($_POST['addCampsite_hidden'] == 'Y')
{
    global $reg_errors;
    $reg_errors = new WP_Error;
        
    if(isset($_POST['pic_name']) && $_POST['pic_name']!=NULL)
    {
    $sb=  $_POST['pic_name'];
   // $ch=time().'_'. $_POST['pic_name'];
   $exp=(explode(",",$sb)) ;
   $temp_count=count($exp);
   $name_pic=[];
//   for($i=0; $i< $temp_count; $i++)
//   {
//       $name_pic[$i]=time().'_'.$exp[$i];
//   }
    $_POST['images']=  json_encode($exp);
    $_POST['campImagesCount']= $temp_count;
    
   // print_r( $_POST['images']);
  //  print_r( $_POST['campImagesCount']);
  //  @die;
    }
       elseif ($_FILES["file"]["error"][0] == 0) 
      {
            $uploadedImages = upload_image($_FILES);
             $campImagesCount = sizeof(json_decode($uploadedImages));
             $_POST['images'] = $uploadedImages;
             $_POST['campImagesCount'] = $campImagesCount;
       }
        else
       {
           $_POST['images'] = '';
           $_POST['campImagesCount'] = 0;
       }
    
        
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
        
//        print_r($_POST);
//        @die;
        
        
        
        $json_months = json_encode($_POST['months']);
        
        if(!isset($json_months))
        {
            $json_months = '';
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
//        print_r($_POST);
//      @die;
        if(empty($_POST['lat']))
        {
            $_POST['lat'] = '';
        }
        if(empty($_POST['long']))
        {
            $_POST['lat'] = '';
        }
        
        
      //print_r($_POST);
      //@die;
//       if ($_FILES["file"]["error"][0] == 0) 
//       {
//             $uploadedImages = upload_image($_FILES);
//             $campImagesCount = sizeof(json_decode($uploadedImages));
//             $_POST['images'] = $uploadedImages;
//             $_POST['campImagesCount'] = $campImagesCount;
//       }
//       else
//       {
//           $_POST['images'] = '';
//           $_POST['campImagesCount'] = 0;
//       }
       if(add_campsite_validation($_POST))
        {
           //@die;
              if(add_campsite_data($_POST))
              {
                  $authentication = true;
                  //$reg_errors->add('Success', 'Campsite Added Successfully');
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
        $requestUri = explode("/", $_SERVER['REQUEST_URI']);
        $key = sizeof($requestUri);
        $requestedURL =  $requestUri[$key-1];
        $location = get_site_url()."/login??redirect_to=".$requestedURL;
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

<style>
.droparea {
    position:relative;
    text-align: center;
}
.multiple {
    position:relative;
    height: 20px;
}
.droparea div, .droparea input, .multiple div, .multiple input {
    position: absolute;
    top:0;
    width: 100%;
    height: 100px;
}
.droparea input, .multiple input {
   // cursor: pointer; 
    opacity: 0; 
}
.droparea .instructions, .multiple .instructions {
    border: 2px dashed #ddd;
    opacity: .8;
}
.droparea .instructions.over, .multiple .instructions.over {
    border: 2px dashed #000;
    background: #ffa;
}
.droparea .progress, .multiple .progress {
    position:absolute;
    bottom: 0;
    width: 100%;
    height: 0;
    color: #fff;
    background: #6b0;
}

.multiple .progress {
    width: 0;
    height: 100%;
}


#areas { float: left; width: 480px; }
div.spot {
    float: left;
    margin: 0 0 0 0;
    width: 100%;
    min-height: 100px;
}
.thumb {
    float: left;
    margin:0 20px 20px 0;
    width: 140px;
    min-height: 105px;
}
.desc {
    float:right;
    width: 460px;
}
.ui-autocomplete { max-height: 200px; overflow-y: scroll; overflow-x: hidden;}

.multiselect-container.dropdown-menu{
    width: 100% !important;
}

li.multiselect-item.multiselect-all a {
    border: none;
}

</style>

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



<!--<div style="background:#ff7444; padding:20px; text-align:center; font-size:35px; color:#fff; font-weight:bold;">
 Add Camp Site <br />

   <a href="#" style="font-size:14px; color:#fff;">  Back to Submit Review
  </a>
</div>-->

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

<div class="col-sm-12 col-xs-12 blog_hd"> Add Campsite
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
    else if(count($reg_errors->get_error_messages()) < 1 && $_POST['addCampsite_hidden'] == 'Y' && $authentication)
    {
        $success = "Campsite added successfully";
        echo "<div class='alert alert-success' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>$success</div>";
    }
    
    ?>
    
    
    
    <form action="" id="add-camp-form" method="POST" enctype="multipart/form-data">
     <input type="hidden" name="addCampsite_hidden" value="Y">
    <div class="col-sm-3 col-xs-12 lable_login">Country  <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><input id="country" name="country" required="required" readonly="readonly" type="text" class="form-control" value="United States" /></div>
  <div class="clear20"></div>
    <div class="col-sm-3 col-xs-12 lable_login">State <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><select id="select" required="required" class="form-control" name="state">
            <option value="">Select State</option>
        <?php if(!empty(get_states())):
            foreach (get_states() as $state) :
            ?>
            <option value="<?php echo $state->state_code ?>"<?php if($selectedState == $state->state_code){echo("selected");}?>><?php echo $state->state_name ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></div>
  <div class="clear20"></div>
 <div class="col-sm-3 col-xs-12 lable_login">Name of Campsite  <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 "><input id="park" required="required" name="camp" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Closest Town  <span style="color:#F00;">*</span></div>
           <div class="col-sm-9 col-xs-12 ">
               <input id="select_city" required="required" name="city" placeholder="Type a City" type="text" class="form-control" value="" />
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
   <div class="col-sm-9 col-xs-12 "><input name="address" class="form-control" type="text" value=""/></div>
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Latitude <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required name="lat" class="form-control" type="number" value=""/></div>
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Longitude <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required name="long" class="form-control" type="number" value=""/></div>
 
   
<!--    <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Camp Phone <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  name="phone" required="required" class="form-control" type="text" />
  e.g., 555-000-5555 or 5550005555
  </div>-->
      <div class="clear20"></div>

   <div class="col-sm-3 col-xs-12 lable_login">Type of Campsite<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><select id="camp_type" class="form-control" required="required" name="type">
       <option value="">- Any Type -</option>
       <option value="car"> Car Camping</option>Backcountry
       <option value="hike"> Hike In</option>
        <option value="rvs"> RVs Only</option>
       </select></div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Price per Night <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input id="price" required="required" value="" placeholder="0" name="price" class="form-control" type="number" min="0"/></div>
   
   <div class="clear20"></div>

   <div class="col-sm-3 col-xs-12 lable_login">Reservations Accepted </div>
   <div class="col-sm-9 col-xs-12 "><select id="reserve"  class="form-control" name="reserve" >
           <option value="">Select Reservations</option>
        <?php foreach ($reserve as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></div>
   
   <div class="clear20"></div>
    <div class="col-sm-3 col-xs-12 lable_login">Climate <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><select id="climate"  class="form-control" required="required" name="climate">
            <option value="">Select Climate</option>
        <?php foreach ($climate as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></div>
   
	<div class="clear20"></div>
	<div class="col-sm-3 col-xs-12 lable_login">Number of Sites <span style="color:#F00;">*</span></div>
        <div class="col-sm-9 col-xs-12 "><input id="sites" required="required" value="" placeholder="0" name="sites" class="form-control" type="number" min="0"/></div>

     <div class="clear20"></div>
     
      <div class="col-sm-3 col-xs-12 lable_login">Best Months to Visit</div>
      <div class="col-sm-9 col-xs-12 "><select id="month" multiple="multiple" class="form-control" name="months[]">
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
      <div class="col-sm-9 col-xs-12 "><select id="hookups" required="required" multiple class="form-control" name="hookups[]">
       <?php if(!empty($hookups)):
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
       <?php if(!empty($amenities)):
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

      <div class="col-sm-3 col-xs-12 lable_login">Campground Website <span style="color:#F00;">*</span></div>
      <div class="col-sm-9 col-xs-12 "><input name="website" required="required" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <!--<input type="hidden" id="latitude" name="lat" value="">
  <input type="hidden" id="longitude" name="long" value="">  -->
  <input type="hidden" id="pic_name" name="pic_name" value="">
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
                            
                            <input name="file[]" id="file" type="file" multiple="multiple" style="margin-top: 10px;
    margin-bottom: 10px;" accept="image/x-png, image/gif, image/jpeg">
                            <!--<p>Max 5 images are allowed...</p>-->
                            <input id="file1" type="file"  class="droparea spot" name="xfile" data-post="<?php echo get_template_directory_uri()?>/droparea/upload.php" data-width="50" data-height="100" data-crop="true"/> 
           <div id="message"></div>
		    <div id="message1"></div>
                        </header>
  </div>
    <div class="clear20"></div>   
  <div class="col-sm-9 col-xs-12 col-lg-offset-3">
      
  <div class="col-sm-3 col-xs-12 padding0"><button class="btn_blue2" type="reset" data-key="reset" id="reset" style="background:#CCC;">Cancel</button> </div>
   <div class="col-sm-3 col-xs-12 padding0"><input class="btn_blue2 margin-left-class" type="button" id="submit1" value="Add Campsite" style="margin-left:3px;"/></div>
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
<script>
$("#add-camp-form :input").change(function() {
   $("#add-camp-form").data("changed",true);
});
$('#add-camp-form').submit(function(e){
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
$("#add-camp-form").trigger('reset');
});
</script>
<script>
    
    $(document).ready(function() {
        if($('#select').val() == '')
        {
             $('#file1').attr('type', 'hidden');
        }
    });
    $("#select").change(function () {
        if($('#select').val() == '' || $('#park').val() == ''  || $('#select_city').val() == '' || $('#camp_type').val() == '' || $('#price').val() == 0  || $('#climate').val() == '' || $('#sites').val() == 0 || $('#hookups').val() == null || $('#amenities').val() == null || $('#file').val() != '' )
        {
            //alert($('#price').val());
             $('#file1').attr('type', 'hidden');
        }
        else
        {
             $('#file1').attr('type', 'file');
        }
    });
    $("#camp_type").change(function () {
        if($('#select').val() == '' || $('#park').val() == ''  || $('#select_city').val() == '' || $('#camp_type').val() == '' || $('#price').val() == 0  || $('#climate').val() == '' || $('#sites').val() == 0 || $('#hookups').val() == null || $('#amenities').val() == null || $('#file').val() != '' )
        {
             $('#file1').attr('type', 'hidden');
        }
        else
        {
             $('#file1').attr('type', 'file');
        }
    });
    $("#reserve").change(function () {
       if($('#select').val() == '' || $('#park').val() == ''  || $('#select_city').val() == '' || $('#camp_type').val() == '' || $('#price').val() == 0  || $('#climate').val() == '' || $('#sites').val() == 0 || $('#hookups').val() == null || $('#amenities').val() == null || $('#file').val() != '' )
        {
             $('#file1').attr('type', 'hidden');
        }
        else
        {
             $('#file1').attr('type', 'file');
        }
    });
    $("#climate").change(function () {
        if($('#select').val() == '' || $('#park').val() == ''  || $('#select_city').val() == '' || $('#camp_type').val() == '' || $('#price').val() == 0  || $('#climate').val() == '' || $('#sites').val() == 0 || $('#hookups').val() == null || $('#amenities').val() == null || $('#file').val() != '' )
        {
             $('#file1').attr('type', 'hidden');
        }
        else
        {
             $('#file1').attr('type', 'file');
        }
    });
    $("#month").change(function () {
        if($('#select').val() == '' || $('#park').val() == ''  || $('#select_city').val() == '' || $('#camp_type').val() == '' || $('#price').val() == 0  || $('#climate').val() == '' || $('#sites').val() == 0 || $('#hookups').val() == null || $('#amenities').val() == null || $('#file').val() != '' )
        {
             $('#file1').attr('type', 'hidden');
        }
        else
        {
             $('#file1').attr('type', 'file');
        }
    });
    $("#hookups").change(function () {
        if($('#select').val() == '' || $('#park').val() == ''  || $('#select_city').val() == '' || $('#camp_type').val() == '' || $('#price').val() == 0  || $('#climate').val() == '' || $('#sites').val() == 0 || $('#hookups').val() == null || $('#amenities').val() == null || $('#file').val() != '' )
        {
             $('#file1').attr('type', 'hidden');
        }
        else
        {
             $('#file1').attr('type', 'file');
        }
    });
    $("#amenities").change(function () {
if($('#select').val() == '' || $('#park').val() == ''  || $('#select_city').val() == '' || $('#camp_type').val() == '' || $('#price').val() == 0  || $('#climate').val() == '' || $('#sites').val() == 0 || $('#hookups').val() == null || $('#amenities').val() == null || $('#file').val() != '' )
        {
             $('#file1').attr('type', 'hidden');
        }
        else
        {
             $('#file1').attr('type', 'file');
        }
    });
    $("#file").change(function () {
       if($('#select').val() == '' || $('#park').val() == ''  || $('#select_city').val() == '' || $('#camp_type').val() == '' || $('#price').val() == 0  || $('#climate').val() == '' || $('#sites').val() == 0 || $('#hookups').val() == null || $('#amenities').val() == null || $('#file').val() != '' )
        {
             $('#file1').attr('type', 'hidden');
        }
        else
        {
             $('#file1').attr('type', 'file');
        }
    });
    </script>
<script>
    jQuery(".droparea").click(function(){
        return false;    
    });
    </script>
	
	

<script>
   $('.droparea').droparea({
                'instructions': 'drag & drop files here',
                'init' : function(result){
                    //console.log('custom init',result);
                },
                'start' : function(area){
                    area.find('.error').remove(); 
                },
                'error': function(result, file, input, area){
                    if((/image/i).test(file.type)){
                        area.find('img').remove();
                        //area.data('value',result.filename);
                        area.append($('<img>',{'src': result.path + result.filename + '?' + Math.random()}));
                    } 
                    //console.log('custom complete',result);
                },
                'complete' : function(result, file, input, area){
                    if((/image/i).test(file.type)){
                        area.find('img').remove();
                        //area.data('value',result.filename);
                        area.append($('<img>',{'src': result.path + result.filename + '?' + Math.random()}));
                    } 
                    //console.log('custom complete',result);
                }
            });
</script>

<script>
// start of ahmad's preview code

$("#file").change(function () {
	//alert(":(");
        $("#message").empty(); // To remove the previous error message
        console.log(this.files);
        var file = this.files;
        //console.log(file.length);
        //for(file)
        
    /*  if(file.length > 5)
        {
            $("#message").html("<p id='error'>Please Select Maximum 5 Image Files</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
            return false;
        }  */
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

//if (typeof (FileReader) != "undefined") {
            var dvPreview = $("#message1");
            dvPreview.html("");
//            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.png)$/;
            var regex = /(.jpg|.jpeg|.png)$/;
            $($(this)[0].files).each(function () {
                var file = $(this);
                if (regex.test(file[0].name.toLowerCase())) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = $("<img />");
                        img.attr("style", "height:60px;width: 60px;margin-left:1px;margin-top:8px;border:2px solid #ff7444");
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
      //  } else {
      //      alert("This browser does not support HTML5 FileReader.");
     //   }

    });

	
	
	
	// start of ahmad's preview code
</script>

<script>
$(document).ready(function(){
var obj=$('#file1');
   var i=0;
        obj.on('drop',function(e){
                e.stopPropagation();
                e.preventDefault();
                var files=e.originalEvent.dataTransfer.files;
              var name= [];
                 for (i = 0; i < files.length; i++)
                      {
                                name[i]=files[i]['name'];		  
                          }
                           $('#pic_name').val(name);
        });
});
</script>
<script>
    $('#month').multiselect({
       dropRight: true,
       includeFilterClearBtn: false,
       buttonWidth: '100%',
       maxHeight: 300,
       includeSelectAllOption: true
});
    $('#hookups').multiselect({
       dropRight: true,
       includeFilterClearBtn: false,
       buttonWidth: '100%',
       maxHeight: 300,
       includeSelectAllOption: true
});

    $('#amenities').multiselect({
       dropRight: true,
       includeFilterClearBtn: false,
       buttonWidth: '100%',
       maxHeight: 300,
       includeSelectAllOption: true
});
    
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
//    var txt_filter;
//    jQuery("#amenities").click(function(){
//        txt_filter = $('#amenities').val();
//    var div = document.getElementById('other');
//    if (txt_filter == 'other') {
//        $('#other').show("fast");
//    }
//    });
</script>
<script type="text/javascript">

    $("#file").change(function () {
        $("#message").empty(); // To remove the previous error message
        console.log(this.files);
        var file = this.files;
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
    });
    
</script>

<script type="text/javascript">
    var txt_filter;
    jQuery("#select_city").click(function(){
        txt_filter = $('#select').val();
    if(txt_filter == '')
    {
      alert("Please Select the State First.");  
    }
    });
    </script>
<script type="text/javascript">
    
     var txt_filter;
jQuery("#select").change(function(){
    $('#select_city').val('');
    $('#camp_type').val('');
    $('#select_city').attr("placeholder", "Type a City");
	 $('#select_city').prop("disabled", false);
    txt_filter = $('#select').val();
    var testingarray1 = [];
    var number_of_names;
    var i;
jQuery.ajax({
  type: "POST",
  url: "<?php echo get_site_url()?>/city",
  data: {selected: txt_filter},
  success: function (data) {
      var arr = JSON.parse(data);
	 
	  
	  
       number_of_names = arr.length;
	 //  console.log(number_of_names);
	   // ahmad's code start
	   if(number_of_names<=0)
	   {
		   alert("Sorry no data available for this state.");
		   
		    $('#select_city').prop("disabled", true);
			 $('#select_city').attr("placeholder", "No cities for this state exists");
	   }
	//    if(number_of_names>=0)
	//   {
		 //  alert("sorry no data available for this state");
	//	    $('#select_city').prop("disabled", false);
	//   }
	   
	   // ahmad's code end
	   
        for (i = 0; i < number_of_names; i++) {
            testingarray1[i] = arr[i].city_name;
        }
      jQuery("#select_city").autocomplete({
            source: testingarray1
        });
//      //alert(resulted_data);
//      $('#select_city').html(resulted_data);
//       }
       
  },
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
	/*	var url="http://maps.googleapis.com/maps/api/geocode/json?address="; $.getJSON(url+encodeURI(park)+'&components=country:'+country+'&administrative_area:'+//region+'&locality:'+city+'&sensor=false'  */
        var url="http://maps.googleapis.com/maps/api/geocode/json?address=";
         jQuery.getJSON(url+encodeURI(park)+'&'+region, function(data){
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
           // $('#latitude').val(place.geometry.location.lat);
          //  $('#longitude').val(place.geometry.location.lng);
            $('#add-camp-form').submit();
        });
    });
</script>
<script type="text/javascript">
// $("select").multipleSelect({
//         placeholder: "Select Users",
//         filter: true
//         });    
$(document).ready(function(){
    
//    var div = document.getElementById('other');
//    div.style.display = 'none';
    
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
