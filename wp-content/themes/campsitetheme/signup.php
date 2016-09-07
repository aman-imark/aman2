<?php
/*
    Template Name: Signup
*/


function print_error($reg_errors)
{
    print_r($reg_errors);
    foreach ( $reg_errors->get_error_messages() as $error ) 
    {

        echo '<div>';
        echo '<strong>Message</strong>: ';
        echo $error . '<br/>';
        echo '</div>';

    }
}

function campsite_registration_validation($username, $password, $email, $terms)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    if ( empty( $username ) || empty( $password ) || empty( $email ) || empty($terms)) 
    {
         //$reg_errors->add('field', 'Required form field is missing');
        return false;
    }
    
    if ( username_exists( $username ) )
    {
        //$reg_errors->add('user_name', 'Sorry, that display name already in use!');
        return false;
    }
    
    if ( 5 > strlen( $password ) ) {
        //$reg_errors->add( 'password', 'Password length must be greater than 5' );
        return false;
    }
    
    if ( !is_email( $email ) ) 
    {
        //$reg_errors->add( 'email_invalid', 'Email is not valid' );
        return false;
    }   
    
    if ( email_exists( $email ) ) 
    {
        //$reg_errors->add( 'email', 'Email Already in use' );
        return false;
    }
    
    //print_r($reg_errors );@die;
    if ( is_wp_error( $reg_errors ) && !empty($reg_errors->errors))
    {
        return false;
    }
    else
    { 
        return true;
    }
    
}
function campsite_complete_registration($username, $password, $email)
{
    global $reg_errors, $username, $password, $email;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) 
    {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        );
        $user = wp_insert_user( $userdata );
        
        $user_registered = false;
        
        if(isset($user))
        {
            $user_meta1 = add_user_meta( $user, 'terms', true);
            $user_meta2 = add_user_meta( $user, 'token', true);
          //  $user_registered = true;
            
            /**
             * Mail send functionality goes here
             */
            
//            if(isset($user_meta))
//            {
//                $to = 'zammadgill@gmail.com';
//                $subject = 'The subject';
//                $body = 'The email body content';
//                $headers = array('Content-Type: text/html; charset=UTF-8');
//                if(wp_mail( $to, $subject, $body, $headers))
//                {
//                    $user_registered = true;
//                }
//            }

if(isset($user_meta1) && isset($user_meta2))
            {
                $to= $email;
                $subject="Account Created";
                $from = 'info@campsite.com';
                $body='Hi, <br/> <br/>Your User ID is '.$user.' <br><br>Click here to login '.$_SERVER[                  'HTTP_HOST'].'/login'.'<br/><br>.';
                $headers = "From: " . strip_tags($from) . "\r\n";
                $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                if(wp_mail( $to, $subject, $body, $headers))
                {
                    $user_registered = true;
                }
            }






        }
//        if($user_registered)
//        {
//            $reg_errors->add('success', 'Registration completed. Goto <a href="' . get_site_url() . '/login">login page');
//            //echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';
//           
//        }
//        else
//        {
//            echo 'Registration Failed. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';
//        }
        return $user_registered;
        
        
    }    
}
get_header();


   
	
	/* START of AHMAD BAJWA's code */

	


