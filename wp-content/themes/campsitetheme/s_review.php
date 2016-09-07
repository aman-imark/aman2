<?php
/* 
* Template Name: Submit_Review
*/
get_header();
if($_POST['review_hidden'] == 'Y')
{ 
    //print_r($_POST);
    //@die;
    global $reg_errors;
    $reg_errors = new WP_Error;
    //check if form was submitted
    $reviewAdded = FALSE;
    if ($_FILES["file"]["error"][0] == 0) 
    {
        $uploadedImages = upload_image($_FILES);
        $reviewImagesCount = sizeof(json_decode($uploadedImages));
        $_POST['images'] = $uploadedImages;
        $_POST['reviewImagesCount'] = $reviewImagesCount;
    }
    else
    {
        $_POST['images'] = '';
        $_POST['reviewImagesCount'] = 0;
    }
    
    if(add_review_validation($_POST))
    {
        if(add_review_data($_POST))
        {
            //echo "Campsite Added Successfully";
            //$reg_errors->add('Success', 'Review Added Successfully');
            //$location = site_url()."/reviews?1";
            //wp_redirect($location);
            $reviewAdded = True;    
        }
    }
    //$reg_errors->add('Success', 'Form submitted successfully');
    if($reviewAdded)
    {
        $reg_errors->add('message', "<div class='alert alert-success' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>Review added successfully</div>");
        //$reg_errors->add( 'failed', 'Registeration failed' );
    }
    else
    {
        $reg_errors->add('message', "<div class='alert alert-danger' class='close' data-dismiss='alert' aria-label='close' style='clear:both;'>Review not added successfully</div>");
    }
}


if($_GET)
{
    //print_r($_GET);
    $selectedState = $_GET['state'];
    $camps = get_camps_by_state($selectedState);
    $selectedCamp = $_GET['camp'];
}
else
{
    $selectedState = NULL;
}
    

$temporary = explode("/", $_SERVER['REQUEST_URI']);


if(!is_user_logged_in()):
    $requestUri = explode("/", $_SERVER['REQUEST_URI']);
    $key = sizeof($requestUri);
    $requestedURL =  $requestUri[$key-1];
    $location = get_site_url()."/login?redirect_to=".$requestedURL;
    
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
        //add_review();
        $count++;
    }
     
    $user = wp_get_current_user();

        //echo do_shortcode('[wpuf_form id="117"]');
        //echo do_shortcode('[wp-review]');
        
        
//echo do_shortcode('[submit-review]');
//echo do_shortcode('[ultimate-reviews]');

?>
<style>
    .lable_login {
  
    font-weight:bold !important;
}
</style>
<div class="clearfix"></div>



<!--
<div style="background:#ff7444; padding:20px; text-align:center; font-size:35px; color:#fff; font-weight: bold;">
 Add Review <br />
</div>
-->

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">

