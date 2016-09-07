<?php

/*
    Template Name: g_search
 */
 
 
 
 /*Code to get search results on ajax call*/
 
 //function get_g_se($data)
//{
    global $wpdb;
$cities_table = $wpdb->prefix . 'cities'; //Good practice
$randomFact = $wpdb->get_results( "SELECT * FROM $cities_table WHERE city_name LIKE '%".$_POST['name']."%'");
//return $randomFact;
//}

//print_r($randomFact);
//@die;

foreach($randomFact as $value)
{
	?>
	
	<div id="user" style=""><span> <?php echo $value['city_name']; ?> </span> </div>
	
	<?php
}

?>