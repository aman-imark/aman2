<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//Function starts from here
function get_all_camps_count_by_admin()
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $query = "SELECT $camps_table.ID,  $camps_table.camp_name, $camps_table.camp_type, $camps_table.camp_price, $camps_table.camp_climate, $camps_table.camp_phone, $camps_table.camp_website, $camps_table.camp_status FROM $camps_table";
    $results = $wpdb->get_results($query);
    $total = sizeof( $results );
    return $total;
}
function get_paginated_camps($offset, $items_per_page)
{
    global $wpdb;
    $camps_table = $wpdb->prefix . 'camps'; //Good practice
    $query = "SELECT $camps_table.ID,  $camps_table.camp_name, $camps_table.camp_type, $camps_table.camp_price, $camps_table.camp_climate, $camps_table.camp_phone, $camps_table.camp_website, $camps_table.camp_status FROM $camps_table";
    $results = $wpdb->get_results( $query . " ORDER BY ID LIMIT ${offset}, ${items_per_page}" );
    $array = json_decode(json_encode($results), True);
    return $array;
}
//Fucntion ends here

//header code starts from here
$total = get_all_camps_count_by_admin();
$items_per_page = 20;
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset = ( $page * $items_per_page ) - $items_per_page;
$allCamps = get_paginated_camps($offset, $items_per_page);
$status = array(1 => "Active", 0 => "Inactive");
//header code ends here

?>

<style>
    table { 
  width: 100%; 
  border-collapse: collapse; 
}
/* Zebra striping */
tr:nth-of-type(odd) { 
  background: #eee; 
}
th { 
  background: #333; 
  color: white; 
  font-weight: bold; 
}
td, th { 
  padding: 6px; 
  border: 1px solid #ccc; 
  text-align: left; 
}
.camps_pagination
{
    float: right;
    font-weight: bold;
    margin-right: 6px;
    margin-bottom: 3px;
}
.tablenav-pages-navspan{
        height: 24px !important;
} 
</style>

<!--Table starts from here-->
<div class="wrap">
    
    <?php    echo "<h2>" . __( 'All Camps Using Admin Panel', 'posts_trdom' ) . "</h2>"; ?>
    <div class="tablenav top">
        <div class="tablenav-pages">
            <span class="pagination-links">
        <?php
   					   $pages =  paginate_links( array(
    'base' => add_query_arg( 'cpage', '%#%' ),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($total / $items_per_page),
    'current' => $page,
    'type' => 'array'
));
                                          
                                                   if( is_array( $pages ) ) {
            $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
            echo '<span class="tablenav-pages-navspan">';
            foreach ( $pages as $page ) {
                //print_r($page);
                    echo " $page ";
            }
           echo '</span>';
        }
                                                   
    ?>
      </span>      
    </div>
        </div>
    <br class="clear">
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Camp Name</th>
                <th>Status</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($allCamps))
            {
                foreach ($allCamps as $camp)
                {
            ?>
            <tr>
                <td><?php echo $camp['ID']?></td>
                <td><a href="<?php echo get_site_url()?>/wp-admin/admin.php?page=editcampbyadmin&id=<?php echo $camp['ID']?>" ><?php echo $camp['camp_name']?></a></td>
                <td><select id="status"  class="form-control" name="status" onchange="getConfirmationOfStatus('<?php echo $camp['ID']?>', '<?php echo $camp['camp_status'] ?> ')">
        <?php foreach ($status as $key => $value) :
            ?>
            <option value="<?php echo $key ?>"<?php if($camp['camp_status'] == $key){echo("selected");}?>><?php echo $value ?></option>
            
            <?php
            endforeach;
?>
        </select></td>
                <td><a href="" onclick="getConfirmation(<?php echo $camp['ID']?>)">Delete</a></td>
            </tr>    
            <?php
                }
            }
            ?>
            
        </tbody>
    </table>
    <div class="tablenav bottom">
        <div class="tablenav-pages">
            <span class="pagination-links">
        <?php                            
                                                   if( is_array( $pages ) ) {
            $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
            echo '<span class="tablenav-pages-navspan">';
            foreach ( $pages as $page ) {
                //print_r($page);
                    echo " $page ";
            }
           echo '</span>';
        }
                                                   
    ?>
      </span>      
    </div>
        </div>
</div>
<!--Table ends here-->
<!--Script starts from here-->
 <script type="text/javascript">
    function getConfirmation(id){
        var txt_filter = id;
        //console.log(txt_filter);
       var retVal = confirm("Do you want to continue ?");
       if( retVal == false ){
          location.reload();
       }
       else{
            jQuery.ajax({
            type: "POST",
            url: "<?php echo get_site_url()?>/wp-admin/admin.php?page=updatecampbyadmin",
            data: {selected: txt_filter},
            success: function (data) {
                alert("Camp deleted succefully");
                 },
            error: function(data) {
              // Stuff
              alert("Camp not deleted succefully");
            }
          });
       }
       }
</script>
 <script type="text/javascript">
    function getConfirmationOfStatus(id, status){
        //var txt_filter = id;
        //console.log(txt_filter);
       var retVal = confirm("Do you want to continue ?");
       if( retVal == false ){
          location.reload();
       }
       else{
            jQuery.ajax({
            type: "POST",
            url: "<?php echo get_site_url()?>/wp-admin/admin.php?page=updatecampbyadmin",
            
            data: {id: id, status: status},
            success: function (data) {
                //alert(data);
                alert("Camp updated succefully");
                location.reload();
                 },
            error: function(data) {
                //alert(data);
              // Stuff
              alert("Camp not updated succefully");
              location.reload();
            }
          });
       }
       }
</script>
<!--Script ends here-->
