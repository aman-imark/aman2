<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="utf-8" >
        <meta name="viewport" content="width=device-width, initial-scale=1" >
       <title>Find Your Campsite</title>
           <!-- ************************************************************************ !-->
           <!-- *****                                                              ***** !-->
           <!-- *****       ¤ Designed and Developed by  LEADconcept               ***** !-->
           <!-- *****               http://www.leadconcept.com                     ***** !-->
           <!-- *****                                                              ***** !-->
           <!-- ************************************************************************ !-->
<!-- JQurey CSS and JS files are added here -->
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">    
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script language=javascript src='http://maps.google.com/maps/api/js?key=AIzaSyDpyFvYW-URekxZNrkbzHyyd_85QZ_pi_c'></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/droparea/droparea.js"></script> 
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js"></script>   
<!-- Jvector Map JS files are added here -->
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/jquery-jvectormap.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/jquery-mousewheel.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/jvectormap.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/abstract-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/abstract-canvas-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/abstract-shape-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/svg-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/svg-group-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/svg-canvas-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/svg-shape-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/svg-path-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/svg-circle-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/svg-image-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/svg-text-element.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/map-object.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/region.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/vector-canvas.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/simple-scale.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/numeric-scale.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/ordinal-scale.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/map.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/jvectormap/js/multimap.js"></script>
<script src="<?php echo get_template_directory_uri()?>/jvectormap/js/jquery-jvectormap-data-us-lcc-en.js"></script>
    <?php echo wp_head();?>
    </head>
    <body>  
<?php
//Functions starts from here
// Intented to use bootstrap 3.
// Location is like a 'primary'
// After, you print menu just add create_bootstrap_menu("primary") in your preferred position;
#add this function in your theme functions.php  
function create_bootstrap_menu( $theme_location ) {
    if ( ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) ) {
         
        $menu_list  = '<nav class="navbar navbar-default pull-right">' ."\n";
        $menu_list .= '<div class="container-fluid nav_cstm padding0">' ."\n";
        //$menu_list .= '<div class="padding0">' ."\n";
        $menu_list .= '<!-- Brand and toggle get grouped for better mobile display -->' ."\n";
        $menu_list .= '<div class="navbar-header">' ."\n";
        $menu_list .= '<button type="button" class="navbar-toggle collapsed" id="b_close" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">' ."\n";
        $menu_list .= '<span class="sr-only">Toggle navigation</span>' ."\n";
        $menu_list .= '<span class="icon-bar"></span>' ."\n";
        $menu_list .= '<span class="icon-bar"></span>' ."\n";
        $menu_list .= '<span class="icon-bar"></span>' ."\n";
        $menu_list .= '</button>' ."\n";
        //$menu_list .= '<a class="navbar-brand" href="' . home_url() . '">' . get_bloginfo( 'name' ) . '</a>';
        $menu_list .= '</div>' ."\n";//navbar-header
           
        $menu_list .= '<!-- Collect the nav links, forms, and other content for toggling -->';
         
         
        $menu = get_term( $locations[$theme_location], 'nav_menu' );
        $menu_items = wp_get_nav_menu_items($menu->term_id);
 
        $menu_list .= '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">' ."\n";
        $menu_list .= '<ul class="nav navbar-nav">' ."\n";
          
        foreach( $menu_items as $menu_item ) {
            if( $menu_item->menu_item_parent == 0 ) {
                 
                $parent = $menu_item->ID;
                
                if(!current_user_can('administrator') && !is_admin() && is_user_logged_in())
                {
                    $menu_array = array();
                    foreach( $menu_items as $submenu ) {
                        if( $submenu->menu_item_parent == $parent ) {
                            $bool = true;
                            if($submenu->title == 'Logout')
                            {
                                $menu_array[] = '<li role="separator" class="divider"></li>' ."\n";
                                $redirect_1 = get_site_url();
                                $menu_array[] = '<li><a href="'. wp_logout_url($redirect_1) .'">' . $submenu->title . '</a></li>' ."\n";
                            }
                            else
                            {
                                $menu_array[] = '<li><a href="' . $submenu->url . '">' . $submenu->title . '</a></li>' ."\n";
                            }
                        }
                    }
                }
                
                if( $bool == true && count( $menu_array ) > 0 ) {
                     
                    $menu_list .= '<li class="dropdown">' ."\n";
                    $menu_list .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . $menu_item->title . ' <span class="caret"></span></a>' ."\n";
                     
                    $menu_list .= '<ul class="dropdown-menu">' ."\n";
                    $menu_list .= implode( "\n", $menu_array );
                    $menu_list .= '</ul>' ."\n";
                     
                } else {
                    if(is_user_logged_in())
                    {
                        if(!current_user_can('administrator') && !is_admin())
                        {
                            if($menu_item->title != 'My Account' && $menu_item->title != 'Login' && $menu_item->title != 'Signup')
                            {
                                $menu_list .= '<li>' ."\n";
                                $menu_list .= '<a href="' . $menu_item->url . '">' . $menu_item->title . '</a>' ."\n";
                            }
                        }
                        else
                        {
                            if($menu_item->title != 'My Account' && $menu_item->title != 'Login' && $menu_item->title != 'Signup')
                            {
                                $menu_list .= '<li>' ."\n";
                                $menu_list .= '<a href="' . $menu_item->url . '">' . $menu_item->title . '</a>' ."\n";
                            }
                        }
                    }
                    else 
                    {
                        if($menu_item->title != 'My Account')
                        {
                            if($menu_item->title == 'Login' || $menu_item->title == 'Signup')
                            {
                                $menu_list .= '<li>' ."\n";
                            $menu_list .= '<a href="' . $menu_item->url . '" style="color:#ff7444 !important;">' . $menu_item->title . '</a>' ."\n";
                            }
                            else
                            {
                                $menu_list .= '<li>' ."\n";
                            $menu_list .= '<a href="' . $menu_item->url . '">' . $menu_item->title . '</a>' ."\n";
                            }
                            
                        }
                    }
                }
            }
            // end <li>
            $menu_list .= '</li>' ."\n";
        }
        $menu_list .= '</ul>' ."\n";
        $menu_list .= '</div>' ."\n";//navbar-collapse
        //$menu_list .= '</div>' ."\n";//navbar-collapse
        $menu_list .= '</div><!-- /.container-fluid -->' ."\n";
        $menu_list .= '</nav>' ."\n";
    } else {
        $menu_list = '<!-- no menu defined in location "'.$theme_location.'" -->';
    }
    echo $menu_list;
}
//Functions ends here
//Code starts from here
$temporary = explode("/", get_permalink());
$key = sizeof($temporary);
$blogId =  $temporary[$key-1];
$args = $args = array(
    'numberposts' => 1,
    'orderby' => 'post_date',
    'order' => 'DESC',
    'post_status' => 'publish', 
    'post_type' => 'blog_post',
    );
