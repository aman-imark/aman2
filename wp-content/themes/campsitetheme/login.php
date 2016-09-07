<?php
/*
    Template Name: Login
*/
//Function starts from here
function redirect_logged_in_user($redirect_to = null) {
    $user = wp_get_current_user();
    if ($_GET) {
        $location = get_site_url() . "/" . $_GET['redirect_to'];
        if (user_can($user, 'manage_options')) {
            wp_redirect($location);
        } else {
            wp_redirect($location);
        }
    } else {
        if (user_can($user, 'manage_options')) {
            wp_redirect(admin_url());
        } else {
            wp_redirect(home_url());
        }
    }
}

function campsite_login_validation($email, $password) {
    global $reg_errors;
    $reg_errors = new WP_Error;
    if (empty($email) || empty($password)) {
        return false;
    }
    if (!is_email($email)) {
        return false;
    }
    if (!email_exists($email)) {
        return false;
    }
    global $authenticate;
    $authenticate = wp_authenticate($email, $password);
    if (is_wp_error($authenticate)) {
        return false;
    }
    if (is_wp_error($reg_errors) && !empty($reg_errors->errors)) {
        return false;
    } else {
        return true;
    }
}

//Function ends here
//Code starts from here
get_header();
if (is_user_logged_in()):
    $location = get_site_url();
    wp_redirect($location);
endif;
if ($_POST['login_hidden'] == 'Y') {
    global $reg_errors;
    $reg_errors = new WP_Error;
    if (campsite_login_validation($_POST['user_email'], $_POST['user_pass'])) {
        wp_set_current_user($authenticate->ID, $authenticate->user_login);
        $user = wp_get_current_user();
        if (isset($_POST['remember'])) {
            wp_set_auth_cookie($authenticate->ID, true);
        } else {
            wp_set_auth_cookie($authenticate->ID);
        }
        redirect_logged_in_user();
    } else {
        $reg_errors->add('Error', 'Login failed, please check your credentials');
    }
} else {
    global $reg_errors;
    $reg_errors = new WP_Error;
}

//Code ends here
?>
<!--Div starts from here-->
<div class="clearfix"></div>
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
                    <form action="" method="POST">
                        <input type="hidden" name="login_hidden" value="Y">
                        <div class="col-sm-3 col-xs-12 lable_login">Email *</div>
                        <div class="col-sm-9 col-xs-12 "><input name="user_email" required="required" type="email" class="form-control" value="" /></div>
                        <div class="clear20"></div>
                        <div class="col-sm-3 col-xs-12 lable_login">Password *</div>
                        <div class="col-sm-9 col-xs-12 "><input name="user_pass" required="required" class="form-control" type="password" /></div>
                        <div class="clear20"></div>
                        <div class="col-sm-9 col-xs-12 col-lg-offset-3">
                            <div class="col-sm-1 col-xs-12 padding0"><input type="checkbox" value="1" name="remember"></div>
                            <div class="col-sm-6 col-xs-12 padding0">Remember Me </div>
                            <a href="<?php echo get_site_url() ?>/change_password" class="pull-left">Forgot Your Password</a>
                            <div class="clear20"></div>
                        </div>
                        <div class="col-sm-3 col-xs-12 pull-right "><input class="btn_blue2" type="submit" name="submit" value="Login"/></div>
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
<div class="clear"></div>
<!--Div ends here-->
<?php
get_footer();
?>