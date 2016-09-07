<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
    if(get_site_url() == "http://localhost/campsite")
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
//    }
    //print_r($imagesNames);
    //@die;
    $array = json_encode($imagesNames);
    return $array;
}

function add_campsite_validation($data)
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

function add_campsite_data($data)
{
    
    $user = wp_get_current_user();
    //$json_months = json_encode($data['months']);
    $json_hookups = json_encode($data['hookups']);
    $json_amenities = json_encode($data['amenities']);
    global $wpdb;
    $table = $wpdb->prefix."camps";
    $result = $wpdb->insert($table, array(
                            'camp_name' => $data['camp'],
                            'states_code' => $data['state'],
                            'cities_id' => $data['city'],
                            'camp_address' => $data['address'],
                            'camp_type' => $data['type'],
                            'camp_price' => $data['price'],
                            'camp_reserve' => $data['reserve'],
                            'camp_climate' => $data['climate'],
                            'camp_sites' => $data['sites'],
                            'camp_months' => $data['months'],
                            'camp_hookups' => $json_hookups,
                            'camp_amenities' => $json_amenities,
                            'camp_website' => $data['website'],
                            'camp_latitude' => $data['lat'],
                            'camp_longitude' => $data['long'],
                            'camp_images' => $data['images'],
                            'camp_images_count' => $data['campImagesCount'],
                            'camp_status' => $data['status'],    
                            'camp_created_by' => $user->ID    
                            )
    );
    
    //$wpdb->show_errors();
    //$wpdb->print_error();
    return $result;
}

//Function ends here

//header code starts from here

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
        if(empty($_POST['lat']))
        {
            $_POST['lat'] = '';
        }
        if(empty($_POST['long']))
        {
            $_POST['lat'] = '';
        }
        
       if ($_FILES["file"]["error"][0] == 0) 
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
       
       if(add_campsite_validation($_POST))
        {
           //print_r($_POST); @die;
              if(add_campsite_data($_POST))
              {
                  //$reg_errors->add('Success', 'Campsite Added Successfully');
                  //$location = site_url()."/reviews?1";
                  //wp_redirect($location);?>
                  <div class="updated"><p><strong><?php _e('Camp saved.' ); ?></strong></p></div>
                  <?php
              }
                
        }
        else
        {
          //$reg_errors->add('Failed', 'Campsite Not Added Successfully');
          ?>
                   <div class="updated"><p><strong><?php _e('Camp Not saved.' ); ?></strong></p></div>
                  <?php
          //$location = site_url()."/reviews";
            //      wp_redirect($location);
        }    
    
    
        
//        @die;
//        //print_r($json_months);
//        //@die;
//        
 }

//header code ends here

//Arrays starts from here
$campTypes =  array('car' => "Car Camping", 'hike' => "Hike In", 'rvs' => "RVs Only");

$climate =  array('mountain' => "Mountain", 'coastal' => "Coastal", 'forest' => "Forest", 'desert' => "Desert");

$months =  array('january' => "January", 'february' => "February", 'march' => "March", 'april' => "April", 'may' => "May", 'june' => "June", 'july' => "July", 'august' => "August", 'september' => "September", 'october' => "October", 'november' => "November", 'december' => "December");

$hookups =  array('electricity' => Electricity, 'sewer' => Sewer, 'water' => Water,  'none' => "None");

$amenities =  array('pets' => "Pets Allowed", 'play' => "Playground Access", 'wifi' => "Wifi",  'showers' => "Showers", 'flush' => "Flush Toilets", 'vault' => "Vault Toilets", 'other' => "Other");

$status = array(1 => "Active", 0 => "Inactive");

$reserve = array(1 => "Yes", 0 => "No");
//Array ends here

?>
<style type="text/css">     
    select {
        width:200px;
    }
    input {
        width:200px;
    }