function upload_image($data,$userID) {
//print_r ($userID);
//@die;
    //$user = wp_get_current_user();

    $date = new DateTime();
    $timestamp = $date->getTimestamp();
    $validextensions = array("jpeg", "jpg", "png");
    $temporary = explode(".", $data["file"]["name"]);
    $file_name_without_extension = $temporary[0];
    $file_extension = end($temporary);
    $updated_file_name = $file_name_without_extension . '_' . $timestamp . '.' . $file_extension;
	//print_r ($updated_file_name);
	//@die;
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
                if (get_site_url() == "http://localhost/campsite") {
                    $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/" . $updated_file_name; // Target path where file is to be stored
                 // print_r($targetPath);
				//  @die;
			   } else {
                    $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/" . $updated_file_name; // Target path where file is to be stored 
                }


                // Moving Uploaded file

                if (move_uploaded_file($sourcePath, $targetPath)) {
                 /*   $image_name = get_profile_image();
                    if (get_site_url() == "http://localhost/campsite") {
                        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/" . $image_name;
                    } else {
                        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/" . $image_name;
                    }
					*/

               //     unlink($targetPath);
                 //   $upload_image = update_usermeta($userID, "image_name", $updated_file_name);
				//print_r($userID);
				// @die;
				$upload_image =add_user_meta( $userID, 'image_name',$updated_file_name );
					//print_r($upload_image);
					//@die;
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

function get_profile_image($userID) {

    //$file = "Desert_1465905743.jpg";
    //$targetPath = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/Koala_1465907566.jpg";
    //unlink($targetPath);
   // $user = wp_get_current_user();
    $image_name = get_user_meta($userID, "image_name", false);
    if (isset($image_name[0])) {
        return $image_name[0];
    } else {
        return $image_name;
    }
} 
	
	


   /* END of AHMAD BAJWA's code */
   
   
   
   
   
    if($_POST['signup_hidden'] == 'Y')
    {   
        global $reg_errors;
        $userRegistered = FALSE;
		
		
		
		
		
        //print_r($_POST); @die;
        if(campsite_registration_validation( $_POST['username'],$_POST['password'],$_POST['email'], $_POST['terms']))
        {
            // sanitize user form input
            global $username, $password, $email;
            $username   =   sanitize_user( $_POST['username'] );
            $password   =   esc_attr( $_POST['password'] );
            $email      =   sanitize_email( $_POST['email'] );
            if(campsite_complete_registration($username, $password, $email))
            {
				$userId=get_user_by_email($email);
			//	print_r( $userId->ID);
				//echo  $userId['data']->ID;
				//@die;
				
				 /* START of AHMAD BAJWA's code */ 
		// print_r ($_FILES);
		// @die;
		 if (!empty($_FILES["file"]["type"])) 
                {
                    if(upload_image($_FILES, $userId->ID)) 
                    {

                        $userRegistered = true;
                        //echo ":)";
                    }
                    //	else{
                    //		echo ":(";
                    //}
                }
            /* END of AHMAD BAJWA's code */
                $userRegistered = true;
            }
        }
		
        if($userRegistered)
        {
            $reg_errors->add('message', "<div class='alert alert-success' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>Registration completed</div>");
            //$reg_errors->add( 'failed', 'Registeration failed' );
        }
        else
        {
            $reg_errors->add('message', "<div class='alert alert-danger' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>Registration failed</div>");
        }
    }
   
   
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
         <li><a href="blog.html">Owner's Blog</a></li>   
                 <li><a href="about.html">About Us </a></li>   
         <li><a href="myaccount.html">My Account</a></li>   
         <li><a href="<?php echo get_site_url()?>/login" style="color:#ff7444 !important;">Login </a></li>   
         <li class="active"><a href="<?php echo get_site_url()?>/signup" style="color:#ff7444 !important;">Signup</a></li>  
      </ul>
      
      
    </div> /.navbar-collapse 
  </div> /.container-fluid 
</nav>
        </div>
    </div>
</div>-->

<!-- Slider Start -->

<!-- Slider End -->
<!--<div class="clearfix"></div>



<div style="background:#ff7444; padding:20px; text-align:center; font-size:35px; color:#fff;">Signup </div>-->

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

<div class="container padding0">

<div class="col-sm-10 col-xs-12 col-sm-offset-1">
<div class="white_box_login">

    <?php
    if (is_wp_error( $reg_errors ) && !empty($reg_errors->errors))
    {
        //print_r($reg_errors->errors);
        foreach ( $reg_errors->get_error_messages() as $error ) 
    {
            //print_r($reg_errors->get_error_messages());
//            if($reg_errors->get_error_messages() == "Danger")
//            {
//                 echo "<div class='alert alert-danger' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>$error</div>";
            echo $error;
 
//            }
            
        //echo '<div class=>';
//        echo '<strong>Message</strong>: ';
//        echo $error . '<br/>';
//        echo '</div>';

    }
        
    }
//    else if($_POST['addCampsite_hidden'] == 'Y')
//    {
//        $success = 'Registration completed. Goto <a href="' . get_site_url() . '/login">login page';
//        echo "<div class='alert alert-success' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>$success</div>";
//    }
    
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="signup_hidden" value="Y">
    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
	
  <header role="banner" class="sidebar-header">
                            <div id="image_preview" class="">
			
<!-- START of AHMAD BAJWA's code -->

			
							<?php
							$userId=get_user_by_email($email);
if (!empty(get_profile_image($userId))){
    $image = get_profile_image($userId);
    //print_r($image[0]); @die;
    $path = get_template_directory_uri() . '/upload/' . $image;
	
    ?>
							
							
                                    <img id="previewing" src="<?php echo $path ?>" width="160" height="160" alt=""> 
                   <?php 
				    }
					  else {
                    ?>
                                    <img id="previewing" src="<?php echo get_template_directory_uri() ?>/images/default.jpg" width="160" height="160" alt="" /> 
                <?php
					  }
                               
                ?>
					
					
	<!-- END of AHMAD BAJWA's code -->				
					
					
                            </div>
                            <!--                        <h4 id='loading' >loading..</h4>-->
                            <div id="message1"></div>
                            <div class="clear20"></div>
                            <input name="file" id="file" type="file" style="width:100% !important;" class="well_profile well-sm">

                        </header>
  
  
  </div>    
    <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">  
    
        <div class="col-sm-3 col-xs-12 lable_login">Display Name *</div>
        <div class="col-sm-9 col-xs-12 "><input name="username" required="required" type="text" class="form-control" value="" /></div>
  <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Email *</div>
   <div class="col-sm-9 col-xs-12 "><input name="email" required="required" class="form-control" type="text" /></div>
  <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Password *</div>
   <div class="col-sm-9 col-xs-12 "><input id="password" name="password" required="required" class="form-control" type="password" /></div>
    <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Confirm Password *</div>
   <div class="col-sm-9 col-xs-12 "><input id="confirm_password" name="password" required="required" class="form-control" type="password" /><span id='message'></span></div> 
      <div class="clear20"></div>
  <div class="col-sm-9 col-xs-12 col-lg-offset-3">
      <div class="col-sm-1 col-xs-12 padding0"><input name="terms" required="required" type="checkbox" value="1" /></div>
  
     <div class="col-sm-11 col-xs-12 padding0">I agree to the Terms of Services </div>
      <div class="clear20"></div>
  <div class="col-sm-3 col-xs-12 padding0"> <input class="btn_blue2" type="submit" name="submit" value="signup"/> </div>
  </div>
  <div class="clear20"></div>


<div class="clearfix"></div>
    
 
</div>
</form>
    <div class="clearfix"></div>
  </div>
</div>


<div class="col-sm-4 col-xs-12 padding0"></div>
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
<script>
    jQuery('#password, #confirm_password').on('keyup', function () {
    if ($('#password').val() == $('#confirm_password').val()) {
        $('#message').html('Matching').css('color', 'green');
    } else 
        $('#message').html('Not Matching').css('color', 'red');
});

 $("#file").change(function () {
        $("#message1").empty(); // To remove the previous error message
        var file = this.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
        {
            $('#previewing').attr('src', '<?php echo get_template_directory_uri() ?>/images/default.jpg');
            $("#message1").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
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
//$("#file").css("color","green");
//$('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
//$('#previewing').attr('width', '250px');
//$('#previewing').attr('height', '230px');
    }
    ;



    </script>


<?php
get_footer();
?>