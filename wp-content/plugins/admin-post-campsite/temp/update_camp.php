<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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



function update_camp_status($data)
{
    //print_r($data);
    $status = NULL;
    $id = $data['id'];
    if($data['status'] == 0)
    {
        $status = 1;
    }  else {
        $status = 0;
    }
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
$result =  $wpdb->update( 
	$camps_table, 
	array( 
		'camp_status' => $status
	), 
	array( 'ID' => $id ) 
	);
//print_r($result);
    return $result;
}

if(isset($_POST['selected']))
{
    get_and_unlink_previous_images($_POST['selected']);
    $camp =  delete_camp($_POST['selected']);
        echo json_encode($camp); 
}
    
if(isset($_POST['id']) && isset($_POST['status']))
{
    //print_r($_POST['selected']); @die;
    $camp = update_camp_status($_POST);
    echo json_encode($camp);
}