</style>                   
<!--Form starts from here-->
<!--<div class="wrap">
    <?php    echo "<h2>" . __( 'Add Camp Using Admin Panel', 'posts_trdom' ) . "</h2>"; ?>
     
    <form name="post_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="post_hidden" value="Y">
        <?php    echo "<h4>" . __( 'OSCommerce Database Settings', 'posts_trdom' ) . "</h4>"; ?>
        <p><?php _e("Database host: " ); ?><input type="text" name="oscimp_dbhost" value="<?php echo $dbhost; ?>" size="20"><?php _e(" ex: localhost" ); ?></p>
        <p><?php _e("Database name: " ); ?><input type="text" name="oscimp_dbname" value="<?php echo $dbname; ?>" size="20"><?php _e(" ex: oscommerce_shop" ); ?></p>
        <p><?php _e("Database user: " ); ?><input type="text" name="oscimp_dbuser" value="<?php echo $dbuser; ?>" size="20"><?php _e(" ex: root" ); ?></p>
        <p><?php _e("Database password: " ); ?><input type="text" name="oscimp_dbpwd" value="<?php echo $dbpwd; ?>" size="20"><?php _e(" ex: secretpassword" ); ?></p>
        <hr />
        <?php    echo "<h4>" . __( 'OSCommerce Store Settings', 'posts_trdom' ) . "</h4>"; ?>
        <p><?php _e("Store URL: " ); ?><input type="text" name="oscimp_store_url" value="<?php echo $store_url; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/" ); ?></p>
        <p><?php _e("Product image folder: " ); ?><input type="text" name="oscimp_prod_img_folder" value="<?php echo $prod_img_folder; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/images/" ); ?></p>
         
     
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'posts_trdom' ) ?>" />
        </p>
    </form>
</div>-->
<div class="wrap">
<h2>Add Camp Using Admin Panel</h2>

<form name="post_form" method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="post_hidden" value="Y">
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Country <span style="color:#F00;">*</span></th>
        <td><input id="country" name="country" required="required" readonly="readonly" type="text" class="form-control" value="United States" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">State or Region <span style="color:#F00;">*</span></th>
        <td><select id="select" required="required" class="form-control" name="state">
            <option value="">Select State</option>
        <?php if(!empty(get_states())):
            foreach (get_states() as $state) :
            ?>
            <option value="<?php echo $state->state_code ?>"><?php echo $state->state_name ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Name of Campsite <span style="color:#F00;">*</span></th>
        <td><input id="park" required="required" name="camp" type="text" class="form-control" value="" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Closest Town <span style="color:#F00;">*</span></th>
        <td><select id="select_city" required="required" class="form-control" name="city"><option value="">Select City</option></select></td>
        </tr>
        
<!--        <tr valign="top">
        <th scope="row">To Auto Populate Geodata</th>   
        <td><input type="button" class="button-primary" id="fetch-geodata" name="Submit" value="<?php _e('Fetch Geodata', 'posts_trdom' ) ?>" /></td>
        </tr>-->
        
        <tr valign="top">
        <th scope="row">Campsite Address </th>
        <td><input name="address" class="form-control" type="text" /></td>
        </tr>
        
