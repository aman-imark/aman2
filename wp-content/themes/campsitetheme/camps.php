<?php

/* 
Template Name: Camp
 */

function get_camps($data)
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $cities_table = $wpdb->prefix . 'cities'; //Good practice
    $states_table = $wpdb->prefix . 'states'; //Good practice
    //$state_code = '%'.$data['selected'].'%';
$results = $wpdb->get_results("SELECT $camps_table.ID, camp_name, $cities_table.city_name, $states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code WHERE states_code LIKE '$data' AND $camps_table.camp_status = 1");
return $results;
}

if($_POST['selected']):
       $cities =  get_camps($_POST['selected']);

    echo json_encode($cities);
    //print_r($cities);
    endif;