$recent_posts = wp_get_recent_posts( $args, ARRAY_A );
$user = wp_get_current_user();
//Code ends here
?>  
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header padding0">
    <div class="container padding0">
        <div class="col-sm-3 col-xs-9 padding0"><a href="<?php echo get_site_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/images/logo.png" class="img-responsive" style="margin-bottom:2px; margin-top:5px;" /></a></div>    
        <?php if (get_site_url() . '/' != get_permalink()):
            ?>
            <div class="col-sm-3 mrg_top11">
                <form class="navbar-form padding0" action="<?php echo get_site_url() ?>/grandsearch" method="get" role="search" id="topsearch">
                    <div class="input-group pull-right srch_mrg">
                        <input name="query" id="query" type="text" class="form-control search_top" value="" placeholder="Search City, Campground, etc..." />
                        <div class="input-group-btn float_search">
                            <button class="btn btn-default btn_search" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
			
			
			<!-- start of ahmad's search    onkeyup="autocomplet()" -->
    <div id="back_search">
	
	</div>
	
	<script>
	//$(document).ready(function(){
		
		$('#query').keyup(function(){
			//alert("abcd");
			
			var query=$(this).val();
                        if(query == '')
                        {
                            $('#back_search').hide();
                            return false;
                        }
		//  console.log(query);
		//alert(query);
			$.post('<?php echo get_site_url()?>/ajaxsearch' , {query:query} , function(data){
				
				if(data != '')
                                {
                                    $('div#back_search').css({'display':'block'});
                                    $('div#back_search').html(data);
                                }
                                else
                                {
                                    var result = "<?php echo 'No results found'?>";
                                   $('div#back_search').css({'display':'block', 'color': 'black'});
                                    $('div#back_search').html(result); 
                                }
				// alert(data);
			});
		});
	//});
	
	</script>
	
    <!-- end of ahmad's search   -->
			
			
			
            <?php
        endif;
        create_bootstrap_menu("primary");
        ?>
    </div>
</div>
<script>
jQuery("#b_close").click(function() {
 if($('#bs-example-navbar-collapse-1').hasClass('in') && !$('#bs-example-navbar-collapse-1').hasClass('out'))
{
    $('#bs-example-navbar-collapse-1').hide();
    $('#bs-example-navbar-collapse-1').addClass('out');
}
else
{
    $('#bs-example-navbar-collapse-1').show();
    $('#bs-example-navbar-collapse-1').removeClass('out');
}
});
jQuery(document).ready(function() {
   var loc = window.location.href.split('/');
      var page = loc[loc.length - 1];
      $('ul.nav a').each(function (i) {
          var href = $(this).attr('href');
          if (href.indexOf(page) !== -1 || href.indexOf(page.split('?')[0]) !== -1) {
              $('ul.nav li.active').removeClass('active');
              $(this).parent().addClass('active');
          }
          if(page == 'profile' || page == 'change_password')
          {
              $('ul.nav li.dropdown').addClass('active');
          }
          if(page == '')
          {
              $('ul.nav li.active').removeClass('active');
          }
      });
});
</script>