<!--        <tr valign="top">
        <th scope="row">Camp Phone <span style="color:#F00;">*</span></th>
        <td><input  name="phone" required="required" class="form-control" type="text" /><?php _e(" ex: 555-000-5555 or 5550005555" ); ?></td>
        </tr>-->
        
        <tr valign="top">
        <th scope="row">Type of Campsite <span style="color:#F00;">*</span></th>
        <td><select id="type"  class="form-control" required="required" name="type">
            <option value="">Select Type</option>
        <?php foreach ($campTypes as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Price per Night <span style="color:#F00;">*</span></th>
        <td><input  required="required" value="0" name="price" class="form-control" type="number" min="0"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Reservations Accepted <span style="color:#F00;"></span></th>
        <td><select id="reserve" class="form-control" name="reserve" >
                <option value="">Select Reservations</option>
        <?php foreach ($reserve as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Climate <span style="color:#F00;">*</span></th>
        <td><select id="climate"  class="form-control" required="required" name="climate">
            <option value="">Select Climate</option>
        <?php foreach ($climate as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Number of Sites <span style="color:#F00;">*</span></th>
        <td><input required="required"  value="0" name="sites" class="form-control" type="number" min="0"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Best Months to Visit</th>
        <td><select id="month" multiple="multiple" class="form-control" name="months[]">
            <?php if(!empty($months)):
            foreach ($months as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Hookups Available <span style="color:#F00;">*</span></th>
        <td><select id="hookups" required="required" multiple class="form-control" name="hookups[]">
            <?php if(!empty($hookups)):
            foreach ($hookups as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Amenities <span style="color:#F00;">*</span></th>
        <td><select id="amenities" required="required" multiple class="form-control" name="amenities[]">
       <?php if(!empty($amenities)):
            foreach ($amenities as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select>
          </td>
        </tr>
        <tr id="other" style="display: none;">
            <th></th>
            <td>
                <input name="other" type="text" class="form-control" value="" />
            </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Website to Reserve <span style="color:#F00;">*</span></th>
        <td><input name="website" required="required" type="text" class="form-control" value="" /></td>
        </tr>
        
<!--        <tr valign="top">
        <th scope="row">Postal Code </th>
        <td><input id="zipcode" name="code" type="text" class="form-control" value="" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Latitude <span style="color:#F00;">*</span></th>
        <td><input id="latitude" required="required" name="lat" type="text" class="form-control" value="" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Longitude <span style="color:#F00;">*</span></th>
        <td><input id="longitude" required="required" name="long" type="text" class="form-control" value="" /></td>
        </tr>-->
        
        <input type="hidden" id="latitude" name="lat" value="">
  <input type="hidden" id="longitude" name="long" value="">
        
        <tr valign="top">
        <th scope="row">Status <span style="color:#F00;">*</span></th>
        <td><select id="status" required="required" class="form-control" name="status" >
                <option value="">Select Status</option>
        <?php foreach ($status as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Upload Images</th>
        <td><header role="banner" class="sidebar-header">
                            <div id="message"></div>
                            <input name="file[]" id="file" type="file" multiple="multiple">
                            <!--<p>Max 4 images are allowed...</p>-->
                            </header></td>
        </tr>
        
        
    </table>
    <?php submit_button(); ?>

</form>
</div>
<!--
<div class="wrap">
    <?php    echo "<h2>" . __( 'Post Camps Using Admin Panel', 'posts_trdom' ) . "</h2>"; ?>
    
    <form name="post_form" method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="post_hidden" value="Y">
        <p><?php _e("Country: " ); ?><span style="color:#F00;">*</span><input id="country" name="country" required="required" readonly="readonly" type="text" class="form-control" value="United States" /></p>
        <p><?php _e("State or Region: " ); ?><span style="color:#F00;">*</span><select id="select" required="required" class="form-control" name="state">
            <option value="">Select State</option>
        <?php if(!empty(get_states())):
            foreach (get_states() as $state) :
            ?>
            <option value="<?php echo $state->state_code ?>"<?php if($selectedState == $state->state_code){echo("selected");}?>><?php echo $state->state_name ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></p>
        <p><?php _e("Camp Name: " ); ?><span style="color:#F00;">*</span><input id="park" required="required" name="camp" type="text" class="form-control" value="" /></p>
        <p><?php _e("Camp City: " ); ?><span style="color:#F00;">*</span><select id="select_city" required="required" class="form-control" name="city"><option value="">Select City</option></select></p>
        <p class="submit"><input type="button" id="fetch-geodata" name="Submit" value="<?php _e('Fetch Geodata', 'posts_trdom' ) ?>" /></p>
        <p><?php _e("Camp Address: " ); ?><span style="color:#F00;">*</span><input  required="required" name="address" class="form-control" type="text" /></p>
        <p><?php _e("Camp Phone: " ); ?><span style="color:#F00;">*</span><input  name="phone" required="required" class="form-control" type="text" /><?php _e(" ex: 555-000-5555 or 5550005555" ); ?></p>
        <p><?php _e("Camp Type: " ); ?><span style="color:#F00;">*</span><select id="type"  class="form-control" required="required" name="type">
            <option value="">Select Type</option>
        <?php foreach ($campTypes as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></p>
        <p><?php _e("Price (US Dollars): " ); ?><span style="color:#F00;">*</span><input  required="required" value="0" name="price" class="form-control" type="number" min="0"/></p>
        <p><?php _e("Climate: " ); ?><span style="color:#F00;">*</span><select id="climate"  class="form-control" required="required" name="climate">
            <option value="">Select Climate</option>
        <?php foreach ($climate as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></p>
        <p><?php _e("No of Sites: " ); ?><span style="color:#F00;">*</span><input required="required"  value="0" name="sites" class="form-control" type="number" min="0"/></p>
        <p><?php _e("Best Month's to Visit: " ); ?><span style="color:#F00;">*</span><select required="required" id="month" multiple="multiple" class="form-control" name="months[]">
            <?php if(!empty($months)):
            foreach ($months as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></p>
        <p><?php _e("Hookups Available: " ); ?><span style="color:#F00;">*</span><select required="required" multiple class="form-control" name="hookups[]">
            <?php if(!empty($hookups)):
            foreach ($hookups as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></p>
        <p><?php _e("Amenities: " ); ?><span style="color:#F00;">*</span><select required="required" multiple class="form-control" name="amenities[]">
            <?php if(!empty($amenities)):
            foreach ($amenities as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"><?php echo $value ?></option>
            
            <?php
            endforeach;
            endif;?>
        </select></p>
        <p><?php _e("Website: " ); ?><input name="website" type="text" class="form-control" value="" /></p>
        <p><?php _e("Postal Code: " ); ?><input id="zipcode" name="code" type="text" class="form-control" value="" /></p>
        <p><?php _e("Latitude: " ); ?><span style="color:#F00;">*</span><input id="latitude" required="required" name="lat" type="text" class="form-control" value="" /></p>
        <p><?php _e("Longitude: " ); ?><span style="color:#F00;">*</span><input id="longitude" required="required" name="long" type="text" class="form-control" value="" /></p>
        <p><?php _e("Upload Images: " ); ?><header role="banner" class="sidebar-header">
                            <div id="message"></div>
                            <input name="file[]" id="file" type="file" multiple="multiple">
                            <p>Max 4 images are allowed...</p>
                            </header>
        </p>
        <p class="submit"><input type="button" name="Cancel" value="<?php _e('Cancel', 'posts_trdom' ) ?>" /><input type="submit" name="Submit" value="<?php _e('Add Campsite', 'posts_trdom' ) ?>" /></p>
                        
    </form>
</div>-->
<!--Form ends here-->

<!--Scripts starts from here-->


<script type="text/javascript">
    
     var txt_filter;
    jQuery("#amenities").click(function(){
        txt_filter = jQuery('#amenities').val();
    //alert(txt_filter);
    
    var div = document.getElementById('other');
    if (txt_filter == 'other') {
        jQuery('#other').show("fast");
        //div.style.display = 'none';
    }
//    else {
//        $('#other').hide("fast");
//        //div.style.display = 'block';
//    }
    
    });
    
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
    txt_filter = jQuery('#select').val();
    if(txt_filter == '')
    {
        return;
    }
    //alert(txt_filter);
jQuery.ajax({
  type: "POST",
  url: "<?php echo get_site_url()?>/city",
  data: {selected: txt_filter},
  success: function (data) {
      
      var arr = JSON.parse(data);
      
      //alert(arr);
    if(arr.length == ''){
         jQuery('#select_city').html('<option value="0" selected="selected">No City Exist</option>');
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
      jQuery('#select_city').html(resulted_data);
       }
       
  },
  error: function(data) {
    // Stuff
  }
});


});

</script>

<script type="text/javascript">

jQuery('#hookups').on('click', function(){
  //$('#select_city').select2();
        var country = "us";//$('#country').val();
        var region = jQuery('#select :selected').text();
        var region1 = jQuery('#select :selected').val();
        var city = jQuery('#select_city :selected').text();
        var city1 = jQuery('#select_city :selected').val();
        var park = jQuery('#park').val();
        if(country == '' || region == '' || city == '' || park == '' || city1 == 0 || region1 == 0){
            alert('Please enter country, region, city, and park name');
            return false;
        }
        var url="http://maps.googleapis.com/maps/api/geocode/json?address=";
         jQuery.getJSON(url+encodeURI(park)+'&components=locality:'+city+'|country:'+country+'|administrative_area:'+region+'&sensor=false', function(data){
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
            jQuery('#latitude').val(place.geometry.location.lat);
            jQuery('#longitude').val(place.geometry.location.lng);
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