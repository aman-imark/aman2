<?php

/* 
Template Name: R_Password
 */

function campsite_reset_password_validation($email, $code, $password)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    
    $flag = false;
    
    if (empty( $password ))
    {
         $reg_errors->add('field', 'Required form field is missing');
    }
    
    $user = get_user_by("email", $email);
    
    $token =  get_user_meta ( $user->ID, "token", false );
    
    //print_r($token); @die;
    
    if($token[0] == $code)
    {
        $set = wp_update_user( array( 'ID' => $user->ID, 'user_pass' => $password ) );
        
        //print_r($set); @die;
        if(!is_wp_error( $set ))
        {
           $flag =true ;
        }
    }
    
    if($flag)
    {
        return true;
    }
    else
    {
        return false;
    }
    
}

function campsite_reset_password()
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    
    if(isset($_GET['code']) && isset($_GET['email']) )
    {
            $code=$_GET['code'];
            $email=$_GET['email'];
            
            
            if($_POST["new_pass"])
            {
                if(campsite_reset_password_validation($email, $code, $_POST["new_pass"]))
                {
                    $reg_errors->add('password_reset', 'Password Updated Successfully');
                }
                else
                {
                    $reg_errors->add('password_reset', 'Password Updation Failed');
                }
                
            }
            
            
    }
    
}


get_header();
//wp_nav_menu(array('theme_location' => 'primary'));
//print_r(get_template_directory_uri()); @die;
//print_r(get_site_url()); @die;

if(is_user_logged_in()):
    $location = get_template_directory_uri()."/login";
    wp_redirect($location);
endif;
//global $user;
campsite_reset_password();
//$user = wp_get_current_user();



        
     // print_r($user);@die;


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
     
        <li><a href="<?php echo get_template_directory_uri()?>/about">Review a Campsite</a></li> 
        <li><a href="<?php echo get_template_directory_uri()?>/blog">Owner's Blog</a></li>   
        <li><a href="<?php echo get_template_directory_uri()?>/about">About Us </a></li>   
        <li><a href="myaccount.html">My Account</a></li>   
        <?php if(!$user->user_login) :?>
        <li><a href="<?php echo get_template_directory_uri()?>/login" style="color:#ff7444 !important;">Login </a></li>   
        <li><a href="<?php echo get_template_directory_uri()?>/signup" style="color:#ff7444 !important;">Signup</a></li>  
        <?php else:
            ?>
        <li><a href="#">Welcome <?php echo $user->user_login;?></a></li>
        <li><a href="<?php echo wp_logout_url(); ?>">Logout</a></li>
        <?php
        
        endif; ?>
      </ul>
      
      
    </div> /.navbar-collapse 
  </div> /.container-fluid 
</nav>
        </div>
    </div>
</div>-->




<!--<div style="background:#ff7444; padding:20px; text-align:center; font-size:35px; color:#fff;">Reset Password  </div>-->

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

	<div class="container padding0">



<div class="container padding0">

<div class="col-sm-8 col-xs-12 col-lg-offset-2">
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
    
    
    <form action="<?php campsite_reset_password()?>" method="POST">
 <div class="col-sm-3 col-xs-12 lable_login">New Password *</div>
 <div class="col-sm-9 col-xs-12 "><input id="password" name="new_pass" required="required" type="password" class="form-control" value="" /></div>
  <div class="clear20"></div>
 <div class="col-sm-3 col-xs-12 lable_login">Confirm Password *</div>
 <div class="col-sm-9 col-xs-12 "><input id="confirm_password" name="conf_pass" required="required" type="password" class="form-control" value="" /><span id='message'></span></div>
  <div class="clear20"></div>
   
  
  <div class="col-sm-3 col-xs-12 pull-right "><input class="btn_blue2" type="submit" name="submit" value="Change Password"/></div>
  
  </form>   
  
  <div class="clear20"></div>
    <div style="text-align:right;">Having problems logging in? <a href="#">Click here to contact our support team.</a>
    </div>
 

</div>
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
    jQuery('#password, #confirm_password').on('keyup', function () {
    if ($('#password').val() == $('#confirm_password').val()) {
        $('#message').html('Matching').css('color', 'green');
    } else 
        $('#message').html('Not Matching').css('color', 'red');
});
</script>

<?php 
get_footer();

?>