<div class="col-sm-12 col-xs-12 blog_hd"> Add Review
    <div class="clear10"></div>
    <img src="<?php echo get_template_directory_uri()?>/images/img_btm_hd.png" alt="">
   </div>

    <div class="container padding0">
        <div class="container padding0">
            <div class="col-sm-10 col-xs-12 col-sm-offset-1">
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
           <div class="col-sm-9 col-xs-12 ">
           <?php 
           if(!empty(get_camps_by_state($selectedState)))
           {
               //print_r(get_camps_by_state($selectedState));
               ?>
               <select id="select_camp" required="required" class= "form-control" name="camp">
                   <option value="">Select Camp</option>
               <?php
            foreach (get_camps_by_state($selectedState) as $camp) :
            
                ?>
            <option value="<?php echo $camp->ID ?>"<?php if($selectedCamp == $camp->ID ){echo("selected");}?>><?php echo $camp->camp_name ?></option>
            
            <?php
            endforeach;
            ?>
            </select>
               <?php
               
           }
           else
           {
                ?>
            <select id="select_camp" required="required" class= "form-control" name="camp"></select>
            <?php   
           }
            
           ?>    
               
            
           <p class="help-block">Can't find the park you're looking for? <a id="add" href="#">Add it here</a>
                            </p>
           </div>
   
           
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Date of Stay<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  required="required" name="stay_date" class="form-control" type="text" id="datepicker" placeholder="Select Date" /></div>
  
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Length of Stay</div>
   <div class="col-sm-9 col-xs-12 "><input placeholder="0" name="length_stay" class="form-control" type="number"  min="0"/><p class="help-block">In nights</p></div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Scenic Beauty <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder=" How beautiful was the nature around this campground?"name="scene" class="form-control" type="number" step="0.01" min="0" max="10" /><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Location<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Did this campground have a great location? Was it hard to access? etc." name="location" class="form-control" type="number"  step="0.01"  min="0" max="10" /><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Family Friendly <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Is this a great place for kids and people of all ages?" name="family" class="form-control" type="number" step="0.01" min="0" max="10" /><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Privacy<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Think about the noise during quiet hours, the space between sites, etc." name="privacy" class="form-control" type="number" step="0.01" min="0" max="10" /><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Cleanliness <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Consider the bathrooms, the sites themselves, the trails nearby, etc." name="clean" class="form-control" type="number" step="0.01"  min="0" max="10"/><p class="help-block">Out of 10</p>
  </div>  
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Bug Factor <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Add bug factor"  name="bug" class="form-control" type="number" step="0.01" min="0" max="10"/><p class="help-block">Out of 10</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Description <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><textarea id="desc"  required name="description" maxlength="1000" rows="6" cols="50" placeholder="Tell us about your experience" class="alpaca-control form-control" ></textarea>
       <p class="help-block">Minimum 250 & Maximum 1000 Characters</p>
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Favorite Site(s) or Loop </div>
   <div class="col-sm-9 col-xs-12 "><input required="required" placeholder="Add favorite sites or loop" name="sites" class="form-control" type="number" min="0" />
  </div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Add a tip</div>
   <div class="col-sm-9 col-xs-12 "><textarea id="tip" name="tip" maxlength="250" rows="3" cols="50" placeholder="Do you have any quick tips about the campground or area?" class="alpaca-control form-control" ></textarea>
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

  
   

  <div class="col-sm-3 col-xs-12 padding0" style="width: 125px;"><a class="btn_blue2" href="#" style="background:#CCC;">Cancel</a> </div>
   <div class="col-sm-3 col-xs-12 padding0"><input class="btn_blue2" type="submit" name="submit" value="Submit Review" style="margin-left:3px;"/></div>
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

if (typeof (FileReader) != "undefined") {
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
                        img.attr("style", "height:60px;width: 60px;;margin-left:1px;margin-top:8px;border:2px solid #ff7444");
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

<script type="text/javascript">
    var txt_filter;
    jQuery("#add").click(function(){
        txt_filter = $('#select').val();
        //alert(txt_filter);
    if(txt_filter == '')
    {
      alert("Please Select the State First.");  
    }
    else
    {
        window.location = '<?php echo get_site_url()?>/addcampsite?state='+txt_filter;
    }
    });
    </script>
<script type="text/javascript">
    var txt_filter;
    jQuery("#select_camp").click(function(){
        txt_filter = $('#select').val();
        //alert(txt_filter);
    if(txt_filter == '')
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
jQuery.ajax({
  type: "POST",
  url: "<?php echo get_site_url()?>/getcamps",
  data: {selected: txt_filter},
  success: function (data) {
      
      var arr = JSON.parse(data);
      
      //alert(arr);
    if(arr.length == ''){
         $('#select_camp').html('<option value="" selected="selected">No Park Exist</option>');
       }
       else
       {
       var resulted_data =  '<option value="">Select Camp</option>';
       for(i=0;i<arr.length;i++)
       {
           //console.log(data[i]);
        resulted_data = resulted_data + "<option value = "+arr[i].ID+">"+arr[i].camp_name+", "+arr[i].city_name+", "+arr[i].state_name+"</option>"
        
      }
      //alert(resulted_data);
      $('#select_camp').html(resulted_data);
       }
       
  },
  error: function(data) {
    // Stuff
  }
});


});


</script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>

<?php

get_footer();?>
