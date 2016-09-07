<?php

/*
    Template Name: City
 */

?>

<?php
/*Code to get cities on ajax call*/

//echo "hello";

function get_cities($data)
{
    global $wpdb;
$cities_table = $wpdb->prefix . 'cities'; //Good practice
$randomFact = $wpdb->get_results( "SELECT * FROM $cities_table WHERE state_code LIKE '$data'");
return $randomFact;
}

if($_POST['selected']):
       $cities =  get_cities($_POST['selected']);

    echo json_encode($cities);
    //print_r($cities);
    endif;

/*Code to delete campsites on ajax call*/
  
function delete_camp($data)
{
    global $wpdb;
$camps_table = $wpdb->prefix . 'camps'; //Good practice
$randomFact = $wpdb->delete($camps_table, array('ID' => $data));
return $randomFact;
}    

function get_and_unlink_previous_images($campId)
{
    $images = json_decode(get_previous_images($campId));
    $imagesUnliked = true;
    if(isset($images))
    {
        if (get_site_url() == "http://localhost/campsite") {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/campsite/wp-content/themes/campsitetheme/upload/";
        } else {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/campsitetheme/upload/";
        }
        foreach($images as $value)
        {
            $targetPathWithImage = $targetPath.$value;
            unlink($targetPathWithImage);
        }
    }
    return $imagesUnliked;
    //print_r($images);
    //@die;
}

function get_previous_images($campId)
{
    $campID = $campId;
    global $wpdb;
    $table = $wpdb->prefix."camps";
    $randomFact = $wpdb->get_row( "SELECT camp_images FROM $table WHERE ID = $campID");
    //$images = json_decode($randomFact->camp_images);
    return $randomFact->camp_images;
}

if(isset($_POST['deleted']))
{
    get_and_unlink_previous_images($_POST['deleted']);
    $camp =  delete_camp($_POST['deleted']);
    echo json_encode($camp);    
}




?>

