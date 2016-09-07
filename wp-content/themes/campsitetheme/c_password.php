<?php
/* 
Template Name: C_Password
*/
//Function starts from here
function campsite_change_password_validation($email) {
    global $authenticate, $reg_errors;
    $reg_errors = new WP_Error;
    if (empty($email)) {
        $reg_errors->add('field', 'Required form field is missing');
    }
    if (!is_email($email)) {
        $reg_errors->add('email_invalid', 'Email is not valid');
    }
    if (!email_exists($email)) {
        $reg_errors->add('email', 'Invalid email address');
    }
    if (is_wp_error($reg_errors) && !empty($reg_errors->errors)) {
        return false;
    } else {
        return true;
    }
}

//Code starts from here
get_header();
if (is_user_logged_in()):
    $location = get_site_url();
    wp_redirect($location);
endif;
if ($_POST['changePassword_hidden'] == 'Y') {
    global $reg_errors, $authenticated;
    $reg_errors = new WP_Error;
    $authenticated = false;
    $user = get_user_by("email", $_POST['user_email']);
    if (campsite_change_password_validation($_POST['user_email'])) {
        $key = rand(10000, 10000000);
        $updated = update_usermeta($user->ID, "token", $key);
        $to = $_POST['user_email'];
        $subject = "Forget Password";
        $from = 'info@campsite.com';
        $body = 'Hi, <br/> <br/>Your User ID is ' . $user->ID . ' <br><br>Click here to reset your password ' . $_SERVER['HTTP_HOST'] . '/resetpassword?code=' . $key . '&email=' . $to . ' <br/><br>.';
        $headers = "From: " . strip_tags($from) . "\r\n";
        $headers .= "Reply-To: " . strip_tags($from) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if ($updated) {
            if (wp_mail($to, $subject, $body, $headers)) {
                $reg_errors->add('Success', 'A link has been sent to your email address. ');
            }
        }
    } else {
        $reg_errors->add('Failed', 'Invalid information provided. ');
    }
}
//Code ends here
?>
<!--Div starts from here-->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">
    <div class="container padding0">
        <div class="container padding0">
            <div class="col-sm-8 col-xs-12 col-sm-offset-2">
                <div class="white_box_login">
                    <?php
                    if (is_wp_error($reg_errors) && !empty($reg_errors->errors)) {
                        foreach ($reg_errors->get_error_messages() as $error) {
                            echo "<div class='alert alert-danger' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>$error</div>";
                        }
                    }
                    ?>
                    <div class="col-md-12">
                        <h3 style="font-weight: bold;">Request Password</h3>
                    </div>
                    <div class="clear20"></div>
                    <form action="" method="POST">
                        <input type="hidden" name="changePassword_hidden" value="Y">
                        <div class="col-sm-3 col-xs-12 lable_login">Email *</div>
                        <div class="col-sm-9 col-xs-12 "><input name="user_email" required="required" type="email" class="form-control" value="" /></div>
                        <div class="clear20"></div>
                        <div class="col-sm-12 col-xs-12 pull-right "><input class="btn_blue2" type="submit" name="submit" value="Request Password" style="float:right;"/></div>
                    </form>   
                    <div class="clear20"></div>
                </div>
                <div class="clear20"></div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-12 padding0"></div>
    </div>
</div>
<!--Div ends here-->
<?php 
get_footer();
?>