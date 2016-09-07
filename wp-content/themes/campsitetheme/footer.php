</div>    
<footer>
   


<div class="container-fluid footer padding0">
	<div class="container">
    	<div class="col-sm-4 col-xs-12">
        	<h4>Main Links</h4>
                <?php wp_nav_menu(array('theme_location' => 'secondary-left'));?>     
<!--            <ul>
            	<li><a href="<?php echo get_site_url()?>/addcampsite"> Add Campsite</a></li>
                <li><a href="<?php echo get_site_url()?>/addreview">Submit Review</a></li>
                <li><a href="<?php echo get_site_url()?>/blog">Owner's Blog</a></li>
                <li><a href="<?php echo get_site_url()?>/about">About Us</a></li>
            </ul>-->
        </div>
        <div class="col-sm-4 col-xs-12">
        	<h4>Our Location</h4>
            <div class="clear10"></div>
         
            	<strong>BROOMFIELD, COLORADO</strong>
   <div class="clear10"></div>
   
   <div class="col-sm-12 padding0" style="border:5px">
       <div id="map" style=" width: 100%; height: 170px; border-radius:5px;"></div>
   </div>
   
<!--   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1292.556831043393!2d-104.89647177520867!3d39.5993757646329!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x876c85bfc5a2a129%3A0x9d120af23b37e267!2sBrookfield+Residential+Colorado!5e0!3m2!1sen!2sus!4v1467095239201" width="273" height="200" frameborder="0" style="border:0" class="img-responsive" allowfullscreen></iframe>-->
<!--<img src="<?php// echo get_template_directory_uri()?>/images/footer_map.jpg" class="img-responsive" alt="" />-->
   <div class="clear10"></div>
</div>
        
        <div class="col-sm-3 col-xs-12 col-lg-offset-1" style="line-height:30px;">
        	<h4>Contact Us</h4>
           <div class="clear10"></div>
           <?php wp_nav_menu(array('theme_location' => 'secondary-right'));?>
<!--            <ul>
            	<li>E-mail: <a href="mailto:info@findyourcampsite.com" title="mailto:info@findyourcampsite.com">info@findyourcampsite.com</a></li>
                
                <li><a href="<?php echo get_site_url()?>/terms">Terms of Service</a></li>
                <li><a href="<?php echo get_site_url()?>/privacy">Privacy Policy</a></li>
            </ul>-->
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
</div>
        
        <script type="text/javascript">
            var window_width = $(window).width();
            if(window_width <= 768){
        //        alert('message');
                $('.srch_mrg').removeClass('pull-right');
            }
        </script>
        
        <script>
    function initialize(){
        
        //var bounds = new google.maps.LatLngBounds();
        var lat = 39.931817;
        var long =  -105.065919;
     var myLatlng = new google.maps.LatLng(lat,long);
     //bounds.extend(myLatlng);
     var myOptions = {
         zoom: 10,
         center: myLatlng,
         mapTypeId: google.maps.MapTypeId.ROADMAP
//         mapTypeControl: true,
//         style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
//         navigationControl: true,
//         style: google.maps.NavigationControlStyle.ZOOM_PAN
         }
      map = new google.maps.Map(document.getElementById("map"), myOptions);
      //document.getElementById('myIframe').src = "https://maps.google.com/maps/embed?q=51.88,-176.6580556";
      var marker = new google.maps.Marker({
          position: myLatlng, 
          map: map,
          title:"Fast marker"
     });
     //map.fitBounds(bounds);
} 

google.maps.event.addDomListener(window,'load', initialize);



</script>
        <script>
    setTimeout(function(){
  $('.alert').fadeOut('slow');
}, 2500);
</script>
        <?php wp_footer();?>
    </footer>
    </body>
</html>

