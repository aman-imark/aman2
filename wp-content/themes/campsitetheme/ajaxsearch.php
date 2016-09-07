<?php

/* 
    Template Name: Ajax_Search
 */

 //start of ahmad's search code
 
 function get_camps_by_name_count_fast($data)
{
    global $wpdb;
	 $camps_table = $wpdb->prefix . 'camps'; //Good practice
	 $cities_table = $wpdb->prefix . 'cities'; //Good practice
	 $states_table = $wpdb->prefix . 'states'; //Good practice
	 $results = array();
	 
	// $queryKeword;
	// if(!empty($data['query']))
	// {
	//	$queryKeword = get_query($data['query']);
	// }
 
 $query = "SELECT $camps_table.ID, camp_name, $cities_table.city_name, $states_table.state_name FROM $camps_table JOIN $cities_table ON $camps_table.cities_id = $cities_table.ID JOIN $states_table ON $camps_table.states_code = $states_table.state_code WHERE $camps_table.camp_status = 1 && $camps_table.camp_name  LIKE '%$data%' ";
	$results = $wpdb->get_results("$query");

	 //print_r($results);
	 //@die;
	// $array = json_decode(json_encode($results), True);
	 //return sizeof($results);
        return $results;
	 
}





/*Code to get search results on ajax call*/
// echo $_POST['query'];
 function get_g_search($data)
{
	//echo $_POST['query'];
	//exit;
	//@die;
	
    global $wpdb;
$cities_table = $wpdb->prefix . 'cities'; //Good practice
$randomFact = $wpdb->get_results( "SELECT * FROM $cities_table WHERE city_name LIKE '%$data%'");
     return $randomFact;
}

//print_r($randomFact);
//@die;
if($_POST['query']){
	
	
       $blue =  get_camps_by_name_count_fast($_POST['query']);
	   
   // echo json_encode($search);
    //print_r($cities);
    


foreach($blue as $value)
{
	?>
	
	<div id="user" style="background:grey"> 
    <a href="<?php echo get_site_url()?>/camp?id=<?php echo $value ->ID ?>"><?php echo $value ->camp_name; ?> </a> <br>
	<?php echo $value ->city_name; ?> - <?php echo $value ->state_name; ?> 
	</div>
	
	<?php
}

}

//end of ahmad's search code 

?>
