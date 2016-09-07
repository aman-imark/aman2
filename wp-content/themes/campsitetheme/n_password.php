<?php
/* 
Template Name: New_Password
 */
function password_validation($data)
{
    global $authenticate, $reg_errors;
    $reg_errors = new WP_Error;
    
    $user = wp_get_current_user();
    
    $email = $user->user_email;
    $password = $data['current'];
    
    $newPassword = $data['new'];
    $confirmPassword = $data['confirm'];
    
    
    if(!empty($email) && !empty($password) && $newPassword == $confirmPassword)
    {
        $authenticate = wp_authenticate($email, $password);
        if(is_wp_error( $authenticate ))
        {
             //$reg_errors->add( 'email_pass', 'Invalid current password' );
             return false;
        }
        else
        {
            return true;
        }
    }
    else 
    {
        return false;
    }
    
}
get_header();

if (isset($_POST['submit'])) 
    {
    
    global $reg_errors;
    $reg_errors = new WP_Error;
    
    //global $authenticate;
    
    //$authenticate = wp_authenticate($email, $password);
    
    //print_r($_POST); @die;
//        $date = new DateTime();
//echo $date->getTimestamp();
//        print_r($date); @die;
        $user = wp_get_current_user();
        
        if(password_validation($_POST))
        {
            $userId = $user->ID;
            $newPassword = $_POST['new'];
            $user_id = wp_update_user( array( 'ID' => $userId, 'user_pass' => $newPassword ) );
            //print_r($user_id); @die;
            if ( is_wp_error( $user_id ) ) 
            {
                $reg_errors->add( 'Failed', 'Password not updated' );
            }
            else 
            {
                $reg_errors->add( 'Success', 'Password updated successfully' );
            }
        }
        else
        {
            $reg_errors->add( 'Failed', 'Invalid Password Provided' );
        }
    }
    else
    {
        global $reg_errors;
    $reg_errors = new WP_Error;
    }

if(!is_user_logged_in()):
    $location = site_url()."/login";
    wp_redirect($location);
endif;
?>

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
    <div>
    <div class="col-md-12">
        <h3 style="font-weight: bold;">Change Password</h3>
    </div>
    <div class="clear20"></div>
    <form action="" method="POST">
 <div class="col-sm-3 col-xs-12 lable_login">Current Password *</div>
 <div class="col-sm-9 col-xs-12 "><input name="current" required="required" type="password" id="current" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <div class="col-sm-3 col-xs-12 lable_login">New Password *</div>
 <div class="col-sm-9 col-xs-12 "><input name="new" required="required" type="password" id="password" class="form-control" value="" /></div>
  <div class="clear20"></div>
  
  <div class="col-sm-3 col-xs-12 lable_login">Confirm Password *</div>
 <div class="col-sm-9 col-xs-12 "><input name="confirm" required="required" type="password" id="confirm_password" class="form-control" value="" /><span id='c_message'></span></div>
  <div class="clear20"></div>
   
  
  <div class="col-sm-3 col-xs-12 pull-right "><input class="btn_blue2" type="submit" name="submit" value="Change Password"/></div>
  
  </form>   
  </div>
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

<script type="text/javascript">
    jQuery('#password, #confirm_password').on('keyup', function () {
    if ($('#password').val() == $('#confirm_password').val()) {
        $('#c_message').html('Matching').css('color', 'green');
    } else 
        $('#c_message').html('Not Matching').css('color', 'red');
});
</script>

<?php 
get_footer();

?>